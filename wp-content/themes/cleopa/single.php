<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cleopa
 */
$single_blog_sidebar = cleopa_get_options('nbcore_blog_sidebar');
$title_position = cleopa_get_options('nbcore_blog_single_title_position');
$page_title = cleopa_get_options('show_title_section');
get_header();
    if('position-1' === $title_position) {
        echo '<div class="nb-page-title-wrap"><div class="container"><div class="nb-page-title">';
        cleopa_posted_on();
        the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        cleopa_get_categories();
        echo '</div></div></div>';
    } ?>
	<div class="container">
		<div class="single-blog row <?php cleopa_blog_classes(); ?>">

			<div id="primary" class="content-area">
				<main id="main" class="site-main">

				<?php
				while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="entry-content">
							<?php
							if(cleopa_get_options('nbcore_blog_single_show_thumb')):
								$thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full' );
								if($thumb):
								?>
								<div class="entry-image">
									<?php
									printf('<img src="%1$s" title="%2$s" width="%3$s" height="%4$s" />',
										$thumb[0],
										esc_attr(get_the_title()),
										$thumb[1],
										$thumb[2]
									);
									?>
								</div>
							<?php endif;
							endif; ?>
							<div class="entry-block">
								<?php if(('position-2' === $title_position) || (('position-1' === $title_position) && !($page_title))) {
									the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
									cleopa_posted_on();
									cleopa_get_categories();
								}?>
								<div class="entry-text">
									<?php
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
									?>
								</div>
								<?php cleopa_get_tags(); ?>
								<div class="clear"></div>
							</div>
						</div>
								<?php
								if('inside-content' === cleopa_get_options('share_buttons_position')) {
									if(cleopa_get_options('nbcore_blog_single_show_social') && function_exists('nbcore_share_social')) {
										$style = cleopa_get_options('share_buttons_style');
										$position = cleopa_get_options('share_buttons_position');
										nbcore_share_social($style,$position);
									}
								}
								?>
								<?php 
								$author_meta = esc_html( get_the_author_meta( 'display_name' ) );
								$author_avatar = get_avatar(get_the_author_meta('ID'), 100);
								$author_desc = get_the_author_meta('user_description');
								if(cleopa_get_options('nbcore_blog_single_show_author')):
									if($author_desc): ?>
										<div class="entry-author-wrap">
											<div class="entry-author">
												<div class="author-image">
													<?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
												</div>
												<div class="author-meta">
													<div class="author-name">
														<?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?>
													</div>
													<div class="author-description">
														<?php echo esc_html($author_desc); ?>
													</div>
												</div>
											</div>
										</div>
									<?php endif;
								endif; ?>
								<?php if(cleopa_get_options('nbcore_blog_single_show_nav')): ?>
								<nav class="single-blog-nav">
									<?php
										previous_post_link( '<div class="prev">%link<span>' .  esc_html__( 'Previous post', 'cleopa' ) . '</span></div>', _x( '<span class="meta-nav"><i class="icon-left-open"></i></span>%title', 'Previous post', 'cleopa' ) );
										next_post_link(     '<div class="next">%link<span>' .  esc_html__( 'Next post', 'cleopa' ) . '</span></div>',     _x( '%title<span class="meta-nav"><i class="icon-right-open"></i></span>', 'Next post', 'cleopa' ) );
									?>
								</nav><!-- .single-nav -->
								<?php endif; ?>
					</article>
                    <?php
                    if(cleopa_get_options('nbcore_blog_single_show_comments')) {
                        if ( comments_open() || get_comments_number() ) {
                            comments_template();
                        }
                    }
                    ?>
				<?php
				endwhile; // End of the loop.
				?>
	
				</main><!-- #main -->
			</div><!-- #primary -->
		<?php
        if('no-sidebar' !== $single_blog_sidebar) {
            get_sidebar();
        }
		?>
		</div>
	</div>
		

<?php
get_footer();
