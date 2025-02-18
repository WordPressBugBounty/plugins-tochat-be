<?php
/**
 * Admin Functions
 *
 * The plugin admin core functions file.
 *
 * @package TOCHATBE\Admin\Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get the current screen ID.
 * 
 * @since 1.3.4
 *
 * @return string|bool The current screen ID or null if not found.
 */
function tochatbe_get_current_screen_id() {
	if ( ! is_admin() ) {
		return null;
	}

	$screen = get_current_screen();
	if ( ! $screen ) {
		return null;
	}

	return $screen->id;
}
