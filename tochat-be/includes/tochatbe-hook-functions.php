<?php
/**
 * Hook Functions
 *
 * This file is used to attach all hooks to the plugin.
 *
 * @package TOCHATBE
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Override about message by post or page.
 *
 * @since 1.0.5
 *
 * @param string $about_message The about message.
 * @return string
 */
function tochatbe_about_message_by_page( $about_message ) {
	if ( ! get_the_ID() ) {
		return $about_message;
	}

	$mod_about_message = get_post_meta( get_the_ID(), '_tochatbe_about_message', true );
	if ( ! $mod_about_message ) {
		return $about_message;
	}

	return $mod_about_message;
}
