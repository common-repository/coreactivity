<?php
/**
 * Name:    Dev4Press\v51\Service\Media\Pixabay\Image
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

namespace Dev4Press\v51\Service\Media\Pixabay;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Image {
	public $id;
	public $type;
	public $url;
	public $slug;
	public $extension;

	public $likes;
	public $views;
	public $downloads;
	public $favorites;
	public $comments;
	public $tags;
	public $name;

	public $width;
	public $height;
	public $images;

	public $user;

	public function __construct( $response ) {
		$this->id        = $response->id;
		$this->type      = $response->type;
		$this->url       = $response->pageURL;
		$this->likes     = $response->likes ?? 0;
		$this->views     = $response->views ?? 0;
		$this->downloads = $response->downloads ?? 0;
		$this->favorites = $response->favorites ?? 0;
		$this->comments  = $response->comments ?? 0;

		$this->width  = $response->imageWidth;
		$this->height = $response->imageHeight;

		$this->user = (object) array(
			'id'    => $response->user_id,
			'name'  => $response->user,
			'image' => (object) array( 'url' => $response->userImageURL ),
		);

		preg_match( '/pixabay\.com\/photos\/(.+?)-\d+?\//', $this->url, $output );

		if ( ! empty( $output ) && isset( $output[1] ) ) {
			$this->slug = $output[1];
			$this->name = str_replace( '-', ' ', $output[1] );
			$this->name = ucfirst( $this->name );
		} else {
			$this->name = ucwords( join( ' ', $this->tags ) );
			$this->slug = sanitize_title( $this->name );
		}

		$this->tags = explode( ',', $response->tags );
		$this->tags = array_map( 'trim', $this->tags );

		$this->images = (object) array(
			'preview'  => $this->_process_image( 'preview', $response ),
			'web'      => $this->_process_image( 'webformat', $response ),
			'large'    => $this->_process_image( 'largeImage', $response ),
			'fullhd'   => $this->_process_image( 'fullHD', $response ),
			'original' => $this->_process_image( 'image', $response ),
			'vector'   => $this->_process_image( 'vector', $response ),
		);

		$this->extension = pathinfo( $this->images->large->url, PATHINFO_EXTENSION );
	}

	public function by_name( $size ) {
		if ( isset( $this->images->$size ) && ! is_null( $this->images->$size ) ) {
			return $this->images->$size;
		}

		return $this->largest();
	}

	public function largest() {
		$list = array( 'original', 'fullhd', 'large', 'web', 'preview' );

		foreach ( $list as $type ) {
			if ( ! is_null( $this->images->$type ) ) {
				return $this->images->$type;
			}
		}

		return null;
	}

	private function _process_image( $name, $response ) {
		if ( ! isset( $response->{$name . 'URL'} ) ) {
			return null;
		}

		$obj = array(
			'url' => $response->{$name . 'URL'},
		);

		if ( isset( $response->{$name . 'Width'} ) ) {
			$obj['width'] = $response->{$name . 'Width'};
		}

		if ( isset( $response->{$name . 'Height'} ) ) {
			$obj['height'] = $response->{$name . 'Height'};
		}

		return (object) $obj;
	}
}
