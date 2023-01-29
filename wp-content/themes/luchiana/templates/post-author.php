<?php
$description = get_the_author_meta( 'description' );
ob_start();
foreach ( ideapark_social_networks() AS $code =>  $name ) {
	if ( $url = get_the_author_meta( $code ) ) { ?>
		<a href="<?php echo esc_url( $url ); ?>" class="c-soc__link">
			<i class="ip-<?php echo esc_attr( $code ) ?> c-soc__icon c-soc__icon--<?php echo esc_attr( $code ) ?>">
				<!-- --></i>
		</a>
	<?php }
}
$author_soc = trim( ob_get_clean() );
if ( $description || $author_soc ) { ?>
	<div class="c-post__author">
		<div class="c-post__author-thumb">
			<?php echo get_avatar( get_the_author_meta( 'email' ), 155 ); ?>
		</div>
		<div class="c-post__author-content">
			<div class="c-post__author-header"><?php esc_html_e( 'Author', 'luchiana' ); ?></div>
			<div class="c-post__author-title"><?php the_author_posts_link(); ?></div>
			<?php echo ideapark_wrap( $description, '<div class="c-post__author-desc">', '</div>' ); ?>
			<?php echo ideapark_wrap( $author_soc, '<div class="c-soc c-post__author-soc">', '</div>' ); ?>
		</div>
	</div>
<?php }