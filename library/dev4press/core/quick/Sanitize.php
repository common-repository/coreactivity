<?php
/**
 * Name:    Dev4Press\v51\Core\Quick\Sanitize
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

use DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sanitize {
	public static function date( $value, $format = 'Y-m-d', $return_on_error = '' ) : string {
		$dt = DateTime::createFromFormat( '!' . $format, $value );

		if ( $dt === false ) {
			return $return_on_error;
		}

		return $dt->format( $format );
	}

	public static function time( $value, $format = 'H:i:s', $return_on_error = '' ) : string {
		return self::date( $value, $format, $return_on_error );
	}

	public static function month( $value, $format = 'Y-m', $return_on_error = '' ) : string {
		return self::date( $value, $format, $return_on_error );
	}

	public static function absint( $value ) : int {
		return absint( $value );
	}

	public static function email( $email ) : string {
		return sanitize_email( $email );
	}

	public static function url( $url ) : string {
		return sanitize_url( $url ); // phpcs:ignore WordPress.WP.DeprecatedFunctions
	}

	public static function key( $text ) : string {
		$text = stripslashes( (string) $text );

		return sanitize_key( $text );
	}

	public static function slug( $text ) : string {
		if ( is_null( $text ) ) {
			return '';
		}

		$text = stripslashes( (string) $text );

		return trim( sanitize_title_with_dashes( $text ), "-_ \t\n\r\0\x0B" );
	}

	public static function slag_with_slashes( $text ) : string {
		if ( is_null( $text ) ) {
			return '';
		}

		$text = stripslashes( (string) $text );
		$text = strtolower( $text );

		return preg_replace( '/[^a-z0-9.\/_\-]/', '', $text );
	}

	public static function text( $text, bool $strip_shortcodes = false ) : string {
		if ( is_null( $text ) ) {
			return '';
		}

		$text = stripslashes( (string) $text );

		if ( $strip_shortcodes ) {
			$text = strip_shortcodes( $text );
		}

		return trim( wp_kses( $text, array() ) );
	}

	public static function html( $text, $tags = null, $protocols = array(), bool $strip_shortcodes = false ) : string {
		if ( is_null( $text ) ) {
			return '';
		}

		$tags = is_null( $tags ) ? 'post' : $tags;
		$text = stripslashes( (string) $text );

		if ( $strip_shortcodes ) {
			$text = strip_shortcodes( $text );
		}

		return wp_kses( trim( $text ), $tags, $protocols );
	}

	public static function deep( array $input, string $method, bool $strip_shortcodes = false ) : array {
		switch ( $method ) {
			default:
			case 'html':
				$input = map_deep( $input, '\Dev4Press\v51\Core\Quick\Sanitize::html' );
				break;
			case 'text':
				$input = map_deep( $input, '\Dev4Press\v51\Core\Quick\Sanitize::text' );
				break;
			case 'key':
				$input = map_deep( $input, '\Dev4Press\v51\Core\Quick\Sanitize::key' );
				break;
			case 'slug':
				$input = map_deep( $input, '\Dev4Press\v51\Core\Quick\Sanitize::slug' );
				break;
		}

		if ( $strip_shortcodes ) {
			$input = map_deep( $input, 'strip_shortcodes' );
		}

		return $input;
	}

	public static function html_classes( $classes ) : string {
		$list = is_array( $classes ) ? $classes : explode( ' ', trim( stripslashes( $classes ) ) );
		$list = array_map( 'sanitize_html_class', $list );

		return trim( join( ' ', $list ) );
	}

	public static function ids_list( $ids, $map = 'absint' ) : array {
		if ( empty( $ids ) ) {
			return array();
		}

		$ids = (array) $ids;

		$ids = array_map( $map, $ids );
		$ids = array_unique( $ids );

		return array_filter( $ids );
	}

	public static function file_path( $filename ) : string {
		$filename_raw = $filename;

		$special_chars = apply_filters(
			__NAMESPACE__ . '\sanitize\file_path_chars',
			array(
				'?',
				'[',
				']',
				'/',
				'\\',
				'=',
				'<',
				'>',
				':',
				';',
				',',
				"'",
				'"',
				'&',
				'$',
				'#',
				'*',
				'(',
				')',
				'|',
				'~',
				'`',
				'!',
				'{',
				'}',
				'%',
				'+',
				chr( 0 ),
			),
			$filename_raw
		);

		$filename = preg_replace( "#\x{00a0}#siu", ' ', $filename );
		$filename = str_replace( $special_chars, '', $filename );
		$filename = str_replace( array( '%20', '+' ), '-', $filename );
		$filename = preg_replace( '/[\r\n\t -]+/', '-', $filename );
		$filename = trim( $filename, '.-_' );

		return apply_filters( __NAMESPACE__ . '\sanitize\file_path', $filename, $filename_raw );
	}

	public static function _get_switch_array( $key, $sub_key = false, $value = 'on' ) : array {
		$source = self::deep( $_POST[ $key ] ?? array(), 'key' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		$source = $sub_key !== false ? ( $source[ $sub_key ] ?? array() ) : $source;
		$result = array();

		foreach ( $source as $name => $val ) {
			if ( $value === $val ) {
				$result[] = sanitize_key( $name );
			}
		}

		return $result;
	}

	public static function _get_slug( string $name, string $default = '' ) : string {
		return ! empty( $_GET[ $name ] ) ? self::slug( $_GET[ $name ] ) : $default; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
	}

	public static function _get_text( string $name, string $default = '' ) : string {
		return ! empty( $_GET[ $name ] ) ? self::text( $_GET[ $name ] ) : $default; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
	}

	public static function _get_absint( string $name, int $default = 0 ) : int {
		return ! empty( $_GET[ $name ] ) ? absint( $_GET[ $name ] ) : $default; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
	}

	public static function _get_ids( string $name, array $default = array() ) : array {
		$ids = isset( $_GET[ $name ] ) ? (array) $_GET[ $name ] : $default; // phpcs:ignore WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput

		return self::ids_list( $ids );
	}
}
