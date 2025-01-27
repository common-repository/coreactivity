<?php

namespace Dev4Press\Plugin\CoreActivity\Log;

use WP_Error;
use WP_Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Upgrader {
	public function __construct() {
		add_filter( 'upgrader_pre_install', array( $this, 'save_pre_install_versions' ), 10, 2 );
		add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete' ), 10, 2 );
	}

	public static function instance() : Upgrader {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Upgrader();
		}

		return $instance;
	}

	public function save_pre_install_versions( $value ) {
		$plugins = get_site_transient( 'update_plugins' );
		$themes  = get_site_transient( 'update_themes' );

		$backup = array(
			'themes'          => wp_get_themes(),
			'plugins'         => get_plugins(),
			'themes_updates'  => $themes->response ?? array(),
			'plugins_updates' => $plugins->response ?? array(),
		);

		update_site_option( 'coreactivity_temp_plugins_themes', $backup, false );

		return $value;
	}

	public function upgrader_process_complete( $obj, $data ) {
		if ( isset( $data['type'] ) && isset( $data['action'] ) ) {
			$_type   = $data['type'];
			$_action = $data['action'];
			$_error  = false;

			if ( $obj->skin->result instanceof WP_Error ) {
				$_error = $obj->skin->result;
			}

			if ( 'plugin' == $_type && 'update' == $_action ) {
				$plugins = isset( $data['plugins'] ) ? (array) $data['plugins'] : array();

				foreach ( $plugins as $plugin_code ) {
					$plugin   = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_code, true, false );
					$previous = $this->get_plugin_previous_version( $plugin_code );
					$package  = $this->get_plugin_package_url( $plugin_code );

					if ( $_error ) {
						do_action( 'coreactivity_upgrader_plugin_update_error', $plugin_code, $plugin, $previous, $package, $_error );
					} else {
						do_action( 'coreactivity_upgrader_plugin_update', $plugin_code, $plugin, $previous, $package );
					}
				}
			} else if ( 'plugin' == $_type && 'install' == $_action ) {
				$plugin      = $obj->new_plugin_data ?? array();
				$plugin_code = $obj->plugin_info();

				if ( $_error ) {
					do_action( 'coreactivity_upgrader_plugin_install_error', $plugin_code, $plugin, $_error );
				} else {
					do_action( 'coreactivity_upgrader_plugin_install', $plugin_code, $plugin );
				}
			} else if ( 'theme' == $_type && 'update' == $_action ) {
				$themes = isset( $data['themes'] ) ? (array) $data['themes'] : array();

				foreach ( $themes as $theme_code ) {
					$theme    = wp_get_theme( $theme_code );
					$previous = $this->get_theme_previous_version( $theme_code );
					$package  = $this->get_theme_package_url( $theme_code );

					if ( $theme instanceof WP_Theme ) {
						if ( $_error ) {
							do_action( 'coreactivity_upgrader_theme_update_error', $theme_code, $theme, $previous, $package, $_error );
						} else {
							do_action( 'coreactivity_upgrader_theme_update', $theme_code, $theme, $previous, $package );
						}
					}
				}
			} else if ( 'theme' == $_type && 'install' == $_action ) {
				$theme      = $obj->new_theme_data ?? array();
				$theme_code = $obj->result['destination_name'] ?? '';

				if ( $_error ) {
					do_action( 'coreactivity_upgrader_theme_install_error', $theme_code, $theme, $_error );
				} else {
					do_action( 'coreactivity_upgrader_theme_install', $theme_code, $theme );
				}
			}
		}

		delete_site_option( 'coreactivity_temp_plugins_themes' );
	}

	protected function get_plugin_package_url( $plugin_code ) {
		$data   = get_site_option( 'coreactivity_temp_plugins_themes', array() );
		$plugin = $data['plugins_updates'][ $plugin_code ] ?? array();
		$plugin = (array) $plugin;

		return empty( $plugin ) ? '' : ( $plugin['package'] ?? '' );
	}

	protected function get_theme_package_url( $theme_code ) {
		$data  = get_site_option( 'coreactivity_temp_plugins_themes', array() );
		$theme = $data['themes_updates'][ $theme_code ] ?? array();
		$theme = (array) $theme;

		return empty( $theme ) ? '' : ( $theme['package'] ?? '' );
	}

	protected function get_plugin_previous_version( $plugin_code ) {
		$data   = get_site_option( 'coreactivity_temp_plugins_themes', array() );
		$plugin = $data['plugins'][ $plugin_code ] ?? array();

		return $plugin['Version'] ?? '';
	}

	protected function get_theme_previous_version( $theme_code ) {
		$data  = get_site_option( 'coreactivity_temp_plugins_themes', array() );
		$theme = $data['themes'][ $theme_code ] ?? array();

		return $theme['Version'] ?? '';
	}
}
