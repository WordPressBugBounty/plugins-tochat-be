<?php
/**
 * Admin Export CSV
 *
 * @package TOCHATBE\Admin\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TOCHATBE_Admin_Export_CSV class.
 */
class TOCHATBE_Admin_Export_CSV {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'export_click_log_csv' ) );
	}

	/**
	 * Export click log as CSV.
	 *
	 * @return void
	 */
	public function export_click_log_csv() {
		if ( ! isset( $_GET['tochatbe_export_click_log'] ) || ! wp_verify_nonce( sanitize_key( $_GET['tochatbe_export_click_log'] ), 'tochatbe_export_click_log' ) ) {
			return;
		}
	
		global $wpdb;
	
		$filename = 'click-log-' . current_time( 'Y-m-d' ) . '.csv';
		
		// Prepare the CSV data.
		$csv_data = '';
	
		// Header row.
		$csv_data .= implode( ',', array( 'IP Address', 'Message', 'Contacted To', 'User', 'Referral', 'Device Type', 'Timestamp' ) ) . "\n";
	
		// Get all logs.
		$logs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}tochatbe_log ORDER BY ID DESC" );
	
		foreach ( $logs as $log ) {
			$ip           = tochatbe_escape_csv_field( $log->ip );
			$message      = tochatbe_escape_csv_field( $log->message );
			$contacted_to = tochatbe_escape_csv_field( $log->contacted_to );
			$user         = tochatbe_escape_csv_field( $log->user );
			$referral     = tochatbe_escape_csv_field( $log->referral );
			$device_type  = tochatbe_escape_csv_field( $log->device_type );
			$timestamp    = tochatbe_escape_csv_field( $log->timestamp );
	
			$csv_data .= implode( ',', array( $ip, $message, $contacted_to, $user, $referral, $device_type, $timestamp ) ) . "\n";
		}
	
		// Send the headers.
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
	
		// Output the CSV data.
		echo wp_kses_post( $csv_data ); 
	
		exit;
	}
}

return new TOCHATBE_Admin_Export_CSV();
