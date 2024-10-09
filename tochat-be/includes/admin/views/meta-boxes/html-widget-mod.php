<?php
/**
 * Admin View: Meta Box HTML Widget Mod
 *
 * @package TOCHATBE\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><div>
	<p>
		<strong>About Message</strong>
	</p>
	<div>
		<textarea name="_tochatbe_about_message" style="width:100%;" rows="5"><?php echo esc_textarea( $about_message ); ?></textarea>
	</div>
</div>
