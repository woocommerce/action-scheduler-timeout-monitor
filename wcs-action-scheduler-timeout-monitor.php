<?php
/**
 * Plugin Name: WooCommerce Subscriptions Action Scheduler Timeout Monitor
 * Plugin URI: https://github.com/Prospress/wcs-action-scheduler-timeout-monitor
 * Description: Gather additional information about subscription action scheduled events which timeout.
 * Author: Prospress Inc.
 * Author URI: http://prospress.com/
 * Version: 1.0
 *
 * Copyright 2017 Prospress, Inc.  (email : freedoms@prospress.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* @version   1.0
* @package   WooCommerce Subscriptions Action Scheduler Timeout Monitor
* @author    Prospress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WCS_Action_Scheduler_Timeout_Monitor {

	protected static $action_id = null;

	protected static $start_time   = null;
	protected static $timeout_time = null;

	/**
	 * Attach callbacks
	 *
	 * @since 1.0
	 */
	public static function init() {
		add_action( 'action_scheduler_before_execute', __CLASS__ . '::start_monitoring_action', 10 );
		add_action( 'action_scheduler_after_execute', __CLASS__ . '::stop_monitoring_action', 10 );
	}

	/**
	 * Attach an 'all' action callback for monitoring execution times.
	 *
	 * @param int $action_id
	 * @since 1.0
	 */
	public static function start_monitoring_action( $action_id ) {
		self::$action_id    = $action_id;
		self::$start_time   = microtime( true );
		self::$timeout_time = self::$start_time + apply_filters( 'action_scheduler_failure_period', 5 * MINUTE_IN_SECONDS );

		add_action( 'all', __CLASS__ . '::track_action_execution_time', 0 );
	}

	/**
	 * Detach 'all' action callbacks
	 *
	 * @since 1.0
	 */
	public static function stop_monitoring_action() {
		self::$action_id = self::$start_time = self::$timeout_time = null;

		remove_action( 'all', __CLASS__ . '::track_action_execution_time', 0 );
	}

	/**
	 * Detach 'all' action callbacks
	 *
	 * @since 1.0
	 */
	public static function track_action_execution_time( $value ) {
		$current_time = microtime( true );

		if ( $current_time + 0.1 > self::$timeout_time ) {
			throw new Exception( sprintf( 'Scheduled action %s was about to timeout after %s', self::$action_id, $current_time - self::$start_time ) );
		}

		return $value;
	}
}
WCS_Action_Scheduler_Timeout_Monitor::init();
