<?php get_header(); ?>
<?php get_template_part( 'templates/page-header' ); ?>
<?php $with_sidebar = ideapark_mod( 'sidebar_blog' ) && is_active_sidebar( 'post-sidebar' ); ?>

<div
	class="c-blog l-section l-section--container l-section--top-margin-80 l-section--bottom-margin <?php if ( $with_sidebar ) { ?> l-section--with-sidebar<?php } ?>">
	<div
		class="l-section__content <?php if ( $with_sidebar ) { ?> l-section__content--with-sidebar<?php } ?>">
		<div class="c-blog-wrap <?php ideapark_class( ideapark_mod( 'post_layout' ) == 'grid', 'c-blog-wrap--grid', 'c-blog-wrap--list' ); ?><?php ideapark_class( $with_sidebar, ' c-blog-wrap--sidebar', ' c-blog-wrap--no-sidebar' ); ?>">
			<?php if ( have_posts() ): ?>
				<div
					class="<?php ideapark_class( ideapark_mod( 'post_layout' ) == 'grid', 'c-blog__grid js-post-masonry', 'c-blog__list' ); ?> <?php ideapark_class( ideapark_mod( 'sticky_sidebar' ), 'js-sticky-sidebar-nearby' ); ?>">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'templates/content-list' ); ?>
					<?php endwhile; ?>
				</div>
				<?php ideapark_corenavi();
			else : ?>
				<div class="c-blog__nothing">
					<div
						class="c-blog__nothing-text"><?php esc_html_e( 'We could not find any results for your search. You can give it another try through the search form below:', 'luchiana' ); ?>
					</div>
					<div class="c-blog__nothing-search">
						<?php get_search_form(); ?>
					</div>
				</div>

			<?php endif; ?>
		</div>
	</div>

	<?php if ( $with_sidebar ) { ?>
		<div class="l-section__sidebar l-section__sidebar--right">
			<?php get_sidebar( 'post' ); ?>
		</div>
	<?php } ?>
</div>

<?php get_footer(); ?>
