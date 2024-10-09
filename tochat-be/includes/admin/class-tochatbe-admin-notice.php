<?php
/**
 * Admin Notice
 *
 * This class is used to display admin notices.
 *
 * @package TOCHATBE\Admin\Classes
 * @since   1.0.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin notice class.
 *
 * @since 1.0.9
 */
class TOCHATBE_Admin_Notice {

	/**
	 * Class constructor.
	 *
	 * @since 1.0.9
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'just_whatsapp_icon_notice' ) );
	}

	/**
	 * Display "Just WhatsApp Icon" notice.
	 *
	 * @since 1.0.9
	 *
	 * @return void
	 */
	public function just_whatsapp_icon_notice() {
		if ( isset( $_GET['tochatbe_just_whatsapp_icon_notice_dismiss'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			update_option( 'tochatbe_just_whatsapp_icon_notice_dismiss', 'yes' );
		}
		?>
		<?php if ( 'yes' !== get_option( 'tochatbe_just_whatsapp_icon_notice_dismiss' ) ) : ?>
			<div class="notice notice-info">
				<p><strong>TOCHAT.BE: </strong>If you want just a simple button with a direct link to WhatsApp, go to <a href="<?php echo esc_url( admin_url( 'admin.php?page=to-chat-be-whatsapp_settings&tab=just_whatsapp_icon' ) ); ?>">settings</a> and activate "Just WhatsApp Icon"<a href="<?php echo esc_url( add_query_arg( array( 'tochatbe_just_whatsapp_icon_notice_dismiss' => 'yes' ) ) ); ?>" style="text-decoration:none;margin-left:10px;"><span class="dashicons dashicons-dismiss"></span></a></p>
			</div>
		<?php endif; ?>
		<?php
	}
}

new TOCHATBE_Admin_Notice();
