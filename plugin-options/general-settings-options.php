<?php // phpcs:ignore WordPress.Files.FileName
/**
 * The file that return the plugin options
 *
 * @package YITH WooCommerce Name your Price\Admin
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$setting = array(

	'general-settings' => array(
		'section_general_settings'     => array(
			'name' => __( 'General settings', 'yith-woocommerce-name-your-price' ),
			'type' => 'title',
			'id'   => 'ywcnp_section_general',
		),

		'button_loop_text'             => array(
			'name'      => __( '"Name Your Price" button text ', 'yith-woocommerce-name-your-price' ),
			'desc'      => __( 'Set the text of the "Name Your Price" button of the Shop page ', 'yith-woocommerce-name-your-price' ),
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'id'        => 'ywcnp_button_loop_label',
			'std'       => __( 'Name Your Price', 'yith-woocommerce-name-your-price' ),
			'default'   => __( 'Name Your Price', 'yith-woocommerce-name-your-price' ),
		),
		'allow_zero'                   => array(
			'name'      => __( 'Allow free purchase', 'yith-woocommerce-name-your-price' ),
			'desc'      => __( 'Allow your customers to purchase your "Name your price" product for free', 'yith-woocommerce-name-your-price' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'off',
			'id'        => 'ywcnp_allow_zero_price',
		),

		'section_general_settings_end' => array(
			'type' => 'sectionend',
			'id'   => 'ywcnp_section_general_end',
		),
	),
);

return apply_filters( 'yith_wc_name_your_price_free_settings', $setting );
