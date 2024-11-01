<?php // phpcs:ignore WordPress.Files.FileName
/**
 * The admin template
 *
 * @package YITH WooCommerce Name your Price\Admin
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$supported_type = ywcnp_get_product_type_allowed();

$product          = wc_get_product( $post->ID );
$is_nameyourprice = $product->get_meta( '_is_nameyourprice' );

$is_visible = $is_nameyourprice && $product->is_type( $supported_type ) ? 'display:block;' : 'display:none;';


?>
<div id="ywcnp_disabled_field_message" class="options_group" style="<?php echo esc_attr( $is_visible ); ?>">

	<span class="description"><?php esc_html_e( 'These fields have been deactivated because of the "Name Your Price" option', 'yith-woocommerce-name-your-price' ); ?></span>

</div>
