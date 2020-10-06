<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package cleopa
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    if('no-thumb' !== cleopa_get_post_meta('page_thumb')) {
        cleopa_featured_thumb();
    }
    ?>
    <div class="entry-content">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links ' . cleopa_get_options('pagination_style') . '">' . esc_html__( 'Pages:', 'cleopa' ),
				'after'  => '</div>',
				'link_before' => '<span>',
				'link_after' => '</span>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
