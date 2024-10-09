<?php
/**
 * Hooks
 *
 * This file is used to attach all hooks to the plugin.
 *
 * @package TOCHATBE
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * See: tochatbe_about_message_by_page()
 */
add_filter( 'tochatbe_about_message', 'tochatbe_about_message_by_page', 10, 1 );
