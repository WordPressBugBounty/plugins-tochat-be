<?php
/**
 * Admin Log Table
 *
 * @package TOCHATBE\Admin\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include WP_List_Table class.
require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

/**
 * TOCHATBE_Admin_Log_Table class.
 */
class TOCHATBE_Admin_Log_Table extends WP_List_Table {

	/**
	 * Table name.
	 *
	 * @var string $table Table name.
	 */
	private $table;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		global $wpdb;

		$this->table = $wpdb->prefix . 'tochatbe_log';

		parent::__construct(
			array(
				'singular' => 'Log', // Singular.
				'plural'   => 'Logs', // Plural.
				'ajax'     => false, // Support ajax or not.
			)
		);
	}

	/**
	 * Prepare items.
	 *
	 * @return void
	 */
	public function prepare_items() {
		$order_by     = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.NonceVerification.Missing
		$order        = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.NonceVerification.Missing
		$search_term  = isset( $_POST['s'] ) ? sanitize_text_field( $_POST['s'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.NonceVerification.Missing
		$data         = $this->wp_list_table_data( $order_by, $order, $search_term );
		$pre_page     = 10;
		$currnet_page = $this->get_pagenum();
		$total_items  = count( $data );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $pre_page,
			)
		);

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = array_slice( $data, ( ( $currnet_page - 1 ) * $pre_page ), $pre_page );
	}

	/**
	 * Display the table.
	 *
	 * @param string $order_by    Order by. Default empty.
	 * @param string $order       Order. Default empty.
	 * @param string $search_term Search term. Default empty.
	 * @return array $data Data.
	 */
	public function wp_list_table_data( $order_by = '', $order = '', $search_term = '' ) {
		global $wpdb;

		// Search results.
		if ( '' !== $search_term ) {
			$search_term = sanitize_text_field( $search_term );

			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$query = $wpdb->prepare(
				"SELECT *
				FROM $this->table
				WHERE ip LIKE %s
				OR message LIKE %s
				OR contacted_to LIKE %s
				OR user LIKE %s
				OR referral LIKE %s
				OR device_type LIKE %s
				OR timestamp LIKE %s",
				'%' . $wpdb->esc_like( $search_term ) . '%',
				'%' . $wpdb->esc_like( $search_term ) . '%',
				'%' . $wpdb->esc_like( $search_term ) . '%',
				'%' . $wpdb->esc_like( $search_term ) . '%',
				'%' . $wpdb->esc_like( $search_term ) . '%',
				'%' . $wpdb->esc_like( $search_term ) . '%',
				'%' . $wpdb->esc_like( $search_term ) . '%'
			);

			return $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		} else { // Display all results.
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			return $wpdb->get_results(
				"SELECT *
                FROM $this->table
                ORDER BY id DESC",
				ARRAY_A
			);
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
	}

	/**
	 * Get sortable columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'ip'           => 'IP Address',
			'message'      => 'Message',
			'contacted_to' => 'Agent Number',
			'user'         => 'User',
			'referral'     => 'Referral',
			'device_type'  => 'Device Type',
			'timestamp'    => 'Date',
		);

		return $columns;
	}

	/**
	 * Get sortable columns.
	 *
	 * @param array  $item        Item.
	 * @param string $column_name Column name.
	 * @return array
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'ip':
			case 'message':
			case 'contacted_to':
			case 'user':
			case 'referral':
			case 'device_type':
			case 'timestamp':
				return wp_kses_post( $item[ $column_name ] );
			default:
				return __( 'No Value', 'tochat-be' );
		}
	}

	/**
	 * Checkbox column.
	 *
	 * @param array $item Item.
	 * @return string Checkbox. HTML formatted.
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="post[]" value="%d" />', absint( $item['id'] ) );
	}

	/**
	 * IP address column.
	 *
	 * @param array $item Item.
	 * @return string IP address. HTML formatted.
	 */
	public function column_ip( $item ) {
		$page    = isset( $_REQUEST['page'] ) ? absint( $_REQUEST['page'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$actions = array(
			'edit'   => sprintf( '<a href="?page=%s&action=%s&id=%d">%s</a>', absint( $page ), 'edit', absint( $item['id'] ), 'Edit' ),
			'delete' => sprintf( '<a href="?page=%s&action=%s&id=%d">%s</a>', absint( $page ), 'delete', absint( $item['id'] ), 'Delete' ),
		);

		return sprintf( '%1$s %2$s', wp_kses_post( $item['ip'] ), $this->row_actions( $actions ) );
	}

	/**
	 * Get hidden columns.
	 *
	 * @return array
	 */
	public function get_hidden_columns() {
		return array();
	}
}
