<?php
/**
 * Name:    Dev4Press\v51\Core\UI\Widgets
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

namespace Dev4Press\v51\Core\UI;

use Dev4Press\v51\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Widgets {
	/** @var \Dev4Press\v51\Core\Admin\Plugin|\Dev4Press\v51\Core\Admin\Menu\Plugin|\Dev4Press\v51\Core\Admin\Submenu\Plugin */
	private $_admin;

	public function __construct( $admin ) {
		$this->_admin = $admin;
	}

	/** @return Widgets */
	public static function instance( $widget, $admin ) {
		static $_d4p_widgets_loader = array();

		if ( ! isset( $_d4p_widgets_loader[ $widget ] ) ) {
			$_d4p_widgets_loader[ $widget ] = new Widgets( $admin );
		}

		return $_d4p_widgets_loader[ $widget ];
	}

	public function a() {
		return $this->_admin;
	}

	public function forms_path_library() : string {
		return $this->a()->path . Library::instance()->base_path() . '/forms/';
	}

	public function forms_path_plugin() : string {
		return $this->a()->path . 'forms/widgets/';
	}

	public function find( $name, $fallback = 'fallback.php' ) : string {
		if ( file_exists( $this->forms_path_plugin() . $name ) ) {
			return $this->forms_path_plugin() . $name;
		} else {
			if ( file_exists( $this->forms_path_library() . $name ) ) {
				return $this->forms_path_library() . $name;
			} else {
				return $this->forms_path_library() . $fallback;
			}
		}
	}

	public function load( $name, $fallback = 'fallback.php' ) {
		include $this->find( $name, $fallback );
	}
}
