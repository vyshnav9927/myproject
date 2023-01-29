<?php if ( ideapark_mod( 'search_enabled' ) ) { ?>
	<div class="c-header__search-button">
	<a class="c-header__button-link c-header__button-link--search js-search-button" type="button"
	   href="<?php echo esc_url( get_search_link() ); ?>" onclick="return false;"><i class="<?php echo ( ideapark_mod( 'custom_header_icon_search' ) ?: 'ip-search' ); ?>"><!-- --></i>
	</a>
	</div><?php } ?>