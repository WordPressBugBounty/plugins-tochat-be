<?php
/**
 * TOCHAT.BE
 *
 * @author      TOCHAT.BE
 * @copyright   2020 TOCHAT.BE
 * @license     GPL-2.0-or-later
 * @package     TOCHATBE
 *
 * @wordpress-plugin
 * Plugin Name: TOCHAT.BE
 * Plugin URI:  https://tochat.be/premium/
 * Description: Add a WhatsApp click to chat button on your website for free. WhatsApp is the most use messenger app in the world. WordPress is the best platform to present your business to the world. Make your customers connect with you with a click.It is very easy and simple. Just install this free plugin and you can connect your WhatsApp account with your WordPress website and communicate with your users.
 * Version:     1.3.4
 * Author:      TOCHAT.BE
 * Author URI:  https://tochat.be
 * Text Domain: tochat-be
 * License:     GPL v2 or later
 * Domain Path: /languages/
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'TOCHATBE_PLUGIN_FILE' ) ) {
	define( 'TOCHATBE_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'TOCHATBE_PLUGIN_PATH' ) ) {
	define( 'TOCHATBE_PLUGIN_PATH', plugin_dir_path( TOCHATBE_PLUGIN_FILE ) );
}

if ( ! defined( 'TOCHATBE_PLUGIN_URL' ) ) {
	define( 'TOCHATBE_PLUGIN_URL', plugin_dir_url( TOCHATBE_PLUGIN_FILE ) );
}

if ( ! defined( 'TOCHATBE_PLUGIN_VER' ) ) {
	define( 'TOCHATBE_PLUGIN_VER', '1.3.4' );
}

/**
 * Install TOCHATBE plugin.
 *
 * This function will be called when the plugin is activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function tochatbe_plugin_installation() {
	require_once TOCHATBE_PLUGIN_PATH . 'includes/class-tochatbe-install.php';
	TOCHATBE_Install::install();
}
register_activation_hook( TOCHATBE_PLUGIN_FILE, 'tochatbe_plugin_installation' );

/**
 * Load TOCHATBE plugin.
 *
 * This function will be called when the plugin is loaded.
 *
 * @since 1.0.0
 *
 * @return void
 */
function tochatbe_plugin_init() {
	require_once TOCHATBE_PLUGIN_PATH . 'includes/class-tochatbe-init.php';

	$tochatbe = new TOCHATBE_Init();
	$tochatbe->init();
}
add_action( 'plugins_loaded', 'tochatbe_plugin_init', 20 );

/**
 * Redirect to add agent if no agent added when plugin activate.
 *
 * @param string $plugin The plugin file.
 * @return void
 */
function tochatbe_redirect_to_add_agent_if_no_agent_added_when_plugin_activate( $plugin ) {
	if ( $plugin !== plugin_basename( TOCHATBE_PLUGIN_FILE ) ) { // phpcs:ignore WordPress.PHP.YodaConditions.NotYoda
		return;
	}

	$agents = get_posts(
		array(
			'post_type'      => 'tochatbe_agent',
			'posts_per_page' => -1,
		)
	);

	if ( $agents ) {
		return;
	}

	wp_safe_redirect( admin_url( 'post-new.php?post_type=tochatbe_agent' ) );
	exit;
}
add_action( 'activated_plugin', 'tochatbe_redirect_to_add_agent_if_no_agent_added_when_plugin_activate' );
