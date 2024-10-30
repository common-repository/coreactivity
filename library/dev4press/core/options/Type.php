<?php
/**
 * Name:    Dev4Press\v51\Core\Options\Type
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Type {
	public const ABSINT = 'absint';
	public const BLOCK = 'block';
	public const BOOLEAN = 'bool';
	public const CHECKBOXES = 'checkboxes';
	public const CHECKBOXES_GROUP = 'checkboxes_group';
	public const CHECKBOXES_HIERARCHY = 'checkboxes_hierarchy';
	public const CODE = 'code';
	public const COLOR = 'color';
	public const CSS_SIZE = 'css_size';
	public const CUSTOM = 'custom';
	public const DATE = 'date';
	public const DATETIME = 'datetime';
	public const DROPDOWN_CATEGORIES = 'dropdown_categories';
	public const DROPDOWN_PAGES = 'dropdown_pages';
	public const EMAIL = 'email';
	public const EXPANDABLE_PAIRS = 'expandable_pairs';
	public const EXPANDABLE_RAW = 'expandable_raw';
	public const EXPANDABLE_TEXT = 'expandable_text';
	public const FILE = 'file';
	public const GROUP = 'group';
	public const GROUP_MULTI = 'group_multi';
	public const HIDDEN = 'hidden';
	public const HTML = 'html';
	public const IMAGE = 'image';
	public const IMAGES = 'images';
	public const INFO = 'info';
	public const INTEGER = 'integer';
	public const LICENSE = 'license';
	public const LINK = 'link';
	public const LISTING = 'listing';
	public const MONTH = 'month';
	public const NUMBER = 'number';
	public const PASSWORD = 'password';
	public const RADIOS = 'radios';
	public const RADIOS_HIERARCHY = 'radios_hierarchy';
	public const RANGE_ABSINT = 'range_absint';
	public const RANGE_INTEGER = 'range_integer';
	public const RICH = 'rich';
	public const SELECT = 'select';
	public const SELECT_MULTI = 'select_multi';
	public const SLUG = 'slug';
	public const SLUG_EXT = 'slug_ext';
	public const SLUG_SLASH = 'slug_slash';
	public const TEXT = 'text';
	public const TEXTAREA = 'textarea';
	public const TEXT_HTML = 'text_html';
	public const TIME = 'time';
	public const X_BY_Y = 'x_by_y';

	public static $_values = array(
		'info'                 => self::INFO,
		'absint'               => self::ABSINT,
		'block'                => self::BLOCK,
		'bool'                 => self::BOOLEAN,
		'checkboxes'           => self::CHECKBOXES,
		'checkboxes_group'     => self::CHECKBOXES_GROUP,
		'checkboxes_hierarchy' => self::CHECKBOXES_HIERARCHY,
		'code'                 => self::CODE,
		'color'                => self::COLOR,
		'css_size'             => self::CSS_SIZE,
		'custom'               => self::CUSTOM,
		'date'                 => self::DATE,
		'datetime'             => self::DATETIME,
		'dropdown_categories'  => self::DROPDOWN_CATEGORIES,
		'dropdown_pages'       => self::DROPDOWN_PAGES,
		'email'                => self::EMAIL,
		'expandable_pairs'     => self::EXPANDABLE_PAIRS,
		'expandable_raw'       => self::EXPANDABLE_RAW,
		'expandable_text'      => self::EXPANDABLE_TEXT,
		'file'                 => self::FILE,
		'group'                => self::GROUP,
		'group_multi'          => self::GROUP_MULTI,
		'hidden'               => self::HIDDEN,
		'html'                 => self::HTML,
		'image'                => self::IMAGE,
		'images'               => self::IMAGES,
		'integer'              => self::INTEGER,
		'license'              => self::LICENSE,
		'link'                 => self::LINK,
		'listing'              => self::LISTING,
		'month'                => self::MONTH,
		'number'               => self::NUMBER,
		'password'             => self::PASSWORD,
		'radios'               => self::RADIOS,
		'radios_hierarchy'     => self::RADIOS_HIERARCHY,
		'range_absint'         => self::RANGE_ABSINT,
		'range_integer'        => self::RANGE_INTEGER,
		'rich'                 => self::RICH,
		'select'               => self::SELECT,
		'select_multi'         => self::SELECT_MULTI,
		'slug'                 => self::SLUG,
		'slug_ext'             => self::SLUG_EXT,
		'slug_slash'           => self::SLUG_SLASH,
		'text'                 => self::TEXT,
		'text_html'            => self::TEXT_HTML,
		'textarea'             => self::TEXTAREA,
		'time'                 => self::TIME,
		'x_by_y'               => self::X_BY_Y,
	);

	public static function to_string( $value ) {
		if ( is_null( $value ) ) {
			return null;
		}

		if ( array_key_exists( $value, self::$_values ) ) {
			return self::$_values[ $value ];
		}

		return 'UNKNOWN';
	}
}
