<?php
require_once(NB_FW_PATHS . 'widgets/wc_best_seller.php');
require_once(NB_FW_PATHS . 'widgets/latest_post.php');

// require_once(NB_FW_PATH . 'vc_elements/example.php');

class NBT_Widget {

    /**
     * Class prefix for autoload
     *
     * @var string
     */
    protected static $prefix = 'NBT_';

    /**
     * Variable hold the page options
     *
     * @var array
     */
    protected static $page_options = array();

    public static function init() {
        add_action('widgets_init', array(__CLASS__, 'NB_loadwidget'));

        self::include_function_plugins();

        // Visual Composer
        if ( is_plugin_active( 'js_composer/js_composer.php' ) ) :
            add_action('vc_after_init', array(__CLASS__, 'vc_after_init_actions'));
            add_filter('vc_grid_item_shortcodes', array(__CLASS__, 'nbfw_add_grid_shortcodes'));
            add_shortcode('vc_gitem_post_date_1', array(__CLASS__, 'vc_blog_date_render'));
            add_shortcode('vc_gitem_post_info', array(__CLASS__, 'vc_post_info_render'));
            add_shortcode('vc_gitem_post_excerpt_1', array(__CLASS__, 'vc_blog_excerpt_render'));
            add_filter('vc_gitem_template_attribute_post_date1', array(__CLASS__, 'vc_gitem_template_attribute_post_date1'), 10, 2);
            add_filter('vc_gitem_template_attribute_post_comment', array(__CLASS__, 'vc_gitem_template_attribute_post_comment'), 10, 2);
            add_filter('vc_gitem_template_attribute_post_excerpt', array(__CLASS__, 'vc_gitem_template_attribute_post_excerpt'), 10, 2);

            // add_action('wp_enqueue_scripts', array(__CLASS__, 'shortcodeScripts'), 9998);
        endif;
    }

    public static function include_function_plugins() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
    }
	
    // Register and load the widget
    public static function NB_loadwidget() {
        register_widget('NB_latest_post_widget');
        register_widget('WC_Best_Seller');
        if(class_exists('WPBakeryShortCode')) {
            require_once(NB_FW_PATHS . 'widgets/vc_elements.php');
            new nb_vc_elements();
        }
       
		// End Element Testimonial
    }

    // Visual Composer
    // After VC Init
    public static function vc_after_init_actions() {

        // Add Tabs custom params values
        $tabstyle = WPBMap::getParam('vc_tta_tabs', 'style');
        $tabstyle['value'][__('Style 1', 'nb-fw')] = 'nbstyle1';
        vc_update_shortcode_param('vc_tta_tabs', $tabstyle);

        $tabshape = WPBMap::getParam('vc_tta_tabs', 'shape');
        $tabshape['value'][__('None', 'nb-fw')] = 'none';
        vc_update_shortcode_param('vc_tta_tabs', $tabshape);

        // Remove Params
        
        // Add new Params
        
    }

    public static function nbfw_add_grid_shortcodes($shortcodes) {
        global $vc_gitem_add_link_param;
        $vc_gitem_add_link_param = apply_filters('vc_gitem_add_link_param', array(
            'type' => 'dropdown',
            'heading' => __('Add link', 'nb-fw'),
            'param_name' => 'link',
            'value' => array(
                __('None', 'nb-fw') => 'none',
                __('Post link', 'nb-fw') => 'post_link',
                __('Post author', 'nb-fw') => 'post_author',
                __('Large image', 'nb-fw') => 'image',
                __('Large image (prettyPhoto)', 'nb-fw') => 'image_lightbox',
                __('Custom', 'nb-fw') => 'custom',
            ),
            'description' => __('Select link option.', 'nb-fw'),
        ));
        $post_data_params = array(
            $vc_gitem_add_link_param,
            array(
                'type' => 'vc_link',
                'heading' => __('URL (Link)', 'nb-fw'),
                'param_name' => 'url',
                'dependency' => array(
                    'element' => 'link',
                    'value' => array('custom'),
                ),
                'description' => __('Add custom link.', 'nb-fw'),
            ),
            array(
                'type' => 'css_editor',
                'heading' => __('CSS box', 'nb-fw'),
                'param_name' => 'css',
                'group' => __('Design Options', 'nb-fw'),
            ),
        );
        $custom_fonts_params = array(
            array(
                'type' => 'font_container',
                'param_name' => 'font_container',
                'value' => '',
                'settings' => array(
                    'fields' => array(
                        'tag' => 'div', // default value h2
                        'text_align',
                        'tag_description' => __('Select element tag.', 'nb-fw'),
                        'text_align_description' => __('Select text alignment.', 'nb-fw'),
                        'font_size_description' => __('Enter font size.', 'nb-fw'),
                        'line_height_description' => __('Enter line height.', 'nb-fw'),
                        'color_description' => __('Select color for your element.', 'nb-fw'),
                    ),
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Use custom fonts?', 'nb-fw'),
                'param_name' => 'use_custom_fonts',
                'value' => array(__('Yes', 'nb-fw') => 'yes'),
                'description' => __('Enable Google fonts.', 'nb-fw'),
            ),
            array(
                'type' => 'font_container',
                'param_name' => 'block_container',
                'value' => '',
                'settings' => array(
                    'fields' => array(
                        'font_size',
                        'line_height',
                        'color',
                        'tag_description' => __('Select element tag.', 'nb-fw'),
                        'text_align_description' => __('Select text alignment.', 'nb-fw'),
                        'font_size_description' => __('Enter font size.', 'nb-fw'),
                        'line_height_description' => __('Enter line height.', 'nb-fw'),
                        'color_description' => __('Select color for your element.', 'nb-fw'),
                    ),
                ),
                'group' => __('Custom fonts', 'nb-fw'),
                'dependency' => array(
                    'element' => 'use_custom_fonts',
                    'value' => array('yes'),
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Yes theme default font family?', 'nb-fw'),
                'param_name' => 'use_theme_fonts',
                'value' => array(__('Yes', 'nb-fw') => 'yes'),
                'description' => __('Yes font family from the theme.', 'nb-fw'),
                'group' => __('Custom fonts', 'nb-fw'),
                'dependency' => array(
                    'element' => 'use_custom_fonts',
                    'value' => array('yes'),
                ),
            ),
            array(
                'type' => 'google_fonts',
                'param_name' => 'google_fonts',
                'value' => '',
                // Not recommended, this will override 'settings'. 'font_family:'.rawurlencode('Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic').'|font_style:'.rawurlencode('900 bold italic:900:italic'),
                'settings' => array(
                    'fields' => array(
                        // Default font style. Name:weight:style, example: "800 bold regular:800:normal"
                        'font_family_description' => __('Select font family.', 'nb-fw'),
                        'font_style_description' => __('Select font styling.', 'nb-fw'),
                    ),
                ),
                'group' => __('Custom fonts', 'nb-fw'),
                'dependency' => array(
                    'element' => 'use_theme_fonts',
                    'value_not_equal_to' => 'yes',
                ),
            ),
        );
        $list = array(
            'vc_gitem_post_date_1' => array(
                'name' => __('Blog Date', 'nb-fw'),
                'base' => 'vc_gitem_post_date_1',
                'icon' => 'vc_icon-vc-gitem-post-date',
                'category' => __('NetBase', 'nb-fw'),
                'description' => __('Post publish date', 'nb-fw'),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Layout', 'nb-fw'),
                        'param_name' => 'el_style',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                        'value' => array(
                            __('Default', 'nb-fw') => 'default',
                            __('Style', 'nb-fw') => 'style1',
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Text align', 'nb-fw'),
                        'param_name' => 'el_align',
                        'description' => __('Select text alignment.', 'nb-fw'),
                        'value' => array(
                            __('Inherit', 'nb-fw') => '',
                            __('Left', 'nb-fw') => 'left',
                            __('Right', 'nb-fw') => 'right',
                            __('Center', 'nb-fw') => 'center',
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'nb-fw'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __('CSS box', 'nb-fw'),
                        'param_name' => 'css',
                        'group' => __('Design Options', 'nb-fw'),
                    ),
                ),
                'post_type' => Vc_Grid_Item_Editor::postType(),
            ),
            'vc_gitem_post_info' => array(
                'name' => __('Blog Info', 'nb-fw'),
                'base' => 'vc_gitem_post_info',
                'icon' => 'vc_icon-vc-gitem-post-title',
                'category' => __('NetBase', 'nb-fw'),
                'description' => __('Post infomation: Author, Categories, Comment, Date', 'nb-fw'),
                'params' => array(
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Add link', 'nb-fw'),
                        'param_name' => 'link',
                        'value' => array(__('Yes', 'nb-fw') => 'yes'),
                        'description' => __('Add link to author, categories?', 'nb-fw'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Icon', 'nb-fw'),
                        'param_name' => 'icon',
                        'value' => array(__('Yes', 'nb-fw') => 'yes'),
                        'description' => __('Show icon with elements?', 'nb-fw'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Show Date?', 'nb-fw'),
                        'param_name' => 'p_date',
                        'value' => array(__('Yes', 'nb-fw') => 'yes'),
                        'group' => __('Elements', 'nb-fw'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Show Author?', 'nb-fw'),
                        'param_name' => 'p_author',
                        'value' => array(__('Yes', 'nb-fw') => 'yes'),
                        'group' => __('Elements', 'nb-fw'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Show Categories?', 'nb-fw'),
                        'param_name' => 'p_categories',
                        'value' => array(__('Yes', 'nb-fw') => 'yes'),
                        'group' => __('Elements', 'nb-fw'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Show Comment?', 'nb-fw'),
                        'param_name' => 'p_comment',
                        'value' => array(__('Yes', 'nb-fw') => 'yes'),
                        'group' => __('Elements', 'nb-fw'),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Separator', 'nb-fw'),
                        'param_name' => 'separator',
                        'description' => __('Separator between elements', 'nb-fw'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Text align', 'nb-fw'),
                        'param_name' => 'el_align',
                        'description' => __('Select text alignment.', 'nb-fw'),
                        'value' => array(
                            __('Inherit', 'nb-fw') => '',
                            __('Left', 'nb-fw') => 'left',
                            __('Right', 'nb-fw') => 'right',
                            __('Center', 'nb-fw') => 'center',
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'nb-fw'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __('CSS box', 'nb-fw'),
                        'param_name' => 'css',
                        'group' => __('Design Options', 'nb-fw'),
                    ),
                ),
                'post_type' => Vc_Grid_Item_Editor::postType(),
            ),
            'vc_gitem_post_excerpt_1' => array(
                'name' => __('Blog Excerpt', 'nb-fw'),
                'base' => 'vc_gitem_post_excerpt_1',
                'icon' => 'vc_icon-vc-gitem-post-excerpt',
                'category' => __('NetBase', 'nb-fw'),
                'description' => __('Excerpt or manual excerpt', 'nb-fw'),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Element tag', 'nb-fw'),
                        'param_name' => 'el_tag',
                        'description' => __('Select element tag.', 'nb-fw'),
                        'value' => array(
                            __('h1', 'nb-fw') => 'h1',
                            __('h2', 'nb-fw') => 'h2',
                            __('h3', 'nb-fw') => 'h3',
                            __('h4', 'nb-fw') => 'h4',
                            __('h5', 'nb-fw') => 'h5',
                            __('h6', 'nb-fw') => 'h6',
                            __('p', 'nb-fw') => 'p',
                            __('div', 'nb-fw') => 'div',
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Text align', 'nb-fw'),
                        'param_name' => 'el_align',
                        'description' => __('Select text alignment.', 'nb-fw'),
                        'value' => array(
                            __('Inherit', 'nb-fw') => '',
                            __('Left', 'nb-fw') => 'left',
                            __('Right', 'nb-fw') => 'right',
                            __('Center', 'nb-fw') => 'center',
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Limit', 'nb-fw'),
                        'param_name' => 'el_limit',
                        'description' => __('Content words limit of post', 'nb-fw'),
                        'value' => '',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'nb-fw'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __('CSS box', 'nb-fw'),
                        'param_name' => 'css',
                        'group' => __('Design Options', 'nb-fw'),
                    ),
                ),
                'post_type' => Vc_Grid_Item_Editor::postType(),
            ),
        );
        $shortcodes = array_merge($shortcodes, $list);
        return $shortcodes;
    }

    public static function vc_blog_date_render($atts) {
        extract(shortcode_atts(
			array(
				'el_style' => 'default',
				'el_align' => '',
				'el_class' => '',
				'css' => '',
			), $atts, 'vc_gitem_post_date_1'
        ));
        switch ($el_style):
            case 'style1':
                $post_d = '{{post_date1}}';
                break;
            default :
                $post_d = '{{post_date}}';
                break;
        endswitch;
        $css_class = 'vc_blog_date vc_gitem-post-data vc_gitem-post-data-source-post_date';
        $css_class = implode(' ', array($css_class, $el_class, 'text-' . $el_align));
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class . vc_shortcode_custom_css_class($css, ' '), 'vc_gitem_post_date_1', $atts);
        $css_class = trim(preg_replace('/\s+/', ' ', $css_class));
        $output = '';
        $output .= '<div class="' . esc_attr($css_class) . '" >';
        $output .= $post_d;
        $output .= '</div>';
        return $output;
    }

    public static function vc_gitem_template_attribute_post_date1($value, $data) {
        extract(array_merge(array(
            'post' => null,
                        ), $data));
        $date = '<span class="vc_post_date-day">' . date_i18n('d', strtotime(get_the_date('', $post->ID))) . '</span>';
        $date .= '<span class="vc_post_date-month">' . date_i18n('M', strtotime(get_the_date('', $post->ID))) . '</span>';
        //    $date .= '<span class="vc_post_date-year">' . date_i18n('Y', strtotime(get_the_date('', $post->ID))) . '</span>';
        return $date;
    }

    public static function vc_post_info_render($atts) {
        extract(shortcode_atts(
                        array(
            'link' => '',
            'icon' => '',
            'p_date' => '',
            'p_author' => '',
            'p_categories' => '',
            'p_comment' => '',
            'separator' => '',
            'el_align' => '',
            'el_class' => '',
            'css' => '',
                        ), $atts, 'vc_gitem_post_info'
        ));
        $post_date = $post_author = $post_categories = $post_comment = '';
        $info = array();
        if (!empty($icon)) {
            vc_icon_element_fonts_enqueue('fontawesome');
        }
        if (!empty($p_author)) {
            $post_author .= '<li>';
            if (!empty($icon)) {
                $post_author .= '<i class="vc_icon_element-icon fa fa-user"></i>';
            }
            $post_author .= esc_html__('Posted by ', 'nb-fw');
            if (!empty($link)) {
                $post_author .= '<a href="{{post_author_href}}">{{post_author}}</a>';
            } else {
                $post_author .= '<span>{{post_author}}</span>';
            }
            $post_author .= '</li>';
            $info[] = $post_author;
        }
        if (!empty($p_categories)) {
            $build_cats = http_build_query(array(
                'atts' => array(
                    'link' => $link,
                    'category_style' => ', '
                )
            ));
            $post_categories .= '<li>';
            if (!empty($icon)) {
                $post_categories .= '<i class="vc_icon_element-icon fa fa-folder"></i>';
            }
            $post_categories .= '{{ post_categories: ' . $build_cats . ' }}';
            $post_categories .= '</li>';
            $info[] = $post_categories;
        }
        if (!empty($p_date)) {
            $post_date .= '<li>';
            if (!empty($icon)) {
                $post_date .= '<i class="vc_icon_element-icon fa fa-calendar"></i>';
            }
            $post_date .= '{{post_data:post_date}}';
            $post_date .= '</li>';
            $info[] = $post_date;
        }
        if (!empty($p_comment)) {
            $build_comments = http_build_query(array(
                'link' => $link,
            ));
            $post_comment .= '<li>';
            if (!empty($icon)) {
                $post_comment .= '<i class="vc_icon_element-icon fa fa-comments"></i>';
            }
            $post_comment .= '<span>{{post_comment:' . $build_comments . '}}</span>';
            $post_comment .= '</li>';
            $info[] = $post_comment;
        }

        $css_class = 'vc_blog_info vc_gitem-post-data vc_gitem-post-data-source-post_info';
        $css_class = implode(' ', array($css_class, $el_class, 'text-' . $el_align));
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class . vc_shortcode_custom_css_class($css, ' '), 'vc_gitem_post_info', $atts);
        $css_class = trim(preg_replace('/\s+/', ' ', $css_class));
        $output = '';
        $output .= '<div class="' . esc_attr($css_class) . '" >';
        $output .= '<ul class="list-inline">';
        $output .= implode("<li>/</li> ", $info);
        $output .= '</ul>';
        $output .= '</div>';
        return $output;
    }

    public static function vc_gitem_template_attribute_post_comment($value, $data) {
        extract(array_merge(array(
            'post' => null,
            'data' => '',
                        ), $data));
        $atts = array();
        parse_str($data, $atts);
        if (!empty($atts['link'])) {
            if (get_comments_number() == 0) {
                $comment_lnk = get_permalink() . '#respond';
            } else {
                $comment_lnk = get_comments_link();
            }
            $comment = '<a class="vc_post_comment" href="' . $comment_lnk . '">' . get_comments_number() . (get_comments_number() > 1 ? __(' Comments', 'nb-fw') : __(' Comment', 'nb-fw')) . '</a>';
        } else {
            $comment = '<span class="vc_post_comment">' . get_comments_number() . (get_comments_number() > 1 ? __(' Comments', 'nb-fw') : __(' Comment', 'nb-fw')) . '</span>';
        }
        return $comment;
    }

    public static function vc_blog_excerpt_render($atts) {
        extract(shortcode_atts(
                        array(
            'el_tag' => 'div',
            'el_align' => '',
            'el_class' => '',
            'el_limit' => '',
            'css' => '',
                        ), $atts, 'vc_gitem_post_excerpt_1'
        ));
        $build_cats = http_build_query(array('atts' => $atts));
        $css_class = 'vc_blog_excerpt vc_gitem-post-data vc_gitem-post-data-source-post_excerpt';
        $css_class = implode(' ', array($css_class, $el_class, 'text-' . $el_align));
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class . vc_shortcode_custom_css_class($css, ' '), 'vc_gitem_post_excerpt_1', $atts);
        $css_class = trim(preg_replace('/\s+/', ' ', $css_class));

        $output .= '<div class="' . esc_attr($css_class) . '" >';
        $output .= '<' . $el_tag . ' >';
        $output .= '{{post_excerpt:' . $build_cats . '}}';
        $output .= '</' . $el_tag . ' >';
        $output .= '</div>';
        return $output;
    }

    public static function vc_gitem_template_attribute_post_excerpt($value, $data) {
        extract(array_merge(array(
            'post' => null,
            'data' => '',
                        ), $data));
        $atts_extended = array();
        parse_str($data, $atts_extended);
        $content = $value;
        if (!empty($atts_extended['atts']['el_limit'])) {
            $content = wp_trim_words(get_the_excerpt(), $atts_extended['atts']['el_limit'], '...');
        }
        return apply_filters('the_excerpt', apply_filters('get_the_excerpt', $content));
    }

    private static function product_loop($query_args, $atts, $loop_name) {
        global $woocommerce_loop;
        $columns = absint($atts['columns']);
        $woocommerce_loop['columns'] = $columns;
        $woocommerce_loop['name'] = $loop_name;
        $query_args = apply_filters('woocommerce_shortcode_products_query', $query_args, $atts, $loop_name);
        $transient_name = 'wc_loop' . substr(md5(json_encode($query_args) . $loop_name), 28) . WC_Cache_Helper::get_transient_version('product_query');
        $products = get_transient($transient_name);
        if (false === $products || !is_a($products, 'WP_Query')) {
            $products = new WP_Query($query_args);
            set_transient($transient_name, $products, DAY_IN_SECONDS * 30);
        }
        ob_start();
        if ($products->have_posts()) :
            ?>
            <?php if (!$atts['product_style'] || $atts['product_style'] == ''): ?>
                1
            <?php else: ?>
                2
            <?php endif; ?>
            <?php do_action("woocommerce_shortcode_before_{$loop_name}_loop", $atts); ?>
            <?php woocommerce_product_loop_start(); ?>
            <?php while ($products->have_posts()) : $products->the_post(); ?>
                    <?php if (!$atts['product_style'] || $atts['product_style'] == ''): ?>
                        <?php wc_get_template_part('content', 'product'); ?>
                <?php else: ?>
                    <div <?php post_class(); ?>>
                    <?php wc_get_template('netbase/content-product/' . esc_attr($atts['product_style']) . '.php'); ?>
                    </div>
                <?php endif; ?>
            <?php endwhile; // end of the loop. ?>
            <?php woocommerce_product_loop_end(); ?>
            <?php do_action("woocommerce_shortcode_after_{$loop_name}_loop", $atts); ?>
        <?php
        else:
            do_action("woocommerce_shortcode_{$loop_name}_loop_no_results", $atts);
        endif;

        woocommerce_reset_loop();
        wp_reset_postdata();

        return '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
    }
}
