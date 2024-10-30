<?php

namespace Dev4Press\Plugin\CoreActivity\Log;

use Dev4Press\Plugin\CoreActivity\Basic\DB;
use Dev4Press\v51\Core\DateTime;
use Dev4Press\v51\Core\Quick\Str;
use Dev4Press\v51\Core\Quick\WPR;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Notifications {
	public function __construct() {
		if ( $this->s( 'instant' ) ) {
			add_action( 'coreactivity_event_logged', array( $this, 'check_for_instant' ), 10, 3 );
		}
	}

	public static function instance() : Notifications {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Notifications();
		}

		return $instance;
	}

	public function s( $name ) {
		return coreactivity_settings()->get( $name, 'notifications' );
	}

	public function last_instant_timestamp() {
		return coreactivity_settings()->get( 'instant_timestamp', 'core' );
	}

	public function last_instant_datetime() {
		return coreactivity_settings()->get( 'instant_datetime', 'core' );
	}

	public function next_instant_timestamp() {
		return $this->last_instant_timestamp() + $this->s( 'instant_delay_minutes' ) * 60;
	}

	public function is_instant_allowed() : bool {
		return time() > $this->next_instant_timestamp();
	}

	public function schedule_digests() {
		if ( ! is_main_site() ) {
			return;
		}

		if ( $this->s( 'daily' ) ) {
			if ( ! wp_next_scheduled( 'coreactivity_daily_digest' ) ) {
				$cron_time = strtotime( 'tomorrow' ) + HOUR_IN_SECONDS * $this->s( 'daily_hour' );

				wp_schedule_event( $cron_time, 'daily', 'coreactivity_daily_digest' );
			}
		} else {
			if ( wp_next_scheduled( 'coreactivity_daily_digest' ) ) {
				WPR::remove_cron( 'coreactivity_daily_digest' );
			}
		}

		if ( $this->s( 'weekly' ) ) {
			if ( ! wp_next_scheduled( 'coreactivity_weekly_digest' ) ) {
				$cron_time = strtotime( 'Next ' . $this->week_day_number_to_name( $this->s( 'weekly_day' ) ) ) + HOUR_IN_SECONDS * $this->s( 'weekly_hour' );

				wp_schedule_event( $cron_time, 'weekly', 'coreactivity_weekly_digest' );
			}
		} else {
			if ( wp_next_scheduled( 'coreactivity_weekly_digest' ) ) {
				WPR::remove_cron( 'coreactivity_weekly_digest' );
			}
		}
	}

	public function daily_digest( array $events, string $from = '', string $to = '' ) {
		if ( empty( $events ) ) {
			return;
		}

		$notification = array(
			'subject' => '[%SITE_NAME%] Website Daily Activity Digest',
			'message' => 'This is a daily digest of events logged on the %SITE_NAME%.
%LOG_PERIOD%
%LOG_DIGEST%

To review all the latest events on the website, visit the coreActivity Logs panel: 
%LOG_URL%.

------------------------------------
Generated by coreActivity plugin for WordPress
https://www.dev4press.com/plugins/coreactivity/',
			'emails'  => $this->s( 'daily_emails' ),
			'tags'    => array(
				'SITE_NAME'  => is_multisite() ? get_site_option( 'site_name' ) : get_option( 'blogname' ),
				'SITE_URL'   => is_multisite() ? get_site_url( get_main_site_id() ) : site_url(),
				'LOG_URL'    => network_admin_url( 'admin.php?page=coreactivity-logs' ),
				'LOG_DIGEST' => $this->format_digest_for_email( $events ),
				'LOG_PERIOD' => ! empty( $from ) && ! empty( $to ) ? PHP_EOL . 'For period: "' . $from . '" to "' . $to . '".' . PHP_EOL : '',
			),
			'events'  => $events,
			'period'  => array(
				'from' => $from,
				'to'   => $to,
			),
		);

		/**
		 * Action fired before the daily digest email is created and sent. Period is specified as GMT/UTC values.
		 *
		 * @param array  $events components and events counts from the database for the specified date period
		 * @param string $from   date period start date/time
		 * @param string $to     date period end date/time
		 */
		do_action( 'coreactivity_notifications_daily_digest', $events, $from, $to );

		$this->handle_email_sending( $notification, 'coreactivity_daily_digest_email' );
	}

	public function weekly_digest( array $events, string $from = '', string $to = '' ) {
		if ( empty( $events ) ) {
			return;
		}

		$notification = array(
			'subject' => '[%SITE_NAME%] Website Weekly Activity Digest',
			'message' => 'This is a weekly digest of events logged on the %SITE_NAME%.
%LOG_PERIOD%
%LOG_DIGEST%

To review all the latest events on the website, visit the coreActivity Logs panel: 
%LOG_URL%.

------------------------------------
Generated by coreActivity plugin for WordPress
https://www.dev4press.com/plugins/coreactivity/',
			'emails'  => $this->s( 'weekly_emails' ),
			'tags'    => array(
				'SITE_NAME'  => is_multisite() ? get_site_option( 'site_name' ) : get_option( 'blogname' ),
				'SITE_URL'   => is_multisite() ? get_site_url( get_main_site_id() ) : site_url(),
				'LOG_URL'    => network_admin_url( 'admin.php?page=coreactivity-logs' ),
				'LOG_DIGEST' => $this->format_digest_for_email( $events ),
				'LOG_PERIOD' => ! empty( $from ) && ! empty( $to ) ? PHP_EOL . 'For period: "' . $from . '" to "' . $to . '".' . PHP_EOL : '',
			),
			'events'  => $events,
			'period'  => array(
				'from' => $from,
				'to'   => $to,
			),
		);

		/**
		 * Action fired before the weekly digest email is created and sent. Period is specified as GMT/UTC values.
		 *
		 * @param array  $events components and events counts from the database for the specified date period
		 * @param string $from   date period start date/time
		 * @param string $to     date period end date/time
		 */
		do_action( 'coreactivity_notifications_weekly_digest', $events, $from, $to );

		$this->handle_email_sending( $notification, 'coreactivity_weekly_digest_email' );
	}

	public function instant_notify( array $events, string $from = '', string $to = '' ) {
		if ( empty( $events ) ) {
			return;
		}

		coreactivity_settings()->set( 'instant_datetime', DateTime::instance()->mysql_date(), 'core' );
		coreactivity_settings()->set( 'instant_timestamp', time(), 'core', true );

		$notification = array(
			'subject' => '[%SITE_NAME%] Website Activity Notice',
			'message' => 'One or more events you have been tracking for instant notifications have been logged on the %SITE_NAME%.
%LOG_PERIOD%
%LOG_EVENTS%

To review all the latest events on the website, visit the coreActivity Logs panel: 
%LOG_URL%.

------------------------------------
Generated by coreActivity plugin for WordPress
https://www.dev4press.com/plugins/coreactivity/',
			'emails'  => $this->s( 'instant_emails' ),
			'tags'    => array(
				'SITE_NAME'  => is_multisite() ? get_site_option( 'site_name' ) : get_option( 'blogname' ),
				'SITE_URL'   => is_multisite() ? get_site_url( get_main_site_id() ) : site_url(),
				'LOG_URL'    => network_admin_url( 'admin.php?page=coreactivity-logs' ),
				'LOG_EVENTS' => $this->format_events_for_email( $events ),
				'LOG_PERIOD' => ! empty( $from ) && ! empty( $to ) ? PHP_EOL . 'For period: "' . $from . '" to "' . $to . '".' . PHP_EOL : '',
			),
			'events'  => $events,
			'period'  => array(
				'from' => $from,
				'to'   => $to,
			),
		);

		/**
		 * Action fired before the instant notification email is created and sent. Period is specified as GMT/UTC values.
		 *
		 * @param array  $events one or more log entries
		 * @param string $from   date period start date/time, and it can be empty
		 * @param string $to     date period end date/time, and it can be empty
		 */
		do_action( 'coreactivity_notifications_instant_notify', $events, $from, $to );

		$this->handle_email_sending( $notification, 'coreactivity_instant_notification_email' );
	}

	public function check_for_instant( $id, $data, $meta ) {
		if ( Activity::instance()->is_instant_notification_enabled( $data['event_id'] ) ) {
			if ( ! wp_next_scheduled( 'coreactivity_instant_notification' ) ) {
				if ( ! $this->is_instant_allowed() ) {
					wp_schedule_single_event( $this->next_instant_timestamp(), 'coreactivity_instant_notification' );
				} else {
					$data['id']   = $id;
					$data['meta'] = $meta;

					$this->instant_notify( array( $data ), true );
				}
			}
		}
	}

	public function scheduled_instant() {
		$events = Activity::instance()->get_events_with_notifications( 'instant' );
		$to     = DateTime::instance()->mysql_date();
		$from   = $this->last_instant_datetime();

		$log = DB::instance()->get_entries_by_event_ids_and_date_range( $events, $from, $to );

		$this->instant_notify( $log, $from, $to );
	}

	public function scheduled_daily() {
		$events = Activity::instance()->get_events_with_notifications( 'daily' );

		if ( ! empty( $events ) ) {
			$to   = gmdate( DateTime::instance()->mysql_format(), strtotime( 'today' ) - 1 );
			$from = gmdate( DateTime::instance()->mysql_format(), strtotime( 'yesterday' ) );

			$log = DB::instance()->get_entries_counts_by_event_ids_and_date_range( $events, $from, $to );

			$this->daily_digest( $log, $from, $to );
		}
	}

	public function scheduled_weekly() {
		$events = Activity::instance()->get_events_with_notifications( 'weekly' );

		if ( ! empty( $events ) ) {
			$to   = gmdate( DateTime::instance()->mysql_format(), strtotime( 'today' ) - 1 );
			$from = gmdate( DateTime::instance()->mysql_format(), strtotime( 'today' ) - WEEK_IN_SECONDS );

			$log = DB::instance()->get_entries_counts_by_event_ids_and_date_range( $events, $from, $to );

			$this->weekly_digest( $log, $from, $to );
		}
	}

	private function week_day_number_to_name( string $code ) : string {
		$days = array(
			'D1' => 'Monday',
			'D2' => 'Tuesday',
			'D3' => 'Wednesday',
			'D4' => 'Thursday',
			'D5' => 'Friday',
			'D6' => 'Saturday',
			'D0' => 'Sunday',
		);

		return $days[ $code ] ?? 'Saturday';
	}

	private function handle_email_sending( array $notification, string $filter ) {
		if ( empty( $notification['emails'] ) ) {
			$notification['emails'] = array( get_site_option( 'admin_email' ) );
		}

		$notification = apply_filters( $filter, $notification );

		$notification['subject'] = Str::replace_tags( $notification['subject'], $notification['tags'] );
		$notification['message'] = Str::replace_tags( $notification['message'], $notification['tags'] );

		foreach ( $notification['emails'] as $email ) {
			wp_mail( $email, wp_specialchars_decode( $notification['subject'] ), $notification['message'] );
		}
	}

	private function format_digest_for_email( array $events ) : string {
		$render = array();

		$i = 1;
		foreach ( $events as $component => $data ) {
			$item = str_pad( $i, 3, ' ', STR_PAD_LEFT ) . '. ';
			$item .= '[' . $component . '] ' . Activity::instance()->get_component_label( $component ) . PHP_EOL;
			$item .= '     Logged Entries: ' . $data['total'] . PHP_EOL;
			$item .= '     View All in Log: ' . network_admin_url( 'admin.php?page=coreactivity-logs&view=component&filter-component=' . $component ) . PHP_EOL;

			foreach ( $data['events'] as $event => $count ) {
				$event_id = Activity::instance()->get_event_id( $component, $event );

				$item .= '     * [' . $event . '] ' . Activity::instance()->get_event_label( $event_id, $event ) . ': ' . $count . PHP_EOL;
			}

			$render[] = $item;

			$i ++;
		}

		return join( PHP_EOL, $render );
	}

	private function format_events_for_email( array $events ) : string {
		$render = array();

		$i = 1;
		foreach ( $events as $event ) {
			$object = Display::instance()->email_object_name( '', (object) $event );

			$item = str_pad( $i, 4, ' ', STR_PAD_LEFT ) . '. ';
			$item .= Activity::instance()->get_event_display( $event['event_id'] ) . PHP_EOL;
			$item .= '      Logged: ' . $event['logged'] . PHP_EOL;
			$item .= '      IP: ' . $event['ip'] . ' · ' . $event['method'] . ( empty( $event['context'] ) ? '' : ' · ' . $event['context'] );

			if ( ! empty( $object ) ) {
				$item .= PHP_EOL . '      ' . $object . PHP_EOL;
			}
			$item .= '      REQUEST: ' . $event['request'];

			$render[] = $item;

			$i ++;
		}

		return join( PHP_EOL . PHP_EOL, $render );
	}
}
