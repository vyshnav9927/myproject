<?php if ( ideapark_woocommerce_on() && ideapark_mod( 'auth_enabled' ) ) { ?>
	<div class="c-header__auth-button">
		<?php echo ideapark_get_account_link(); ?>
	</div>
<?php } ?>