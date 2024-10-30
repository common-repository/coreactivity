<?php
/**
 * Name:    Dev4Press\v51\Services\GEOIP\Location
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

namespace Dev4Press\v51\Service\GEOIP;

use Dev4Press\v51\Core\Helpers\Data;
use Dev4Press\v51\Core\Quick\Misc;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Location {
	public $status = 'invalid';

	public $ip = '';
	public $country_code = '';
	public $country_name = '';
	public $region_code = '';
	public $region_name = '';
	public $city = '';
	public $zip_code = '';
	public $time_zone = '';
	public $latitude = '';
	public $longitude = '';

	public $continent_code = '';

	public function __construct( $input ) {
		foreach ( (array) $input as $key => $value ) {
			if ( property_exists( $this, $key ) ) {
				$this->$key = $value;
			}
		}
	}

	public function location() : string {
		$location = '';

		if ( $this->status == 'active' && ! empty( $this->country_name ) ) {
			$location .= $this->country_name;

			if ( ! empty( $this->region_name ) ) {
				$location .= ', ' . $this->region_name;
			}

			if ( ! empty( $this->city ) ) {
				$location .= ', ' . $this->city;
			}

			if ( ! empty( $this->continent_code ) ) {
				$location .= ' (' . $this->continent() . ')';
			}
		}

		return $location;
	}

	public function flag( string $not_found = 'image' ) : string {
		return Misc::flag_from_country_code( $this->country_code, $this->location(), $this->status, $not_found );
	}

	public function serialize( string $format = 'json' ) {
		return $format == 'json' ? wp_json_encode( $this->data() ) : maybe_serialize( $this->data() );
	}

	public function data() : array {
		$data = (array) $this;

		return array_filter( $data );
	}

	public function meta() : array {
		$data = $this->data();

		foreach ( array( 'status', 'ip', 'country_code', 'country_name' ) as $key ) {
			if ( isset( $data[ $key ] ) ) {
				unset( $data[ $key ] );
			}
		}

		return $data;
	}

	public function continent() {
		if ( empty( $this->continent_code ) ) {
			return '';
		}

		$list = Data::list_of_continents();

		return $list[ $this->continent_code ] ?? $this->continent_code;
	}
}
