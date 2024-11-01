<?php
/**
 * This is the name your price template.
 *
 * @package YITH WooCommerce Name Your Price\Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>

<div id="ywcnp_form_name_your_price" style="margin:10px 0px;">
	<?php do_action( 'ywcnp_before_suggest_price_single' ); ?>
	<?php
	$sugg_label_text = get_option( 'ywcnp_button_loop_label', __( 'Choose the amount', 'yith-woocommerce-name-your-price' ) );
	?>
	<label for="ywcnp_suggest_price_single"><?php echo esc_html( $sugg_label_text ); ?></label>
	<input type="text"  id="ywcnp_suggest_price_single" class="ywcnp_sugg_price short"  name="ywcnp_amount"/>
	<?php do_action( 'ywcnp_after_suggest_price_single' ); ?>
</div>
