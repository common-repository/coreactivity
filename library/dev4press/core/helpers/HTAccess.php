<?php
/**
 * Name:    Dev4Press\v51\Core\Helpers\HTAccess
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

// phpcs:ignoreFile WordPress.WP.AlternativeFunctions

namespace Dev4Press\v51\Core\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTAccess {
	public $begin = 'BEGIN';
	public $end = 'END';

	public $path = '';

	public function __construct( $path = '' ) {
		$this->path = $path == '' ? ABSPATH . '.htaccess' : $path;
	}

	public function is_writable() : bool {
		return is_writable( $this->path );
	}

	public function file_exists() : bool {
		return file_exists( $this->path );
	}

	public function load() {
		if ( $this->file_exists() ) {
			return file( $this->path, FILE_IGNORE_NEW_LINES );
		} else {
			return array();
		}
	}

	public function remove( $marker, $cleanup = false, $backup = false ) : bool {
		return $this->insert( $marker, array(), 'end', $cleanup, $backup );
	}

	public function insert( $marker, $insertion = array(), $location = 'end', $cleanup = false, $backup = false ) : bool {
		if ( ! $this->file_exists() || $this->is_writable() ) {
			if ( ! $this->file_exists() ) {
				$marker_data = '';
			} else {
				$marker_data = $this->load();
			}

			if ( $backup ) {
				$backup_path = $this->path . '.backup';

				if ( file_exists( $backup_path ) ) {
					wp_delete_file( $backup_path );
				}

				copy( $this->path, $backup_path );
			}

			$f = fopen( $this->path, 'w' );

			if ( $f === false ) {
				return false;
			}

			$result = true;
			if ( flock( $f, LOCK_EX ) ) {
				if ( $location == 'start' ) {
					$this->write( $f, $marker, $insertion );

					$insertion = array();
				}

				if ( $marker_data ) {
					$state = true;

					foreach ( $marker_data as $marker_line ) {
						if ( strpos( $marker_line, '# ' . $this->begin . ' ' . $marker ) !== false ) {
							$state = false;
						}

						if ( $state ) {
							fwrite( $f, $marker_line . PHP_EOL );
						}

						if ( strpos( $marker_line, '# ' . $this->end . ' ' . $marker ) !== false ) {
							$state = true;
						}
					}
				}

				if ( $location == 'end' ) {
					$this->write( $f, $marker, $insertion );
				}

				fflush( $f );
				flock( $f, LOCK_UN );
			} else {
				$result = false;
			}

			fclose( $f );

			if ( $cleanup ) {
				$this->cleanup();
			}

			return $result;
		} else {
			return false;
		}
	}

	public function write( $f, $marker, $insertion = array() ) {
		if ( is_array( $insertion ) && ! empty( $insertion ) ) {
			fwrite( $f, PHP_EOL . '# BEGIN ' . $marker . PHP_EOL );

			foreach ( $insertion as $insert_line ) {
				fwrite( $f, $insert_line . PHP_EOL );
			}

			fwrite( $f, '# END ' . $marker . PHP_EOL );
		}
	}

	public function cleanup() : bool {
		if ( $this->file_exists() && $this->is_writable() ) {
			$marker_data = $this->load();
			$modded_data = array();

			$f = fopen( $this->path, 'w' );

			if ( $f === false ) {
				return false;
			}

			if ( flock( $f, LOCK_EX ) ) {
				$line_start  = 0;
				$line_end    = 0;
				$marker_size = count( $marker_data );

				for ( $i = 0; $i < $marker_size; $i ++ ) {
					if ( ! empty( trim( $marker_data[ $i ] ) ) ) {
						$line_start = $i;
						break;
					}
				}

				for ( $i = $marker_size - 1; $i > 0; $i -- ) {
					if ( ! empty( trim( $marker_data[ $i ] ) ) ) {
						$line_end = $i;
						break;
					}
				}

				$prev_empty     = false;
				$prev_comment   = false;
				$prev_directive = false;
				for ( $i = $line_start; $i < $line_end + 1; $i ++ ) {
					$begin      = false;
					$comment    = false;
					$end        = false;
					$directive  = false;
					$add_before = false;
					$add_after  = false;
					$marker     = $marker_data[ $i ];
					$line       = trim( $marker );

					if ( substr( $line, 0, 8 ) == '# BEGIN ' ) {
						$begin = true;
					} else if ( substr( $line, 0, 5 ) == '# END' ) {
						$end = true;
					} else if ( substr( $line, 0, 2 ) == '# ' ) {
						$comment = true;
					} else if ( ! empty( $line ) ) {
						$directive = true;
					}

					if ( $directive ) {
						if ( ! $prev_comment && ! $prev_directive && ! $prev_empty ) {
							$add_before = true;
						}
					}

					if ( $comment ) {
						if ( $prev_directive || ( ! $prev_empty && ! $prev_comment ) ) {
							$add_before = true;
						}
					}

					if ( $end ) {
						$add_after = true;
					}

					if ( $add_before ) {
						$modded_data[] = '';
					}

					if ( $directive || $begin || $end || $comment ) {
						$modded_data[] = $marker;
					}

					if ( $add_after ) {
						$modded_data[] = '';
					}

					$prev_comment   = $begin || $end || $comment;
					$prev_empty     = $add_after;
					$prev_directive = $directive;
				}

				foreach ( $modded_data as $marker_line ) {
					fwrite( $f, $marker_line . PHP_EOL );
				}

				fflush( $f );
				flock( $f, LOCK_UN );
			} else {
				fclose( $f );

				return false;
			}

			fclose( $f );

			return true;
		}

		return false;
	}

	public function check() : array {
		global $is_apache;

		$mods = $is_apache && function_exists( 'apache_get_modules' ) ? apache_get_modules() : array();

		$check = array(
			'is_apache'          => $is_apache,
			'file'               => '.htaccess',
			'htaccess'           => $this->path,
			'found'              => $is_apache && $this->file_exists(),
			'writable'           => $is_apache && $this->is_writable(),
			'automatic'          => false,
			'apache_get_modules' => ! empty( $mods ),
			'mod_rewrite'        => in_array( 'mod_rewrite', $mods ),
			'mod_alias'          => in_array( 'mod_alias', $mods ),
			'mod_setenvif'       => in_array( 'mod_setenvif', $mods ),
			'mod_headers'        => in_array( 'mod_headers', $mods ),
		);

		if ( $is_apache && ! $check['found'] ) {
			$check['writable'] = is_writable( ABSPATH );
		}

		if ( $is_apache && $check['writable'] && $check['is_apache'] ) {
			$check['automatic'] = true;
		}

		return $check;
	}
}
