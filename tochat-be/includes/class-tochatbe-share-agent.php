<?php
/**
 * Share Agent
 *
 * This class is used to share agent.
 *
 * @package TOCHATBE\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TOCHATBE_Share_Agent class.
 */
class TOCHATBE_Share_Agent {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'share_agent' ) );
	}

	/**
	 * Share agent.
	 *
	 * @return void
	 */
	public function share_agent() {
		if ( ! isset( $_GET['tochatbe_agent_share'] ) || empty( $_GET['tochatbe_agent_share'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$agent_number = sanitize_text_field( wp_unslash( $_GET['tochatbe_agent_share'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( ! is_numeric( $agent_number ) ) {
			wp_die( 'Invalid agent WhatsApp number!' );
		}

		$agent_number = absint( $agent_number );
		$agent        = get_posts(
			array(
				'post_type'      => 'tochatbe_agent',
				'posts_per_page' => 1,
				'post_status'    => 'publish',
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => 'agent_number',
						'value'   => $agent_number,
						'compare' => '=',
					),
				),
			)
		);

		if ( ! $agent ) {
			wp_die( 'No agent found!' );
		}

		$agent = $agent[0];

		TOCHATBE_Log::log(
			array(
				'contacted_to' => $agent_number,
				'referral'     => 'Agent shared',
			)
		);

		wp_safe_redirect( 'https://wa.me/' . $agent_number );
		exit;
	}
}

return new TOCHATBE_Share_Agent();
