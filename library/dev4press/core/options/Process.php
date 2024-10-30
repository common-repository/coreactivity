<?php
/**
 * Name:    Dev4Press\v51\Core\Options\Process
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

namespace Dev4Press\v51\Core\Options;

use Dev4Press\v51\Core\Quick\Arr;
use Dev4Press\v51\Core\Quick\Sanitize;
use Dev4Press\v51\Core\Quick\Str;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Process {
	public string $base;
	public string $prefix;
	public array $settings;
	public array $bool_values = array(
		true,
		false,
	);

	public function __construct( $base = 'dev4press-value', $prefix = 'dev4press' ) {
		$this->base   = $base;
		$this->prefix = $prefix;
	}

	public static function instance( $base = 'dev4press-value', $prefix = 'dev4press' ) : Process {
		static $process = array();

		if ( ! isset( $process[ $base ] ) ) {
			$process[ $base ] = new Process( $base, $prefix );
		}

		return $process[ $base ];
	}

	public function prepare( $settings ) : Process {
		$this->settings = $settings;

		return $this;
	}

	public function process( array $request ) : array {
		$list = array();

		foreach ( $this->settings as $setting ) {
			if ( $setting->type != '_' ) {
				$post = $request[ $setting->type ] ?? array();

				$list[ $setting->type ][ $setting->name ] = $this->process_single( $setting, $post );
			}
		}

		return $list;
	}

	public function slug_slashes( $key ) {
		$key = strtolower( $key );

		return preg_replace( '/[^a-z0-9.\/_\-]/', '', $key );
	}

	public function process_single( $setting, $post ) {
		$input = $setting->input;
		$base  = $post[ $setting->name ] ?? '';

		switch ( $input ) {
			default:
				$value = apply_filters( $this->prefix . '_process_option_call_back_for_' . $input, null, $base, $setting );

				if ( is_null( $value ) ) {
					$value = Sanitize::text( (string) $base );
				}
				break;
			case 'skip':
			case 'info':
			case 'hr':
			case 'custom':
				$value = null;
				break;
			case 'expandable_raw':
				$value = array();

				if ( is_array( $base ) ) {
					foreach ( $base as $id => $data ) {
						if ( $id > 0 ) {
							$_val = trim( stripslashes( $data['value'] ) );

							if ( $_val != '' ) {
								$value[] = $_val;
							}
						}
					}
				}
				break;
			case 'expandable_text':
				$value = array();

				if ( is_array( $base ) ) {
					foreach ( $base as $id => $data ) {
						if ( $id > 0 ) {
							$_val = Sanitize::text( $data['value'] );

							if ( ! empty( $_val ) ) {
								$value[] = $_val;
							}
						}
					}
				}
				break;
			case 'expandable_pairs':
				$value = array();

				if ( is_array( $base ) ) {
					foreach ( $base as $id => $data ) {
						if ( $id > 0 ) {
							$_key = Sanitize::text( $data['key'] );
							$_val = Sanitize::text( $data['value'] );

							if ( ! empty( $_key ) && ! empty( $_val ) ) {
								$value[ $_key ] = $_val;
							}
						}
					}
				}
				break;
			case 'range_integer':
				$value = intval( $base['a'] ?? 0 ) . '=>' . intval( $base['b'] ?? 0 );
				break;
			case 'range_absint':
				$value = absint( $base['a'] ?? 0 ) . '=>' . absint( $base['b'] ?? 0 );
				break;
			case 'x_by_y':
				$value = Sanitize::text( $base['x'] ?? '' ) . 'x' . Sanitize::text( $base['y'] ?? '' );
				break;
			case 'html':
			case 'code':
			case 'text_html':
			case 'text_rich':
				$value = Sanitize::html( $base );
				break;
			case 'bool':
				$value = ! empty( $base ) ? $this->bool_values[0] : $this->bool_values[1];
				break;
			case 'number':
				$value = floatval( $base );
				break;
			case 'integer':
				$value = intval( $base );
				break;
			case 'image':
			case 'absint':
			case 'dropdown_pages':
			case 'dropdown_categories':
				$value = absint( $base );
				break;
			case 'images':
				if ( empty( $base ) ) {
					$value = array();
				} else {
					$value = Sanitize::ids_list( (array) $base );
				}
				break;
			case 'listing':
				$value = Str::split_to_list( stripslashes( $base ) );
				break;
			case 'media':
				$value = 0;
				if ( ! empty( $base ) ) {
					$value = Sanitize::absint( substr( $base, 3 ) );
				}
				break;
			case 'checkboxes':
			case 'checkboxes_hierarchy':
			case 'select_multi':
			case 'group_multi':
				if ( empty( $base ) ) {
					$value = array();
				} else {
					$value = array_map( '\Dev4Press\v51\Core\Quick\Sanitize::text', (array) $base );
				}
				break;
			case 'css_size':
				$sizes = Arr::get_css_size_units();

				$value = Sanitize::text( $base['val'] ?? '' );
				$unit  = strtolower( Sanitize::text( $base['unit'] ?? '' ) );

				if ( ! isset( $sizes[ $unit ] ) ) {
					$unit = 'px';
				}

				$value = $value . $unit;
				break;
			case 'slug':
				$value = Sanitize::slug( $base );
				break;
			case 'slug_ext':
				$value = Sanitize::key( $base );
				break;
			case 'slug_slash':
				$value = Sanitize::slag_with_slashes( $base );
				break;
			case 'email':
				$value = Sanitize::email( $base );
				break;
			case 'date':
				$value = Sanitize::date( $base );
				break;
			case 'time':
				$value = Sanitize::time( $base );
				break;
			case 'datetime':
				$value = Sanitize::date( $base, 'Y-m-d H:i:s' );
				break;
			case 'month':
				$value = Sanitize::month( $base );
				break;
			case 'license':
				$value = Sanitize::text( $base );
				$check = preg_match( '/^\d{4}-\d{8}-[A-Z0-9]{6}-[A-Z0-9]{6}-\d{4}$/', $value );

				if ( $check !== 1 ) {
					$value = '';
				}
				break;
			case 'text':
			case 'textarea':
			case 'password':
			case 'group':
			case 'link':
			case 'color':
			case 'block':
			case 'hidden':
			case 'select':
			case 'radios':
			case 'radios_hierarchy':
				$value = Sanitize::text( $base );
				break;
		}

		return $value;
	}
}
