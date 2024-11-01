<?php
/**
 * This file contain all plugin functions.
 *
 * @package YITH WooCommerce Name your Price\Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ywcnp_get_product_type_allowed' ) ) {
	/**
	 * Return the product type that can receive the "name your price" field
	 *
	 * @author YITH
	 * @since 1.0.0
	 * @return array
	 */
	function ywcnp_get_product_type_allowed() {

		return apply_filters( 'ywcnp_product_types', array( 'simple' ) );
	}
}

if ( ! function_exists( 'ywcnp_get_error_message' ) ) {
	/**
	 * Return the error messages
	 *
	 * @author YITH
	 * @since 1.0.0
	 * @param string $message_type The message type.
	 * @return string
	 */
	function ywcnp_get_error_message( $message_type ) {

		$messages = apply_filters(
			'ywcnp_add_error_message',
			array(
				'negative_price' => get_option( 'ywcnp_negative_price_label', __( 'Please enter a value greater to 0', 'yith-woocommerce-name-your-price' ) ),
				'invalid_price'  => get_option( 'ywcnp_invalid_price_label', __( 'Please enter a valid price', 'yith-woocommerce-name-your-price' ) ),
			)
		);

		return $messages[ $message_type ];
	}
}


if ( ! function_exists( 'ywcnp_product_is_name_your_price' ) ) {
	/**
	 * Check if product is "name your price"
	 *
	 * @param WC_Product $product The product.
	 *
	 * @return bool
	 * @author YITH
	 * @since 1.0.0
	 */
	function ywcnp_product_is_name_your_price( $product ) {

		$r = false;

		if ( $product instanceof WC_Product ) {
			if ( $product->is_type( array( 'simple', 'variation' ) ) ) {
				$r = $product->get_meta( '_is_nameyourprice' );

			} elseif ( $product->is_type( 'variable' ) ) {
				$r = $product->get_meta( '_variation_has_nameyourprice' );
			}
		}

		return $r;
	}
}

if ( ! function_exists( 'ywcnp_format_number' ) ) {

	/**
	 * Format a number
	 *
	 * @author YITH
	 * @since 1.0.0
	 * @param string $number The number to format.
	 * @return string
	 */
	function ywcnp_format_number( $number ) {

		$number = str_replace( get_option( 'woocommerce_price_thousand_sep' ), '', $number );

		return wc_format_decimal( $number );
	}
}
