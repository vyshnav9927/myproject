<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version       2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="c-cart__totals cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) {
	echo 'calculated_shipping';
} ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<table class="c-cart__totals-table shop_table">

		<tr class="c-cart__totals-subtotal cart-subtotal">
			<th class="c-cart__sub-sub-header"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<td class="c-cart__totals-price" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr>
				<td colspan="2" class="c-cart__totals-space c-cart__totals-space--hr"></td>
			</tr>
			<tr class="c-cart__totals-discount cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th class="c-cart__sub-sub-header"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td class="c-cart__totals-price" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<tr>
				<td colspan="2" class="c-cart__totals-space c-cart__totals-space--hr"></td>
			</tr>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<tr>
				<td colspan="2" class="c-cart__totals-space c-cart__totals-space--hr"></td>
			</tr>
			<tr class="shipping-calculator">
				<td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>" colspan="2"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr>
				<td colspan="2" class="c-cart__totals-space c-cart__totals-space--hr"></td>
			</tr>
			<tr class="fee">
				<th class="c-cart__sub-header"><?php echo esc_html( $fee->name ); ?></th>
				<td class="c-cart__totals-price" data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) :
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
				? sprintf( ' <small>(' . esc_html__( '(estimated for %s)', 'woocommerce' ) . ')</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
				: '';

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr>
						<td colspan="2" class="c-cart__totals-space c-cart__totals-space--hr"></td>
					</tr>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<th class="c-cart__sub-sub-header"><?php echo esc_html( $tax->label ) . $estimated_text; ?></th>
						<td class="c-cart__totals-price" data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="2" class="c-cart__totals-space c-cart__totals-space--hr"></td>
				</tr>
				<tr class="tax-total">
					<th class="c-cart__sub-sub-header"><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></th>
					<td class="c-cart__totals-price" data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr>
			<td colspan="2" class="c-cart__totals-space c-cart__totals-space--hr"></td>
		</tr>

		<tr class="order-total">
			<th class="c-cart__sub-sub-header"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td class="c-cart__totals-price c-cart__totals-price--total" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

		<tr>
			<td class="c-cart__totals-action wc-proceed-to-checkout" colspan="2">
				<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
			</td>
		</tr>

	</table>


	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
