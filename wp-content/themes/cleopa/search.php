<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package cleopa
 */

get_header();
cleopa_page_title();
?>

    <div class="container">
        <div class="row">
            <section id="primary" class="content-area">
                <main id="main" class="site-main content-search">

                    <?php
                    if (have_posts()) : ?>

                        <header class="page-header">
                            <h1 class="page-title"><?php printf(esc_html__('Search Results for: %s', 'cleopa'), '<span>' . get_search_query() . '</span>'); ?></h1>
                        </header><!-- .page-header -->

                        <?php
                        /* Start the Loop */
                        while (have_posts()) : the_post();

                            /**
                             * Run the loop for the search to output the results.
                             * If you want to overload this in a child theme then include a file
                             * called content-search.php and that will be used instead.
                             */
							 
                            get_template_part( 'template-parts/content', 'search' );
							
                        endwhile;

                        cleopa_paging_nav();

                    else : ?>
                        <section class="no-results not-found">
                            <header class="page-header">
                                <h1 class="page-title"><?php esc_html_e('Nothing Found', 'cleopa'); ?></h1>
                            </header><!-- .page-header -->

                            <div class="page-content">
                                <?php
                                if (is_home() && current_user_can('publish_posts')) : ?>

                                    <p><?php printf(wp_kses(esc_html__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'cleopa'), array('a' => array('href' => array()))), esc_url(admin_url('post-new.php'))); ?></p>

                                <?php elseif (is_search()) : ?>

                                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'cleopa'); ?></p>
                                    <?php
                                    get_search_form();

                                else : ?>

                                    <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'cleopa'); ?></p>
                                    <?php
                                    get_search_form();

                                endif; ?>
                            </div><!-- .page-content -->
                        </section><!-- .no-results -->
                        <?php
                    endif; ?>

                </main><!-- #main -->
            </section><!-- #primary -->
        </div>
    </div>


<?php
get_footer();
