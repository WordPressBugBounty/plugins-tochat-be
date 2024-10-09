<?php
/**
 * Admin Meta Box
 *
 * @package TOCHATBE\Admin\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin Meta Box class.
 *
 * @since 1.0.0
 */
class TOCHATBE_Admin_Mod_Meta_Box {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_agent_meta_data' ) );
	}

	/**
	 * Register meta boxes.
	 *
	 * @return void
	 */
	public function register_meta_boxes() {
		add_meta_box(
			'tochatbe-mod-metabox',
			'TOCHAT.BE',
			array( $this, 'meta_box' ),
			array( 'page', 'post' ),
			'side',
			'high'
		);
	}

	/**
	 * Display meta box.
	 *
	 * @return void
	 */
	public function meta_box() {
		global $post;

		$about_message = get_post_meta( $post->ID, '_tochatbe_about_message', true );

		require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/meta-boxes/html-widget-mod.php';
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
		if ( wp_is_post_revision( $post_id ) ) {
			$post_id = wp_is_post_revision( $post_id );
		}

		if ( isset( $_POST['_tochatbe_about_message'] ) ) {
			update_post_meta( $post->ID, '_tochatbe_about_message', sanitize_textarea_field( $_POST['_tochatbe_about_message'] ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}
}

new TOCHATBE_Admin_Mod_Meta_Box();
