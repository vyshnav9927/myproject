<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="c-catalog-ordering <?php ideapark_class( ideapark_mod( '_with_filter_desktop' ), 'c-catalog-ordering--desktop-filter', '' ); ?>">
	<div class="c-catalog-ordering__col c-catalog-ordering__col--result">
		<?php woocommerce_result_count(); ?>
	</div>
	<div class="c-catalog-ordering__col c-catalog-ordering__col--ordering">
		<?php woocommerce_catalog_ordering(); ?>
	</div>
	<?php if ( ideapark_mod( '_with_filter_desktop' ) ) { ?>
		<button class="h-cb c-catalog-ordering__filter-show-button c-catalog-ordering__filter-show-button--desktop js-filter-show-button" type="button">
			<?php esc_html_e( 'Filter', 'luchiana' ); ?><i
				class="ip-filter c-catalog-ordering__filter-ico"></i>
		</button>
	<?php } ?>
	<?php if ( ideapark_mod( '_with_filter' ) ) { ?>
		<button class="h-cb c-catalog-ordering__filter-show-button c-catalog-ordering__filter-show-button--mobile js-filter-show-button" type="button">
			<?php esc_html_e( 'Filter', 'luchiana' ); ?><i
				class="ip-filter c-catalog-ordering__filter-ico"></i>
		</button>
	<?php } ?>
</div>