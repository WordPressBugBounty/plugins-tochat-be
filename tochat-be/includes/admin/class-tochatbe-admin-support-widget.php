<?php
/**
 * Admin Support Widget
 *
 * This class is used to display the support widget in the admin area.
 *
 * @package TOCHATBE\Admin\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin support widget class.
 *
 * @since 1.2.3
 */
class TOCHATBE_Admin_Support_Widget {

	/**
	 * Class constructor.
	 *
	 * @since 1.2.3
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_support_widget' ) );
	}

	/**
	 * Admin support widget.
	 *
	 * @since 1.2.3
	 */
	public function admin_support_widget() {
		$current_screen = get_current_screen();
		if ( ! $current_screen || ! $current_screen->parent_base || 'to-chat-be-whatsapp' !== $current_screen->parent_base ) {
			return;
		}

		wp_enqueue_script( 'tochatbe-support-widget', 'https://widget.tochat.be/bundle.js?key=63c429f3-d5c3-4603-b7bb-513349758c47', array(), '1.0.0', true );
	}
}

return new TOCHATBE_Admin_Support_Widget();
