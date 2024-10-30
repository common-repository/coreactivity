<?php
/**
 * Name:    Dev4Press\v51\Core\Plugins\Core
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

namespace Dev4Press\v51\Core\Plugins;

use Dev4Press\v51\API\Four;
use Dev4Press\v51\Core\DateTime;
use Dev4Press\v51\Core\Quick\BBP;
use Dev4Press\v51\Core\Quick\KSES;
use Dev4Press\v51\Core\Quick\WPR;
use Dev4Press\v51\Library;
use Dev4Press\v51\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Core {
	public bool $is_debug = false;

	public bool $widgets = false;
	public bool $enqueue = false;
	public bool $features = false;
	public bool $license = false;

	public string $cap = 'activate_plugins';
	public string $svg_icon = '';
	public string $plugin = '';
	public string $url = '';
	public string $path = '';

	protected array $_system_requirements = array();
	protected array $_widget_instance = array();
	protected int $_plugins_loaded_priority = 10;
	protected int $_after_setup_theme_priority = 10;
	protected DateTime $_datetime;

	public function __construct() {
		$this->_datetime = new DateTime();

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), $this->_plugins_loaded_priority );
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ), $this->_after_setup_theme_priority );
	}

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function datetime() : DateTime {
		return $this->_datetime;
	}

	public function plugins_loaded() {
		$this->is_debug = WordPress::instance()->is_script_debug();

		if ( $this->widgets === true || ! empty( $this->widgets ) ) {
			add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		}

		if ( $this->enqueue ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		$this->load_textdomain();

		$this->_system_requirements = $this->check_system_requirements();

		if ( ! empty( $this->_system_requirements ) ) {
			if ( is_admin() ) {
				add_action( 'admin_notices', array( $this, 'system_requirements_notices' ) );
			} else {
				$this->deactivate();
			}
		} else {
			$this->init_capabilities();
			$this->cron_controls();
			$this->run();
		}
	}

	public function load_textdomain() {
		load_plugin_textdomain( $this->plugin, false, $this->plugin . '/languages' );
		load_plugin_textdomain( 'd4plib', false, $this->plugin . '/' . Library::instance()->base_path() . '/languages' );
	}

	public function init_capabilities() {
		$role = get_role( 'administrator' );

		if ( ! is_null( $role ) ) {
			$role->add_cap( $this->cap );
		} else {
			$this->cap = 'activate_plugins';
		}
	}

	public function plugin_name() : string {
		return $this->plugin . '/' . $this->plugin . '.php';
	}

	public function deactivate() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		// This call deactivates this plugin only, it can't deactivate other plugins
		deactivate_plugins( $this->plugin_name() );
	}

	public function recommend( $panel = 'update' ) : string {
		$four = Four::instance( 'plugin', $this->plugin, $this->s()->i()->version, $this->s()->i()->build );
		$four->ad();

		return $four->ad_render( $panel );
	}

	public function after_setup_theme() {
	}

	public function cron_controls() {
		if ( $this->license ) {
			$this->license_control();
		}
	}

	public function license_control() {
		if ( ! $this->l()->is_freemius() ) {
			add_action( $this->plugin . '-license-validation', array( $this, 'cron_license_validation' ) );

			if ( ! wp_next_scheduled( $this->plugin . '-license-validation' ) ) {
				wp_schedule_event( time() + HOUR_IN_SECONDS, 'weekly', $this->plugin . '-license-validation' );
			}
		}
	}

	public function widgets_init() {
	}

	public function enqueue_scripts() {
	}

	public function system_requirements_notices() {
		$plugin   = $this->s()->i()->name();
		$versions = array();

		foreach ( $this->_system_requirements as $req ) {
			if ( $req[1] == 0 ) {
				/* translators: Plugin activation system requirements notice, not active. %1$s: Requirement Name. %2$s: Requirements Version. %3$s: Requirement Name with markup. */
				$versions[] = sprintf( _x( '%1$s version %2$s (%3$s is not active on your website)', 'System requirement version', 'd4plib' ), $req[0], '<strong>' . $req[2] . '</strong>', '<strong style="color: #900;">' . $req[0] . '</strong>' );
			} else {
				/* translators: Plugin activation system requirements notice, not valid. %1$s: Requirement Name. %2$s: Requirements Version. %3$s: Active version. */
				$versions[] = sprintf( _x( '%1$s version %2$s (your system runs version %3$s)', 'System requirement version', 'd4plib' ), $req[0], '<strong>' . $req[2] . '</strong>', '<strong style="color: #900;">' . $req[1] . '</strong>' );
			}
		}

		$render = '<div class="notice notice-error"><p>';
		/* translators: Plugin activation system requirements notice, failed. %1$s: Plugin Name. %2$s: Requirements list. */
		$render .= sprintf( _x( 'System requirements check for %1$s failed. This plugin requires %2$s. The plugin will now be disabled.', 'System requirement notice', 'd4plib' ), '<strong>' . $plugin . '</strong>', join( ', ', $versions ) );
		$render .= '</p></div>';

		echo KSES::standard( $render ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$this->deactivate();
	}

	public function store_widget_instance( $instance ) {
		$this->_widget_instance = (array) $instance;
	}

	public function widget_instance() : array {
		return $this->_widget_instance;
	}

	public function cron_license_validation() {
		if ( $this->license ) {
			$this->l()->validate();
		}
	}

	public function dashboard_license_validation() {
		$dashboard = $this->s()->get( 'dashboard', 'license' );

		if ( $dashboard + DAY_IN_SECONDS < time() ) {
			if ( ! WPR::is_scheduled_single( $this->plugin . '-license-validation' ) ) {
				$this->s()->set( 'dashboard', time(), 'license', true, true );

				wp_schedule_single_event( time() + 5, $this->plugin . '-license-validation' );
			}
		}
	}

	protected function check_system_requirements() : array {
		if ( defined( 'DEV4PRESS_NO_SYSREQ_CHECK' ) && DEV4PRESS_NO_SYSREQ_CHECK ) {
			return array();
		}

		global $wpdb;

		$list = array();

		$cms = $this->s()->i()->requirement_version( WordPress::instance()->cms() );

		if ( WordPress::instance()->is_version_equal_or_higher( $cms ) === false ) {
			$list[] = array( WordPress::instance()->cms_title(), WordPress::instance()->version(), $cms );
		}

		$php = $this->s()->i()->requirement_version( 'php' );

		if ( version_compare( Library::instance()->php_version(), $php, '>=' ) === false ) {
			$list[] = array( 'PHP', Library::instance()->php_version(), $php );
		}

		$mysql = $this->s()->i()->requirement_version( 'mysql' );

		if ( version_compare( $wpdb->db_version(), $mysql, '>=' ) === false ) {
			$list[] = array( 'MySQL', $wpdb->db_version(), $mysql );
		}

		$bbpress = $this->s()->i()->requirement_version( 'bbpress' );

		if ( $bbpress !== false ) {
			if ( BBP::is_active() ) {
				$installed = bbp_get_version();

				if ( version_compare( $installed, $bbpress, '>=' ) === false ) {
					$list[] = array( 'bbPress', $installed, $bbpress );
				}
			} else {
				$list[] = array( 'bbPress', 0, $bbpress );
			}
		}

		return $list;
	}

	abstract public function run();

	/** @return NULL|\Dev4Press\v51\Core\Plugins\Settings */
	abstract public function s();

	/** @return NULL|\Dev4Press\v51\Core\Plugins\Settings */
	abstract public function b();

	/** @return NULL|\Dev4Press\v51\Core\Features\Load */
	abstract public function f();

	/** @return NULL|\Dev4Press\v51\Core\Plugins\License */
	abstract public function l();
}
