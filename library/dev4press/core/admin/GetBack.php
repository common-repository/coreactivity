<?php
/**
 * Name:    Dev4Press\v51\Core\Admin\GetBack
 * Version: v5.1
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2024 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v51\Core\Admin;

use Dev4Press\v51\Core\Quick\WPR;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class GetBack {
	protected $admin;
	protected $page;

	public function __construct( $admin ) {
		$this->admin = $admin;

		$this->page = isset( $_REQUEST['page'] ) ? sanitize_key( $_REQUEST['page'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification

		$this->process();
	}

	/** @return \Dev4Press\v51\Core\Admin\Plugin|\Dev4Press\v51\Core\Admin\Menu\Plugin|\Dev4Press\v51\Core\Admin\Submenu\Plugin */
	public function a() {
		return $this->admin;
	}

	public function p() {
		return $this->page;
	}

	public function is_single_action( $name, $key = 'single-action' ) : bool {
		return $this->_get( $key ) == $name;
	}

	public function is_bulk_action() : bool {
		return ( $this->_is() && $this->_get() != '-1' ) || ( $this->_is( 'action2' ) && $this->_get( 'action2' ) != '-1' );
	}

	public function get_single_action( $key = 'single-action' ) : string {
		return $this->_get( $key );
	}

	public function get_bulk_action() : string {
		$action = $this->_get();
		$action = $action == '-1' ? '' : $action;

		if ( $action == '' ) {
			$action = $this->_get( 'action2' );
			$action = $action == '-1' ? '' : $action;
		}

		return $action;
	}

	public function check_nonce( $action, $nonce = '_wpnonce', $die = true ) {
		check_ajax_referer( $action, $nonce, $die );
	}

	protected function process() {
		if ( $this->a()->panel == 'dashboard' ) {
			if ( $this->is_single_action( 'dismiss-topic-prefix', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdtox_hide' );
			}

			if ( $this->is_single_action( 'dismiss-forum-notices', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdfon_hide' );
			}

			if ( $this->is_single_action( 'dismiss-topic-polls', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdpol_hide' );
			}

			if ( $this->is_single_action( 'dismiss-bbpress-toolbox', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdbbx_hide' );
			}

			if ( $this->is_single_action( 'dismiss-power-search', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdpos_hide' );
			}

			if ( $this->is_single_action( 'dismiss-quantum-theme', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdqnt_hide' );
			}
		}

		if ( $this->a()->panel == 'features' ) {
			if ( ! empty( $this->a()->subpanel ) ) {
				if ( $this->is_single_action( 'reset' ) ) {
					$this->feature_reset();
				} else if ( $this->is_single_action( 'network-copy' ) ) {
					$this->feature_network_copy();
				}
			}
		}

		if ( $this->a()->panel == 'tools' ) {
			if ( $this->is_single_action( 'export', 'run' ) ) {
				$this->tools_export();
			}
		}
	}

	protected function feature_network_copy() {
		$feature = $this->a()->subpanel;

		check_ajax_referer( $this->a()->plugin_prefix . '-feature-network-copy-' . $feature );

		if ( $this->a()->plugin()->f()->is_network_enabled() && ! is_network_admin() ) {
			$settings = $this->a()->plugin()->f()->s()->feature_get( $feature );

			foreach ( $settings as $key => $value ) {
				$this->a()->plugin()->f()->b()->set( $feature . '__' . $key, $value, 'features' );
			}

			$this->a()->plugin()->f()->b()->save( 'features' );
		}

		wp_redirect( $this->a()->current_url() . '&message=feature-network-copy' );
		exit;
	}

	protected function feature_reset() {
		$feature = $this->a()->subpanel;

		check_ajax_referer( $this->a()->plugin_prefix . '-feature-reset-' . $feature );

		if ( $this->a()->plugin()->f()->is_network_enabled() && ! is_network_admin() ) {
			$this->a()->plugin()->f()->b()->reset_feature( $feature );
		} else {
			$this->a()->plugin()->f()->s()->reset_feature( $feature );
		}

		wp_redirect( $this->a()->current_url() . '&message=feature-reset' );
		exit;
	}

	protected function front_dismiss_plugin( $option ) {
		$this->a()->settings()->set( $option, true, 'core', true );

		wp_redirect( $this->a()->current_url( false ) );
		exit;
	}

	protected function tools_export() {
		check_ajax_referer( 'dev4press-plugin-' . $this->a()->plugin_prefix );

		if ( ! WPR::is_current_user_admin() ) {
			wp_die( esc_html__( 'Only administrators can use export features.', 'd4plib' ) );
		}

		$data = $this->a()->settings()->export_to_secure_json();

		if ( $data !== false ) {
			$export_date = gmdate( 'Y-m-d-H-m-s' );
			$export_name = $this->a()->plugin . '-settings-' . $export_date . '.json';

			header( 'Content-type: application/force-download' );
			header( 'Content-Disposition: attachment; filename="' . $export_name . '"' );

			die( $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			wp_redirect( $this->a()->current_url( false ) );
			exit;
		}
	}

	protected function _get( $key = 'action', $default = '' ) : string {
		return isset( $_GET[ $key ] ) ? sanitize_key( $_GET[ $key ] ) : $default; // phpcs:ignore WordPress.Security.NonceVerification
	}

	protected function _is( $key = 'action' ) : bool {
		return isset( $_GET[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification
	}
}
