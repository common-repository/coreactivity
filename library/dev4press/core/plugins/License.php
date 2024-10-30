<?php
/**
 * Name:    Dev4Press\v51\Core\Plugins\License
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

use Dev4Press\v51\Core\Quick\URL;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class License {
	protected string $freemius = '';
	protected string $plugin = '';
	private string $site_url = '';

	public function __construct() {
		$this->site_url = wp_parse_url( network_site_url(), PHP_URL_HOST );
	}

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function dashboard() {
		if ( $this->is_freemius() ) {
			return;
		}

		$this->plugin()->dashboard_license_validation();
	}

	public function get_upgrade_url() : string {
		return URL::add_campaign_tracking( 'https://www.dev4press.com/plugins/' . $this->plugin . '/pricing/', 'license-upgrade' );
	}

	public function is_freemius() : bool {
		return ! empty( $this->freemius ) && function_exists( $this->freemius );
	}

	public function can_use_premium_code__premium_only() : bool {
		return $this->is_valid();
	}

	public function is_valid() : bool {
		if ( $this->is_freemius() ) {
			return true;
		}

		$code   = $this->get_license_code();
		$info   = $this->plugin()->s()->raw_get( 'info', 'license' );
		$record = $this->plugin()->s()->raw_get( 'record', 'license' );

		if ( ! empty( $code ) ) {
			if ( $record == 'in-progress' ) {
				return true;
			}

			if ( $record == 'server-problem' ) {
				return ( $info['valid'] ?? 'no' ) == 'yes';
			}

			if ( $record == 'valid' && ( $info['valid'] ?? 'no' ) == 'yes' ) {
				return true;
			}
		}

		return false;
	}

	public function get_license_code() {
		$code = $this->get_raw_license_code();

		$check = preg_match( '/^\d{4}-\d{8}-[A-Z0-9]{6}-[A-Z0-9]{6}-\d{4}$/', $code );

		if ( $check !== 1 ) {
			$code = '';
		}

		return $code;
	}

	public function get( $api, $version, $format = 'json' ) : array {
		$code = $this->get_license_code();
		$url  = $this->api_url( $api, $version, $code );

		$response = wp_remote_get( $url );

		return $this->process_response( $response, $format );
	}

	public function post( $api, $version, $data, $format = 'json' ) : array {
		$code = $this->get_license_code();
		$url  = $this->api_url( $api, $version, $code );

		$options = array(
			'timeout' => 60,
			'method'  => 'POST',
			'body'    => wp_json_encode( $data ),
			'headers' => array(
				'X-Dev4press-Source-Domain' => $this->site_url,
				'Referer'                   => URL::current_url(),
			),
		);

		$response = wp_remote_post( $url, $options );

		return $this->process_response( $response, $format );
	}

	public function validate() {
		if ( $this->is_freemius() ) {
			return;
		}

		$code_raw = $this->get_raw_license_code();
		$code     = $this->get_license_code();
		$record   = 'valid';
		$result   = array();
		$last     = array();

		if ( empty( $code_raw ) ) {
			$record = 'empty';
			$result = array(
				'status' => 'empty',
				'valid'  => 'no',
			);
		} else if ( empty( $code ) ) {
			$record = 'invalid';
			$result = array(
				'status' => 'invalid',
				'valid'  => 'no',
			);
			$last   = array(
				'error'   => 'code-invalid',
				'message' => __( 'Code is not in a valid format.', 'd4plib' ),
			);
		} else {
			$url = $this->validation_url( $code, $this->plugin()->plugin );

			$options = array(
				'timeout' => 60,
				'headers' => array(
					'X-Dev4press-Source-Domain' => $this->site_url,
					'Referer'                   => URL::current_url(),
				),
			);

			$response = wp_remote_get( $url, $options );
			$message  = '';

			if ( ! is_wp_error( $response ) ) {
				$body  = wp_remote_retrieve_body( $response );
				$json  = json_decode( $body, true );
				$error = 'invalid-response';

				if ( is_array( $json ) && isset( $json['obj'] ) && isset( $json['uts'] ) && isset( $json['sig'] ) ) {
					$check = sha1( wp_json_encode( $json['obj'] ) . '.' . $json['uts'] );

					if ( $check == $json['sig'] ) {
						$error  = $json['obj']['error'] ?? '';
						$result = array(
							'status'  => $json['obj']['status'] ?? 'invalid',
							'valid'   => $json['obj']['valid'] ?? 'no',
							'api'     => $json['obj']['api'] ?? 'no',
							'control' => $json['obj']['control'] ?? 'no',
							'domain'  => $json['obj']['website'] ?? '',
							'entry'   => $json['obj']['entry'] ?? '',
							'type'    => $json['obj']['type'] ?? '',
						);

						if ( $result['valid'] == 'no' ) {
							$record = 'invalid';
						}

						if ( ! empty( $error ) ) {
							$result['error'] = $error;
						}
					}
				}

				if ( empty( $result ) ) {
					$record = 'server-problem';

					$last = array(
						'error'   => 'invalid-response',
						'message' => __( 'Validation server response is not valid.', 'd4plib' ),
					);
				}
			} else {
				$record  = 'server-problem';
				$error   = 'request-failed';
				$message = $response->get_error_message();
			}

			if ( empty( $result ) ) {
				$last = array(
					'error'   => $error,
					'message' => $message,
				);

				$result = $this->plugin()->s()->raw_get( 'info', 'license' );
			}
		}

		$this->plugin()->s()->bulk( array(
			'info'   => $result,
			'check'  => time(),
			'last'   => $last,
			'record' => $record,
		), 'license', true, true );
	}

	private function validation_url( $code, $plugin ) : string {
		$url = 'https://api.dev4press.com/license/1.0/%s/%s/%s/';

		return sprintf( $url, $code, $this->site_url, $plugin );
	}

	private function api_url( $api, $version, $code ) : string {
		$url = 'https://api.dev4press.com/%s/%s/%s/%s/';

		return sprintf( $url, $api, $version, $code, $this->site_url );
	}

	protected function get_raw_license_code() {
		return $this->plugin()->s()->raw_get( 'code', 'license' );
	}

	protected function process_response( $response, $format = 'json' ) : array {
		if ( is_wp_error( $response ) ) {
			return array(
				'error'   => $response->get_error_code(),
				'message' => $response->get_error_message(),
			);
		} else {
			$code = wp_remote_retrieve_response_code( $response );
			$body = wp_remote_retrieve_body( $response );

			if ( $format == 'raw' ) {
				return array(
					'code' => $code,
					'body' => $body,
				);
			} else if ( $format == 'json' ) {
				$data = json_decode( $body, true );

				if ( is_array( $data ) && ! empty( $data ) ) {
					if ( $code == 200 ) {
						return $data;
					} else {
						if ( isset( $data['error'] ) ) {
							return array(
								'error'   => $code,
								'message' => $data['error'],
							);
						}
					}
				}
			}

			return array(
				'error'   => $code,
				'message' => __( 'Nothing received', 'd4plib' ),
			);
		}
	}

	abstract protected function plugin();
}
