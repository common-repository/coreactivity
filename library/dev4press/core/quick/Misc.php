<?php
/**
 * Name:    Dev4Press\v51\Core\Quick\Misc
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

use Dev4Press\v51\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Misc {
	public static function get_regex_error( $error_code ) : string {
		if ( is_bool( $error_code ) ) {
			return 'OK';
		}

		$errors = array_flip( get_defined_constants( true )['pcre'] );

		if ( isset( $errors[ $error_code ] ) ) {
			return $errors[ $error_code ];
		}

		return 'UNKNOWN_ERROR';
	}

	public static function php_ini_size_value( $name ) {
		$ini = ini_get( $name );

		if ( $ini === false ) {
			return 0;
		}

		$ini  = trim( $ini );
		$last = strtoupper( $ini[ strlen( $ini ) - 1 ] );
		$ini  = absint( substr( $ini, 0, strlen( $ini ) - 1 ) );

		switch ( $last ) {
			case 'G':
				$ini = $ini * GB_IN_BYTES;
				break;
			case 'M':
				$ini = $ini * MB_IN_BYTES;
				break;
			case 'K':
				$ini = $ini * KB_IN_BYTES;
				break;
		}

		return $ini;
	}

	public static function hex_to_rgba( string $color, string $default = '0,0,0' ) : string {
		if ( empty( $color ) ) {
			return $default;
		}

		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} else if ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		$rgb = array_map( 'hexdec', $hex );

		return implode( ',', $rgb );
	}

	public static function flag_from_country_code( string $country_code, string $location, string $status = 'active', string $not_found = 'image' ) : string {
		$_base = Library::instance()->url() . 'resources/vendor/flags/img/flag_placeholder.png';

		if ( $status == 'active' ) {
			if ( $country_code != '' ) {
				return '<img src="' . $_base . '" class="flag flag-' . strtolower( $country_code ) . '" title="' . $location . '" alt="' . $location . '" />';
			}
		} else if ( $status == 'private' ) {
			return '<img src="' . $_base . '" class="flag" title="' . esc_html__( 'Localhost or Private IP', 'd4plib' ) . '" alt="' . esc_html__( 'Localhost or Private IP', 'd4plib' ) . '" />';
		}

		if ( $not_found == 'image' ) {
			return '<img src="' . $_base . '" class="flag" title="' . esc_html__( 'IP can\'t be located.', 'd4plib' ) . '" alt="' . esc_html__( 'IP can\'t be located.', 'd4plib' ) . '" />';
		} else {
			return '';
		}
	}
}
