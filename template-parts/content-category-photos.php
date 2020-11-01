<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Swanwick
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php swanwick_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail();
			}
			$excerpt = get_the_excerpt();
			if ( empty( $excerpt ) ) {
				$excerpt = wp_trim_words( $post->post_content, 25 );
			}
			echo wpautop( $excerpt );
			// Link to the post.
			echo '<p class="the-permalink the-permalink-' . $post->ID . '"><a href="' . get_the_permalink( $post->ID ) . '">' . esc_html__( 'Read more', 'swanwick' ) . ' &raquo;</a></p>';

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'swanwick' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php swanwick_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
