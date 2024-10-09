<?php
/**
 * Agent
 *
 * This class is used to manage agents.
 *
 * @package TOCHATBE\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TOCHATBE_Agent class.
 */
class TOCHATBE_Agent {

	/**
	 * Agent ID.
	 *
	 * @var int
	 */
	private $agent_id;

	/**
	 * Class constructor.
	 *
	 * @param int $agent_id Agent ID.
	 */
	public function __construct( $agent_id ) {
		$this->agent_id = $agent_id;
	}

	/**
	 * Get agent ID.
	 *
	 * @return int Agent ID.
	 */
	public function get_name() {
		return get_post_meta( $this->agent_id, 'agent_name', true );
	}

	/**
	 * Get agent title.
	 *
	 * @return string Agent title.
	 */
	public function get_title() {
		return get_post_meta( $this->agent_id, 'agent_title', true );
	}

	/**
	 * Get agent number.
	 *
	 * @return string Agent number.
	 */
	public function get_number() {
		return get_post_meta( $this->agent_id, 'agent_number', true );
	}

	/**
	 * Get agent group ID.
	 *
	 * @return int Agent group ID.
	 */
	public function get_group_id() {
		return get_post_meta( $this->agent_id, 'agent_group_id', true );
	}

	/**
	 * Get pre-defined message.
	 *
	 * @return string Pre-defined message.
	 */
	public function get_pre_defined_message() {
		return get_post_meta( $this->agent_id, 'pre_defined_message', true );
	}

	/**
	 * Get agent image.
	 *
	 * @return string Agent image.
	 */
	public function get_image() {
		if ( has_post_thumbnail( $this->agent_id ) ) {
			return get_the_post_thumbnail_url( $this->agent_id );
		} else {
			return TOCHATBE_PLUGIN_URL . 'assets/images/ToChatBe.png';
		}
	}

	/**
	 * Get agent type.
	 *
	 * @return string Agent type.
	 */
	public function get_type() {
		$type = get_post_meta( $this->agent_id, 'agent_type', true );

		if ( ! $type ) {
			return 'number';
		}

		return $type;
	}
}
