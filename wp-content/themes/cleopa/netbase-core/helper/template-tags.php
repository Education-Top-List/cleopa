<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package cleopa
 */
function cleopa_get_header() {
    $header_style = cleopa_get_options('nbcore_header_style');

    get_template_part('template-parts/headers/' . $header_style);
}

function cleopa_main_nav() {
    $admin_url = get_admin_url() . 'customize.php?url=' . get_permalink() . '&autofocus%5Bsection%5D=menu_locations';

    if (has_nav_menu('primary')) {
        echo '<nav class="main-navigation">';
        echo '<a class="mobile-toggle-button icon-menu"></a>';
        echo '<div class="menu-main-menu-wrap">';
        echo '<div class="menu-main-menu-title"><h3>' . esc_html__('Navigation', 'cleopa') . '</h3><span class="icon-cancel-circle"></span></div>';
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class' => 'nb-navbar',
            'link_before' => '<span>',
            'link_after' => '</span>',
        ));

        echo '</div></nav>';
    } else {
        echo '<ul><li><a href="' . $admin_url . '">' . esc_html__('Assign a menu here', 'cleopa') . '</a></li></ul>';
    }
}

function nbcore_sub_menu()
{
    $admin_url = get_admin_url() . 'customize.php?url=' . get_permalink() . '&autofocus%5Bsection%5D=menu_locations';

    if (has_nav_menu('header-sub')) {
        echo '<nav class="sub-navigation" role="navigation">';

        wp_nav_menu(array(
            'theme_location' => 'header-sub',
            'menu_class' => 'nb-header-sub-menu',
            'link_before' => '<span>',
            'link_after' => '</span>',
        ));

        echo '</nav>';
    } else {
        echo '<a href="' . $admin_url . '">' . esc_html__('Assign a menu for the Sub Menu ', 'core-wp') . '</a>';
    }
}

function cleopa_get_nav_mobile() {
    if (has_nav_menu('primary')) {
        echo '<nav class="main-mobile-navigation">';

        echo '<button class="mobile-toggle-button icon-menu"></button>';

        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class' => 'nb-mobile-navbar',
            'link_before' => '<span>',
            'link_after' => '</span>',
        ));

        echo '</nav>';
    }
}

function cleopa_header_class() {
    $classes = array();

    $classes['header_style'] = cleopa_get_options('nbcore_header_style');

    if (cleopa_get_options('nbcore_header_fixed')) {
        $classes['header_fixed'] = 'fixed';
    }
	
	if (function_exists('get_field') && get_field('page_custom_header') && is_page()) {
		$classes[] = 'site-header-customize';
	}

    echo implode(' ', $classes);
}

if (!function_exists('cleopa_header_woo_section')) {

    function cleopa_header_woo_section($account = TRUE) {
        $header_style = cleopa_get_options('nbcore_header_style');

        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            if ($account):
                ?>
                <div class="header-account-wrap">
					<?php if (is_user_logged_in()):
						if ('default' == $header_style): ?>
							<span class="account-text"><?php esc_html_e('My Account', 'cleopa'); ?></span>
						<?php else: ?>
							<i class="icon-user-o"></i>
						<?php endif; ?>
						<div class="nb-account-dropdown">
							<?php wc_get_template('myaccount/navigation.php'); ?>
						</div>
					<?php else: ?>
						<a href="<?php echo esc_url(wp_login_url()); ?>"
						   class="not-logged-in simplemodal-login" title="<?php esc_attr_e('Login', 'cleopa'); ?>">
							<?php if ('default' == $header_style): ?>
								<span class="account-text"><?php esc_html_e('Login', 'cleopa'); ?></span>
							<?php else: ?>
								<i class="icon-user-o"></i>
							<?php endif; ?>
						</a>
					<?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="header-cart-wrap">
                <a class="nb-cart-section" href="<?php echo wc_get_cart_url(); ?>"
                   title="<?php esc_attr_e('View cart', 'cleopa'); ?>">
                    <i class="icon-bag"></i>
                    <span class="counter"><?php echo WC()->cart->get_cart_contents_count(); ?><span class="counter-l"></span><span class="counter-r"></span></span>
            <?php echo WC()->cart->get_cart_total(); ?>
                </a>
                <div class="mini-cart-section">
                    <div class="mini-cart-wrap">
            <?php woocommerce_mini_cart(); ?>
                    </div>
                </div>
            </div>
        <?php
        }
    }

}
if (!function_exists('cleopa_header_acc_section')) {

    function cleopa_header_acc_section() {
        $header_style = cleopa_get_options('nbcore_header_style');

        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) :
			?>
			<div class="header-account-wrap">
				<?php if (is_user_logged_in()):
					if ('default' == $header_style): ?>
						<span class="account-text"><?php esc_html_e('My Account', 'cleopa'); ?></span>
					<?php else: ?>
						<i class="icon-user-o"></i>
					<?php endif; ?>
					<div class="nb-account-dropdown">
						<?php wc_get_template('myaccount/navigation.php'); ?>
					</div>
				<?php else: ?>
					<a href="<?php echo esc_url(wp_login_url()); ?>"
					   class="not-logged-in simplemodal-login" title="<?php esc_attr_e('Login', 'cleopa'); ?>">
						<?php if ('default' == $header_style): ?>
							<span class="account-text"><?php esc_html_e('Login', 'cleopa'); ?></span>
						   <?php else: ?>
							<i class="icon-user-o"></i>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php
    }

}

function cleopa_social_section($text = false) {
    $facebook = cleopa_get_options('nbcore_header_facebook');
    $twitter = cleopa_get_options('nbcore_header_twitter');
    $linkedin = cleopa_get_options('nbcore_header_linkedin');
    $instagram = cleopa_get_options('nbcore_header_instagram');
    $blog = cleopa_get_options('nbcore_header_blog');
    $pinterest = cleopa_get_options('nbcore_header_pinterest');
    $ggplus = cleopa_get_options('nbcore_header_ggplus');
    if ($facebook || $twitter || $linkedin || $instagram || $blog || $pinterest || $ggplus) {
        echo '<ul class="social-section">';
        if ($facebook) {
            echo '<li class="social-item"><a href="' . esc_url($facebook) . '"><i class="icon-facebook"></i></a></li>';
        }
        if ($twitter) {
            echo '<li class="social-item"><a href="' . esc_url($twitter) . '"><i class="icon-twitter"></i></a></li>';
        }
        if ($linkedin) {
            echo '<li class="social-item"><a href="' . esc_url($linkedin) . '"><i class="icon-linkedin"></i></a></li>';
        }
        if ($instagram) {
            echo '<li class="social-item"><a href="' . esc_url($instagram) . '"><i class="icon-instagram"></i></a></li>';
        }
        if ($blog) {
            echo '<li class="social-item"><a href="' . esc_url($blog) . '"><i class="icon-blogger"></i></a></li>';
        }
        if ($pinterest) {
            echo '<li class="social-item"><a href="' . esc_url($pinterest) . '"><i class="icon-pinterest2"></i></a></li>';
        }
        if ($ggplus) {
            echo '<li class="social-item"><a href="' . esc_url($ggplus) . '"><i class="icon-gplus"></i></a></li>';
        }
        echo '</ul>';
    }
}

function cleopa_search_section($popup = true) {
    echo '<div class="header-search-wrap">';
    if ($popup) {
        echo '<a class="icon-header-search popup-search" href="#nbt-search-wrap" data-rel="prettyPhoto" target="_self"><i class="icon-search"></i></a>';
        echo '<div id="nbt-search-wrap"><div class="nbt-search-wrap">';
    }
    get_search_form();
    if ($popup) {
        echo '</div></div>';
    }
    echo '</div>';
}

function cleopa_get_site_logo() {
    $logo = cleopa_get_options('nbcore_logo_upload');
    if ($logo) {
        $output = '<div class="main-logo img-logo">';
        if ((is_page() && !is_single()) || is_archive()) {
            $output .= '<h1>' . get_bloginfo('name') . '</h1>';
        }
		
		if ((function_exists('get_field') && get_field('page_custom_header')) && cleopa_get_options('nbcore_logo_upload2')) {
			$logo = cleopa_get_options('nbcore_logo_upload2');
		}
		$logoid = attachment_url_to_postid($logo);
		$logo_srcset = wp_get_attachment_image_srcset( $logoid, 'full' );
		
        $output .= '<a href="' . esc_url(home_url('/')) . '" title="' . get_bloginfo('description') . '">';
        $output .= '<img src="' . $logo . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '"' . ($logo_srcset ? ' srcset="' . $logo_srcset . '"' : '') . '>';
        $output .= '</a>';
        $output .= '</div>';
    } else {
        $output = '<div class="main-logo text-logo">';
        if ((is_page() && !is_single()) || is_archive()) {
            $output .= '<h1>' . get_bloginfo('name') . '</h1>';
        }
        $output .= '<a href="' . esc_url(home_url('/')) . '" title="' . get_bloginfo('description') . '">';
        $output .= get_bloginfo('name');
        $output .= '</a>';
        $output .= '</div>';
    }
    print($output);
}

function cleopa_featured_thumb() {
    $blog_layout = cleopa_get_options('nbcore_blog_archive_layout');
    if (has_post_thumbnail()):
        if ('classic' == $blog_layout) {
            $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        } else {
            $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'cleopa-masonry');
        }
        if ('classic' === $blog_layout):
            ?>
            <div class="entry-image">
                <a href="<?php the_permalink(); ?>">
            <?php
            printf('<img src="%1$s" title="%2$s" width="%3$s" height="%4$s" />', $thumb[0], esc_attr(get_the_title()), $thumb[1], $thumb[2]
            );
            ?>
                </a>
            </div>
                <?php else: ?>
            <div class="entry-image">
                    <?php
                    printf('<img src="%1$s" title="%2$s" width="%3$s" height="%4$s" />', $thumb[0], esc_attr(get_the_title()), $thumb[1], $thumb[2]
                    );
                    ?>
                <div class="image-mask">
                    <a href="<?php the_permalink(); ?>"><span><?php esc_html_e('View post &rarr;', 'cleopa'); ?></span></a>
            <?php
            $post = get_post();
            $words = str_word_count(strip_tags($post->post_content));
            $minutes = floor($words / 180);
            if (1 < $minutes) {
                $estimated_time = $minutes . esc_html__(' minutes read', 'cleopa');
            } else {
                $estimated_time = esc_html__('1 minute read', 'cleopa');
            }
            echo '<div class="read-time"> ' . $estimated_time . '</div>';
            ?>
                </div>

            </div>
		<?php
		endif;
	endif;
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function cleopa_posted_on($meta_date = true) {
	$html = '';

	if (cleopa_get_options('nbcore_blog_meta_author')) {
		$byline = sprintf(
				esc_html_x('%s', 'post author', 'cleopa'), '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
		);

		$html .= '<span class="byline"><i class="icon-user-o"></i>' . $byline . '</span>';
	}

	if (cleopa_get_options('nbcore_blog_meta_date') && $meta_date) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf($time_string, esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_attr(get_the_modified_date('c')), esc_html(get_the_modified_date())
		);

		$posted_on = sprintf(
				esc_html_x('%s', 'post date', 'cleopa'), $time_string);

		$html .= '<span class="posted-on"><i class="icon-time"></i>' . $posted_on . '</span>';
	};

	if ('masonry' !== cleopa_get_options('nbcore_blog_archive_layout')) {
		if (cleopa_get_options('nbcore_blog_meta_read_time')) {
			$post = get_post();
			$words = str_word_count(strip_tags($post->post_content));
			$minutes = floor($words / 180);
			if (1 < $minutes) {
				$estimated_time = $minutes . ' minutes read';
			} else {
				$estimated_time = esc_html__('1 minute read', 'cleopa');
			}

			$html .= '<span class="read-time"> ' . $estimated_time . '</span>';
		}
	}



	if ('' != $html) {
		echo '<div class="entry-meta">' . $html . '</div>';
	}
}

function cleopa_get_date() {
	if (cleopa_get_options('nbcore_blog_meta_date')) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf($time_string, esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_attr(get_the_modified_date('c')), esc_html(get_the_modified_date())
		);

		$posted_on = sprintf(
				esc_html_x('%s', 'post date', 'cleopa'), $time_string);

		echo '<span class="posted-on"><i class="icon-time"></i>' . $posted_on . '</span>';
	};
}

function cleopa_get_categories() {
	if (cleopa_get_options('nbcore_blog_meta_category')):
		?>
        <div class="entry-cat">
        <?php echo get_the_category_list(', '); ?>
        </div>
    <?php
    endif;
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 * TODO entry-footer wrap div rearrange
 */
function cleopa_get_tags() {
    if (cleopa_get_options('nbcore_blog_meta_tag')) {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html__(', ', 'cleopa'));
            if ($tags_list) {
                printf('<div class="entry-footer"><span class="tags-links icon-tags">' . esc_html__('%1$s', 'cleopa') . '</span></div>', $tags_list); // WPCS: XSS OK.
            }
        }
    }
}

function cleopa_get_excerpt() {
    echo '<p class="entry-summary">';
    $limit = cleopa_get_options('nbcore_excerpt_length');
    $excerpt = wp_trim_words(get_the_excerpt(), $limit, ' [...]');
    echo esc_html($excerpt);
    echo '</p>';
}

if ( ! function_exists( 'cleopa_excerpt_more' ) && ! is_admin() ) :
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
	 * a 'Continue reading' link.
	 *
	 * Create your own twentysixteen_excerpt_more() function to override in a child theme.
	 *
	 * @since Twenty Sixteen 1.0
	 *
	 * @return string 'Continue reading' link prepended with an ellipsis.
	 */
	function cleopa_excerpt_more() {
		$link = sprintf( '<a href="%1$s" class="more-link">%2$s<span class="meta-nav">&rarr;</span></a>',
			esc_url( get_permalink( get_the_ID() ) ),
			/* translators: %s: Name of current post */
			sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'cleopa' ), get_the_title( get_the_ID() ) )
		);
		return ' &hellip; ' . $link;
	}
	add_filter( 'excerpt_more', 'cleopa_excerpt_more' );
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function cleopa_categorized_blog() {
    if (false === ($all_the_cool_cats = get_transient('nbcore_categories'))) {
        // Create an array of all the categories that are attached to posts.
        $all_the_cool_cats = get_categories(array(
            'fields' => 'ids',
            'hide_empty' => 1,
            // We only need to know if there is more than one category.
            'number' => 2,
        ));

        // Count the number of categories that are attached to the posts.
        $all_the_cool_cats = count($all_the_cool_cats);

        set_transient('nbcore_categories', $all_the_cool_cats);
    }

    if ($all_the_cool_cats > 1) {
        // This blog has more than 1 category so cleopa_categorized_blog should return true.
        return true;
    } else {
        // This blog has only 1 category so cleopa_categorized_blog should return false.
        return false;
    }
}

function cleopa_paging_nav() {
    // Don't print empty markup if there's only one page.
    if ($GLOBALS['wp_query']->max_num_pages < 2) {
        return;
    }

    $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
    $pagenum_link = html_entity_decode(get_pagenum_link());
    $query_args = array();
    $url_parts = explode('?', $pagenum_link);

    if (isset($url_parts[1])) {
        wp_parse_str($url_parts[1], $query_args);
    }

    $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
    $pagenum_link = trailingslashit($pagenum_link) . '%_%';

    $format = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
    $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

    // Set up paginated links.
    $links = paginate_links(array(
        'nbcore' => $pagenum_link,
        'format' => $format,
        'total' => $GLOBALS['wp_query']->max_num_pages,
        'current' => $paged,
        'mid_size' => 1,
        'add_args' => array_map('urlencode', $query_args),
        'prev_text' => wp_kses(__('<i class=\'icon-left-open\'></i>', 'cleopa'), array('i' => array('class' => array()))),
        'next_text' => wp_kses(__('<i class=\'icon-right-open\'></i>', 'cleopa'), array('i' => array('class' => array()))),
    ));

    if ($links) :
        ?>
        <nav class="navigation paging-navigation <?php echo cleopa_get_options('pagination_style'); ?>">
            <div class="pagination loop-pagination">
        <?php
        echo wp_kses($links, array(
            'a' => array(
                'href' => array(),
                'class' => array()
            ),
            'i' => array(
                'class' => array()
            ),
            'span' => array(
                'class' => array()
            )
        ));
        ?>
            </div><!--/ .pagination -->
        </nav><!--/ .navigation -->
        <?php
    endif;
}

function cleopa_page_title() {
    if (cleopa_get_options('show_title_section')) {
        if (is_home() || is_front_page()) {
            if (cleopa_get_options('home_page_title_section')) {
                echo '<div class="nb-page-title-wrap"><div class="container"><div class="nb-page-title"><h2><span>';
                esc_html_e('Home', 'cleopa');
                echo '</span></h2></div></div></div>';
            }
        } elseif (is_page()) {
            if ((function_exists('get_field') && get_field('page_title_section')) || !function_exists('get_field')) {
                echo '<div class="nb-page-title-wrap"><div class="container"><div class="nb-page-title"><h2><span>';
                the_title();
                echo '</span></h2>';
                if (function_exists('woocommerce_breadcrumb')) {
                    if (cleopa_get_options('nbcore_wc_breadcrumb')) {
                        woocommerce_breadcrumb();
                    }
                }
                echo '</div></div></div>';
            }
        } else {

            echo '<div class="nb-page-title-wrap"><div class="container"><div class="nb-page-title"><h2><span>';

            if (function_exists('is_shop') && is_shop()) {
                echo esc_html(cleopa_get_options('nbcore_shop_title'));
            } elseif (function_exists('is_product_category') && is_product_category()) {
                echo single_cat_title();
            } elseif (function_exists('is_product_tag') && is_product_tag()) {
                echo single_tag_title();
            } elseif (is_post_type_archive()) {
                post_type_archive_title();
            } elseif (is_tax()) {
                single_term_title();
            } elseif (is_category()) {
                echo single_cat_title('', false);
            } elseif (is_archive()) {
                echo the_archive_title();
            } elseif (is_search()) {
                esc_html_e('Search Results', 'cleopa');
            } else {
                the_title();
            }

            echo '</span></h2>';

            if (function_exists('woocommerce_breadcrumb')) {
                if (cleopa_get_options('nbcore_wc_breadcrumb')) {
                    woocommerce_breadcrumb();
                }
            }

            echo '</div></div></div>';
        }
    }
}

/**
 * Flush out the transients used in cleopa_categorized_blog.
 */
function cleopa_category_transient_flusher() {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient('nbcore_categories');
}

add_action('edit_category', 'cleopa_category_transient_flusher');
add_action('save_post', 'cleopa_category_transient_flusher');

if (!function_exists('cleopa_default_options')) {

    function cleopa_default_options() {
        return array(
            'nbcore_blog_archive_layout' => 'classic',
            'nbcore_blog_sidebar' => 'right-sidebar',
            'nbcore_excerpt_only' => false,
            'nbcore_excerpt_length' => '35',
            'nbcore_blog_single_sidebar' => 'right-sidebar',
            'nbcore_color_scheme' => 'scheme_1',
            'nbcore_primary_color' => '#f68d7d',
            'nbcore_secondary_color' => '#e68373',
            'nbcore_background_color' => '#ffffff',
            'nbcore_inner_background' => '#f0f7fc',
            'nbcore_heading_color' => '#444444',
            'nbcore_body_color' => '#666666',
            'nbcore_meta_color' => '#999999',
            'nbcore_link_color' => '#f68d7d',
            'nbcore_link_hover_color' => '#e68373',
            'nbcore_divider_color' => '#d7d7d7',
            'nbcore_header_style' => 'left-inline',
            'nbcore_logo_upload' => '',
            'nbcore_logo_width' => '180',
            'nbcore_menu_resp' => '768',
            'nbcore_header_fixed' => false,
            'header_bgcolor' => '#ffffff',
            'nbcore_blog_width' => '70',
            'nbcore_blog_meta_date' => true,
            'nbcore_blog_meta_read_time' => true,
            'nbcore_blog_meta_author' => true,
            'nbcore_blog_meta_category' => true,
            'nbcore_blog_meta_tag' => true,
            'nbcore_blog_sticky_sidebar' => false,
            'nbcore_blog_meta_align' => 'left',
            'show_title_section' => true,
            'nbcore_page_title_image' => '',
            'nbcore_page_title_padding' => '40',
            'nbcore_page_title_color' => '#444444',
            'body_font_family' => 'google,Poppins',
            'body_font_style' => '400',
            'body_font_size' => '14',
            'heading_font_family' => 'google,Poppins',
            'heading_font_style' => '400',
            'heading_base_size' => '16',
            'subset_cyrillic' => false,
            'subset_greek' => false,
            'subset_vietnamese' => false,
            'nbcore_wc_breadcrumb' => true,
            'nbcore_wc_content_width' => '70',
            'nbcore_pa_swatch_style' => '',
            'nbcore_wc_attr' => false,
            'nbcore_shop_title' => esc_html__('Shop', 'cleopa'),
            'nbcore_shop_action' => true,
            'nbcore_shop_sidebar' => 'right-sidebar',
            'nbcore_loop_columns' => 'four-columns',
            'nbcore_products_per_page' => '12',
            'nbcore_product_list' => 'grid-type',
            'nbcore_shop_content_width' => '70',
            'nbcore_grid_product_description' => false,
            'nbcore_pd_details_title' => true,
            'nbcore_pd_details_width' => '70',
            'nbcore_pd_details_sidebar' => 'right-sidebar',
            'nbcore_wc_sale' => 'style-1',
            'nbcore_pd_images_width' => '50',
            'nbcore_pd_thumb_pos' => 'bottom-thumb',
            'nbcore_pd_meta_layout' => 'left-images',
            'nbcore_pd_featured_autoplay' => false,
            'nbcore_info_style' => 'accordion-tabs',
            'nbcore_reviews_form' => 'full-width',
            'nbcore_reviews_round_avatar' => true,
            'nbcore_add_cart_style' => 'style-1',
            'nbcore_pd_show_social' => true,
            'nbcore_show_related' => true,
            'nbcore_pd_related_columns' => '4',
            'nbcore_show_upsells' => false,
            'nbcore_pd_upsells_columns' => '4',
            'nbcore_pb_background' => '#f68d7d',
            'nbcore_pb_background_hover' => '#f8a497',
            // 'nbcore_pb_background_hover' => '#e68373',
            'nbcore_pb_text' => '#ffffff',
            'nbcore_pb_text_hover' => '#ffffff',
            'nbcore_pb_border' => '#f68d7d',
            'nbcore_pb_border_hover' => '#f8a497',
            'nbcore_sb_background' => '#ffffff',
            'nbcore_sb_background_hover' => '#f68d7d',
            'nbcore_sb_text' => '#f68d7d',
            'nbcore_sb_text_hover' => '#ffffff',
            'nbcore_sb_border' => '#f68d7d',
            'nbcore_sb_border_hover' => '#f68d7d',
            'nbcore_button_padding' => '30',
            'nbcore_button_border_radius' => '5',
            'nbcore_button_border_width' => '1',
            'nbcore_cart_layout' => 'cart-layout-2',
            'nbcore_show_cross_sells' => true,
            'nbcore_cross_sells_per_row' => '4',
            'nbcore_cross_sells_limit' => '6',
            'home_page_title_section' => false,
            'nbcore_show_footer_top' => false,
            'nbcore_footer_top_layout' => 'layout-1',
            'nbcore_footer_top_color' => '#444444',
            'nbcore_footer_top_hover_color' => '#f68d7d',
            'nbcore_footer_top_bg' => '#ffffff',
            'nbcore_show_footer_bot' => false,
            'nbcore_footer_bot_layout' => 'layout-7',
            'nbcore_footer_bot_color' => '#999999',
            'nbcore_footer_bot_hover_color' => '#f68d7d',
            'nbcore_footer_bot_bg' => '#ffffff',
            'nbcore_footer_abs_color' => '#999999',
            'nbcore_footer_abs_hover_color' => '#f68d7d',
            'nbcore_footer_abs_bg' => '#ffffff',
            'nbcore_top_section_padding' => '10',
            'nbcore_middle_section_padding' => '20',
            'nbcore_bot_section_padding' => '30',
            'nbcore_header_top_bg' => '#f68d7d',
            'nbcore_header_top_color' => '#ffffff',
            'nbcore_header_top_hover_color' => '#ffffff',
            'nbcore_header_middle_bg' => '#ffffff',
            'nbcore_header_middle_color' => '#333333',
            'nbcore_header_middle_hover_color' => '#f68d7d',
            'nbcore_header_bot_bg' => '#fff',
            'nbcore_header_bot_color' => '#333333',
            'nbcore_header_bot_hover_color' => '#f68d7d',
            'nbcore_footer_top_heading' => '#999999',
            'nbcore_footer_bot_heading' => '#999999',
            'nbcore_blog_archive_comments' => true,
            'nbcore_blog_archive_summary' => true,
            'nbcore_blog_archive_post_style' => 'style-1',
            'nbcore_blog_single_title_position' => 'position-1',
            'nbcore_blog_single_show_thumb' => true,
            'nbcore_blog_single_title_size' => '36',
            'nbcore_blog_single_show_social' => true,
            'nbcore_blog_single_show_author' => true,
            'nbcore_blog_single_show_nav' => true,
            'nbcore_blog_single_show_comments' => true,
            'nbcore_page_title_size' => '36',
            'nbcore_footer_abs_padding' => '10',
            'share_buttons_style' => 'style-1',
            'share_buttons_position' => 'inside-content',
            'pagination_style' => 'pagination-style-2',
            'show_back_top' => true,
            'back_top_shape' => 'square',
            'back_top_style' => 'light',
            'shop_sticky_sidebar' => false,
            'product_sticky_sidebar' => false,
            //meta
            'page_thumb' => 'no-thumb',
            'page_sidebar' => 'full-width',
            'page_content_width' => '70',
            'nbcore_page_layout' => 'full-width',
            'nbcore_blog_masonry_columns' => '2',
            'product_category_wishlist' => true,
            'product_category_quickview' => true,
            'nbcore_header_mainmn_bg' => '#fff',
            'nbcore_header_mainmn_color' => '#646464',
            'nbcore_header_mainmn_bor' => '#646464',
            'nbcore_header_mainmnhover_bg' => '#fff',
            'nbcore_header_mainmnhover_color' => '#646464',
            'nbcore_header_mainmnhover_bor' => '#646464',
            'nbcore_header_text_section' =>'',
            'nbcore_header_facebook' => '',
            'nbcore_header_linkedin' => '',
            'nbcore_header_instagram' => '',
            'nbcore_header_blog' => '',
            'nbcore_header_pinterest' => '',
            'nbcore_header_ggplus' => '',
            'nbcore_header_twitter' => '',
            'nbcore_footer_abs_left_content' => '',
            'nbcore_footer_abs_right_content' => '',
            'nbcore_show_to_shop' => '',
            'nbcore_shop_banner' => '',
            'nbcore_upsells_limit' => '',
            'nbcore_logo_upload2' => '',
            'header_socials'=>'',
            'nbcore_header_preloading'=>false,
            'nbcore_header_style_preloading'=>'demo1'
        );
    }
}

function cleopa_get_options($option)
{
    $result = '';
    $default = cleopa_default_options();

    if(class_exists('NBFW_Metaboxes')) {
        $meta = '';
        $global = '';

        if(!is_admin()) {
            if(is_single()) {
                $id = get_the_ID();
                $global = get_post_meta($id, 'nbcore_global_setting', true);
                $meta = get_post_meta($id, $option, true);
            } elseif(is_tax() || is_category() || is_tag()) {
                $id = get_queried_object_id();
                $global = get_term_meta($id, 'nbcore_global_setting', true);
                $meta = get_term_meta($id, $option, true);
            }

            if($meta !== '' && $global !== '') {
                $result = $meta;
            } else {
                $result = get_theme_mod($option, $default[$option]);
            }
        }
    } else {
        $result = get_theme_mod($option, $default[$option]);
    }

    return $result;
}

// function cleopa_get_post_meta($option) {
//     if ('' === get_post_meta(get_the_ID(), $option, true)) {
//         return cleopa_default_options($option);
//     } else {
//         return get_post_meta(get_the_ID(), $option, true);
//     }
// }

function cleopa_blog_classes() {
    $classes = array();

    $classes['sidebar'] = cleopa_get_options('nbcore_blog_sidebar');
    $classes['meta_align'] = 'meta-align-' . cleopa_get_options('nbcore_blog_meta_align');
    $classes['post_style'] = cleopa_get_options('nbcore_blog_archive_post_style');

    if ('masonry' === cleopa_get_options('nbcore_blog_archive_layout')) {
        $classes['masonry_columns'] = 'masonry-' . cleopa_get_options('nbcore_blog_masonry_columns') . '-columns';
    }


    echo implode(' ', $classes);
}

function cleopa_shop_classes() {
    $classes = array();

    if ((is_shop() || is_product_category() || is_product_tag()) && 'list-type' !== cleopa_get_options('nbcore_product_list')) {
        $classes['shop_columns'] = cleopa_get_options('nbcore_loop_columns');
    }

    $classes['meta_layout'] = cleopa_get_options('nbcore_pd_meta_layout');

    if (function_exists('is_product') && is_product()) {
        $classes['nbcore_pd_thumb_pos'] = cleopa_get_options('nbcore_pd_thumb_pos');
    }

    if ('split' === cleopa_get_options('nbcore_reviews_form')) {
        $classes['nbcore_reviews_form'] = 'split-reviews-form';
    }

    if (cleopa_get_options('nbcore_reviews_round_avatar')) {
        $classes['nbcore_round_avatar'] = 'round-reviewer-avatar';
    }

    $classes['wc_tab_style'] = cleopa_get_options('nbcore_info_style');

    if (is_product()) {
        $classes['related_columns'] = 'related-' . cleopa_get_options('nbcore_pd_related_columns') . '-columns';
        $classes['upsells_columns'] = 'upsells-' . cleopa_get_options('nbcore_pd_upsells_columns') . '-columns';
    }

    echo implode(' ', $classes);
}

if (!function_exists('cleopa_header_add_to_cart_fragment')) {
    add_filter('woocommerce_add_to_cart_fragments', 'cleopa_header_add_to_cart_fragment');

    function cleopa_header_add_to_cart_fragment($fragments) {
        global $woocommerce;

        ob_start();
        ?>
        <a class="nb-cart-section" href="<?php echo wc_get_cart_url(); ?>"
           title="<?php esc_attr_e('View cart', 'cleopa'); ?>">
            <i class="icon-bag"></i>
            <span class="counter"><?php echo WC()->cart->get_cart_contents_count(); ?><span class="counter-l"></span><span class="counter-r"></span></span>
        <?php echo WC()->cart->get_cart_total(); ?>
        </a>
        <?php
        $fragments['a.nb-cart-section'] = ob_get_clean();

        return $fragments;
    }

}

add_filter('woocommerce_add_to_cart_fragments', 'cleopa_mini_cart_fragments');

function cleopa_mini_cart_fragments($fragments) {

    ob_start();
    ?>
    <div class="mini-cart-wrap">
    <?php woocommerce_mini_cart(); ?>
    </div>
    <?php
    $fragments['.mini-cart-wrap'] = ob_get_clean();

    return $fragments;
}

if (!function_exists('woocommerce_template_loop_add_to_cart') && cleopa_get_options('nbcore_wc_attr')) {

    function woocommerce_template_loop_add_to_cart($args = array()) {
        global $product;

        if ($product) {
            $defaults = array(
                'quantity' => 1,
                'class' => implode(' ', array_filter(array(
                    'button',
                    'product_type_' . $product->get_type(),
                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                    $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : ''
                )))
            );

            $args = apply_filters('woocommerce_loop_add_to_cart_args', wp_parse_args($args, $defaults), $product);

            if ($product->get_type() == "variable") {
                woocommerce_variable_add_to_cart();
            } else {
                wc_get_template('loop/add-to-cart.php', $args);
            }
        }
    }

}

function cleopa_back_to_top() {
    $shape = cleopa_get_options('back_top_shape');
    $style = cleopa_get_options('back_top_style');
    echo '<div class="nb-back-to-top-wrap"><a id="back-to-top-button" class="' . esc_attr($shape) . ' ' . esc_attr($style) . '" href="#"><i class="icon-angle-up"></i><span>' . esc_html__('Top', 'cleopa') . '</span></a></div>';
}
