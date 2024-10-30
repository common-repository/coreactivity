<?php
/**
 * Name:    Dev4Press\v51\Core\Quick\BBP
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

namespace Dev4Press\v51\Core\Quick;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BBP {
	public static function is_active( $min_version = '2.6' ) : bool {
		if ( WPR::is_plugin_active( 'bbpress/bbpress.php' ) && function_exists( 'bbp_get_version' ) ) {
			return version_compare( bbp_get_version(), $min_version, '>=' );
		} else {
			return false;
		}
	}

	public static function get_post_types() : array {
		if ( self::is_active() ) {
			return array(
				bbp_get_forum_post_type(),
				bbp_get_topic_post_type(),
				bbp_get_reply_post_type(),
			);
		}

		return array();
	}

	public static function list_user_roles() : array {
		$dynamic_roles = bbp_get_dynamic_roles();

		return array_keys( $dynamic_roles );
	}

	public static function get_user_roles( bool $translate = true ) : array {
		$roles = array();

		$dynamic_roles = bbp_get_dynamic_roles();

		foreach ( $dynamic_roles as $role => $obj ) {
			$roles[ $role ] = $translate ? bbp_translate_user_role( $obj['name'] ) : $obj['name'];
		}

		return $roles;
	}

	public static function get_moderator_roles( bool $translate = true ) : array {
		$roles = array();

		$dynamic_roles = bbp_get_dynamic_roles();

		foreach ( $dynamic_roles as $role => $obj ) {
			if ( isset( $obj['capabilities']['moderate'] ) && $obj['capabilities']['moderate'] ) {
				$roles[ $role ] = $translate ? bbp_translate_user_role( $obj['name'] ) : $obj['name'];
			}
		}

		return $roles;
	}

	public static function has_moderator_role() : bool {
		$roles = array_keys( self::get_moderator_roles() );

		if ( is_user_logged_in() ) {
			if ( is_super_admin() ) {
				return true;
			} else {
				global $current_user;

				if ( is_array( $current_user->roles ) ) {
					$matched = array_intersect( $current_user->roles, $roles );

					return ! empty( $matched );
				}
			}
		}

		return false;
	}

	public static function can_moderate() : bool {
		return current_user_can( 'moderate' );
	}

	public static function get_forums_list( $args = array() ) : array {
		$defaults = array(
			'post_type'   => bbp_get_forum_post_type(),
			'numberposts' => - 1,
		);

		$args = wp_parse_args( $args, $defaults );

		$_forums = get_posts( $args );

		$forums = array();

		foreach ( $_forums as $forum ) {
			$forums[ $forum->ID ] = (object) array(
				'id'     => $forum->ID,
				'url'    => get_permalink( $forum->ID ),
				'parent' => $forum->post_parent,
				'title'  => $forum->post_title,
			);
		}

		return $forums;
	}

	public static function is_user_moderator( int $user_id ) : bool {
		return WPR::is_user_roles( $user_id, bbp_get_moderator_role() );
	}

	public static function is_user_keymaster( int $user_id ) : bool {
		return WPR::is_user_roles( $user_id, bbp_get_keymaster_role() );
	}

	public static function is_current_user_moderator() : bool {
		return WPR::is_current_user_roles( bbp_get_moderator_role() );
	}

	public static function is_current_user_keymaster() : bool {
		return WPR::is_current_user_roles( bbp_get_keymaster_role() );
	}

	public static function can_use_pretty_urls() : bool {
		if ( function_exists( 'bbp_use_pretty_urls' ) ) {
			return bbp_use_pretty_urls();
		}

		global $wp_rewrite;

		return $wp_rewrite->using_permalinks();
	}
}
