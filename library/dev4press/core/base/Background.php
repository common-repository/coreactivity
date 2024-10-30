<?php
/**
 * Name:    Dev4Press\v51\Core\Base\Background
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

namespace Dev4Press\v51\Core\Base;

use DateTime;
use Dev4Press\v51\Core\Helpers\IP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Background {
	protected $method = '';
	protected $transient = '';
	protected $abort_transient = '';

	protected $data = array();

	protected $timer = 0;
	protected $offset = 0;
	protected $max = 0;
	protected $delay = 10;
	protected $abort = '';

	public function __construct() {
		$this->max   = ini_get( 'max_execution_time' );
		$this->timer = $this->now();

		if ( $this->offset == 0 ) {
			$this->offset = absint( $this->max * .6 );
		}
	}

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function load() {
		if ( empty( $this->data ) ) {
			wp_raise_memory_limit();

			$this->prepare();
			$this->get();
		}
	}

	public function handler() {
		$this->load();

		if ( $this->abort == 'abort' ) {
			$this->add_message( __( 'Process has been aborted.', 'd4plib' ) );
			$this->status( 'abort' );

			$this->save();
		} else {
			if ( $this->data['status'] == 'working' ) {
				$this->worker();
			} else if ( $this->data['status'] == 'waiting' ) {
				$this->init();
			} else if ( $this->data['status'] == 'idle' ) {
				$this->data['info']['started'] = $this->now();
				$this->data['info']['user_id'] = get_current_user_id();
				$this->data['info']['ip']      = IP::visitor();

				$this->add_message( __( 'Getting ready to start.', 'd4plib' ) );
				$this->status( 'waiting' );

				$this->save();
				$this->spawn();
			}
		}
	}

	public function get() {
		$this->data  = get_site_transient( $this->transient );
		$this->abort = get_site_transient( $this->abort_transient );

		if ( $this->data === false ) {
			$this->data = $this->init_data();
		}

		if ( ! is_string( $this->abort ) ) {
			$this->abort = '';
		}
	}

	public function abort() {
		set_site_transient( $this->abort_transient, 'abort' );
	}

	public function delete() {
		delete_site_transient( $this->transient );
		delete_site_transient( $this->abort_transient );
	}

	public function stalled() {
		$this->load();

		if ( $this->data['status'] == 'working' && $this->has_more() ) {
			$last = absint( $this->now() ) - absint( $this->data['info']['latest'] );

			if ( $last > $this->max * 3 ) {
				$this->spawn();
			}
		}
	}

	protected function init() {

	}

	protected function prepare() {

	}

	protected function worker() {
		$this->add_message( __( 'Starting the thread worker processing.', 'd4plib' ) );

		$this->save();
		$result = true;

		if ( $this->has_more() ) {
			while ( $this->has_more() && $this->is_on_time() ) {
				$result = $this->task();

				if ( ! $result ) {
					break;
				}
			}
		}

		if ( $result && $this->has_more() ) {
			/* translators: Background process threads finished. %s: Thread elapsed time. */
			$this->add_message( sprintf( __( 'Processing thread finished after %s seconds.', 'd4plib' ), number_format( $this->elapsed(), 2 ) ) );
			$this->add_message( __( 'Spawning new background processing thread.', 'd4plib' ) );

			$this->data['info']['threads'] ++;
			$this->save();

			$this->spawn();
		} else {
			$threads = $this->data['info']['threads'];

			$this->data['info']['ended'] = $this->now();
			$this->data['info']['timer'] = $this->data['info']['ended'] - $this->data['info']['started'];

			$this->finish();

			/* translators: Background process threads message. %s: Number of threads. */
			$this->add_message( sprintf( _n( 'Process finished using %s thread.', 'Process finished using %s threads.', $threads, 'd4plib' ), $threads ) );
			$this->status( 'done' );

			$this->save();
		}
	}

	protected function status( string $status ) {
		$this->data['status'] = $status;
	}

	protected function task_start( string $title ) {
		$this->data['info']['tasks'] ++;

		if ( ! isset( $this->data['tasks'][ $title ] ) ) {
			$this->data['tasks'][ $title ] = array();
		}

		$this->data['tasks'][ $title ][] = array(
			'title' => $title,
			'start' => $this->now(),
			'end'   => 0,
		);

		$this->save();
	}

	protected function task_end( string $title, bool $done = false ) {
		if ( $done ) {
			$this->data['info']['done'] ++;
		}

		$last_id = array_key_last( $this->data['tasks'][ $title ] );

		$this->data['tasks'][ $title ][ $last_id ]['end'] = $this->now();

		$this->save();
	}

	protected function init_data() : array {
		return array(
			'status'   => 'idle',
			'data'     => $this->defaults(),
			'messages' => array(),
			'info'     => array(
				'started' => 0,
				'ended'   => 0,
				'latest'  => 0,
				'timer'   => 0,
				'threads' => 0,
				'total'   => 0,
				'tasks'   => 0,
				'done'    => 0,
				'user_id' => 0,
				'ip'      => '',
			),
			'tasks'    => array(),
		);
	}

	protected function save() {
		$this->data['info']['latest'] = $this->now();

		set_site_transient( $this->transient, $this->data );

		delete_site_transient( $this->abort_transient );
	}

	protected function now() {
		return microtime( true );
	}

	protected function elapsed() {
		return $this->now() - $this->timer;
	}

	protected function is_on_time() : bool {
		return $this->elapsed() < $this->max - $this->offset;
	}

	protected function has_more() : bool {
		return $this->data['info']['done'] < $this->data['info']['total'];
	}

	protected function add_message( string $message, string $type = 'system' ) {
		$this->data['messages'][] = array(
			'time'    => $this->now(),
			'message' => $message,
			'type'    => $type,
		);
	}

	abstract public function start();

	abstract public function finish();

	abstract protected function spawn();

	abstract protected function task() : bool;

	abstract protected function defaults() : array;

	public static function render_messages( array $messages, bool $reverse = false ) : string {
		if ( $reverse ) {
			$messages = array_reverse( $messages );
		}

		$_icons = array(
			'info'     => 'ui-info',
			'system'   => 'ui-server',
			'activity' => 'ui-play',
			'warning'  => 'ui-warning',
			'error'    => 'ui-close-square',
		);

		$_labels = array(
			'info'     => __( 'Process', 'd4plib' ),
			'system'   => __( 'System', 'd4plib' ),
			'activity' => __( 'Activity', 'd4plib' ),
			'warning'  => __( 'Warning', 'd4plib' ),
			'error'    => __( 'Error', 'd4plib' ),
		);

		$render = '<ul>';

		foreach ( $messages as $message ) {
			$now = DateTime::createFromFormat( 'U.u', $message['time'] );

			if ( $now === false ) {
				$now = DateTime::createFromFormat( 'U', absint( $message['time'] ?? 0 ) );
			}

			$date_time = $now === false ? '/' : $now->format( 'm-d-Y H:i:s' );

			$render .= '<li class="__message __message-' . esc_attr( $message['type'] ) . '">';
			$render .= '<span class="__date-time">' . $date_time . '</span>';
			$render .= '<span class="__icon" title="' . esc_attr( $_labels[ $message['type'] ] ) . '"><i class="d4p-icon d4p-' . esc_attr( $_icons[ $message['type'] ] ) . ' d4p-icon-fw"></i></span>';
			$render .= '<span class="__content">' . esc_html( $message['message'] ) . '</span>';
			$render .= '</li>';
		}

		$render .= '</ul>';

		return $render;
	}
}
