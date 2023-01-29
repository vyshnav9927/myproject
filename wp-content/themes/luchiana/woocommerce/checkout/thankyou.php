<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @package       WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="c-order">

	<?php if ( $order ) { ?>

		<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) { ?>

			<div class="c-order__result">
				<i class="ip-cart-failed c-order__result-ico c-order__result-ico--failed"></i>
				<div class="c-order__result-message">
					<?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?>
				</div>
				<div class="c-order__result-action">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="c-button"><?php esc_html_e( 'Pay', 'woocommerce' ) ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="c-button"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
				</div>
			</div>

		<?php } else { ?>

			<div class="c-order__result">
				<i class="ip-cart-success c-order__result-ico c-order__result-ico--success"></i>
				<div class="c-order__result-message">
					<?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?>
				</div>
			</div>

			<ul class="c-order__details">

				<li class="c-order__details-item c-order__details-item--order">
					<span class="c-order__details-title"><?php esc_html_e( 'Order number:', 'woocommerce' ); ?></span>
					<span class="c-order__details-value"><?php echo ideapark_wrap( $order->get_order_number() ); ?></span>
				</li>

				<li class="c-order__details-item c-order__details-item--date">
					<span class="c-order__details-title"><?php esc_html_e( 'Date:', 'woocommerce' ); ?></span>
					<span class="c-order__details-value"><?php echo wc_format_datetime( $order->get_date_created() ); ?></span>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="c-order__details-item c-order__details-item--email">
						<span class="c-order__details-title"><?php esc_html_e( 'Email:', 'woocommerce' ); ?></span>
						<span class="c-order__details-value"><?php echo ideapark_wrap( $order->get_billing_email() ); ?></span>
					</li>
				<?php endif; ?>

				<li class="c-order__details-item c-order__details-item--total">
					<span class="c-order__details-title"><?php esc_html_e( 'Total:', 'woocommerce' ); ?></span>
					<span class="c-order__details-value"><?php echo ideapark_wrap( $order->get_formatted_order_total() ); ?></span>
				</li>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="c-order__details-item c-order__details-item--payment-method">
						<span class="c-order__details-title"><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?></span>
						<span class="c-order__details-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
					</li>
				<?php endif; ?>
			</ul>

		<?php } ?>

		<div class="c-order__info">
			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
		</div>

	<?php } else { ?>

		<div class="c-order__result">
			<i class="ip-cart-success c-order__result-ico c-order__result-ico--success"></i>
			<div class="c-order__result-message">
				<?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?>
			</div>
		</div>

	<?php } ?>

</div>