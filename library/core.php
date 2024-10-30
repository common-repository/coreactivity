<?php
/**
 * Name:    Dev4Press Core Loader
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

use Dev4Press\v51\Library;
use Dev4Press\v51\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_library_path = dirname( __FILE__ ) . '/';

if ( ! defined( 'DEV4PRESS_TAB' ) ) {
	define( 'DEV4PRESS_TAB', "\t" );
}

if ( ! defined( 'DEV4PRESS_V51_PATH' ) ) {
	define( 'DEV4PRESS_V51_PATH', $_library_path );
}

require DEV4PRESS_V51_PATH . 'functions/Core.php';
require DEV4PRESS_V51_PATH . 'functions/Bridge.php';

require DEV4PRESS_V51_PATH . 'autoload.php';

WordPress::instance();
Library::instance();
