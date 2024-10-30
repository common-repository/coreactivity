<?php
/**
 * Name:    Dev4Press\v51\Core\Task\AJAX
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

namespace Dev4Press\v51\Core\Task;

use Dev4Press\v51\Core\Base\Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AJAX extends Background {
	protected $method = 'ajax';
	protected $nonce = '';
	protected $action = '';

	public function __construct() {
		parent::__construct();

		add_action( 'wp_ajax_' . $this->action, array( $this, 'handler' ) );
		add_action( 'wp_ajax_nopriv_' . $this->action, array( $this, 'handler' ) );
	}

	protected function prepare() {
		if ( isset( $_REQUEST['_ajax_nonce'] ) && wp_verify_nonce( $_REQUEST['_ajax_nonce'], $this->nonce ) ) {
			return;
		}

		wp_die( - 1 );
	}

	protected function spawn() {
		$url = admin_url( 'admin-ajax.php' );
		$url = add_query_arg( array(
			'action'      => $this->action,
			'_ajax_nonce' => wp_create_nonce( $this->nonce ),
		), $url );

		sleep( $this->delay );

		wp_remote_get( $url, array(
				'method'      => 'GET',
				'timeout'     => 60,
				'redirection' => 5,
				'httpversion' => '1.1',
				'blocking'    => false,
				'headers'     => array(),
			)
		);

		if ( wp_doing_ajax() ) {
			header( "Expires: Wed, 10 Aug 2000 00:00:00 GMT" );
			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
			header( "Cache-Control: no-store, no-cache, must-revalidate" );
			header( "Cache-Control: post-check=0, pre-check=0", false );
			header( "Pragma: no-cache" );

			http_response_code( 204 );
			die();
		}
	}
}
