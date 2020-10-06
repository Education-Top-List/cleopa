<?php
/**
 * @param $option
 * @return array
 */

function cleopa_woocommerce($option){
    $attributes = array();
    if ( class_exists( 'WooCommerce' ) ) {
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $attributes = array();
        foreach($attribute_taxonomies as $attr)
        {
            if($attr->attribute_type == 'select')
            {
                $key = 'pa_'.$attr->attribute_name;
                $attributes[$key] = $attr->attribute_label;
            }
        }
    }
    $woocommerce = [
        'nbcore_pa_swatch_intro' => [
            'settings' => [],
            'controls' => [
                'label' => esc_html__('Swatch style Attributes', 'cleopa'),
                'section' => 'product_category',
                'type' => 'Cleopa_Customize_Control_Heading',
            ],
        ],
        'nbcore_pa_swatch_style' => [
            'settings' => [
                'default' => '',
                'sanitize_callback' => ['Cleopa_Customize_Sanitize', 'sanitize_checkbox']
            ],
            'controls' => [
                'label' => esc_html__('Swatch style Attributes', 'cleopa'),
                'description' => esc_html__('This options also effect for product attributes', 'cleopa'),
                'section' => 'product_category',
                'type' => 'Cleopa_Customize_Control_Checkbox_List',
                'choices' => $attributes,
                'condition' => array(
                    'element' => 'nbcore_wc_attr',
                    'value'   => 1,
                )
            ],
        ],
        'nbcore_wc_attr' => [
            'settings' => [
                'default' => true,
                'sanitize_callback' => ['Cleopa_Customize_Sanitize', 'sanitize_checkbox']
            ],
            'controls' => [
                'label' => esc_html__('Show Attribute ?', 'cleopa'),
                'description' => esc_html__('This options also effect for product attributes in product archive', 'cleopa'),
                'section' => 'product_category',
                'type' => 'Cleopa_Customize_Control_Switch',
            ]
        ],
    ];
    $option = array_merge($option,$woocommerce);
    return $option;
}
add_filter( 'woocommerce_hook', 'cleopa_woocommerce');

function cleopa_color($option){

    $color = [
        'nbcore_meta_color' => array(
            'settings' => [
                'default' => '#999999',
                'transport' => 'postMessage',
                'sanitize_callback' => 'wp_filter_nohtml_kses'
            ],
            'controls' => [
                'label' => esc_html__('Meta data Color', 'cleopa'),
                'section' => 'type_color',
                'type' => 'Cleopa_Customize_Control_Color',
            ],
        ),
        'nbcore_header_extend_intro' => array(
            'settings' => array(),
            'controls' => array(
                'label' => esc_html__('Header Extend', 'cleopa'),
                'section' => 'header_colors',
                'type' => 'Cleopa_Customize_Control_Heading',
            ),
        ),
        'nbcore_header_top_hover_color' => [
            'settings' => [
                'default' => '#ffffff',
                'transport' => 'postMessage',
                'sanitize_callback' => 'wp_filter_nohtml_kses',
            ],
            'controls' => [
                'label' => esc_html__('Text Hover Header Top Color', 'cleopa'),
                'section' => 'header_colors',
                'type' => 'Cleopa_Customize_Control_Color',
            ],
        ],
        'nbcore_header_middle_hover_color' => [
            'settings' => [
                'default' => '#f68d7d',
                'transport' => 'postMessage',
                'sanitize_callback' => 'wp_filter_nohtml_kses',
            ],
            'controls' => [
                'label' => esc_html__('Text Hover Header Middle Color', 'cleopa'),
                'section' => 'header_colors',
                'type' => 'Cleopa_Customize_Control_Color',
            ],
        ],
        'nbcore_header_bot_hover_color' => [
            'settings' => [
                'default' => '#f68d7d',
                'transport' => 'postMessage',
                'sanitize_callback' => 'wp_filter_nohtml_kses',
            ],
            'controls' => [
                'label' => esc_html__('Text Hover Header Footer Color', 'cleopa'),
                'section' => 'header_colors',
                'type' => 'Cleopa_Customize_Control_Color',
            ],
        ],
        'nbcore_footer_extend_intro' => array(
            'settings' => array(),
            'controls' => array(
                'label' => esc_html__('Footer Extend', 'cleopa'),
                'section' => 'footer_colors',
                'type' => 'Cleopa_Customize_Control_Heading',
            ),
        ),
        'nbcore_footer_top_hover_color' => [
            'settings' => [
                'default' => '#f68d7d',
                'transport' => 'postMessage',
                'sanitize_callback' => 'wp_filter_nohtml_kses'
            ],
            'controls' => [
                'label' => esc_html__('Text Hover Footer Top Color', 'cleopa'),
                'section' => 'footer_colors',
                'type' => 'Cleopa_Customize_Control_Color',
            ]
        ],
        'nbcore_footer_bot_hover_color' => [
            'settings' => [
                'default' => '#f68d7d',
                'transport' => 'postMessage',
                'sanitize_callback' => 'wp_filter_nohtml_kses'
            ],
            'controls' => [
                'label' => esc_html__('Text Hover Footer Top Color', 'cleopa'),
                'section' => 'footer_colors',
                'type' => 'Cleopa_Customize_Control_Color',
            ],
        ],
        'nbcore_footer_abs_hover_color' => [
            'settings' => [
                'default' => '#f68d7d',
                'transport' => 'postMessage',
                'sanitize_callback' => 'wp_filter_nohtml_kses'
            ],
            'controls' => [
                'label' => esc_html__('Text Hover Footer Absolute Color', 'cleopa'),
                'section' => 'footer_colors',
                'type' => 'Cleopa_Customize_Control_Color',
            ],
        ],
    ];

    $option = array_merge($option,$color);
    return $option;
}
add_filter('color_hook','cleopa_color');

function cleopa_header($option){

    $header = [
        'nbcore_logo_upload2' => [
            'settings' => [
                'default' => '',
                'sanitize_callback' => ['Cleopa_Customize_Sanitize', 'sanitize_file_image']
            ],
            'controls' => [
                'label' => esc_html__('Site Logo (optional)', 'cleopa'),
                'section' => 'header_general',
                'type' => 'WP_Customize_Image_Control'
            ]
        ],
        'nbcore_menu_resp' => [
            'settings' => [
                'default' => '768',
                'transport' => 'postMessage',
                'sanitize_callback' => ['Cleopa_Customize_Sanitize', 'sanitize_selection']
            ],
            'controls' => [
                'label' => esc_html__('Menu Responsive', 'cleopa'),
                'section' => 'header_general',
                'type' => 'Cleopa_Customize_Control_Select',
                'choices' => [
                    '576' => esc_html__('576 px', 'cleopa'),
                    '768' => esc_html__('768 px', 'cleopa'),
                    '992' => esc_html__('992 px', 'cleopa'),
                    '1200' => esc_html__('1200 px', 'cleopa'),
                    '0' => esc_html__('Always', 'cleopa'),
                ],
            ],
        ],

        'header_socials' => [
            'settings' => [],
            'controls' => [
                'label' => esc_html__('Socials', 'cleopa'),
                'description' => esc_html__('Your social links', 'cleopa'),
                'type' => 'Cleopa_Customize_Control_Heading',
                'section' => 'header_social',
            ],
        ],
        'nbcore_header_facebook' => [
            'settings' => [
                'default' => '',
                'transport' => 'refresh',
                'sanitize_callback' => 'esc_url_raw'
            ],
            'controls' => [
                'label' => esc_html__('Facebook', 'cleopa'),
                'section' => 'header_social',
                'type' => 'url',
            ]
        ],
        'nbcore_header_twitter' => [
            'settings' => [
                'default' => '',
                'transport' => 'refresh',
                'sanitize_callback' => 'esc_url_raw'
            ],
            'controls' => [
                'label' => esc_html__('Twitter', 'cleopa'),
                'section' => 'header_social',
                'type' => 'url',
            ],
        ],
        'nbcore_header_linkedin' => [
            'settings' => [
                'default' => '',
                'transport' => 'refresh',
                'sanitize_callback' => 'esc_url_raw'
            ],
            'controls' => [
                'label' => esc_html__('Linkedin', 'cleopa'),
                'section' => 'header_social',
                'type' => 'url',
            ],
        ],
        'nbcore_header_instagram' => [
            'settings' => [
                'default' => '',
                'transport' => 'refresh',
                'sanitize_callback' => 'esc_url_raw'
            ],
            'controls' => [
                'label' => esc_html__('Instagram', 'cleopa'),
                'section' => 'header_social',
                'type' => 'url',
            ],
        ],
        'nbcore_header_blog' => [
            'settings' => [
                'default' => '',
                'transport' => 'refresh',
                'sanitize_callback' => 'esc_url_raw'
            ],
            'controls' => [
                'label' => esc_html__('Blog', 'cleopa'),
                'section' => 'header_social',
                'type' => 'url',
            ],
        ],
        'nbcore_header_pinterest' => [
            'settings' => [
                'default' => '',
                'transport' => 'refresh',
                'sanitize_callback' => 'esc_url_raw'
            ],
            'controls' => [
                'label' => esc_html__('Pinterest', 'cleopa'),
                'section' => 'header_social',
                'type' => 'url',
            ],
        ],
        'nbcore_header_ggplus' => [
            'settings' => [
                'default' => '',
                'transport' => 'refresh',
                'sanitize_callback' => 'esc_url_raw'
            ],
            'controls' => [
                'label' => esc_html__('Google Plus', 'cleopa'),
                'section' => 'header_social',
                'type' => 'url',
            ],
        ],
    ];

    $option = array_merge($option,$header);
    return $option;
}
add_filter( 'header_hook', 'cleopa_header' );