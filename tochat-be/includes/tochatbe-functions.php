<?php
/**
 * Functions
 *
 * The plugin core functions file.
 *
 * @package TOCHATBE\Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get plugin option.
 *
 * @param string $option  Option name.
 * @param string $section Section name.
 * @param string $default Default value. Default is empty string.
 * @return mixed Option value.
 */
function tochatbe_get_option( $option, $section, $default = '' ) {
	$options = get_option( $section );

	if ( isset( $options[ $option ] ) ) {
		return $options[ $option ];
	}

	return $default;
}

/**
 * Get plugin appearance option.
 *
 * @param string $option  Option name.
 * @param string $default Default value. Default is empty string.
 * @return mixed Option value.
 */
function tochatbe_appearance_option( $option, $default = '' ) {
	return tochatbe_get_option( $option, 'tochatbe_appearance_settings', $default );
}

/**
 * Get plugin basic option.
 *
 * @param string $option  Option name.
 * @param string $default Default value. Default is empty string.
 * @return mixed Option value.
 */
function tochatbe_basic_option( $option, $default = '' ) {
	return tochatbe_get_option( $option, 'tochatbe_basic_settings', $default );
}

/**
 * Get plugin analytics option.
 *
 * @param string $option  Option name.
 * @param string $default Default value. Default is empty string.
 * @return mixed Option value.
 */
function tochatbe_google_analytics_option( $option, $default = '' ) {
	return tochatbe_get_option( $option, 'tochatbe_google_analytics_settings', $default );
}

/**
 * Get plugin Facebook analytics option.
 *
 * @param string $option  Option name.
 * @param string $default Default value. Default is empty string.
 * @return mixed Option value.
 */
function tochatbe_facebook_analytics_option( $option, $default = '' ) {
	return tochatbe_get_option( $option, 'tochatbe_facebook_analytics_settings', $default );
}

/**
 * Get plugin order button option.
 *
 * @param string $option  Option name.
 * @param string $default Default value. Default is empty string.
 * @return mixed Option value.
 */
function tochatbe_woo_order_button_option( $option, $default = '' ) {
	return tochatbe_get_option( $option, 'tochatbe_woo_order_button_settings', $default );
}

/**
 * Get plugin schedule option.
 *
 * @param string $day    Day name.
 * @param string $result Result.
 * @return string|false Option value. False if option not found.
 */
function tochatbe_get_schedule_option( $day, $result ) {
	$schedule = tochatbe_basic_option( 'schedule' );

	if ( ! isset( $schedule[ $day ] ) ) {
		return false;
	}
	if ( ! isset( $schedule[ $day ][ $result ] ) ) {
		return false;
	}

	return $schedule[ $day ][ $result ];
}

/**
 * Get plugin filter by pages option.
 *
 * @param string $option  Option name.
 * @return mixed|false Option value. False if option not found.
 */
function tochatbe_get_filter_by_pages_option( $option ) {
	$filter = tochatbe_basic_option( 'filter_by_pages' );

	if ( ! isset( $filter[ $option ] ) ) {
		return false;
	}

	return $filter[ $option ];
}

/**
 * Get plugin GDPR option.
 *
 * @param string $option  Option name.
 * @param string $default Default value. Default is empty string.
 * @return mixed Option value.
 */
function tochatbe_gdpr_option( $option, $default = '' ) {
	return tochatbe_get_option( $option, 'tochatbe_gdpr_settings', $default );
}

/**
 * Get plugin type & chat option.
 *
 * @param string $option  Option name.
 * @param string $default Default value. Default is empty string.
 * @return mixed Option value.
 */
function tochatbe_type_and_chat_option( $option, $default = '' ) {
	return tochatbe_get_option( $option, 'tochatbe_type_and_chat_settings', $default );
}

/**
 * Get plugin just WhatsApp icon option.
 *
 * @param string $option  Option name.
 * @param string $default Default value. Default is empty string.
 * @return mixed Option value.
 */
function tochatbe_just_whatsapp_icon_option( $option, $default = '' ) {
	return tochatbe_get_option( $option, 'tochatbe_just_whatsapp_icon_settings', $default );
}

/**
 * GDPR checkbox.
 *
 * @return void
 */
function tochatbe_gdpr_check() {
	$policy_page_id    = tochatbe_gdpr_option( 'privacy_page' );
	$policy_page_title = get_the_title( $policy_page_id );
	$policy_page_url   = get_the_permalink( $policy_page_id );
	$message           = str_replace(
		'{policy_page}',
		sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $policy_page_url ), esc_html( $policy_page_title ) ),
		esc_textarea( tochatbe_gdpr_option( 'message' ) )
	);

	if ( 'yes' === tochatbe_gdpr_option( 'status' ) ) {
		?>
			<div class="tochatbe-gdpr">
				<label for="tochatbe-gdpr-checkbox">
					<input type="checkbox" id="tochatbe-gdpr-checkbox"> <?php echo $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</label>
			</div>
		<?php
	}
}

/**
 * Get current URL.
 *
 * @return string Current URL.
 */
function tochatbe_get_current_url() {
	global $wp;

	return home_url( add_query_arg( array(), $wp->request ) );
}

/**
 * Page dropdown.
 *
 * Display a dropdown of pages.
 *
 * @param array $args Arguments. Default is empty array.
 * @return void
 */
function tochatbe_page_dropdown( $args = array() ) {
	$html     = '';
	$multiple = ( isset( $args['multiple'] ) ) ? 'multiple' : '';
	$name     = ( isset( $args['name'] ) ) ? 'name="' . esc_attr( $args['name'] ) . '"' : '';
	$class    = ( isset( $args['class'] ) ) ? 'class="' . esc_attr( $args['class'] ) . '"' : '';

	$query = new WP_Query(
		array(
			'post_type'      => 'page',
			'posts_per_page' => -1,
		)
	);

	$html .= "<select $class $name $multiple>" . PHP_EOL;

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$selected = '';
			if ( $multiple && isset( $args['selected'] ) && is_array( $args['selected'] ) ) {
				$selected = in_array( get_the_ID(), $args['selected'] ) ? 'selected' : '';
			}

			$html .= '<option value="' . get_the_ID() . '" ' . $selected . '>' . get_the_title() . '</option>' . PHP_EOL;
		}

		wp_reset_postdata();
	}

	$html .= '</select>' . PHP_EOL;

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get WooCommerce order statuses.
 *
 * @return array|false Array of statuses, false if WC is not active.
 */
function tochatbe_get_woo_order_statuses() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}

	return wc_get_order_statuses();
}

/**
 * Escape CSV field.
 * 
 * @param string $field Field.
 * @return string Escaped field.
 */
function tochatbe_escape_csv_field( $field ) {
	$field = str_replace( '"', '""', $field );
	if ( strpos( $field, ',' ) !== false || strpos( $field, '"' ) !== false || strpos( $field, "\n" ) !== false ) {
		$field = '"' . $field . '"';
	}
	
	return $field;
}