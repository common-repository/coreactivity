<?php
/**
 * Name:    Dev4Press\v51\Core\Shared\Enqueue
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

namespace Dev4Press\v51\Core\Shared;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Resources {
	private $_ui = array(
		'js'  => array(
			'meta'          => array(
				'path' => 'js/',
				'file' => 'meta',
				'ext'  => 'js',
				'min'  => true,
			),
			'media'         => array(
				'path' => 'js/',
				'file' => 'media',
				'ext'  => 'js',
				'min'  => true,
			),
			'ctrl'          => array(
				'path' => 'js/',
				'file' => 'ctrl',
				'ext'  => 'js',
				'min'  => true,
			),
			'customizer'    => array(
				'path' => 'js/',
				'file' => 'customizer',
				'ext'  => 'js',
				'min'  => true,
			),
			'widgets'       => array(
				'path' => 'js/',
				'file' => 'widgets',
				'ext'  => 'js',
				'min'  => true,
			),
			'wizard'        => array(
				'path' => 'js/',
				'file' => 'wizard',
				'ext'  => 'js',
				'min'  => true,
			),
			'confirmsubmit' => array(
				'path' => 'js/',
				'file' => 'confirmsubmit',
				'ext'  => 'js',
				'min'  => true,
			),
			'dialogs'       => array(
				'path' => 'js/',
				'file' => 'dialogs',
				'ext'  => 'js',
				'min'  => true,
			),
			'admin'         => array(
				'path' => 'js/',
				'file' => 'admin',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'dialogs', 'confirmsubmit' ),
			),
		),
		'css' => array(
			'pack'       => array(
				'path' => 'css/',
				'file' => 'pack',
				'ext'  => 'css',
				'min'  => true,
			),
			'pack-embed' => array(
				'path' => 'css/',
				'file' => 'pack-embed',
				'ext'  => 'css',
				'min'  => true,
			),
			'about'      => array(
				'path' => 'css/',
				'file' => 'about',
				'ext'  => 'css',
				'min'  => true,
			),
			'grid'       => array(
				'path' => 'css/',
				'file' => 'grid',
				'ext'  => 'css',
				'min'  => true,
			),
			'ctrl'       => array(
				'path' => 'css/',
				'file' => 'ctrl',
				'ext'  => 'css',
				'min'  => true,
			),
			'meta'       => array(
				'path' => 'css/',
				'file' => 'meta',
				'ext'  => 'css',
				'min'  => true,
			),
			'options'    => array(
				'path' => 'css/',
				'file' => 'options',
				'ext'  => 'css',
				'min'  => true,
			),
			'shared'     => array(
				'path' => 'css/',
				'file' => 'shared',
				'ext'  => 'css',
				'min'  => true,
			),
			'widgets'    => array(
				'path' => 'css/',
				'file' => 'widgets',
				'ext'  => 'css',
				'min'  => true,
			),
			'customizer' => array(
				'path' => 'css/',
				'file' => 'customizer',
				'ext'  => 'css',
				'min'  => true,
			),
			'admin'      => array(
				'path' => 'css/',
				'file' => 'admin',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'shared' ),
			),
			'wizard'     => array(
				'path' => 'css/',
				'file' => 'wizard',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'admin' ),
			),
			'rtl'        => array(
				'path' => 'css/',
				'file' => 'rtl',
				'ext'  => 'css',
				'min'  => true,
			),
			'balloon'    => array(
				'path' => 'css/',
				'file' => 'balloon',
				'ext'  => 'css',
				'min'  => true,
			),
		),
	);

	private $_shared = array(
		'js'  => array(
			'animated-popup'         => array(
				'lib'  => true,
				'path' => 'animated-popup/',
				'file' => 'animated-popup.min',
				'ver'  => '2.0',
				'ext'  => 'js',
				'min'  => false,
			),
			'micromodal'             => array(
				'lib'  => true,
				'path' => '',
				'file' => 'micromodal.min',
				'ver'  => '0.4.10',
				'ext'  => 'js',
				'min'  => false,
			),
			'flatpickr'              => array(
				'lib'        => true,
				'path'       => 'flatpickr/',
				'file'       => 'flatpickr.min',
				'ver'        => '4.6.13',
				'ext'        => 'js',
				'min'        => false,
				'min_locale' => true,
				'locales'    => array(
					'bg',
					'da',
					'de',
					'es',
					'fr',
					'it',
					'nl',
					'nn',
					'no',
					'pl',
					'pt',
					'ru',
					'sl',
					'sr',
					'sv',
				),
			),
			'flatpickr-confirm-date' => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'confirm-date',
				'ver'  => '4.6.13',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' ),
			),
			'flatpickr-month-select' => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'month-select',
				'ver'  => '4.6.13',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' ),
			),
			'flatpickr-week-select'  => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'week-select',
				'ver'  => '4.6.13',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' ),
			),
			'flatpickr-minmax-time'  => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'minmax-time',
				'ver'  => '4.6.13',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' ),
			),
			'flatpickr-range'        => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'range',
				'ver'  => '4.6.13',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' ),
			),
			'cookies'                => array(
				'lib'  => true,
				'path' => '',
				'file' => 'cookies.min',
				'ver'  => '3.0.1',
				'ext'  => 'js',
				'min'  => false,
			),
			'kjua'                   => array(
				'lib'  => true,
				'path' => '',
				'file' => 'kjua.min',
				'ver'  => '1.13.1',
				'ext'  => 'js',
				'min'  => false,
			),
			'mark'                   => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.mark.min',
				'ver'  => '9.0.0',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' ),
			),
			'fitvids'                => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.fitvids.min',
				'ver'  => '1.2.0',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' ),
			),
			'select'                 => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.select.min',
				'ver'  => '2.2.6',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' ),
			),
		),
		'css' => array(
			'font'                   => array(
				'lib'  => false,
				'path' => 'css/',
				'file' => 'font',
				'ext'  => 'css',
				'min'  => true,
			),
			'font-embed'             => array(
				'lib'  => false,
				'path' => 'css/',
				'file' => 'font-embed',
				'ext'  => 'css',
				'min'  => true,
			),
			'animated-popup'         => array(
				'lib'  => true,
				'path' => 'animated-popup/',
				'file' => 'animated-popup.min',
				'ver'  => '2.0',
				'ext'  => 'css',
				'min'  => false,
			),
			'micromodal'             => array(
				'lib'  => false,
				'path' => 'css',
				'file' => 'micromodal',
				'ver'  => '0.4.10',
				'ext'  => 'css',
				'min'  => true,
			),
			'flags'                  => array(
				'lib'  => true,
				'path' => 'flags/css/',
				'file' => 'flags.min',
				'ver'  => '2015.10',
				'ext'  => 'css',
				'min'  => false,
			),
			'flatpickr'              => array(
				'lib'  => true,
				'path' => 'flatpickr/',
				'file' => 'flatpickr.min',
				'ver'  => '4.6.13',
				'ext'  => 'css',
				'min'  => false,
			),
			'flatpickr-confirm-date' => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'confirm-date',
				'ver'  => '4.6.13',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'flatpickr' ),
			),
			'flatpickr-month-select' => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'month-select',
				'ver'  => '4.6.13',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'flatpickr' ),
			),
			'grid-table'             => array(
				'lib'  => false,
				'path' => 'css/',
				'file' => 'table',
				'ext'  => 'css',
				'min'  => true,
			),
		),
	);

	public function __construct() {

	}

	public static function instance() : Resources {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Resources();
		}

		return $instance;
	}

	public function shared_js() : array {
		return $this->_shared['js'];
	}

	public function shared_css() : array {
		return $this->_shared['css'];
	}

	public function ui_js() : array {
		return $this->_ui['js'];
	}

	public function ui_css() : array {
		return $this->_ui['css'];
	}
}
