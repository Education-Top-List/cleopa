<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package cleopa
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
        <?php
        cleopa_featured_thumb();
		?>
		<div class="entry-block">
			<?php
			// cleopa_get_date();
			the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
			cleopa_posted_on();
			if(cleopa_get_options('nbcore_blog_archive_comments')):?>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
					<span class="comments-link"><i class="icon-speech-bubble"></i><?php comments_popup_link( esc_html__( 'Leave a comment', 'cleopa' ), esc_html__( 'One Comment', 'cleopa' ), esc_html__( '% Comments', 'cleopa' ) ); ?></span>
				<?php endif; ?>
			<?php endif;
			if(cleopa_get_options('nbcore_blog_archive_summary')):
			?>
				<div class="entry-text">
					<?php
					if(cleopa_get_options('nbcore_excerpt_only')) :
						cleopa_get_excerpt();
						echo '<div class="read-more-link"><a class="bt-4 nb-secondary-button" href="' . get_permalink() . '">' . esc_html__('View post', 'cleopa') . '<span>&rarr;</span></a></div>';
					else :
						the_content( sprintf(
						/* translators: %s: Name of current post. */
							wp_kses( esc_html__( 'Continue reading %s ', 'cleopa' ) . '<span class="meta-nav">&rarr;</span>', array( 'span' => array( 'class' => array() ) ) ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						) );

						wp_link_pages( array(
							'before' => '<div class="page-links ' . cleopa_get_options('pagination_style') . '">' . esc_html__( 'Pages:', 'cleopa' ),
							'after'  => '</div>',
							'link_before' => '<span>',
							'link_after' => '</span>',
						) );
					endif; ?>
				</div>
			<?php endif; ?>
			<?php cleopa_get_tags(); ?>
		</div>
	</div>
	
</article><!-- #post-## -->
