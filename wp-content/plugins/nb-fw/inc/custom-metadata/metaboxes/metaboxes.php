<?php

class NBFW_Metaboxes
{
    protected $prefix;

    public function __construct()
    {
        $this->prefix = 'nbcore_';
        add_action('cmb2_admin_init', array($this, 'get_settings'));
    }

    public function get_settings()
    {
        $settings = array();
        $global_setting_arr = array(
            'name' => __('Use Custom Settings', 'nb-fw'),
            'desc' => __('Check this and the global settings from Customize will be overwritten', 'nb-fw'),
            'id' => 'nbcore_global_setting',
            'type' => 'checkbox',
            'tab' => 'global'
        );

        $settings['page'] = $this->page();
        $settings['single_product'] = $this->product();
        $settings['product_category'] = $this->product_category();
        $settings['single'] = $this->single();
        $settings['archive'] = $this->archive();

        foreach ($settings as $key => $setting) {
            if ($fields = $setting['fields']) {
                unset($setting['fields']);

                array_unshift($fields, $global_setting_arr);

                $cmb = new_cmb2_box($setting);

                foreach ($fields as $field) {
                    if(isset($setting['tabs'])) {
                        $field['render_row_cb'] = array('CMB2_Tabs', 'tabs_render_row_cb');
                    }
                    $cmb->add_field($field);
                }
            }
        }
    }

    public function page()
    {
        $sp = $this->prefix . 'pages_';

        return array(
            'id' => 'page_settings',
            'title' => __('Page Settings', 'nb-fw'),
            'object_types' => array('page'), // Post type
            'priority' => 'core',
            'tabs' => array(
                'global' => array(
                    'label' => __('Global', 'nb-fw')
                ),
                'layout' => array(
                    'label' => __( 'Layout', 'nb-fw' ),
                ),
            ),
            'fields' => array(
                array(
                    'name' => esc_html__('Page Class', 'nb-fw'),
                    'id' => 'page_class',
                    'type' => 'text',
                    'tab' => 'layout'
                ),
                array(
                    'name' => esc_html__('Page title', 'nb-fw'),
                    'id' => $sp . 'title',
                    'type' => 'checkbox',
                    'tab' => 'layout'
                ),

                array(
                    'name' => esc_html__('Page title', 'nb-fw'),
                    'id' => $sp . 'title',
                    'type' => 'select',
                    'tab' => 'layout',
                    'default' => '',
                    'options' => array(
                        ''  => esc_html__('Default', 'nb-fw'),
                        1   => esc_html__('Show title for this page', 'nb-fw'),
                        0   => esc_html__('Do not show title for this page', 'nb-fw'),
                    ),
                ),

                array(
                    'name' => esc_html__('Page Sidebar', 'nb-fw'),
                    'id' => $sp . 'sidebar',
                    'type' => 'select',
                    'tab' => 'layout',
                    'default' => 'no-sidebar',
                    'options' => array(
                        'no-sidebar'    => esc_html__('No Sidebar', 'nb-fw'),
                        'left-sidebar'  => esc_html__('Left Sidebar', 'nb-fw'),
                        'right-sidebar' => esc_html__('Right Sidebar', 'nb-fw'),
                    ),
                ),
                array(
                    'name' => esc_html__('Main content width', 'nb-fw'),
                    'desc' => esc_html__('Content width in percent', 'nb-fw'),
                    'id' => $sp . 'content_width',
                    'type' => 'text',
                    'tab' => 'layout',
                    'attributes' => array(
                        'type' => 'number',
                        'pattern' => '\d*',
                        'min' => 60,
                        'max' => 80,
                        'data-conditional-id'    => $sp . 'sidebar',
                        'data-conditional-value' => wp_json_encode( array( 'left-sidebar', 'right-sidebar' ) ),
                    ),
                    'sanitization_cb' => 'absint',
                    'escape_cb'       => 'absint',
                ),
            ),
        );
    }

    public function product_category()
    {
        return array(
            'id' => 'product_category_settings',
            'title' => __('Product Category Settings', 'nb-fw'),
            'object_types' => array('term'), // Post type
            'taxonomies'   => array( 'product_cat' ),
            'priority' => 'low',
            'show_names' => true, // Show field names on the left
            'fields' => array(
                array(
                    'name' => __('Sidebar layout', 'nb-fw'),
                    'id' => $this->prefix . 'shop_sidebar',
                    'type' => 'select',
                    'options' => array(
                        'no-sidebar' => __('Full width', 'nb-fw'),
                        'left-sidebar' => __('Left Sidebar', 'nb-fw'),
                        'right-sidebar' => __('Right Sidebar', 'nb-fw'),
                    ),

                ),
                array(
                    'name' => __('Content Width ', 'nb-fw'),
                    'id' => $this->prefix . 'shop_content_width',
                    'type' => 'text',
                    'attributes' => array(
                        'type' => 'number',
                        'pattern' => '\d*',
                        'min' => 60,
                        'max' => 80,
                        'data-conditional-id'    => $this->prefix . 'shop_sidebar',
                        'data-conditional-value' => wp_json_encode( array( 'left-sidebar', 'right-sidebar' ) ),
                    ),
                ),
                array(
                    'name' => __('Product List Style', 'nb-fw'),
                    'id' => $this->prefix . 'product_list',
                    'type' => 'select',
                    'options' => array(
                        'grid-type' => __('Grid Type', 'nb-fw'),
                        'list-type' => __('List Type', 'nb-fw'),
                    ),
                ),
                array(
                    'name' => __('Show Product Description', 'nb-fw'),
                    'id' => $this->prefix . 'grid_product_description',
                    'type' => 'checkbox',
                    'attributes' => array(
                        'data-conditional-id'    => $this->prefix . 'product_list',
                        'data-conditional-value' => 'grid-type',
                    ),
                ),
                array(
                    'name' => __('Products Per Row', 'nb-fw'),
                    'id' => $this->prefix . 'loop_columns',
                    'type' => 'select',
                    'options' => array(
                        'two-columns' => __('2', 'nb-fw'),
                        'three-columns' => __('3', 'nb-fw'),
                        'four-columns' => __('4', 'nb-fw'),
                    ),
                    'attributes' => array(
                        'data-conditional-id'    => $this->prefix . 'product_list',
                        'data-conditional-value' => 'grid-type',
                    ),
                ),
                array(
                    'name' => __('Products Per Page', 'nb-fw'),
                    'id' => $this->prefix . 'products_per_page',
                    'type' => 'text',
                    'attributes' => array(
                        'type' => 'number',
                        'pattern' => '\d*',
                        'min' => 1,
                    )
                ),
                array(
                    'name' => __('Sale Tag Style', 'nb-fw'),
                    'id' => $this->prefix . 'wc_sale',
                    'type' => 'select',
                    'options' => array(
                        'style-1' => __('Style 1', 'core-wp'),
                        'style-2' => __('Style 2', 'core-wp'),
                        'style-3' => __('Style 3', 'core-wp'),
                    ),
                ),
                array(
                    'name' => __('Wishlist Button', 'nb-fw'),
                    'id' => 'product_category_wishlist',
                    'type' => 'checkbox'
                ),
                array(
                    'name' => __('Quickview Button', 'nb-fw'),
                    'id' => 'product_category_quickview',
                    'type' => 'checkbox'
                ),
            ),
        );
    }

    public function product()
    {
        $sp = $this->prefix . 'pd_';

        return array(
            'id' => 'product_settings',
            'title' => __('Product Settings', 'nb-fw'),
            'object_types' => array('product'), // Post type
            'priority' => 'core',
            'show_names' => true, // Show field names on the left
            'tabs'      => array(
                'global' => array(
                    'label' => __( 'Global', 'nb-fw' ),
                ),
                'layout' => array(
                    'label' => __( 'Layout', 'nb-fw' ),
                ),
                'product_gallery'  => array(
                    'label' => __( 'Product Gallery', 'nb-fw' ),
                    'icon'  => 'dashicons-share', // Dashicon
                ),
                'info_tabs' => array(
                    'label' => __( 'Tabs', 'nb-fw' ),
                ),
                'related_products' => array(
                    'label' => __( 'Layout', 'nb-fw' ),
                ),
            ),
            'tab_style'   => 'default',
            'fields' => array(
                array(
                    'name' => __('Sidebar Position', 'nb-fw'),
                    'id' => $sp . 'details_sidebar',
                    'type' => 'select',
                    'tab' => 'layout',
                    'options' => array(
                        'no-sidebar' => esc_html__('No Sidebar', 'nb-fw'),
                        'left-sidebar' => esc_html__('Left Sidebar', 'nb-fw'),
                        'right-sidebar' => esc_html__('Right Sidebar', 'nb-fw'),
                    ),
                ),
                array(
                    'name' => __('Content width', 'nb-fw'),
                    'id' => $sp . 'details_width',
                    'type' => 'text',
                    'tab' => 'layout',
                    'attributes' => array(
                        'type' => 'number',
                        'pattern' => '\d*',
                        'min' => 60,
                        'max' => 80,
                        'data-conditional-id'    => $sp . 'details_sidebar',
                        'data-conditional-value' => wp_json_encode( array( 'left-sidebar', 'right-sidebar' ) ),
                    ),
                ),
                array(
                    'name' => __('Product meta layout', 'nb-fw'),
                    'id' => $sp . 'meta_layout',
                    'type' => 'select',
                    'tab' => 'layout',
                    'options' => array(
                        'left-images' => esc_html__('Left Images', 'nb-fw'),
                        'right-images' => esc_html__('Right Images', 'nb-fw'),
                        'wide' => esc_html__('Wide', 'nb-fw'),
                    ),
                ),
                array(
                    'name' => __('Product images width', 'nb-fw'),
                    'id' => $sp . 'images_width',
                    'type' => 'text',
                    'tab' => 'product_gallery',
                    'attributes' => array(
                        'type' => 'number',
                        'pattern' => '\d*',
                        'min' => 60,
                        'max' => 80,
                        'data-conditional-id'    => $sp . 'meta_layout',
                        'data-conditional-value' => wp_json_encode( array( 'left-images', 'right-images' ) ),
                    ),
                ),
                array(
                    'name' => __('Small thumb position', 'nb-fw'),
                    'id' => $sp . 'thumb_pos',
                    'type' => 'select',
                    'tab' => 'product_gallery',
                    'options' => array(
                        'bottom-thumb' => __('Bottom thumb', 'nb-fw'),
                        'left-thumb' => __('Left thumb', 'nb-fw'),
                        'inside-thumb' => __('Inside thumb', 'nb-fw'),
                    ),
                ),
                array(
                    'name' => __('Tabs Style', 'nb-fw'),
                    'id' => 'nbcore_info_style',
                    'type' => 'select',
                    'tab' => 'info_tabs',
                    'options' => array(
                        'horizontal-tabs' => esc_html__('Horizontal', 'nb-fw'),
                        'accordion-tabs' => esc_html__('Accordion', 'nb-fw'),
                    ),
                ),
                array(
                    'name' => __('Reviews Form Style', 'nb-fw'),
                    'id' => 'nbcore_reviews_form',
                    'type' => 'select',
                    'tab' => 'info_tabs',
                    'options' => array(
                        'split' => esc_html__('Split', 'core-wp'),
                        'full-width' => esc_html__('Full Width', 'core-wp'),
                    ),
                ),
                array(
                    'name' => __('Show Upsells Products ?', 'nb-fw'),
                    'id' => 'nbcore_show_upsells',
                    'type' => 'checkbox',
                    'tab' => 'related_products',
                ),
                array(
                    'name' => __('Upsells Products Per Row', 'nb-fw'),
                    'id' => $sp . 'upsells_columns',
                    'type' => 'select',
                    'tab' => 'related_products',
                    'options' => array(
                        '2' => esc_html__('2 Products', 'nb-fw'),
                        '3' => esc_html__('3 Products', 'nb-fw'),
                        '4' => esc_html__('4 Products', 'nb-fw'),
                    ),
                    'attributes' => array(
                        'data-conditional-id'    => 'nbcore_show_upsells',
                    ),
                ),
                array(
                    'name' => __('Upsells Products Limit', 'nb-fw'),
                    'id' => 'nbcore_upsells_limit',
                    'type' => 'text',
                    'tab' => 'related_products',
                    'attributes' => array(
                        'type' => 'number',
                        'pattern' => '\d*',
                        'min' => 2,
                        'data-conditional-id'    => 'nbcore_show_upsells',
                    ),
                ),
                array(
                    'name' => __('Show Related Products ?', 'nb-fw'),
                    'id' => 'nbcore_show_related',
                    'type' => 'checkbox',
                    'tab' => 'related_products',
                ),
                array(
                    'name' => __('Related Products Per Row', 'nb-fw'),
                    'id' => $sp . 'related_columns',
                    'type' => 'select',
                    'tab' => 'related_products',
                    'options' => array(
                        '2' => esc_html__('2 Products', 'nb-fw'),
                        '3' => esc_html__('3 Products', 'nb-fw'),
                        '4' => esc_html__('4 Products', 'nb-fw'),
                    ),
                    'attributes' => array(
                        'data-conditional-id' => 'nbcore_show_related',
                    ),
                ),
            ),
        );
    }

    public function archive()
    {
        $sp = $this->prefix . 'blog_';

        return array(
            'id' => 'archive_settings',
            'title' => __('Archive Post Settings', 'nb-fw'),
            'object_types' => array('term'),
            'taxonomies'   => array( 'category' ),
            'priority' => 'core',
            'fields' => array(
                array(
                    'name' => __('Sidebar Position', 'nb-fw'),
                    'id' => $sp . 'sidebar',
                    'type' => 'select',
                    'options' => array(
                        'no-sidebar' => __('No Sidebar', 'nb-fw'),
                        'left-sidebar' => __('Left Sidebar', 'nb-fw'),
                        'right-sidebar' => __('Right Sidebar', 'nb-fw'),
                    ),
                ),
                array(
                    'name' => __('Blog Width', 'nb-fw'),
                    'id' => $sp . 'width',
                    'type' => 'text',
                    'attributes' => array(
                        'type' => 'number',
                        'pattern' => '\d*',
                        'min' => 60,
                        'max' => 80,
                        'data-conditional-id'    => $sp . 'sidebar',
                        'data-conditional-value' => wp_json_encode( array( 'left-sidebar', 'right-sidebar' ) ),
                    ),
                ),
                array(
                    'name' => __('Blog Archive Layout', 'nb-fw'),
                    'id' => $sp . 'archive_layout',
                    'type' => 'select',
                    'options' => array(
                        'classic' => __('Classic', 'nb-fw'),
                        'modern' => __('Modern', 'nb-fw'),
                    ),
                ),
                array(
                    'name' => __('Classic Columns', 'nb-fw'),
                    'id' => $sp . 'classic_columns',
                    'type' => 'select',
                    'attributes' => array(
                        'data-conditional-id'    => $sp . 'archive_layout',
                        'data-conditional-value' => 'classic'
                    ),
                    'options' => array(
                        '1' => __('1 Columns', 'nb-fw'),
                        '2' => __('2 Columns', 'nb-fw'),
                        '3' => __('3 Columns', 'nb-fw'),
                    ),
                ),
            ),
        );
    }

    public function single()
    {
        $sp = $this->prefix . 'blog_';

        return array(
            'id' => 'single_settings',
            'title' => esc_html__('Single Post Settings', 'nb-fw'),
            'object_types' => array('post'), // Post type
            'priority' => 'core',
            'tabs'      => array(
                'global' => array(
                    'label' => esc_html__('Global', 'nb-fw')
                ),
                'layout' => array(
                    'label' => esc_html__( 'Layout', 'nb-fw' ),
                ),
            ),
            'fields' => array(
                array(
                    'name' => esc_html__('Sidebar Position', 'nb-fw'),
                    'id' => $sp . 'sidebar',
                    'tab' => 'layout',
                    'type' => 'select',
                    'default' => 'no-sidebar',
                    'options' => array(
                        'no-sidebar'    => esc_html__('No Sidebar', 'nb-fw'),
                        'left-sidebar'  => esc_html__('Left Sidebar', 'nb-fw'),
                        'right-sidebar' => esc_html__('Right Sidebar', 'nb-fw'),
                    ),
                ),
                array(
                    'name' => esc_html__('Blog Width', 'nb-fw'),
                    'id' => $sp . 'width',
                    'tab' => 'layout',
                    'type' => 'text',
                    'attributes' => array(
                        'type' => 'number',
                        'pattern' => '\d*',
                        'min' => 60,
                        'max' => 80,
                        'data-conditional-id'    => $sp . 'sidebar',
                        'data-conditional-value' => wp_json_encode( array( 'left-sidebar', 'right-sidebar' ) ),
                    ),
                ),
                array(
                    'name' => esc_html__('Post Title Style', 'nb-fw'),
                    'id' => $sp . 'single_title_position',
                    'type' => 'select',
                    'tab' => 'layout',
                    'default' => 'position-1',
                    'options' => array(
                        'position-1' => esc_html__('Style 1', 'nb-fw'),
                        'position-2' => esc_html__('Style 2', 'nb-fw'),
                    ),
                ),
                array(
                    'name' => esc_html__('Post title font size', 'nb-fw'),
                    'desc' => esc_html__('Unit: px', 'nb-fw'),
                    'id' => $sp . 'single_title_size',
                    'tab' => 'layout',
                    'type' => 'text',
                    'attributes' => array(
                        'type' => 'number',
                        'pattern' => '\d*',
                        'min' => 16,
                        'max' => 70
                    ),
                ),
            ),
        );
    }
}
new NBFW_Metaboxes();