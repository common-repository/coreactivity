<?php
/**
 * Name:    Dev4Press\v51\Core\Quick\Request
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

namespace Dev4Press\v51\Core\Quick;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Request {
	public static function is_post() : bool {
		return isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'POST';
	}

	public static function is_get() : bool {
		return isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'GET';
	}

	public static function has_key( $key, $scope = 'REQUEST' ) : bool {
		switch ( $scope ) {
			default:
			case 'REQUEST':
				return isset( $_REQUEST[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification
			case 'POST':
				return isset( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification
			case 'GET':
				return isset( $_GET[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification
		}
	}
}
