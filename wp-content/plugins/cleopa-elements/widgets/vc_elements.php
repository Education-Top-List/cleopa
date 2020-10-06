<?php
if (!defined('ABSPATH')) {
    die('-1');
}


// Check vc active or no active
if (class_exists('WPBakeryShortCode')) {
    // Begin Netbase Elements
    class nb_vc_elements extends WPBakeryShortCode {

        // Element Init
        function __construct() {
            add_action('init', array($this, 'netbase_elements_map'), 12);

            add_shortcode('nb_wcproducts', array($this, 'nb_wcproducts_render'));
            add_shortcode('nb_wpposts', array($this, 'nb_wpposts_render'));
            add_shortcode('nb_instagram', array($this, 'nb_instagram_render'));
            add_shortcode('nb_imgsgal', array($this, 'nb_imgsgal_render'));
            if (function_exists('vc_is_inline')) {
                if (!vc_is_inline()) {
                    add_shortcode('nb_testimonial', array($this, 'nb_testimonial'));
                    add_shortcode('nb_testimonial_item', array($this, 'nb_testimonial_item'));
                }
            } else {
                add_shortcode('nb_testimonial', array($this, 'nb_testimonial'));
                add_shortcode('nb_testimonial_item', array($this, 'nb_testimonial_item'));
            }

            add_action('wp_enqueue_scripts', array($this, 'carouselScripts'));
            add_action('wp_enqueue_scripts', array($this, 'isotopeScripts'));
            add_action('wp_enqueue_scripts', array($this, 'nbfwScripts'));
        }

        // Element Mapping
        public function netbase_elements_map() {

            $post_categories_dropdown = array();
            $post_categories_dropdown[] = array(
                'label' => __('Select category', 'nb-fw'),
                'value' => '',
            );
            $post_categories_values = $this->getCategories();
            $post_categories_dropdown = array_merge($post_categories_dropdown, $post_categories_values);
            $order_posts = array(
                '',
                __('Date', 'nb-fw') => 'date',
                __('ID', 'nb-fw') => 'ID',
                __('Author', 'nb-fw') => 'author',
                __('Title', 'nb-fw') => 'title',
                __('Name', 'nb-fw') => 'name',
                __('Modified', 'nb-fw') => 'modified',
                __('Parent', 'nb-fw') => 'parent',
                __('Random', 'nb-fw') => 'rand',
                __('Comment count', 'nb-fw') => 'comment_count',
                __('Menu order', 'nb-fw') => 'menu_order',
            );
            $order_way_values = array(
                '',
                __('Descending', 'nb-fw') => 'DESC',
                __('Ascending', 'nb-fw') => 'ASC',
            );

            if (class_exists('WooCommerce')) {
                $args = array(
                    'type' => 'post',
                    'child_of' => 0,
                    'parent' => '',
                    'orderby' => 'parent',
                    'order' => 'ASC',
                    'hide_empty' => false,
                    'hierarchical' => 1,
                    'exclude' => '',
                    'include' => '',
                    'number' => '',
                    'taxonomy' => 'product_cat',
                    'pad_counts' => false,
                );
                $categories = get_categories($args);
                $product_categories_dropdown = array();
                $this->getCategoryChildsFull(0, 0, $categories, 0, $product_categories_dropdown);

                $attributes_tax = wc_get_attribute_taxonomies();
                $attributes = array();
                foreach ($attributes_tax as $attribute) {
                    $attributes[$attribute->attribute_label] = $attribute->attribute_name;
                }

                $product_views = array(
                    __('Recent products', 'nb-fw') => 'recent_products',
                    __('Featured products', 'nb-fw') => 'featured_products',
                    __('Product Category', 'nb-fw') => 'product_cat',
                    __('Sale Products', 'nb-fw') => 'sale_products',
                    __('Best Selling Products', 'nb-fw') => 'best_selling_products',
                    __('Top Rated Products', 'nb-fw') => 'top_rated_products',
                );
                $order_products = array(
                    '',
                    __('Date', 'nb-fw') => 'date',
                    __('ID', 'nb-fw') => 'ID',
                    __('Author', 'nb-fw') => 'author',
                    __('Title', 'nb-fw') => 'title',
                    __('Modified', 'nb-fw') => 'modified',
                    __('Random', 'nb-fw') => 'rand',
                    __('Comment count', 'nb-fw') => 'comment_count',
                    __('Menu order', 'nb-fw') => 'menu_order',
                );

                vc_map(array(
                    "name" => __("NB - Woocommerce Products"),
                    "base" => "nb_wcproducts",
                    'icon' => 'icon-wpb-woocommerce',
                    "category" => __('Netbase Elements', 'nb-fw'),
                    "params" => array(
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Style', 'nb-fw'),
                            'param_name' => 'style',
                            'value' => array(
                                __('Default', 'nb-fw') => '',
                                __('Product Carousel', 'nb-fw') => 'carousel',
                                __('Product Masonry', 'nb-fw') => 'masonry',
                            ),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('Equal height', 'nb-fw'),
                            'param_name' => 'equal_height',
                            'value' => array(__('Yes', 'nb-fw') => 'true'),
                            'std' => 'true',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'style',
                                'value_not_equal_to' => array('masonry'),
                            ),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Show', 'nb-fw'),
                            'param_name' => 'view',
                            'value' => $product_views,
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Category', 'nb-fw'),
                            'value' => $product_categories_dropdown,
                            'param_name' => 'category',
                            'save_always' => true,
                            'description' => __('Product category list', 'nb-fw'),
                            'dependency' => array(
                                'element' => 'view',
                                'value' => array('product_cat'),
                            ),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('Product description', 'nb-fw'),
                            'param_name' => 'product_desc',
                            'value' => array(__('Show', 'nb-fw') => 'true'),
                            'std' => 'true',
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Description character limit of post', 'nb-fw'),
                            'param_name' => 'limit',
                            'value' => '15',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'product_desc',
                                'value' => array('true'),
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Per page', 'nb-fw'),
                            'value' => 12,
                            'save_always' => true,
                            'param_name' => 'per_page',
                            'description' => __('The "per_page" shortcode determines how many products to show on the page', 'nb-fw'),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Columns - Desktop', 'nb-fw'),
                            'value' => array(
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                            ),
                            'std' => '4',
                            'param_name' => 'columns-xl',
                            'save_always' => true,
                            'edit_field_class' => 'vc_col-sm-6',
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Columns - Tablet', 'nb-fw'),
                            'value' => array(
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                            ),
                            'std' => '4',
                            'param_name' => 'columns-lg',
                            'save_always' => true,
                            'edit_field_class' => 'vc_col-sm-6',
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Columns - Tablet Portrait', 'nb-fw'),
                            'value' => array(
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                            ),
                            'std' => '3',
                            'param_name' => 'columns-md',
                            'save_always' => true,
                            'edit_field_class' => 'vc_col-sm-6',
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Columns - Mobile Landscape', 'nb-fw'),
                            'value' => array(
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                            ),
                            'std' => '2',
                            'param_name' => 'columns-sm',
                            'save_always' => true,
                            'edit_field_class' => 'vc_col-sm-6',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Rows', 'nb-fw'),
                            'value' => 1,
                            'param_name' => 'rows',
                            'save_always' => true,
                            'description' => __('The rows attribute controls how many rows wide the products should be before wrapping.', 'nb-fw'),
                            'dependency' => array(
                                'element' => 'style',
                                'value' => array('carousel'),
                            ),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Order by', 'nb-fw'),
                            'param_name' => 'orderby',
                            'value' => $order_products,
                            'save_always' => true,
                            'description' => sprintf(__('Select how to sort retrieved products. More at %s.', 'nb-fw'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>'),
                            'dependency' => array(
                                'element' => 'view',
                                'value_not_equal_to' => 'best_selling_products',
                            ),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Sort order', 'nb-fw'),
                            'param_name' => 'order',
                            'value' => $order_way_values,
                            'save_always' => true,
                            'description' => sprintf(__('Designates the ascending or descending order. More at %s.', 'nb-fw'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>'),
                            'dependency' => array(
                                'element' => 'view',
                                'value_not_equal_to' => 'best_selling_products',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Extra class name', 'nb-fw'),
                            'param_name' => 'class',
                            'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Gap', 'nb-fw'),
                            'param_name' => 'gap',
                            'group' => __('Slide Options', 'nb-fw'),
                            'description' => __('Select gap between slide elements.', 'nb-fw'),
                            'value' => array(
                                '0px' => '0',
                                '1px' => '1',
                                '2px' => '2',
                                '3px' => '3',
                                '4px' => '4',
                                '5px' => '5',
                                '10px' => '10',
                                '15px' => '15',
                                '20px' => '20',
                                '25px' => '25',
                                '30px' => '30',
                                '35px' => '35',
                            ),
                            'std' => '30',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'style',
                                'value' => array('carousel'),
                            ),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('Navigation', 'nb-fw'),
                            'param_name' => 'nav',
                            'group' => __('Slide Options', 'nb-fw'),
                            'description' => __('Show next/prev buttons.', 'nb-fw'),
                            'value' => array(__('Show', 'nb-fw') => 'true'),
                            'std' => 'true',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'style',
                                'value' => array('carousel'),
                            ),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Navigation Layout', 'nb-fw'),
                            'param_name' => 'nav_layout',
                            'group' => __('Slide Options', 'nb-fw'),
                            'value' => array(
                                'Style 1' => 'style1',
                                'Style 2' => 'style2',
                                'Style 3' => 'style3',
                                'Style 4' => 'style4',
                                'Style 5' => 'style5',
                            ),
                            'std' => 'style1',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'nav',
                                'value' => array('true'),
                            ),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('Dots', 'nb-fw'),
                            'param_name' => 'dots',
                            'group' => __('Slide Options', 'nb-fw'),
                            'description' => __('Show dots navigation.', 'nb-fw'),
                            'value' => array(__('Show', 'nb-fw') => 'true'),
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'style',
                                'value' => array('carousel'),
                            ),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Navigation Layout', 'nb-fw'),
                            'param_name' => 'dots_layout',
                            'group' => __('Slide Options', 'nb-fw'),
                            'value' => array(
                                'Circle' => 'circle',
                                'Square' => 'square',
                                'Square 2' => 'square2',
                                'Square 3' => 'square3',
                                'Rounded' => 'rounded',
                                'Rounded 2' => 'rounded2',
                            ),
                            'std' => 'circle',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'dots',
                                'value' => array('true'),
                            ),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('Autoplay', 'nb-fw'),
                            'param_name' => 'autoplay',
                            'group' => __('Slide Options', 'nb-fw'),
                            'description' => __('Autoplay.', 'nb-fw'),
                            'save_always' => true,
                            'value' => array(__('Yes', 'nb-fw') => 'true'),
                            'std' => 'true',
                            'dependency' => array(
                                'element' => 'style',
                                'value' => array('carousel'),
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Autoplay Speed', 'nb-fw'),
                            'param_name' => 'autoplayspeed',
                            'group' => __('Slide Options', 'nb-fw'),
                            'description' => __('autoplay speed.', 'nb-fw'),
                            'value' => '5000',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'autoplay',
                                'value' => array('true'),
                            ),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('HoverPause', 'nb-fw'),
                            'param_name' => 'hoverpause',
                            'group' => __('Slide Options', 'nb-fw'),
                            'description' => __('Pause on mouse hover.', 'nb-fw'),
                            'value' => array(__('Yes', 'nb-fw') => 'true'),
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'autoplay',
                                'value' => array('true'),
                            ),
                        ),
                        array(
                            'type' => 'css_editor',
                            'heading' => __('CSS box', 'nb-fw'),
                            'param_name' => 'css',
                            'group' => __('Design Options', 'nb-fw'),
                            'save_always' => true,
                        ),
                    )
                ));
            }

            vc_map(array(
                "name" => __("NB - Wordpress Blog Post"),
                "base" => "nb_wpposts",
                'icon' => 'icon-wpb-wp',
                "category" => __('Netbase Elements', 'nb-fw'),
                "params" => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Style', 'nb-fw'),
                        'param_name' => 'style',
                        'value' => array(
                            __('Default', 'nb-fw') => '',
                            __('Post Carousel', 'nb-fw') => 'wppostcarousel',
                            __('Post Masonry', 'nb-fw') => 'wppostmasonry',
                        ),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Equal height', 'nb-fw'),
                        'param_name' => 'equal_height',
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'std' => 'true',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'style',
                            'value_not_equal_to' => array('wppostmasonry'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Layout', 'nb-fw'),
                        'param_name' => 'layout',
                        'value' => array(
                            __('List - No image', 'nb-fw') => 'wp-list',
                            __('Image Top', 'nb-fw') => 'wp-img-top',
                            __('Image Left', 'nb-fw') => 'wp-img-left',
                            __('Image Right', 'nb-fw') => 'wp-img-right',
                            __('Image Left/Right', 'nb-fw') => 'wp-img-leftright',
                            __('Image Top/Bottom', 'nb-fw') => 'wp-img-topbot',
                            __('Blog 1', 'nb-fw') => 'wp-blog1',
                            __('Blog 2', 'nb-fw') => 'wp-blog2',
                            __('Blog 3', 'nb-fw') => 'wp-blog3',
                            __('Grid', 'nb-fw') => 'wp-grid',
                        ),
                        'std' => 'wp-img-top',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Per page', 'nb-fw'),
                        'value' => 12,
                        'save_always' => true,
                        'param_name' => 'per_page',
                        'description' => __('The "per_page" shortcode determines how many posts to show on the page', 'nb-fw'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Desktop', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '4',
                        'param_name' => 'columns-xl',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                        'dependency' => array(
                            'element' => 'layout',
                            'value_not_equal_to' => 'wp-list',
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Tablet', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '4',
                        'param_name' => 'columns-lg',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                        'dependency' => array(
                            'element' => 'layout',
                            'value_not_equal_to' => 'wp-list',
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Tablet Portrait', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '3',
                        'param_name' => 'columns-md',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                        'dependency' => array(
                            'element' => 'layout',
                            'value_not_equal_to' => 'wp-list',
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Mobile Landscape', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '2',
                        'param_name' => 'columns-sm',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                        'dependency' => array(
                            'element' => 'layout',
                            'value_not_equal_to' => 'wp-list',
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Rows', 'nb-fw'),
                        'value' => 1,
                        'param_name' => 'rows',
                        'save_always' => true,
                        'description' => __('The rows attribute controls how many rows wide the posts should be before wrapping.', 'nb-fw'),
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('wppostcarousel'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Show Filter by categories', 'nb-fw'),
                        'param_name' => 'show_filter',
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('wppostmasonry'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Filter by categories', 'nb-fw'),
                        'param_name' => 'filter',
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'autocomplete',
                        'heading' => __('Categories', 'js_composer'),
                        'param_name' => 'category',
                        'settings' => array(
                            'multiple' => true,
                            'min_length' => 1,
                            'groups' => true,
                            'unique_values' => true,
                            'display_inline' => true,
                            'delay' => 500,
                            'auto_focus' => true,
                            'values' => $post_categories_values,
                        ),
                        'dependency' => array(
                            'element' => 'filter',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Order by', 'nb-fw'),
                        'param_name' => 'orderby',
                        'value' => $order_posts,
                        'save_always' => true,
                        'description' => sprintf(__('Select how to sort retrieved posts. More at %s.', 'nb-fw'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Sort order', 'nb-fw'),
                        'param_name' => 'order',
                        'value' => $order_way_values,
                        'save_always' => true,
                        'description' => sprintf(__('Designates the ascending or descending order. More at %s.', 'nb-fw'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>'),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'nb-fw'),
                        'param_name' => 'class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Thumbnail size', 'nb-fw'),
                        'param_name' => 'thumb',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => array(
                            '1:1' => '1-1',
                            '4:3' => '4-3',
                            '3:4' => '3-4',
                            '16:9' => '16-9',
                            '9:16' => '9-16',
                            'Custom' => 'custom',
                        ),
                        'std' => '4-3',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'layout',
                            'value_not_equal_to' => 'wp-list',
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Thumb Width', 'nb-fw'),
                        'param_name' => 'thumb_w',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => '100',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'thumb',
                            'value' => array('custom'),
                        ),
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Thumb Height', 'nb-fw'),
                        'param_name' => 'thumb_h',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => '100',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'thumb',
                            'value' => array('custom'),
                        ),
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Title tag', 'nb-fw'),
                        'param_name' => 'title_tag',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => array(
                            'H1' => 'h1',
                            'H2' => 'h2',
                            'H3' => 'h3',
                            'H4' => 'h4',
                            'H5' => 'h5',
                            'H6' => 'h6',
                            'Div' => 'div',
                        ),
                        'std' => 'h4',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Title link', 'nb-fw'),
                        'param_name' => 'title_lnk',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Title character limit of post', 'nb-fw'),
                        'param_name' => 'title_limit',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => '5',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Categories', 'nb-fw'),
                        'param_name' => 'categories',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Tags', 'nb-fw'),
                        'param_name' => 'tags',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Author', 'nb-fw'),
                        'param_name' => 'author',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Comments count', 'nb-fw'),
                        'param_name' => 'comments',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Date', 'nb-fw'),
                        'param_name' => 'datetime',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Display DateTime as "Time Ago"', 'nb-fw'),
                        'param_name' => 'timeago',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'layout',
                            'value_not_equal_to' => array('wp-blog2'),
                        ),
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Content character limit of post', 'nb-fw'),
                        'param_name' => 'limit',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => '15',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Readmore link', 'nb-fw'),
                        'param_name' => 'readmore',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Readmore text', 'nb-fw'),
                        'param_name' => 'readmoretxt',
                        'group' => __('Content Options', 'nb-fw'),
                        'value' => 'Read more',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'readmore',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Gap', 'nb-fw'),
                        'param_name' => 'gap',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Select gap between slide elements.', 'nb-fw'),
                        'value' => array(
                            '0px' => '0',
                            '1px' => '1',
                            '2px' => '2',
                            '3px' => '3',
                            '4px' => '4',
                            '5px' => '5',
                            '10px' => '10',
                            '15px' => '15',
                            '20px' => '20',
                            '25px' => '25',
                            '30px' => '30',
                            '35px' => '35',
                        ),
                        'std' => '30',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('wppostcarousel'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Navigation', 'nb-fw'),
                        'param_name' => 'nav',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Show next/prev buttons.', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'std' => 'true',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('wppostcarousel'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Navigation Layout', 'nb-fw'),
                        'param_name' => 'nav_layout',
                        'group' => __('Slide Options', 'nb-fw'),
                        'value' => array(
                            'Style 1' => 'style1',
                            'Style 2' => 'style2',
                            'Style 3' => 'style3',
                            'Style 4' => 'style4',
                            'Style 5' => 'style5',
                        ),
                        'std' => 'style1',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'nav',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Dots', 'nb-fw'),
                        'param_name' => 'dots',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Show dots navigation.', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('wppostcarousel'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Navigation Layout', 'nb-fw'),
                        'param_name' => 'dots_layout',
                        'group' => __('Slide Options', 'nb-fw'),
                        'value' => array(
                            'Circle' => 'circle',
                            'Square' => 'square',
                            'Square 2' => 'square2',
                            'Square 3' => 'square3',
                            'Rounded' => 'rounded',
                            'Rounded 2' => 'rounded2',
                        ),
                        'std' => 'circle',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'dots',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Autoplay', 'nb-fw'),
                        'param_name' => 'autoplay',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Autoplay.', 'nb-fw'),
                        'save_always' => true,
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'std' => 'true',
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('wppostcarousel'),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Autoplay Speed', 'nb-fw'),
                        'param_name' => 'autoplayspeed',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('autoplay speed.', 'nb-fw'),
                        'value' => '5000',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'autoplay',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('HoverPause', 'nb-fw'),
                        'param_name' => 'hoverpause',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Pause on mouse hover.', 'nb-fw'),
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'autoplay',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __('CSS box', 'nb-fw'),
                        'param_name' => 'css',
                        'group' => __('Design Options', 'nb-fw'),
                        'save_always' => true,
                    ),
                )
            ));

            vc_map(array(
                'name' => __('NB - Testimonials'),
                'base' => 'nb_testimonial',
                'category' => __('Netbase Elements', 'nb-fw'),
                'as_parent' => array('only' => 'nb_testimonial_item'),
                'description' => __("Testimonials in one list.", "nb-fw"),
                'content_element' => true,
                'show_settings_on_create' => true,
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Style', 'nb-fw'),
                        'param_name' => 'style',
                        'value' => array(
                            __('Default', 'nb-fw') => '',
                            __('Carousel', 'nb-fw') => 'carousel',
                            __('Masonry', 'nb-fw') => 'masonry',
                        ),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Equal height', 'nb-fw'),
                        'param_name' => 'equal_height',
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'std' => 'true',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'style',
                            'value_not_equal_to' => array('masonry'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Desktop', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '4',
                        'param_name' => 'columns-xl',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Tablet', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '4',
                        'param_name' => 'columns-lg',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Tablet Portrait', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '3',
                        'param_name' => 'columns-md',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Mobile Landscape', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '2',
                        'param_name' => 'columns-sm',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Testimonial Layout', 'nb-fw'),
                        'param_name' => 'layout',
                        'value' => array(
                            'Image Left' => 'img-left',
                            'Image Right' => 'img-right',
                            'Image Top' => 'img-top',
                            'Image Bottom' => 'img-bottom',
                            'Box 1' => 'box1',
                            'Box 2' => 'box2',
                        ),
                        'std' => 'img-left',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Size of avatar', 'nb-fw'),
                        'param_name' => 'ava_size',
                        'description' => __('How big would you like it? (px)', 'nb-fw'),
                        'value' => '',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Avatar Style', 'nb-fw'),
                        'param_name' => 'ava_style',
                        'value' => array(
                            'Circle' => 'circle',
                            'Square' => 'square',
                            'Rounded' => 'rounded',
                            'Style 1' => 'style1',
                            'Style 2' => 'style2',
                            'Style 3' => 'style3',
                            'Style 4' => 'style4',
                            'Style 5' => 'style5',
                            'Style 6' => 'style6',
                        ),
                        'std' => 'circle',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Name tag', 'nb-fw'),
                        'param_name' => 'name_tag',
                        'value' => array(
                            'H1' => 'h1',
                            'H2' => 'h2',
                            'H3' => 'h3',
                            'H4' => 'h4',
                            'H5' => 'h5',
                            'H6' => 'h6',
                            'Div' => 'div',
                        ),
                        'std' => 'h4',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Position tag', 'nb-fw'),
                        'param_name' => 'position_tag',
                        'value' => array(
                            'H1' => 'h1',
                            'H2' => 'h2',
                            'H3' => 'h3',
                            'H4' => 'h4',
                            'H5' => 'h5',
                            'H6' => 'h6',
                            'Div' => 'div',
                        ),
                        'std' => 'h5',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Gap', 'nb-fw'),
                        'param_name' => 'gap',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Select gap between slide elements.', 'nb-fw'),
                        'value' => array(
                            '0px' => '0',
                            '1px' => '1',
                            '2px' => '2',
                            '3px' => '3',
                            '4px' => '4',
                            '5px' => '5',
                            '10px' => '10',
                            '15px' => '15',
                            '20px' => '20',
                            '25px' => '25',
                            '30px' => '30',
                            '35px' => '35',
                        ),
                        'std' => '30',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('carousel'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Navigation', 'nb-fw'),
                        'param_name' => 'nav',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Show next/prev buttons.', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'std' => 'true',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('carousel'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Navigation Layout', 'nb-fw'),
                        'param_name' => 'nav_layout',
                        'group' => __('Slide Options', 'nb-fw'),
                        'value' => array(
                            'Style 1' => 'style1',
                            'Style 2' => 'style2',
                            'Style 3' => 'style3',
                            'Style 4' => 'style4',
                            'Style 5' => 'style5',
                        ),
                        'std' => 'style1',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'nav',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Dots', 'nb-fw'),
                        'param_name' => 'dots',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Show dots navigation.', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('carousel'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Navigation Layout', 'nb-fw'),
                        'param_name' => 'dots_layout',
                        'group' => __('Slide Options', 'nb-fw'),
                        'value' => array(
                            'Circle' => 'circle',
                            'Square' => 'square',
                            'Square 2' => 'square2',
                            'Square 3' => 'square3',
                            'Rounded' => 'rounded',
                            'Rounded 2' => 'rounded2',
                        ),
                        'std' => 'circle',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'dots',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Autoplay', 'nb-fw'),
                        'param_name' => 'autoplay',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Autoplay.', 'nb-fw'),
                        'save_always' => true,
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'std' => 'true',
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('carousel'),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Autoplay Speed', 'nb-fw'),
                        'param_name' => 'autoplayspeed',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('autoplay speed.', 'nb-fw'),
                        'value' => '5000',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'autoplay',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('HoverPause', 'nb-fw'),
                        'param_name' => 'hoverpause',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Pause on mouse hover.', 'nb-fw'),
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'autoplay',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'nb-fw'),
                        'param_name' => 'class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __('CSS box', 'nb-fw'),
                        'param_name' => 'css',
                        'group' => __('Design Options', 'nb-fw'),
                        'save_always' => true,
                    ),
                ),
                'js_view' => 'VcColumnView'
            ));
            vc_map(array(
                'name' => __('NB - Testimonial Item', 'nb-fw'),
                'base' => 'nb_testimonial_item',
                'icon' => 'vc_icon-vc-gitem-post-author',
                'category' => __('Netbase Elements', 'nb-fw'),
                'content_element' => true,
                'as_child' => array('only' => 'nb_testimonial'),
                'is_container' => false,
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Avatar', 'nb-fw'),
                        'param_name' => 'image',
                        'value' => '',
                        'description' => __('Select image from media library.', 'nb-fw'),
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Name', 'nb-fw'),
                        'param_name' => 'name',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Position', 'nb-fw'),
                        'param_name' => 'position',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textarea_html',
                        'heading' => __('Content', 'nb-fw'),
                        'param_name' => 'content',
                        'save_always' => true,
                        'holder' => 'div',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'nb-fw'),
                        'param_name' => 'class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                        'save_always' => true,
                    ),
                )
            ));

            vc_map(array(
                'name' => __('NB - Images Gallery'),
                'base' => 'nb_imgsgal',
                'icon' => 'icon-wpb-images-stack',
                'category' => __('Netbase Elements', 'nb-fw'),
                'description' => __("Responsive image gallery.", "nb-fw"),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Layout', 'nb-fw'),
                        'param_name' => 'img_layout',
                        'value' => array(
                            __('Default', 'nb-fw') => '',
                            __('Carousel', 'nb-fw') => 'carousel',
                            __('Masonry', 'nb-fw') => 'masonry',
                        ),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Equal height', 'nb-fw'),
                        'param_name' => 'equal_height',
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'std' => 'true',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'img_layout',
                            'value_not_equal_to' => array('masonry'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Filter', 'nb-fw'),
                        'param_name' => 'img_filter',
                        'description' => __('Show / Hide filter navigation.', 'nb-fw'),
                        'save_always' => true,
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'std' => 'true',
                        'dependency' => array(
                            'element' => 'img_layout',
                            'value' => array('masonry'),
                        ),
                    ),
                    array(
                        'type' => 'param_group',
                        'value' => '',
                        'heading' => __('Categories', 'nb-fw'),
                        'param_name' => 'imggal',
                        'group' => __('Images', 'nb-fw'),
                        // Note params is mapped inside param-group:
                        'params' => array(
                            array(
                                'type' => 'attach_image',
                                'heading' => __('Image', 'nb-fw'),
                                'param_name' => 'img_src',
                                'value' => '',
                                'description' => __('Select image from media library.', 'nb-fw'),
                                'admin_label' => true,
                                'edit_field_class' => 'vc_col-sm-6',
                            ),
                            array(
                                'type' => 'colorpicker',
                                'heading' => __('Background Color', 'js_composer'),
                                'param_name' => 'bg_color',
                                'description' => __('Select button text color.', 'js_composer'),
                                'edit_field_class' => 'vc_col-sm-6',
								'dependency' => array(
									'element' => 'img_src',
									'not_empty' => true,
								),
                            ),
                            array(
                                'type' => 'vc_link',
                                'heading' => __('Custom link', 'js_composer'),
                                'param_name' => 'img_lnk',
                                'description' => __('Add link to image.', 'js_composer'),
								'dependency' => array(
									'element' => 'img_src',
									'not_empty' => true,
								),
                            ),
                            array(
                                'type' => 'exploded_textarea_safe',
                                'heading' => __('Categories', 'nb-fw'),
                                'param_name' => 'img_cats',
                                'save_always' => true,
								'dependency' => array(
									'element' => 'img_src',
									'not_empty' => true,
								),
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Title', 'nb-fw'),
                                'param_name' => 'img_title',
                                'save_always' => true,
								'dependency' => array(
									'element' => 'img_src',
									'not_empty' => true,
								),
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => __('Description', 'nb-fw'),
                                'param_name' => 'img_desc',
                                'holder' => 'div',
                                'save_always' => true,
								'dependency' => array(
									'element' => 'img_src',
									'not_empty' => true,
								),
                            ),
                            array(
                                'type' => 'checkbox',
                                'heading' => __('Custom width (Masonry Layout)', 'nb-fw'),
                                'param_name' => 'custom_width',
                                'value' => array(__('Yes', 'nb-fw') => 'true'),
                                'save_always' => true,
								'dependency' => array(
									'element' => 'img_src',
									'not_empty' => true,
								),
                            ),
                            array(
                                'type' => 'dropdown',
                                'heading' => __('Width - Desktop', 'nb-fw'),
                                'value' => array(
                                    '1' => '1',
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4',
                                    '5' => '5',
                                    '6' => '6',
                                    '7' => '7',
                                    '8' => '8',
                                    '9' => '9',
                                    '10' => '10',
                                    '11' => '11',
                                    '12' => '12',
                                ),
                                'std' => '4',
                                'param_name' => 'width-xl',
                                'edit_field_class' => 'vc_col-sm-6',
                                'dependency' => array(
                                    'element' => 'custom_width',
                                    'value' => array('true'),
                                ),
                            ),
                            array(
                                'type' => 'dropdown',
                                'heading' => __('Width - Tablet', 'nb-fw'),
                                'value' => array(
                                    '1' => '1',
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4',
                                    '5' => '5',
                                    '6' => '6',
                                    '7' => '7',
                                    '8' => '8',
                                    '9' => '9',
                                    '10' => '10',
                                    '11' => '11',
                                    '12' => '12',
                                ),
                                'std' => '4',
                                'param_name' => 'width-lg',
                                'edit_field_class' => 'vc_col-sm-6',
                                'dependency' => array(
                                    'element' => 'custom_width',
                                    'value' => array('true'),
                                ),
                            ),
                            array(
                                'type' => 'dropdown',
                                'heading' => __('Width - Tablet Portrait', 'nb-fw'),
                                'value' => array(
                                    '1' => '1',
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4',
                                    '5' => '5',
                                    '6' => '6',
                                    '7' => '7',
                                    '8' => '8',
                                    '9' => '9',
                                    '10' => '10',
                                    '11' => '11',
                                    '12' => '12',
                                ),
                                'std' => '6',
                                'param_name' => 'width-md',
                                'edit_field_class' => 'vc_col-sm-6',
                                'dependency' => array(
                                    'element' => 'custom_width',
                                    'value' => array('true'),
                                ),
                            ),
                            array(
                                'type' => 'dropdown',
                                'heading' => __('Width - Mobile Landscape', 'nb-fw'),
                                'value' => array(
                                    '1' => '1',
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4',
                                    '5' => '5',
                                    '6' => '6',
                                    '7' => '7',
                                    '8' => '8',
                                    '9' => '9',
                                    '10' => '10',
                                    '11' => '11',
                                    '12' => '12',
                                ),
                                'std' => '6',
                                'param_name' => 'width-sm',
                                'edit_field_class' => 'vc_col-sm-6',
                                'dependency' => array(
                                    'element' => 'custom_width',
                                    'value' => array('true'),
                                ),
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Extra class name', 'nb-fw'),
                                'param_name' => 'img_class',
                                'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                                'save_always' => true,
								'dependency' => array(
									'element' => 'img_src',
									'not_empty' => true,
								),
                            ),
                        )
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Gap', 'nb-fw'),
                        'param_name' => 'img_gap',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Select gap between slide elements.', 'nb-fw'),
                        'value' => array(
                            '0px' => '0',
                            '1px' => '1',
                            '2px' => '2',
                            '3px' => '3',
                            '4px' => '4',
                            '5px' => '5',
                            '10px' => '10',
                            '15px' => '15',
                            '20px' => '20',
                            '25px' => '25',
                            '30px' => '30',
                            '35px' => '35',
                        ),
                        'std' => '30',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'img_layout',
                            'value' => array('carousel'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('On click action', 'nb-fw'),
                        'param_name' => 'img_onclick',
                        'value' => array(
                            __('None', 'nb-fw') => '',
                            __('Link to large image', 'nb-fw') => 'img_link_large',
                            __('Open prettyPhoto', 'nb-fw') => 'link_image',
                            __('Open custom link', 'nb-fw') => 'custom_link',
                        ),
                        'description' => __('Select action for click action.', 'nb-fw'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Link Target', 'js_composer'),
                        'param_name' => 'img_link_target',
                        'value' => vc_target_param_list(),
                        'dependency' => array(
                            'element' => 'img_onclick',
                            'value' => array(
                                'custom_link',
                                'img_link_large',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Desktop', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '6' => '6',
                        ),
                        'std' => '4',
                        'param_name' => 'columns-xl',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Tablet', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '6' => '6',
                        ),
                        'std' => '4',
                        'param_name' => 'columns-lg',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Tablet Portrait', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '6' => '6',
                        ),
                        'std' => '3',
                        'param_name' => 'columns-md',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Mobile Landscape', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '6' => '6',
                        ),
                        'std' => '2',
                        'param_name' => 'columns-sm',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Navigation', 'nb-fw'),
                        'param_name' => 'nav',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Show next/prev buttons.', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'std' => 'true',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'img_layout',
                            'value' => array('carousel'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Navigation Layout', 'nb-fw'),
                        'param_name' => 'nav_layout',
                        'group' => __('Slide Options', 'nb-fw'),
                        'value' => array(
                            'Style 1' => 'style1',
                            'Style 2' => 'style2',
                            'Style 3' => 'style3',
                            'Style 4' => 'style4',
                            'Style 5' => 'style5',
                        ),
                        'std' => 'style1',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'nav',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Dots', 'nb-fw'),
                        'param_name' => 'dots',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Show dots navigation.', 'nb-fw'),
                        'value' => array(__('Show', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'img_layout',
                            'value' => array('carousel'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Navigation Layout', 'nb-fw'),
                        'param_name' => 'dots_layout',
                        'group' => __('Slide Options', 'nb-fw'),
                        'value' => array(
                            'Circle' => 'circle',
                            'Square' => 'square',
                            'Square 2' => 'square2',
                            'Square 3' => 'square3',
                            'Rounded' => 'rounded',
                            'Rounded 2' => 'rounded2',
                        ),
                        'std' => 'circle',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'dots',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Autoplay', 'nb-fw'),
                        'param_name' => 'autoplay',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Autoplay.', 'nb-fw'),
                        'save_always' => true,
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'std' => 'true',
                        'dependency' => array(
                            'element' => 'img_layout',
                            'value' => array('carousel'),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Autoplay Speed', 'nb-fw'),
                        'param_name' => 'autoplayspeed',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('autoplay speed.', 'nb-fw'),
                        'value' => '5000',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'autoplay',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('HoverPause', 'nb-fw'),
                        'param_name' => 'hoverpause',
                        'group' => __('Slide Options', 'nb-fw'),
                        'description' => __('Pause on mouse hover.', 'nb-fw'),
                        'value' => array(__('Yes', 'nb-fw') => 'true'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'autoplay',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'nb-fw'),
                        'param_name' => 'class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __('CSS box', 'nb-fw'),
                        'param_name' => 'css',
                        'group' => __('Design Options', 'nb-fw'),
                        'save_always' => true,
                    ),
                ),
            ));
            vc_map(array(
                "name" => __("NB - Instagram Feed"),
                "base" => "nb_instagram",
                "category" => __('Netbase Elements', 'nb-fw'),
                "params" => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Instagram Username', 'nb-fw'),
                        'param_name' => 'ins_access_token',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Limit of photo', 'nb-fw'),
                        'param_name' => 'ins_limit',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Layout', 'nb-fw'),
                        'value' => array(
                            'Fixed' => 'fixed',
                            'Dynamic' => 'dynamic',
                        ),
                        'std' => 'fixed',
                        'param_name' => 'ins_layout',
                        'edit_field_class' => 'vc_col-sm-6',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Gutter', 'nb-fw'),
                        'param_name' => 'ins_gutter',
                        'edit_field_class' => 'vc_col-sm-6',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Width of image (px)', 'nb-fw'),
                        'param_name' => 'ins_w',
                        'edit_field_class' => 'vc_col-sm-6',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'ins_layout',
                            'value' => array('fixed'),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Height of image (px)', 'nb-fw'),
                        'param_name' => 'ins_h',
                        'edit_field_class' => 'vc_col-sm-6',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'ins_layout',
                            'value' => array('fixed'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Large Desktops', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                        ),
                        'std' => '4',
                        'param_name' => 'columns-xl',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                        'dependency' => array(
                            'element' => 'ins_layout',
                            'value' => array('dynamic'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Desktops', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                        ),
                        'std' => '4',
                        'param_name' => 'columns-lg',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                        'dependency' => array(
                            'element' => 'ins_layout',
                            'value' => array('dynamic'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Tablets', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                        ),
                        'std' => '3',
                        'param_name' => 'columns-md',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                        'dependency' => array(
                            'element' => 'ins_layout',
                            'value' => array('dynamic'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Columns - Mobile Landscape', 'nb-fw'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                        ),
                        'std' => '2',
                        'param_name' => 'columns-sm',
                        'save_always' => true,
                        'edit_field_class' => 'vc_col-sm-6',
                        'dependency' => array(
                            'element' => 'ins_layout',
                            'value' => array('dynamic'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Image ratio', 'nb-fw'),
                        'param_name' => 'ins_ratio',
                        'value' => array(
                            '1:1' => '1-1',
                            '4:3' => '4-3',
                            '3:4' => '3-4',
                            '16:9' => '16-9',
                            '9:16' => '9-16',
                        ),
                        'std' => '1-1',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'ins_layout',
                            'value' => array('dynamic'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Image Resolution', 'nb-fw'),
                        'value' => array(
                            'Thumbnail (150x150)' => 'thumbnail',
                            'Medium (320x320)' => 'low_resolution',
                            'Large (640x640)' => 'standard_resolution',
                        ),
                        'std' => 'thumbnail',
                        'param_name' => 'ins_resolution',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'nb-fw'),
                        'param_name' => 'class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nb-fw'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __('CSS box', 'nb-fw'),
                        'param_name' => 'css',
                        'group' => __('Design Options', 'nb-fw'),
                        'save_always' => true,
                    ),
                )
            ));
        }

        public function getCategoryChilds($parent_id, $pos, $array, $level, &$dropdown) {
            for ($i = $pos; $i < count($array); $i ++) {
                if ($array[$i]->category_parent == $parent_id) {
                    $data = array(
                        str_repeat('- ', $level) . $array[$i]->name => $array[$i]->slug,
                    );
                    $dropdown = array_merge($dropdown, $data);
                    $this->getCategoryChilds($array[$i]->term_id, $i, $array, $level + 1, $dropdown);
                }
            }
        }

        protected function getCategoryChildsFull($parent_id, $pos, $array, $level, &$dropdown) {
            for ($i = $pos; $i < count($array); $i ++) {
                if ($array[$i]->category_parent == $parent_id) {
                    $name = str_repeat('- ', $level) . $array[$i]->name;
                    $value = $array[$i]->slug;
                    $dropdown[] = array(
                        'label' => $name,
                        'value' => $value,
                    );
                    $this->getCategoryChildsFull($array[$i]->term_id, $i, $array, $level + 1, $dropdown);
                }
            }
        }

        function getCategories() {
            $terms = get_terms('category', 'orderby=name&hide_empty=0');
            if ($terms) {
                foreach ($terms as $key => $term) {
                    $dropdown[] = array(
                        'label' => $term->name,
                        'value' => $term->slug,
                    );
                }
            }
            return $dropdown;
        }

        private static function product_loopc($query_args, $atts, $loop_name, $style) {
            global $woocommerce_loop, $product_list, $product_desc, $product_desc_limit;

            $columns_xl = (isset($atts['columns-xl']) && $atts['columns-xl']) ? absint($atts['columns-xl']) : 4;
            $columns_lg = (isset($atts['columns-lg']) && $atts['columns-lg']) ? absint($atts['columns-lg']) : $columns_xl;
            $columns_md = (isset($atts['columns-md']) && $atts['columns-md']) ? absint($atts['columns-md']) : $columns_lg;
            $columns_sm = (isset($atts['columns-sm']) && $atts['columns-sm']) ? absint($atts['columns-sm']) : $columns_md;
            $rows = (isset($atts['rows']) && $atts['rows']) ? absint($atts['rows']) : 1;
            $woocommerce_loop['columns'] = $columns_xl;
            $woocommerce_loop['name'] = $loop_name;
            $product_desc = (isset($atts['product_desc']) && $atts['product_desc']) ? $atts['product_desc'] : '';
            $product_desc_limit = (isset($atts['limit']) && $atts['limit']) ? $atts['limit'] : '';
            $query_args = apply_filters('woocommerce_shortcode_products_query', $query_args, $atts, $loop_name);
            $transient_name = 'wc_loop' . substr(md5(json_encode($query_args) . $loop_name), 28) . WC_Cache_Helper::get_transient_version('product_query');
            $products = get_transient($transient_name);

            if (false === $products || !is_a($products, 'WP_Query')) {
                $products = new WP_Query($query_args);
                set_transient($transient_name, $products, DAY_IN_SECONDS * 30);
            }

            ob_start();

            if ($products->have_posts()) {
                if (isset($atts['nav']) && $atts['nav']):
                    vc_icon_element_fonts_enqueue('entypo');
                endif;
                $productoptions = ' data-cols-xl=' . $columns_xl
                        . ' data-cols-lg=' . $columns_lg
                        . ' data-cols-md=' . $columns_md
                        . ' data-cols-sm=' . $columns_sm
                        . ' data-byRow=' . ($rows > 1 ? '0' : '1');
                if ($atts['style'] == 'carousel'):
                    $productoptions .= ' data-slide=owl-carousel'
                            . ' data-margin=' . ($atts['gap'] ? absint($atts['gap']) : 30)
                            . ' data-nav=' . ($atts['nav'] ? $atts['nav'] : 'false')
                            . ((isset($atts['nav_layout']) && $atts['nav_layout']) ? ' data-navlayout=' . $atts['nav_layout'] : '')
                            . ' data-dots=' . ($atts['dots'] ? $atts['dots'] : 'false')
                            . ((isset($atts['dots_layout']) && $atts['dots_layout']) ? ' data-dotslayout=' . $atts['dots_layout'] : '')
                            . ' data-autoplay=' . ((isset($atts['autoplay']) && $atts['autoplay']) ? $atts['autoplay'] : 'false')
                            . ' data-autoplayspeed=' . ((isset($atts['autoplayspeed']) && $atts['autoplayspeed']) ? absint($atts['autoplayspeed']) : 'false')
                            . ' data-autoplayHoverPause=' . ((isset($atts['hoverpause']) && $atts['hoverpause']) ? $atts['hoverpause'] : 'false');
                elseif ($atts['style'] == 'masonry'):
                    $productoptions .= ' data-layout=isotope'
                            . ' data-layout_mode=mansory';
                endif;

                // Prime caches before grabbing objects.
                update_post_caches($products->posts, array('product', 'product_variation'));
                ?>

                <?php if ($atts['style'] == 'carousel'): ?>
                    <div class="nb_owl-carousel">
                    <?php endif; ?>
                    <div class="nb_wc-products<?php echo ($atts['style'] == 'carousel' ? ' owl-carousel' : ''); ?>"<?php echo esc_attr($productoptions); ?>>

                        <?php $i = 1; ?>
                        <?php
                        while ($products->have_posts()) :

                            $products->the_post();
                            echo ($i == 1 ? '<div class="products-column' . ($atts['style'] == 'masonry' ? ' nb-isotope-item isotope-item' : '') . '">' : '');
                            echo (isset($atts['equal_height']) && $atts['equal_height'] ? '<div class="equal_box">' : '');
                            wc_get_template_part('content', 'product');
                            echo (isset($atts['equal_height']) && $atts['equal_height'] ? '</div>' : '');
                            ;
                            if ($i == $rows):
                                echo '</div>';
                                $i = 1;
                            else:
                                $i++;
                            endif;
                            ?>

                        <?php endwhile; // end of the loop. ?>

                    </div>
                    <?php if ($atts['style'] == 'carousel'): ?>
                    </div>
                <?php endif; ?>

                <?php
            } else {
                do_action("woocommerce_shortcode_{$loop_name}_loop_no_results", $atts);
            }

            woocommerce_reset_loop();
            wp_reset_postdata();
            wp_reset_query();

            return '<div class="woocommerce columns-' . $columns_xl . ' nb_wcproducts_' . ($atts['style'] ? $atts['style'] : 'default') . '">' . ob_get_clean() . '</div>';
        }

        private static function post_loop($query_args, $atts, $style) {
            if ($atts['layout'] == 'wp-list'):
                $columns_xl = $columns_lg = $columns_md = $columns_sm = 1;
            else:
                $columns_xl = (isset($atts['columns-xl']) && $atts['columns-xl']) ? absint($atts['columns-xl']) : 4;
                $columns_lg = (isset($atts['columns-lg']) && $atts['columns-lg']) ? absint($atts['columns-lg']) : $columns_xl;
                $columns_md = (isset($atts['columns-md']) && $atts['columns-md']) ? absint($atts['columns-md']) : $columns_lg;
                $columns_sm = (isset($atts['columns-sm']) && $atts['columns-sm']) ? absint($atts['columns-sm']) : $columns_md;
            endif;
            $rows = (isset($atts['rows']) && absint($atts['rows'])) ? absint($atts['rows']) : 1;

            $nbposts = new WP_Query(apply_filters('widget_posts_args', $query_args));

            ob_start();

            if ($nbposts->have_posts()):
                if (isset($atts['nav']) && $atts['nav']):
                    vc_icon_element_fonts_enqueue('entypo');
                endif;
                $postoptions = ' data-cols-xl=' . $columns_xl
                        . ' data-cols-lg=' . $columns_lg
                        . ' data-cols-md=' . $columns_md
                        . ' data-cols-sm=' . $columns_sm
                        . ' data-byRow=' . ($rows > 1 ? '0' : '1');
                if ($atts['style'] == 'wppostcarousel'):
                    $postoptions .= ' data-slide=owl-carousel'
                            . ' data-margin=' . ($atts['gap'] ? absint($atts['gap']) : 30)
                            . ' data-nav=' . ($atts['nav'] ? $atts['nav'] : 'false')
                            . ((isset($atts['nav_layout']) && $atts['nav_layout']) ? ' data-navlayout=' . $atts['nav_layout'] : '')
                            . ' data-dots=' . ($atts['dots'] ? $atts['dots'] : 'false')
                            . ((isset($atts['dots_layout']) && $atts['dots_layout']) ? ' data-dotslayout=' . $atts['dots_layout'] : '')
                            . ' data-autoplay=' . ((isset($atts['autoplay']) && $atts['autoplay']) ? $atts['autoplay'] : 'false')
                            . ' data-autoplayspeed=' . ((isset($atts['autoplayspeed']) && $atts['autoplayspeed']) ? absint($atts['autoplayspeed']) : 'false')
                            . ' data-autoplayHoverPause=' . ((isset($atts['hoverpause']) && $atts['hoverpause']) ? $atts['hoverpause'] : 'false');
                elseif ($atts['style'] == 'wppostmasonry'):
                    $postoptions .= ' data-layout=isotope'
                            . ' data-layout_mode=mansory';
                    if ($atts['show_filter']):
                        ?>
                        <div class="filters-group filters-button-group">
                            <?php echo '<a class="filter-btn is-checked" data-filter="*"><span>' . __('All', 'nb-fw') . '</span></a>'; ?>
                            <?php
                            if (isset($atts['category'])):
                                $catsfilter = explode(', ', $atts['category']);
                                foreach ($catsfilter as $cat) :
                                    $term = get_term_by('slug', $cat, 'category');
                                    $name = $term->name;
                                    echo '<a class="filter-btn" data-filter=".' . $cat . '"><span>' . $name . '</span></a>';
                                endforeach;
                            else:
                                $catsfilter = get_categories();
                                foreach ($catsfilter as $cat) :
                                    echo '<a class="filter-btn" data-filter=".' . $cat->slug . '"><span>' . $cat->name . '</span></a>';
                                endforeach;
                            endif;
                            ?>
                        </div>
                        <?php
                    endif;
                endif;
                ?>
                <?php if ($atts['style'] == 'wppostcarousel'): ?>
                    <div class="nb_owl-carousel">
                    <?php endif; ?>
                    <div class="wp-posts <?php echo ($atts['style'] == 'wppostcarousel' ? 'owl-carousel' : ('columns-' . $columns_lg)); ?>"<?php echo esc_attr($postoptions); ?>>
                        <?php $i = 1; ?>
                        <?php
                        while ($nbposts->have_posts()) :
                            $nbposts->the_post();
                            echo (($i == 1 && $atts['style'] == 'wppostcarousel') ? '<div class="posts-column">' : '');
                            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                            $categories = get_the_category();
                            $filtercls = '';
                            if ($categories):
                                foreach ($categories as $cat):
                                    $filtercls .= ' ' . $cat->slug;
                                endforeach;
                            endif;
                            ?>
                            <div class="nb_wp_post nb_<?php echo ($atts['layout'] . ($atts['style'] == 'wppostmasonry' ? (' nb-isotope-item isotope-item' . $filtercls) : '') . (isset($atts['equal_height']) && $atts['equal_height'] ? ' equal_box' : '')); ?>">
                                <div class="nb_wp_post-i">
                                    <?php
                                    if ($atts['layout'] != 'wp-list' && $featured_img_url):
                                        $thumbsize = '';
                                        if ($atts['thumb'] == 'custom'):
                                            $thumbsize = 'width:' . ($atts['thumb_w'] ? $atts['thumb_w'] : '100') . 'px;'
                                                    . 'height:' . ($atts['thumb_h'] ? $atts['thumb_h'] : '100') . 'px;';
                                        endif;
                                        ?>
                                        <div class="nb-post-thumb thumb_<?php echo ($atts['thumb']); ?>">
                                            <div class="nb-post-thumb-i">
                                                <a href="<?php the_permalink(); ?>" style="background-image:url(<?php echo $featured_img_url; ?>);<?php echo ($thumbsize); ?>"></a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($atts['layout'] == 'wp-blog2' && $atts['datetime']): ?>
                                        <div class="nb-post-date">
                                            <div class="nb-post-date-i">
                                                <span class="vc_post_date-day"><?php echo date_i18n('d', strtotime(get_the_date(''))); ?></span>
                                                <span class="vc_post_date-month"><?php echo date_i18n('F', strtotime(get_the_date(''))); ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($atts['layout'] != 'wp-blog1'): ?>
                                        <div class="nb-post-block">
                                        <?php endif; ?>
                                        <div class="nb-post-header">
                                            <?php
                                            if ($atts['categories']):
                                                $cats = get_the_category_list(esc_html__(', ', 'nb-fw'));
                                                echo $cats ? ('<div class="nb-post-cats">' . $cats . '</div>') : '';
                                            endif;
                                            ?>
                                            <?php if ($atts['author'] || ($atts['layout'] != 'wp-blog2' && $atts['datetime']) || $atts['comments']): ?>
                                                <div class="nb-post-meta">
                                                    <?php if ($atts['author']): ?>
                                                        <span class="nb-post-author"><?php the_author(); ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($atts['layout'] != 'wp-blog2' && $atts['datetime']): ?>
                                                        <span class="nb-post-datetime"><?php echo $atts['timeago'] ? human_time_diff(strtotime(get_the_date()), current_time('timestamp')) : get_the_date(); ?></span>
                                                    <?php endif; ?>
                                                    <?php
                                                    if ($atts['comments']):
                                                        $num_comments = get_comments_number(); // get_comments_number returns only a numeric value
                                                        if (comments_open()) {
                                                            if ($num_comments > 1) {
                                                                $comments = $num_comments . __(' Comments');
                                                            } else {
                                                                $comments = $num_comments . __(' Comment');
                                                            }
                                                            $write_comments = '<a class="nb-post-comments" href="' . get_comments_link() . '">' . $comments . '</a>';
                                                        } else {
                                                            $write_comments = '<span class="nb-post-comments">' . __('Comments are off for this post.') . '</span>';
                                                        }
                                                        echo $write_comments;
                                                    endif;
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                            <<?php echo ($atts['title_tag'] ? $atts['title_tag'] : 'h4'); ?>>
                                            <?php if ($atts['title_lnk']): ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php
                                                    if (get_the_title()):
                                                        echo (absint($atts['title_limit']) > 0 ? wp_trim_words(get_the_title(), $atts['title_limit'], '...') : get_the_title());
                                                    else :
                                                        echo get_the_ID();
                                                    endif;
                                                    ?>
                                                </a>
                                            <?php else: ?>
                                                <?php
                                                if (get_the_title()):
                                                    echo (absint($atts['title_limit']) > 0 ? wp_trim_words(get_the_title(), $atts['title_limit'], '...') : get_the_title());
                                                else :
                                                    echo get_the_ID();
                                                endif;
                                                ?>
                                            <?php endif; ?>
                                            </<?php echo ($atts['title_tag'] ? $atts['title_tag'] : 'h4'); ?>>
                                        </div>
                                        <?php if ($atts['limit'] == '' || absint($atts['limit']) > 0): ?>
                                            <div class="nb-post-content">
                                                <?php
                                                if (has_excerpt()) :
                                                    echo '<p>' . (absint($atts['limit']) > 0 ? wp_trim_words(get_the_excerpt(), $atts['limit'], '...') : get_the_excerpt()) . '</p>';
                                                else :
                                                    echo '<p>' . (absint($atts['limit']) > 0 ? wp_trim_words(get_the_content(), $atts['limit'], '...') : get_the_content()) . '</p>';
                                                endif;
                                                ?>
                                                <?php if ($atts['readmore']): ?>
                                                    <div class="nb-post-readmore">
                                                        <a class="readmore" href="<?php the_permalink(); ?>"><?php echo ($atts['readmoretxt'] ? $atts['readmoretxt'] : esc_html__('Read more', 'nb-fw')); ?></a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php
                                        if ($atts['tags']):
                                            $tags = get_the_tag_list('', esc_html__(', ', 'nb-fw'));
                                            echo $tags ? ('<div class="nb-post-tags">' . $tags . '</div>') : '';
                                        endif;
                                        ?>
                                        <?php if ($atts['layout'] != 'wp-blog1'): ?>
                                        </div>
                                    <?php endif; ?>								
                                </div>
                            </div>
                            <?php
                            if ($atts['style'] == 'wppostcarousel'):
                                if ($i == $rows):
                                    echo '</div>';
                                    $i = 1;
                                else:
                                    $i++;
                                endif;
                            endif;

                        endwhile; // end of the loop. 
                        ?>

                    </div>
                    <?php if ($atts['style'] == 'wppostcarousel'): ?>
                    </div>
                    <?php
                endif;
            endif;
            wp_reset_query();

            return ob_get_clean();
        }

        private static function _maybe_add_category_args($args, $category, $operator) {
            if (!empty($category)) {
                if (empty($args['tax_query'])) {
                    $args['tax_query'] = array();
                }
                $args['tax_query'][] = array(
                    array(
                        'taxonomy' => 'product_cat',
                        'terms' => array_map('sanitize_title', explode(',', $category)),
                        'field' => 'slug',
                        'operator' => $operator,
                    ),
                );
            }
            return $args;
        }

        // Element HTML
        public function nb_wcproducts_render($atts) {
            extract(shortcode_atts(
                            array(
                'carousel' => '',
                'style' => '',
                'view' => '',
                'equal_height' => '',
                'product_desc' => '',
                'limit' => '',
                'category' => '',
                'per_page' => '',
                'columns-xl' => '',
                'columns-lg' => '',
                'columns-md' => '',
                'columns-xs' => '',
                'rows' => '',
                'orderby' => '',
                'order' => '',
                'gap' => '',
                'nav' => '',
                'nav_layout' => '',
                'dots' => '',
                'dots_layout' => '',
                'autoplay' => '',
                'autoplayspeed' => '',
                'hoverpause' => '',
                'class' => '',
                'css' => '',
                            ), $atts, 'nb_wcproducts'
            ));
            $operator = 'IN';
            $meta_query = WC()->query->get_meta_query();
            $tax_query = WC()->query->get_tax_query();

            switch ($atts['view']) {
                case 'recent_products':
                    $query_args['ignore_sticky_posts'] = 1;
                    $query_args['orderby'] = $atts['orderby'] ? $atts['orderby'] : 'date';
                    $query_args['order'] = $atts['order'] ? $atts['order'] : 'desc';
                    break;

                case 'featured_products':
                    $query_args['ignore_sticky_posts'] = 1;
                    $query_args['orderby'] = $atts['orderby'] ? $atts['orderby'] : 'date';
                    $query_args['order'] = $atts['order'] ? $atts['order'] : 'desc';
                    $tax_query[] = array(
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'featured',
                        'operator' => 'IN',
                    );
                    break;

                case 'product_cat':
                    if (!$atts['category']) {
                        return '';
                    }
                    $orderby = $atts['orderby'] ? $atts['orderby'] : 'menu_order title';
                    $order = $atts['order'] ? $atts['order'] : 'asc';
                    $ordering_args = WC()->query->get_catalog_ordering_args($orderby, $order);
                    $query_args['ignore_sticky_posts'] = 1;
                    $query_args['orderby'] = $ordering_args['orderby'];
                    $query_args['order'] = $ordering_args['order'];
                    if (isset($ordering_args['meta_key'])) {
                        $query_args['meta_key'] = $ordering_args['meta_key'];
                    }
                    break;

                case 'sale_products':
                    $query_args['orderby'] = $atts['orderby'] ? $atts['orderby'] : 'date';
                    $query_args['order'] = $atts['order'] ? $atts['order'] : 'desc';
                    $query_args['no_found_rows'] = 1;
                    $query_args['post__in'] = array_merge(array(0), wc_get_product_ids_on_sale());
                    break;

                case 'best_selling_products':
                    $query_args['ignore_sticky_posts'] = 1;
                    $query_args['meta_key'] = 'total_sales';
                    $query_args['orderby'] = 'meta_value_num';
                    break;

                case 'top_rated_products':
                    $query_args['ignore_sticky_posts'] = 1;
                    $query_args['orderby'] = $atts['orderby'] ? $atts['orderby'] : 'title';
                    $query_args['order'] = $atts['order'] ? $atts['order'] : 'asc';
                    break;
            }

            $query_args = array_merge($query_args, array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => $atts['per_page'],
                'meta_query' => $meta_query,
                'tax_query' => $tax_query,
            ));

            if (isset($atts['category'])) {
                $query_args = self::_maybe_add_category_args($query_args, $atts['category'], $operator);
            }

            if ($atts['view'] == 'top_rated_products') {
                add_filter('posts_clauses', array('WC_Shortcodes', 'order_by_rating_post_clauses'));
            }

            $product_loop = self::product_loopc($query_args, $atts, $atts['view'], $atts['style']);

            if ($atts['view'] == 'product_cat') {
                WC()->query->remove_ordering_args();
            }

            if ($atts['view'] == 'top_rated_products') {
                remove_filter('posts_clauses', array('WC_Shortcodes', 'order_by_rating_post_clauses'));
            }

            $css_class = 'wpb_content_element nb_' . $atts['view'] . ' nb_style_' . ($atts['style'] ? $atts['style'] : 'default') . (isset($atts['equal_height']) && $atts['equal_height'] ? ' equal_heights' : '');
            $css_class = implode(' ', array($css_class, $class));
            $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class . vc_shortcode_custom_css_class($css, ' '), 'nb_wcproducts', $atts);
            $css_class = trim(preg_replace('/\s+/', ' ', $css_class));
            $output = '<div class="' . esc_attr($css_class) . '" >'
                    . $product_loop
                    . '</div>';

            return $output;
        }

        public function nb_wpposts_render($atts) {
            extract(shortcode_atts(
                            array(
                'style' => '',
                'equal_height' => '',
                'layout' => '',
                'per_page' => '',
                'columns-xl' => '',
                'columns-lg' => '',
                'columns-md' => '',
                'columns-xs' => '',
                'rows' => '',
                'filter' => '',
                'show_filter' => '',
                'category' => '',
                'orderby' => '',
                'order' => '',
                'gap' => '',
                'nav' => '',
                'nav_layout' => '',
                'dots' => '',
                'dots_layout' => '',
                'autoplay' => '',
                'autoplayspeed' => '',
                'hoverpause' => '',
                'class' => '',
                'css' => '',
                'thumb' => '',
                'thumb_w' => '',
                'thumb_h' => '',
                'title_tag' => '',
                'title_lnk' => '',
                'title_limit' => '',
                'categories' => '',
                'tags' => '',
                'author' => '',
                'datetime' => '',
                'timeago' => '',
                'comments' => '',
                'limit' => '15',
                'readmore' => '',
                'readmoretxt' => '',
                            ), $atts, 'nb_wpposts'
            ));
            $tax_query = array(
                array(
                    'field' => 'slug',
                    'terms' => (($atts['filter'] && isset($atts['category'])) ? $atts['category'] : ''),
                ),
            );
            $query_args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => $atts['per_page'],
                'category_name' => (($atts['filter'] && isset($atts['category'])) ? $atts['category'] : ''),
                'orderby' => $atts['orderby'],
                'order' => $atts['order'],
            );

            $latest_posts = self::post_loop($query_args, $atts, $atts['style']);
            $output = '';
            if ($latest_posts):
                $css_class = 'wpb_content_element nb_wpposts_' . ($atts['style'] ? $atts['style'] : 'default') . ' nb_layout_' . ($atts['layout'] ? $atts['layout'] : 'default') . (isset($atts['equal_height']) && $atts['equal_height'] ? ' equal_heights' : '');
                $css_class = implode(' ', array($css_class, $class));
                $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class . vc_shortcode_custom_css_class($css, ' '), 'nb_wpposts', $atts);
                $css_class = trim(preg_replace('/\s+/', ' ', $css_class));
                $output = '<div class="' . esc_attr($css_class) . '" >'
                        . $latest_posts
                        . '</div>';
            endif;

            return $output;
        }

        function nb_testimonial($atts, $content = null) {
            global $style, $ava_size, $ava_style, $name_tag, $position_tag, $layout, $equal_height;
            $class = '';
            extract(shortcode_atts(
                            array(
                'style' => '',
                'equal_height' => '',
                'layout' => '',
                'ava_size' => '',
                'ava_style' => '',
                'name_tag' => '',
                'position_tag' => '',
                'columns-xl' => '',
                'columns-lg' => '',
                'columns-md' => '',
                'columns-sm' => '',
                'gap' => '',
                'nav' => '',
                'nav_layout' => '',
                'dots' => '',
                'dots_layout' => '',
                'autoplay' => '',
                'autoplayspeed' => '',
                'hoverpause' => '',
                'class' => '',
                'css' => '',
                            ), $atts, 'nb_testimonial'
            ));
            $css_class = 'wpb_content_element nb_testimonials_' . ($atts['style'] ? $atts['style'] : 'default') . ' nb_layout_' . ($atts['layout'] ? $atts['layout'] : 'default') . (isset($atts['equal_height']) && $atts['equal_height'] ? ' equal_heights' : '');
            $css_class = implode(' ', array($css_class, $class));
            $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class . vc_shortcode_custom_css_class($css, ' '), 'nb_testimonials_list', $atts);
            $css_class = trim(preg_replace('/\s+/', ' ', $css_class));
            $columns_xl = (isset($atts['columns-xl']) && $atts['columns-xl']) ? absint($atts['columns-xl']) : 1;
            $columns_lg = (isset($atts['columns-lg']) && $atts['columns-lg']) ? absint($atts['columns-lg']) : $columns_xl;
            $columns_md = (isset($atts['columns-md']) && $atts['columns-md']) ? absint($atts['columns-md']) : $columns_lg;
            $columns_sm = (isset($atts['columns-sm']) && $atts['columns-sm']) ? absint($atts['columns-sm']) : $columns_md;
            $testimonialoptions = ' data-cols-xl=' . $columns_xl
                    . ' data-cols-lg=' . $columns_lg
                    . ' data-cols-md=' . $columns_md
                    . ' data-cols-sm=' . $columns_sm;
            if ($atts['style'] == 'carousel' && $content):
                $testimonialoptions .= ' data-slide=owl-carousel'
                        . ' data-margin=' . ($atts['gap'] ? absint($atts['gap']) : 30)
                        . ' data-nav=' . ($atts['nav'] ? $atts['nav'] : 'false')
                        . (isset($atts['nav_layout']) ? ' data-navlayout=' . $atts['nav_layout'] : '')
                        . ' data-dots=' . ($atts['dots'] ? $atts['dots'] : 'false')
                        . (isset($atts['dots_layout']) ? ' data-dotslayout=' . $atts['dots_layout'] : '')
                        . ' data-autoplay=' . ((isset($atts['autoplay']) && $atts['autoplay']) ? $atts['autoplay'] : 'false')
                        . ' data-autoplayspeed=' . ((isset($atts['autoplayspeed']) && $atts['autoplayspeed']) ? absint($atts['autoplayspeed']) : 'false')
                        . ' data-autoplayHoverPause=' . ((isset($atts['hoverpause']) && $atts['hoverpause']) ? $atts['hoverpause'] : 'false');
            elseif ($atts['style'] == 'masonry'):
                $testimonialoptions .= ' data-layout=isotope'
                        . ' data-layout_mode=mansory';
            endif;
            if (isset($atts['nav']) && $atts['nav']):
                vc_icon_element_fonts_enqueue('entypo');
            endif;
            $output = '<div class="' . esc_attr($css_class) . '" >'
                    . ($atts['style'] == 'carousel' ? '<div class="nb_owl-carousel">' : '')
                    . '<div class="nb_testimonials' . ($atts['style'] == 'carousel' ? ' owl-carousel' : '') . '"' . $testimonialoptions . '>'
                    . do_shortcode($content)
                    . '</div>'
                    . ($atts['style'] == 'carousel' ? '</div>' : '')
                    . '</div>';

            return $output;
        }

        function nb_testimonial_item($atts, $content = null) {
            extract(shortcode_atts(
                            array(
                'image' => '',
                'name' => '',
                'position' => '',
                'class' => '',
                            ), $atts, 'nb_testimonial_item'
            ));
            global $style, $ava_size, $ava_style, $name_tag, $position_tag, $layout, $equal_height;

            $css_class = 'nb_testimonial';
            $css_class = implode(' ', array($css_class, $class));
            $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class, 'nb_testimonial', $atts);
            $css_class = trim(preg_replace('/\s+/', ' ', $css_class));

            if ($image) {
                $ava_size = $ava_size ? $ava_size : '100';
                $img_src = wp_get_attachment_image_src($image, array($ava_size, $ava_size));
                if ($img_src) {
                    $image = '<div class="nb_testimonial-avatar nb-' . $ava_style . '"><div class="nb_testimonial-avatar-i"><div class="nb_testimonial-avatar-ii" style="background-image:url(' . $img_src[0] . ');width:' . $ava_size . 'px;height:' . $ava_size . 'px;"></div></div></div>';
                }
            }
            $name = '';
            if ($atts['name']) {
                $name = '<' . ($name_tag ? $name_tag : 'h4') . ' class="nb_testimonial-name">' . $atts['name'] . '</' . ($name_tag ? $name_tag : 'h4') . '>';
            }
            $position = '';
            if ($atts['position']) {
                $position = '<' . ($position_tag ? $position_tag : 'h4') . ' class="nb_testimonial-position">' . $atts['position'] . '</' . ($position_tag ? $position_tag : 'h4') . '>';
            }


            $output = '<div class="nb_testimonial-item' . ($style == 'masonry' ? ' nb-isotope-item isotope-item' : '') . '">'
                    . '<div class="nb_testimonial nb_testimonial-' . $layout . (isset($equal_height) && $equal_height ? ' equal_box' : '') . '"'
                    . ($layout == 'box2' ? ' style="margin-top:' . absint(absint($ava_size) / 2) . 'px;padding-top:' . (absint(absint($ava_size) / 2) + 20) . 'px;"' : '')
                    . '>'
                    . (($layout == 'img-top' || $layout == 'img-bottom' || $layout == 'box1') ? '<div class="nb_testimonial-img-info">' : '')
                    . $image
                    . (($layout == 'img-left' || $layout == 'img-right') ? '<div class="nb_testimonial-detail">' : '')
                    . (($name || $position) ? ('<div class="nb_testimonial-info">' . $name . $position . '</div>') : '')
                    . (($layout == 'img-top' || $layout == 'img-bottom' || $layout == 'box1') ? '</div>' : '')
                    . '<div class="nb_testimonial-content">' . do_shortcode($content) . '</div>'
                    . (($layout == 'img-left' || $layout == 'img-right') ? '</div>' : '')
                    . '</div></div>';
            return $output;
        }

        public function getMedia($insf_access_token) {
            $url = "https://api.instagram.com/v1/users/self/media/recent?access_token=".$insf_access_token."";
            $get = wp_remote_get($url);
            $response = wp_remote_retrieve_body($get);
            $json = json_decode($response);
			if ($json){
				return $json->data;
			}
        }

        function nb_instagram_render($atts, $content = null) {
            extract(shortcode_atts(
                            array(
                'ins_access_token' => '',
                'ins_limit' => '10',
                'ins_layout' => '',
                'ins_gutter' => '',
                'ins_w' => '',
                'ins_h' => '',
                'ins_ratio' => '',
                'columns-xl' => '',
                'columns-lg' => '',
                'columns-md' => '',
                'columns-sm' => '',
                'ins_resolution' => '',
                'class' => '',
                'css' => '',
                            ), $atts, 'nb_instagram'
            ));
            $output = '';
            if ($atts['ins_access_token'] != ''):
                $datas = self::getMedia($atts['ins_access_token']);
                if (isset($datas)):
                    $css_class = 'wpb_content_element nb_wp_instagram';
                    $css_class = implode(' ', array($css_class, $atts['class']));
                    $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class . vc_shortcode_custom_css_class($atts['css'], ' '), 'nb_wp_instagram', $atts);
                    $css_class = trim(preg_replace('/\s+/', ' ', $css_class));

                    $istyle = '';
                    $istyle .= $atts['ins_gutter'] ? (' style="padding:' . absint(absint($atts['ins_gutter']) / 2) . 'px;"') : '';
                    if ($atts['ins_layout'] == 'dynamic'):
                        $columns_xl = (isset($atts['columns-xl']) && $atts['columns-xl']) ? absint($atts['columns-xl']) : 1;
                        $columns_lg = (isset($atts['columns-lg']) && $atts['columns-lg']) ? absint($atts['columns-lg']) : $columns_xl;
                        $columns_md = (isset($atts['columns-md']) && $atts['columns-md']) ? absint($atts['columns-md']) : $columns_lg;
                        $columns_sm = (isset($atts['columns-sm']) && $atts['columns-sm']) ? absint($atts['columns-sm']) : $columns_md;
                        $isstyle = ' data-cols-xl=' . $columns_xl
                                . ' data-cols-lg=' . $columns_lg
                                . ' data-cols-md=' . $columns_md
                                . ' data-cols-sm=' . $columns_sm
                                . ($atts['ins_gutter'] ? (' style="margin:-' . absint(absint($atts['ins_gutter']) / 2) . 'px;"') : '');
                    endif;

                    $output = '<div class="' . esc_attr($css_class) . '"' . $isstyle . '>';
                    foreach ($datas as $key => $data):
                        if ($key < intval($atts['ins_limit']) || $atts['ins_limit'] == '-1'):
                            $image = $data->images->$atts['ins_resolution']->url;
                            $output .= '<div class="ins-item" data-date="' . esc_attr($data->created_time) . '" data-likes="' . esc_attr($data->likes->count) . '"' . $istyle . '>'
                                    . '<a class="ins-thumb-link' . ($atts['ins_ratio'] ? ' thumb_' . $atts['ins_ratio'] : '') . '"'
                                    . ' href="' . esc_url($data->link) . '" target="_blank"'
                                    . ' style="background-image:url(' . esc_url($image) . ');'
                                    . ($atts['ins_layout'] == 'fixed' ?
                                            (($atts['ins_w'] ? 'width:' . absint($atts['ins_w']) : '75') . 'px;'
                                            . ($atts['ins_h'] ? 'height:' . absint($atts['ins_h']) : '75') . 'px;') : '')
                                    . '"></a>'
                                    . '</div>';
                        endif;
                    endforeach;
                    $output .= '</div>';
                endif;
            endif;
            return $output;
        }

        function nb_imgsgal_render($atts) {
            $class = '';
            extract(shortcode_atts(
                            array(
                'img_layout' => '',
                'equal_height' => '',
                'img_filter' => '',
                'imggal' => '',
                'img_style' => '',
                'img_gap' => '',
                'img_onclick' => '',
                'img_link_target' => '',
                'columns-xl' => '',
                'columns-lg' => '',
                'columns-md' => '',
                'columns-sm' => '',
                'nav' => '',
                'nav_layout' => '',
                'dots' => '',
                'dots_layout' => '',
                'autoplay' => '',
                'autoplayspeed' => '',
                'hoverpause' => '',
                'class' => '',
                'css' => '',
                            ), $atts, 'nb_imgsgal'
            ));
            $imggal = vc_param_group_parse_atts($atts['imggal']);
            if ($imggal):
                $css_class = 'nb_gallery nb_gallery_' . ($atts['img_layout'] ? $atts['img_layout'] : 'default') . ((isset($atts['equal_height']) && $atts['equal_height']) ? ' equal_heights' : '');
                $css_class = implode(' ', array($css_class, $class));
                $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class . vc_shortcode_custom_css_class($css, ' '), 'nb_gallery', $atts);
                $css_class = trim(preg_replace('/\s+/', ' ', $css_class));
                $columns_xl = (isset($atts['columns-xl']) && $atts['columns-xl']) ? absint($atts['columns-xl']) : 1;
                $columns_lg = (isset($atts['columns-lg']) && $atts['columns-lg']) ? absint($atts['columns-lg']) : $columns_xl;
                $columns_md = (isset($atts['columns-md']) && $atts['columns-md']) ? absint($atts['columns-md']) : $columns_lg;
                $columns_sm = (isset($atts['columns-sm']) && $atts['columns-sm']) ? absint($atts['columns-sm']) : $columns_md;
                $width_xl = absint(12 / $columns_xl);
                $width_lg = absint(12 / $columns_lg);
                $width_md = absint(12 / $columns_md);
                $width_sm = absint(12 / $columns_sm);
                $galoptions = '';
                if ($atts['img_layout'] == 'carousel'):
                    $galoptions = ' data-cols-xl=' . $columns_xl
                            . ' data-cols-lg=' . $columns_lg
                            . ' data-cols-md=' . $columns_md
                            . ' data-cols-sm=' . $columns_sm
                            . ' data-slide=owl-carousel'
                            . ' data-margin=' . ($atts['img_gap'] ? absint($atts['img_gap']) : 30)
                            . ' data-nav=' . ($atts['nav'] ? $atts['nav'] : 'false')
                            . (isset($atts['nav_layout']) ? ' data-navlayout=' . $atts['nav_layout'] : '')
                            . ' data-dots=' . ($atts['dots'] ? $atts['dots'] : 'false')
                            . (isset($atts['dots_layout']) ? ' data-dotslayout=' . $atts['dots_layout'] : '')
                            . ' data-autoplay=' . ((isset($atts['autoplay']) && $atts['autoplay']) ? $atts['autoplay'] : 'false')
                            . ' data-autoplayspeed=' . ((isset($atts['autoplayspeed']) && $atts['autoplayspeed']) ? absint($atts['autoplayspeed']) : 'false')
                            . ' data-autoplayHoverPause=' . ((isset($atts['hoverpause']) && $atts['hoverpause']) ? $atts['hoverpause'] : 'false');
                    if (isset($atts['nav']) && $atts['nav']):
                        vc_icon_element_fonts_enqueue('entypo');
                    endif;
                elseif ($atts['img_layout'] == 'masonry'):
                    $galoptions = ' data-layout=isotope'
                            . ' data-layout_mode=mansory';
                else:
                    $galoptions = ' data-cols-xl=' . $columns_xl
                            . ' data-cols-lg=' . $columns_lg
                            . ' data-cols-md=' . $columns_md
                            . ' data-cols-sm=' . $columns_sm;
                endif;
                $rand = get_the_ID() . '-' . rand();
                $items = '';
                $filter = array();
                foreach ($imggal as $key => $data):
                    if ($data['img_src']):
                        $img_width_xl = (isset($data['custom_width']) && $data['custom_width']!='' && $data['width-xl']) ? $data['width-xl'] : $width_xl;
                        $img_width_lg = (isset($data['custom_width']) && $data['custom_width']!='' && $data['width-lg']) ? $data['width-lg'] : $width_lg;
                        $img_width_md = (isset($data['custom_width']) && $data['custom_width']!='' && $data['width-md']) ? $data['width-md'] : $width_md;
                        $img_width_sm = (isset($data['custom_width']) && $data['custom_width']!='' && $data['width-sm']) ? $data['width-sm'] : $width_sm;
                        $filtercls = '';
                        $cats = '';
                        if ($data['img_cats']):
                            $cats = explode( ',', vc_value_from_safe($data['img_cats']));
                            foreach ($cats as $key => $cat):
                                $id = str_replace(' ', '_', strtolower($cat));
                                $filtercls .= ' ' . $id;
                                if (!(in_array($cat, $filter))):
                                    $filter[$id] = $cat;
                                endif;
                            endforeach;
                        endif;
                        $filtercls = implode(' ', array($filtercls, $data['img_class']));
                        $img_src = wp_get_attachment_image_src($data['img_src'], 'large');
						if (is_array($img_src)){
							$img_src = $img_src[0];
						}
                        $link = '';
                        switch ($img_onclick) {
                            case 'img_link_large':
								$a_attrs['href'] = $img_src;
                                $a_attrs['class'] = 'nb_img_link';
								if (isset($img_link_target) && $img_link_target != ''){
									$a_attrs['target'] = $img_link_target;
								}
                                break;
                            case 'link_image':
                                wp_enqueue_script('prettyphoto');
                                wp_enqueue_style('prettyphoto');
                                $a_attrs['class'] = 'nb_img_link prettyphoto';
                                $a_attrs['data-rel'] = 'prettyPhoto[rel-' . $rand . ']';
                                // backward compatibility
                                $a_attrs['href'] = $img_src;
                                break;
                            case 'custom_link':
								if (isset($data['img_lnk'])){
									$custom_links = vc_build_link($data['img_lnk']);
									foreach ($custom_links as $k => $v){
										if ($k == 'url'){
											$a_attrs['href'] = $v;
										} elseif ($k == 'target') {
											if ($v !== ''){
												$a_attrs[$k] = $v;
											}
										} else {
											$a_attrs[$k] = $v;
										}
									}
								} else {
									$a_attrs['href'] = '';
								}
                                $a_attrs['class'] = 'nb_img_link';
                                break;
                        }
                        $items .= '<div class="nb_galleryimg' . $filtercls . ($atts['img_layout'] == 'masonry' ? (' nb-isotope-item isotope-item"' . ' data-width-xl=' . $img_width_xl . ' data-width-lg=' . $img_width_lg . ' data-width-md=' . $img_width_md . ' data-width-sm=' . $img_width_sm) : '"') . '>';
                        $items .= '<div class="nb_galleryimg-i"' . (isset($data['bg_color']) ? ' style="background-color:' . $data['bg_color'] . '"' : '') . '>';
                        $items .= '<div class="nb_img"><img class="" src="' . esc_url($img_src) . '" alt="' . ($data['img_title'] ? $data['img_title'] : '') . '" /></div>';
                        if ($data['img_title'] || $data['img_desc']):
                            $items .= '<div class="nb_img_caption"><div class="nb_img_caption-i">'
                                    . ($data['img_title'] ? ('<h4 class="nb_img_title">' . $data['img_title'] . '</h4>') : '')
                                    . ($data['img_desc'] ? ('<div class="nb_img_desc">' . $data['img_desc'] . '</div>') : '')
                                    . '</div></div>';
                        endif;
                        if (isset($a_attrs['href']) && $a_attrs['href']!=''):
                            $items .= '<a ' . vc_stringify_attributes($a_attrs) . '></a>';
                        endif;
                        $items .= '</div>';
                        $items .= '</div>';
                    endif;
                endforeach;
                
                $output = '<div class="' . esc_attr($css_class) . '" >'
                        . ($atts['img_layout'] == 'carousel' ? '<div class="nb_owl-carousel">' : '');
                if ($img_filter && count($filter)):
                    $output .= '<div class="filters-group filters-button-group">'
                        . '<a class="filter-btn is-checked" data-filter="*"><span>' . __('All', 'nb-fw') . '</span></a>';
					$filtercls = '';
                    foreach ($filter as $key => $filter_option):
                        $output .= '<a class="filter-btn" data-filter=".' . $key . '"><span>' . $filter_option . '</span></a>';
						$filtercls .= ' ' . $key;
                    endforeach;
                    $output .= '</div>';
                endif;
                $output .= '<div class="nb_galleryimgs' . ($atts['img_layout'] == 'carousel' ? ' owl-carousel' : '') . '"' . $galoptions . '>';
				if ($atts['img_layout'] == 'masonry'):
                    $output .= '<div class="nb-isotope-item isotope-item' . $filtercls . '"></div>';
				endif;
                $output .= $items;
                $output .= '</div>'
                        . ($atts['img_layout'] == 'carousel' ? '</div>' : '')
                        . '</div>';

                return $output;
            endif;
        }

        function carouselScripts() {
            wp_register_script('vc_nb_owl-carousel', vc_asset_url('lib/owl-carousel2-dist/owl.carousel.min.js'), array('jquery',), WPB_VC_VERSION, true);
            wp_register_style('vc_nb_owl-carousel-css', vc_asset_url('lib/owl-carousel2-dist/assets/owl.min.css'), array(), WPB_VC_VERSION);
            wp_enqueue_script('vc_nb_owl-carousel');
            wp_enqueue_style('vc_nb_owl-carousel-css');

            wp_register_style('nbfwowlStyles', plugin_dir_url(__FILE__) . 'assets/css/owlstyle.css', array(), WPB_VC_VERSION);
            wp_enqueue_style('nbfwowlStyles');
        }

        function isotopeScripts() {
            wp_register_script('vc_grid-js-imagesloaded', vc_asset_url('lib/bower/imagesloaded/imagesloaded.pkgd.min.js'));
            wp_register_style('isotope-css', vc_asset_url('css/lib/isotope.min.css'), array(), WPB_VC_VERSION);
            wp_register_script('isotope', vc_asset_url('lib/bower/isotope/dist/isotope.pkgd.min.js'), array('jquery'), WPB_VC_VERSION, true);
        }

        function nbfwScripts() {
            wp_register_script('equalheightJs', plugin_dir_url(__FILE__) . 'assets/js/jquery.matchHeight-min.js', array('jquery'), WPB_VC_VERSION, true);
            wp_register_script('nbfwJs', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery', 'vc_nb_owl-carousel', 'vc_grid-js-imagesloaded', 'isotope', 'equalheightJs'), WPB_VC_VERSION, true);
            wp_enqueue_script('nbfwJs');
            wp_register_style('nbfwStyles', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), WPB_VC_VERSION);
            wp_enqueue_style('nbfwStyles');
        }

    }

}

if (class_exists('WPBakeryShortCodesContainer')) {

    class WPBakeryShortCode_nb_testimonial extends WPBakeryShortCodesContainer {
        
    }

}
if (class_exists('WPBakeryShortCode')) {

    class WPBakeryShortCode_nb_testimonial_item extends WPBakeryShortCode {
        
    }

}
