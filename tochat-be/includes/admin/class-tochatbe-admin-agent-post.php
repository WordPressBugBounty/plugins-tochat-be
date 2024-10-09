<?php
/**
 * Admin Agent Post class.
 *
 * @package TOCHATBE\Admin\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TOCHATBE_Admin_Agent_Post class.
 */
class TOCHATBE_Admin_Agent_Post {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'setup_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_agent_meta_data' ) );
		add_filter( 'manage_edit-tochatbe_agent_columns', array( $this, 'edit_table_columns' ) );
		add_action( 'manage_tochatbe_agent_posts_custom_column', array( $this, 'manage_table_columns' ), 10, 2 );
	}

	/**
	 * Setup post type.
	 *
	 * @return void
	 */
	public function setup_post_type() {
		$args = array(
			'public'            => true,
			'labels'            => array(
				'name'                  => esc_html_x( 'Agents', 'Post type general name', 'tochat-be' ),
				'singular_name'         => esc_html_x( 'Agent', 'Post type singular name', 'tochat-be' ),
				'menu_name'             => esc_html_x( 'Agents', 'Admin Menu text', 'tochat-be' ),
				'name_admin_bar'        => esc_html_x( 'Agent', 'Add New on Toolbar', 'tochat-be' ),
				'add_new'               => esc_html__( 'Add New', 'tochat-be' ),
				'add_new_item'          => esc_html__( 'Add New Agent', 'tochat-be' ),
				'new_item'              => esc_html__( 'New Agent', 'tochat-be' ),
				'edit_item'             => esc_html__( 'Edit Agent', 'tochat-be' ),
				'view_item'             => esc_html__( 'View Agent', 'tochat-be' ),
				'all_items'             => esc_html__( 'All Agents', 'tochat-be' ),
				'search_items'          => esc_html__( 'Search Agents', 'tochat-be' ),
				'parent_item_colon'     => esc_html__( 'Parent Agents:', 'tochat-be' ),
				'not_found'             => esc_html__( 'No agents found.', 'tochat-be' ),
				'not_found_in_trash'    => esc_html__( 'No agents found in Trash.', 'tochat-be' ),
				'featured_image'        => esc_html_x( 'Agent Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'tochat-be' ),
				'set_featured_image'    => esc_html_x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'tochat-be' ),
				'remove_featured_image' => esc_html_x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'tochat-be' ),
				'use_featured_image'    => esc_html_x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'tochat-be' ),
				'archives'              => esc_html_x( 'Agent archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'tochat-be' ),
				'insert_into_item'      => esc_html_x( 'Insert into agent', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'tochat-be' ),
				'uploaded_to_this_item' => esc_html_x( 'Uploaded to this agent', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'tochat-be' ),
				'filter_items_list'     => esc_html_x( 'Filter agents list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'tochat-be' ),
				'items_list_navigation' => esc_html_x( 'Agents list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'tochat-be' ),
				'items_list'            => esc_html_x( 'Agents list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'tochat-be' ),
			),
			'has_archive'       => true,
			'show_in_menu'      => 'to-chat-be-whatsapp',
			'show_in_admin_bar' => false,
			'supports'          => array( 'thumbnail' ),
		);
		register_post_type( 'tochatbe_agent', $args );
	}

	/**
	 * Register meta boxes.
	 *
	 * @return void
	 */
	public function register_meta_boxes() {
		add_meta_box(
			'tochatbe-agent-metabox',
			'Agent Data',
			array( $this, 'meta_box' ),
			'tochatbe_agent',
			'normal',
			'high'
		);
	}

	/**
	 * Meta box.
	 *
	 * @return void
	 */
	public function meta_box() {
		global $post;
		require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/meta-boxes/html-agent-add.php';
	}

	/**
	 * Save agent meta data.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function save_agent_meta_data( $post_id ) {
		global $post, $wpdb;

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( null === $post_id || empty( $_POST ) ) {
			return;
		}

		if ( ! isset( $_POST['post_type'] ) || 'tochatbe_agent' !== $_POST['post_type'] ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			$post_id = wp_is_post_revision( $post_id );
		}

		if ( isset( $_POST['agent_name'] ) ) {
			update_post_meta( $post->ID, 'agent_name', sanitize_text_field( $_POST['agent_name'] ) );
		}

		if ( isset( $_POST['agent_title'] ) ) {
			update_post_meta( $post->ID, 'agent_title', sanitize_text_field( $_POST['agent_title'] ) );
		}

		if ( isset( $_POST['agent_number'] ) ) {
			update_post_meta( $post->ID, 'agent_number', sanitize_text_field( $_POST['agent_number'] ) );
		}

		if ( isset( $_POST['agent_group_id'] ) ) {
			update_post_meta( $post->ID, 'agent_group_id', sanitize_text_field( $_POST['agent_group_id'] ) );
		}

		if ( isset( $_POST['agent_type'] ) ) {
			update_post_meta( $post->ID, 'agent_type', sanitize_text_field( $_POST['agent_type'] ) );
		}

		if ( isset( $_POST['pre_defined_message'] ) ) {
			update_post_meta( $post->ID, 'pre_defined_message', sanitize_textarea_field( $_POST['pre_defined_message'] ) );
		}

		$wpdb->update(
			$wpdb->posts,
			array( 'post_title' => sanitize_text_field( $_POST['agent_name'] ) ),
			array( 'ID' => $post_id )
		);
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	/**
	 * Edit table columns.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public function edit_table_columns( $columns ) {
		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'title'        => 'Agent Name',
			'agent_title'  => 'Agent Title',
			'agent_number' => 'Agent Number',
			'agent_type'   => 'Agent Type',
			'date'         => 'Date',
		);

		return $columns;
	}

	/**
	 * Manage table columns.
	 *
	 * @param string $column Column.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public function manage_table_columns( $column, $post_id ) {
		$agent_type     = get_post_meta( $post_id, 'agent_type', true );
		$agent_number   = get_post_meta( $post_id, 'agent_number', true );
		$agent_group_id = get_post_meta( $post_id, 'agent_group_id', true );

		switch ( $column ) {
			case 'agent_title':
				echo esc_html( get_post_meta( $post_id, 'agent_title', true ) );
				break;

			case 'agent_number':
				echo 'number' === $agent_type ? esc_html( $agent_number ) : esc_html( $agent_group_id );
				break;

			case 'agent_type':
				echo esc_html( get_post_meta( $post_id, 'agent_type', true ) );
				break;

			default:
				// code...
				break;
		}
	}
}

new TOCHATBE_Admin_Agent_Post();
