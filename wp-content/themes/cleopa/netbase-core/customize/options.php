<?php

/*
* One value *

'condition' => array(
    'element' => 'element',
    'value'   => 'value',
)

* Array *

'condition' => array(
    'element' => array('element1', 'element2'),
    'value'   => array('value1', 'value2'),
)

* Not Value *

'condition' => array(
    'element' => 'element',
    'value'   => '!value',
)


*/

class Cleopa_Customize_Options
{
    public function footer()
    {
        return array(
            'title' => esc_html__('Footer', 'cleopa'),
            'priority' => 17,
            'options' => array(
                'nbcore_footer_top_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Footer top section', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_show_footer_top' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show this section', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_footer_top_layout' => array(
                    'settings' => array(
                        'default' => 'layout-9',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Columns', 'cleopa'),
                        'section' => 'footer',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'layout-1' => get_template_directory_uri() . '/assets/images/options/footers/footer-1.png',
                            'layout-2' => get_template_directory_uri() . '/assets/images/options/footers/footer-2.png',
                            'layout-3' => get_template_directory_uri() . '/assets/images/options/footers/footer-3.png',
                            'layout-4' => get_template_directory_uri() . '/assets/images/options/footers/footer-4.png',
                            'layout-5' => get_template_directory_uri() . '/assets/images/options/footers/footer-5.png',
                            'layout-6' => get_template_directory_uri() . '/assets/images/options/footers/footer-6.png',
                            'layout-7' => get_template_directory_uri() . '/assets/images/options/footers/footer-7.png',
                            'layout-8' => get_template_directory_uri() . '/assets/images/options/footers/footer-8.png',
                            'layout-9' => get_template_directory_uri() . '/assets/images/options/footers/footer-9.png',
                        ),
                    ),
                ),
                'nbcore_footer_bot_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Footer bottom section', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_show_footer_bot' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show this section', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_footer_bot_layout' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Columns', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'layout-1' => get_template_directory_uri() . '/assets/images/options/footers/footer-1.png',
                            'layout-2' => get_template_directory_uri() . '/assets/images/options/footers/footer-2.png',
                            'layout-3' => get_template_directory_uri() . '/assets/images/options/footers/footer-3.png',
                            'layout-4' => get_template_directory_uri() . '/assets/images/options/footers/footer-4.png',
                            'layout-5' => get_template_directory_uri() . '/assets/images/options/footers/footer-5.png',
                            'layout-6' => get_template_directory_uri() . '/assets/images/options/footers/footer-6.png',
                            'layout-7' => get_template_directory_uri() . '/assets/images/options/footers/footer-7.png',
                            'layout-8' => get_template_directory_uri() . '/assets/images/options/footers/footer-8.png',
                            'layout-9' => get_template_directory_uri() . '/assets/images/options/footers/footer-9.png',
                        ),
                    ),
                ),
                'nbcore_footer_abs_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Absolute Footer', 'cleopa'),
                        'description' => esc_html__('These area take text and HTML code for its content', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_footer_abs_left_content' => array(
                    'settings' => array(
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Left content', 'cleopa'),
                        'type' => 'textarea',
                    ),
                ),
                'nbcore_footer_abs_right_content' => array(
                    'settings' => array(
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Right content', 'cleopa'),
                        'type' => 'textarea',
                    ),
                ),
                'nbcore_footer_abs_padding' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Padding top and bottom', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '5',
                            'max' => '60',
                            'step' => '1',
                        ),
                    ),
                ),
            ),
        );
    }

    public function blog()
    {
        return array(
            'title' => esc_html__('Blog', 'cleopa'),
            'priority' => 16,
            'sections' => array(
                'blog_general' => array(
                    'title' => esc_html__('General', 'cleopa')
                ),
                'blog_archive' => array(
                    'title' => esc_html__('Blog Archive', 'cleopa'),
                ),
                'blog_single' => array(
                    'title' => esc_html__('Blog Single', 'cleopa')
                ),
            ),
            'options' => array(
                'nbcore_blog_layout_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Layout', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_blog_sidebar' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Sidebar position', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'left-sidebar' => get_template_directory_uri() . '/assets/images/options/2cl.png',
                            'no-sidebar' => get_template_directory_uri() . '/assets/images/options/1c.png',
                            'right-sidebar' => get_template_directory_uri() . '/assets/images/options/2cr.png',
                        ),
                    ),
                ),
                'nbcore_blog_width' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Blog width', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => '%',
                            'min' => '60',
                            'max' => '80',
                            'step' => '1'
                        ),
                    ),
                ),
                'nbcore_blog_meta_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Post meta', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_blog_meta_date' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show date', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_blog_meta_read_time' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show time to read', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_blog_meta_author' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show author', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_blog_meta_category' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show categories', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_blog_meta_tag' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show Tags', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Switch',
                        'condition' => array(
                            'element' => 'nbcore_blog_archive_layout',
                            'value'   => 'classic',
                        )
                    ),
                ),
                'nbcore_blog_other_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Other', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_blog_sticky_sidebar' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Sticky sidebar', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_blog_meta_align' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Meta align', 'cleopa'),
                        'section' => 'blog_general',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'left' => get_template_directory_uri() . '/assets/images/options/meta-left.png',
                            'center' =>get_template_directory_uri() . '/assets/images/options/meta-center.png',
                            'right' => get_template_directory_uri() . '/assets/images/options/meta-right.png',
                        ),
                    ),
                ),
                'nbcore_blog_archive_layout' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Blog Archive Layout', 'cleopa'),
                        'section' => 'blog_archive',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'classic' => get_template_directory_uri() . '/assets/images/options/classic.png',
                            'masonry' => get_template_directory_uri() . '/assets/images/options/masonry.png',
                        ),
                    ),
                ),
                'nbcore_blog_masonry_columns' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Columns', 'cleopa'),
                        'section' => 'blog_archive',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            '2' => esc_html__('2', 'cleopa'),
                            '3' => esc_html__('3', 'cleopa'),
                        ),
                        'condition' => array(
                            'element' => 'nbcore_blog_archive_layout',
                            'value'   => 'masonry',
                        )
                    ),
                ),
                'nbcore_blog_archive_post_style' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Post style', 'cleopa'),
                        'section' => 'blog_archive',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'style-1' => get_template_directory_uri() . '/assets/images/options/post-style-1.png',
                            'style-2' => get_template_directory_uri() . '/assets/images/options/post-style-2.png',
                        ),
                    ),
                ),
                'nbcore_blog_archive_summary' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show Post summary', 'cleopa'),
                        'section' => 'blog_archive',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_excerpt_only' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show Excerpt Only', 'cleopa'),
                        'section' => 'blog_archive',
                        'type' => 'Cleopa_Customize_Control_Switch',
                        'condition' => array(
                            'element' => array('nbcore_blog_archive_layout', 'nbcore_blog_archive_summary'),
                            'value'   => array('classic', 1),
                        )
                    ),
                ),
                'nbcore_excerpt_length' => array(
                    'settings' => array(
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Excerpt Length', 'cleopa'),
                        'section' => 'blog_archive',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'min' => '20',
                            'max' => '100',
                            'step' => '1',
                        ),
                        'condition' => array(
                            'element' => 'nbcore_excerpt_only',
                            'value'   => 1,
                        )
                    ),
                ),
                'nbcore_blog_archive_comments' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show Comments number', 'cleopa'),
                        'section' => 'blog_archive',
                        'type' => 'Cleopa_Customize_Control_Switch',
                        'condition' => array(
                            'element' => 'nbcore_blog_archive_layout',
                            'value'   => 'classic',
                        )
                    ),
                ),
                'nbcore_blog_single_title_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Post title', 'cleopa'),
                        'section' => 'blog_single',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_blog_single_title_position' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Post title style', 'cleopa'),
                        'section' => 'blog_single',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'position-1' => get_template_directory_uri() . '/assets/images/options/post-title-1.png',
                            'position-2' => get_template_directory_uri() . '/assets/images/options/post-title-2.png',
                        ),
                    ),
                ),
                'nbcore_blog_bg_single_title' => array(
                    'settings' => array(
                        'default' => '',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Background Blog Single Title', 'cleopa'),
                        'section' => 'blog_single',
                        'type' => 'WP_Customize_Cropped_Image_Control',
                        'flex_width'  => true,
                        'flex_height' => true,
                        'width' => 2000,
                        'height' => 1000,
                        'condition' => array(
                            'element' => 'nbcore_blog_single_title_position',
                            'value'   => 'position-1',
                        )
                    ),
                ),
                'nbcore_blog_single_title_size' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Font size', 'cleopa'),
                        'section' => 'blog_single',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '16',
                            'max' => '70',
                            'step' => '1',
                        ),
                    ),
                ),
                'nbcore_blog_single_layout_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Layout', 'cleopa'),
                        'section' => 'blog_single',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_blog_single_show_thumb' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('featured thumbnail', 'cleopa'),
                        'description' => esc_html__('Show featured thumbnail of this post on top of its content', 'cleopa'),
                        'section' => 'blog_single',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    )
                ),
                'nbcore_blog_single_show_social' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show social button', 'cleopa'),
                        'section' => 'blog_single',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_blog_single_show_author' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show author info', 'cleopa'),
                        'section' => 'blog_single',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_blog_single_show_nav' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show post navigation', 'cleopa'),
                        'section' => 'blog_single',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_blog_single_show_comments' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show post comments', 'cleopa'),
                        'section' => 'blog_single',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                )
            ),
        );
    }
    public static function pages()
    {
        return array(
            'title' => esc_html__('Pages', 'cleopa'),
            'priority' => 18,
            'sections' => array(
                'pages_general' => array(
                    'title' => esc_html__('General', 'cleopa')
                ),
               
                
            ),
            'options' => array(
                'nbcore_pages_layout_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Layout', 'cleopa'),
                        'section' => 'pages_general',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_page_title_image' => array(
                    'settings' => array(
                        'default'=>''
                        //'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_file_image')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Image Background Title Pages', 'cleopa'),
                        'section' => 'pages_general',
                        'type' => 'WP_Customize_Cropped_Image_Control',
                        'flex_width'  => true,
                        'flex_height' => true,
                        'width' => 2000,
                        'height' => 1000,
                    ),
                ),
                'nbcore_pages_sidebar' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Sidebar position', 'cleopa'),
                        'section' => 'pages_general',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'left-sidebar' => get_template_directory_uri() . '/assets/images/options/2cl.png',
                            'no-sidebar' => get_template_directory_uri() . '/assets/images/options/1c.png',
                            'right-sidebar' => get_template_directory_uri() . '/assets/images/options/2cr.png',
                        ),
                    ),
                ),                  
                'nbcore_page_content_width' => array(
                    'settings' => array(
                        'default' => '70',
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Page content width', 'cleopa'),
                        'section' => 'pages_general',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => '%',
                            'min' => '60',
                            'max' => '80',
                            'step' => '1'
                        ),
                        'condition' => array(
                            'element' => 'nbcore_pages_sidebar',
                            'value'   => '!no-sidebar',
                        )
                    ),
                ), 
                
            ),
        );
    }

    public function color()
    {
        return array(
            'title' => esc_html__('Color', 'cleopa'),
            'priority' => 13,
            'sections' => apply_filters('nbt_color_array',
                array(
                    'general_color' => array(
                        'title' => esc_html__('General', 'cleopa')
                    ),
                    'type_color' => array(
                        'title' => esc_html__('Type', 'cleopa')
                    ),
                    'header_colors' => array(
                        'title' => esc_html__('Header', 'cleopa')
                    ),
                    'footer_colors' => array(
                        'title' => esc_html__('Footer', 'cleopa')
                    ),
                    'button_colors' => array(
                        'title' => esc_html__('Buttons', 'cleopa')
                    ),
                    'other_colors' => array(
                        'title' => esc_html__('Other', 'cleopa')
                    ),
                )
            ),
            'options' => apply_filters('color_hook',array(
                'nbcore_main_colors_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Main Colors', 'cleopa'),
                        'section' => 'general_color',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_primary_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Primary Color', 'cleopa'),
                        'section' => 'general_color',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_secondary_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Secondary Color', 'cleopa'),
                        'section' => 'general_color',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_background_colors_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Background', 'cleopa'),
                        'section' => 'general_color',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_background_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Site Background Color', 'cleopa'),
                        'section' => 'general_color',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_inner_background' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Inner Background Color', 'cleopa'),
                        'section' => 'general_color',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_text_colors_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Text', 'cleopa'),
                        'section' => 'type_color',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_heading_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Heading Color', 'cleopa'),
                        'section' => 'type_color',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_body_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Body Color', 'cleopa'),
                        'section' => 'type_color',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_link_colors_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Link', 'cleopa'),
                        'section' => 'type_color',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_link_color' => array(
                    'settings' => array(
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Link Color', 'cleopa'),
                        'section' => 'type_color',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_link_hover_color' => array(
                    'settings' => array(
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Link Hover Color', 'cleopa'),
                        'section' => 'type_color',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_divider_colors_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Divider', 'cleopa'),
                        'section' => 'type_color',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_divider_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Divider Color', 'cleopa'),
                        'section' => 'type_color',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_top_colors_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Header Top', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_header_top_bg' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('background color', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_top_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text color', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_middle_colors_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Header Middle', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_header_middle_bg' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('background color', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_middle_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text color', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_bottom_colors_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Header Bottom', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_header_bot_bg' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('background color', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_bot_color' => array(
                    'settings' => array(
                        'default' => '#646464',
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text color', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_mainmn_colors_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Main Menu', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_header_mainmn_bg' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('background color', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_mainmn_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text color', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_mainmn_bor' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Border color', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_mainmnhover_bg' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('background hover', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_mainmnhover_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text hover', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_header_mainmnhover_bor' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('border hover', 'cleopa'),
                        'section' => 'header_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_footer_top_color_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Footer top', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_footer_top_heading' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Heading color', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_footer_top_color' => array(
                    'settings' => array(
                        'default' => '#777777',
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text color', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_footer_top_bg' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Background color', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_footer_bot_color_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Footer bottom', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_footer_bot_heading' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Heading color', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_footer_bot_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text color', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_footer_bot_bg' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Background color', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_footer_abs_color_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Footer Absolute Bottom', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_footer_abs_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text color', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_footer_abs_bg' => array(
                    'settings' => array(
                        'default' => '#1f1f1f',
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Background color', 'cleopa'),
                        'section' => 'footer_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_pb_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Primary button', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_pb_background' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Background', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_pb_background_hover' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Background Hover', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_pb_text' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_pb_text_hover' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text hover', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_pb_border' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Border', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_pb_border_hover' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Border hover', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_sb_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Secondary button', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_sb_background' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Background', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_sb_background_hover' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Background Hover', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_sb_text' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_sb_text_hover' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text hover', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_sb_border' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Border', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_sb_border_hover' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Border hover', 'cleopa'),
                        'section' => 'button_colors',
                        'type' => 'Cleopa_Customize_Control_Color',
                    ),
                ),
                'nbcore_page_title_color_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Page title', 'cleopa'),
                        'section' => 'other_colors',
                        'type' => 'Cleopa_Customize_Control_Heading'
                    ),
                ),
                'nbcore_page_title_color' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text color', 'cleopa'),
                        'section' => 'other_colors',
                        'type' => 'Cleopa_Customize_Control_Color'
                    ),
                ),
            )),
        );
    }

    public function elements()
    {
        return array(
            'title' => esc_html__('Elements', 'cleopa'),
            'priority' => 12,
            'sections' => array(
                'title_section_element' => array(
                    'title' => esc_html__('Title Section', 'cleopa')
                ),
                'button_element' => array(
                    'title' => esc_html__('Button', 'cleopa')
                ),
                'share_buttons_element' => array(
                    'title' => esc_html__('Social Share', 'cleopa')
                ),
                'pagination_element' => array(
                    'title' => esc_html__('Pagination', 'cleopa')
                ),
                'back_top_element' => array(
                    'title' => esc_html__('Back to Top Button', 'cleopa')
                ),
                'preloading_element' => array(
                    'title' => esc_html__('Preloading', 'cleopa')
                ),
            ),
            'options' => array(
                'show_title_section' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show title section', 'cleopa'),
                        'section' => 'title_section_element',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'home_page_title_section' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show Homepage title', 'cleopa'),
                        'description' => esc_html__('Turn this off to not display the title section for only homepage', 'cleopa'),
                        'section' => 'title_section_element',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_page_title_size' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Font size', 'cleopa'),
                        'section' => 'title_section_element',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '16',
                            'max' => '70',
                            'step' => '1'
                        ),
                    ),
                ),
                'nbcore_page_title_padding' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Padding top and bottom', 'cleopa'),
                        'section' => 'title_section_element',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '15',
                            'max' => '105',
                            'step' => '1'
                        ),
                    ),
                ),
                'nbcore_page_title_color_focus' => array(
                    'settings' => array(),
                    'controls' => array(
                        'section' => 'title_section_element',
                        'type' => 'Cleopa_Customize_Control_Focus',
                        'choices' => array(
                            'other_colors' => esc_html__('Edit color', 'cleopa')
                        ),
                    ),
                ),
                'nbcore_button_padding' => array(
                    'settings' => array(
                        'default' => '30',
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Padding left & right', 'cleopa'),
                        'section' => 'button_element',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '5',
                            'max' => '60',
                            'step' => '1'
                        ),
                    ),
                ),
                'nbcore_button_border_radius' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Border Radius', 'cleopa'),
                        'section' => 'button_element',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '0',
                            'max' => '50',
                            'step' => '1'
                        ),
                    ),
                ),
                'nbcore_button_border_width' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Border Width', 'cleopa'),
                        'section' => 'button_element',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '1',
                            'max' => '10',
                            'step' => '1'
                        ),
                    ),
                ),
                'share_buttons_style' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Style','cleopa'),
                        'section' => 'share_buttons_element',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            'style-1' => esc_html__('Style 1', 'cleopa'),
                            'style-2' => esc_html__('Style 2', 'cleopa'),
                        ),
                    ),
                ),
                'share_buttons_position' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Buttons position','cleopa'),
                        'section' => 'share_buttons_element',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'inside-content' => get_template_directory_uri() . '/assets/images/options/ss-inside.png',
                            'floating' => get_template_directory_uri() . '/assets/images/options/ss-floating.png',
                        ),
                    ),
                ),
                'pagination_style' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Style', 'cleopa'),
                        'section' => 'pagination_element',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            'pagination-style-1' => esc_html__('Style 1','cleopa'),
                            'pagination-style-2' => esc_html__('Style 2','cleopa'),
                        ),
                    ),
                ),
                'show_back_top' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show button', 'cleopa'),
                        'section' => 'back_top_element',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'back_top_shape' => array(
                    'settings' => array(
                        'default' => 'circle',
                        'transport' => 'postMessage',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show button', 'cleopa'),
                        'section' => 'back_top_element',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            'circle' => esc_html__('Circle','cleopa'),
                            'square' => esc_html__('Square','cleopa'),
                        ),
                    ),
                ),
                'back_top_style' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show button', 'cleopa'),
                        'section' => 'back_top_element',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            'light' => esc_html__('Light','cleopa'),
                            'dark' => esc_html__('Dark','cleopa'),
                        ),
                    ),
                ),
                'nbcore_header_preloading' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show Preloading', 'cleopa'),
                        'section' => 'preloading_element',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_header_style_preloading' => array(
                    'settings' => array(
                        'transport' => 'refresh',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Choose style preloading', 'cleopa'),
                        'section' => 'preloading_element',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'condition' => array(
                            'element' => 'nbcore_header_preloading',
                            'value' => 1,
                        ),
                        'choices' => array(
                            'demo1' => 'Square',
                            'demo2' => 'Square Zoom',
                            'demo3' => 'Square Rotate',
                            'demo4' => 'square Scale',
                            'demo5' => 'Square Shape',
                            'demo6' => 'Square Double Rotate',
                            'demo7' => 'Square Zoom & Rotate',
                            'demo8' => 'Square Dance',
                            'demo9' => 'Square Interleaved',
                            'demo10' => 'Circle Toggle',
                            'demo11' => 'Circle Zoom',
                            'demo12' => 'Circle Scroll',
                            'demo13' => 'Circle Dance',
                            'demo14' => 'Twisted',
                            'demo15' => 'Twisted Sporadic',
                        ),
                    ),
                ),
            ),
        );
    }

    public function header()
    {
        return array(
            'title' => esc_html__('Header Options', 'cleopa'),
            'description' => esc_html__('header description', 'cleopa'),
            'priority' => 11,
            'sections' => apply_filters('nbt_header_array',
                array(
                    'header_presets' => array(
                        'title' => esc_html__('Presets', 'cleopa'),
                    ),
                    'header_general' => array(
                        'title' => esc_html__('Sections', 'cleopa'),
                    ),
                )
            ),
            'options' => apply_filters('header_hook',array(
                'header_heading' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Header style', 'cleopa'),
                        'description' => esc_html__('Quickly select a preset to change your header layout.', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Heading',
                        'section' => 'header_presets',
                    ),
                ),
                'nbcore_header_style' => array(
                    'settings' => array(
                        'transport' => 'refresh',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'section' => 'header_presets',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'creative' => get_template_directory_uri() . '/assets/images/options/headers/creative.png',
                            'left-inline' => get_template_directory_uri() . '/assets/images/options/headers/left-inline.png',
                            'left-stack' => get_template_directory_uri() . '/assets/images/options/headers/left-stack.png',
                            'mid-stack' => get_template_directory_uri() . '/assets/images/options/headers/mid-stack.png',
                            'modern' => get_template_directory_uri() . '/assets/images/options/headers/modern.png',
                            'plain' => get_template_directory_uri() . '/assets/images/options/headers/plain.png',
                            'simple' => get_template_directory_uri() . '/assets/images/options/headers/simple.png',
                            'split' => get_template_directory_uri() . '/assets/images/options/headers/split.png',
                        ),
                    ),
                ),
                'nbcore_general_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('General', 'cleopa'),
                        'section' => 'header_general',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_logo_upload' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_file_image')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Site Logo', 'cleopa'),
                        'section' => 'header_general',
                        'description' => esc_html__('If you don\'t upload logo image, your site\'s logo will be the Site Title ', 'cleopa'),
                        'type' => 'WP_Customize_Upload_Control'
                    ),
                ),
                'nbcore_logo_width' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Logo Area Width', 'cleopa'),
                        'section' => 'header_general',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '100',
                            'max' => '600',
                            'step' => '10',
                        ),
                    ),
                ),
                'nbcore_header_fixed' => array(
                    'settings' => array(
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Fixed header', 'cleopa'),
                        'section' => 'header_general',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_header_text_section' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Text section', 'cleopa'),
                        'section' => 'header_general',
                        'type' => 'textarea',
                    ),
                ),
                'nbcore_header_top_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Header topbar', 'cleopa'),
                        'section' => 'header_general',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_top_section_padding' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Top & bottom padding', 'cleopa'),
                        'section' => 'header_general',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '0',
                            'max' => '45',
                            'step' => '1'
                        ),
                    ),
                ),
                'nbcore_header_middle_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Header Middle', 'cleopa'),
                        'section' => 'header_general',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_middle_section_padding' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Top & bottom padding', 'cleopa'),
                        'section' => 'header_general',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '0',
                            'max' => '45',
                            'step' => '1'
                        ),
                    ),
                ),
                'nbcore_header_bot_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Header bottom', 'cleopa'),
                        'section' => 'header_general',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_bot_section_padding' => array(
                    'settings' => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Top & bottom padding', 'cleopa'),
                        'section' => 'header_general',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '0',
                            'max' => '45',
                            'step' => '1'
                        ),
                    ),
                ),
                'nbcore_header_color_focus' => array(
                    'settings' => array(),
                    'controls' => array(
                        'section' => 'header_general',
                        'type' => 'Cleopa_Customize_Control_Focus',
                        'choices' => array(
                            'header_colors' => esc_html__('Edit color', 'cleopa'),
                        ),
                    ),
                ),
                )

            ),
        );
    }

    public function typo()
    {
        return array(
            'title' => esc_html__('Typography', 'cleopa'),
            'priority' => 14,
            'options' => array(
                'body_font_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Body Font', 'cleopa'),
                        'section' => 'typography',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'body_font_family' => array(
                    'settings' => array(
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label'   => esc_html__( 'Font Family', 'cleopa' ),
                        'dependency' => 'body_font_style',
                        'type'    => 'Cleopa_Customize_Control_Typography',
                    ),
                ),
                'body_font_style' => array(
                    'settings' => array(
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Font Styles', 'cleopa'),
                        'type'    => 'Cleopa_Customize_Control_Font_Style',
                        'choices' => array(
                            'italic' => true,
                            'underline' => true,
                            'uppercase' => true,
                            'weight' => true,
                        ),
                    ),
                ),
                'body_font_size' => array(
                    'settings' => array(
                        'sanitize_callback' => 'absint',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Font Size', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '8',
                            'max' => '30',
                            'step' => '1',
                        ),
                    ),
                ),
                'heading_font_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Heading Font', 'cleopa'),
                        'section' => 'typography',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'heading_font_family' => array( 
                    'settings' => array(
                        'sanitize_callback' => 'wp_filter_nohtml_kses'
                    ),
                    'controls' => array(
                        'label'   => esc_html__( 'Heading font', 'cleopa' ),
                        'dependency' => 'heading_font_style',
                        'type'    => 'Cleopa_Customize_Control_Typography',
                    ),
                ),
                'heading_font_style' => array(
                    'settings' => array(
                        'sanitize_callback' => 'wp_filter_nohtml_kses',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Font Styles', 'cleopa'),
                        'type'    => 'Cleopa_Customize_Control_Font_Style',
                        'choices' => array(
                            'italic' => true,
                            'underline' => true,
                            'uppercase' => true,
                            'weight' => true,
                        ),
                    ),
                ),
                'heading_base_size' => array(
                    'settings' => array(
                        'sanitize_callback' => 'absint',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Heading base size', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => 'px',
                            'min' => '10',
                            'max' => '40',
                            'step' => '1',
                        ),
                    ),
                ),
                'subset_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Font subset', 'cleopa'),
                        'description' => esc_html__('Turn these settings on if you have to support these scripts', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'subset_cyrillic' => array(
                    'settings' => array(
                        'transport' => 'refresh',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox'),
                    ),
                    'controls' => array(
                        'label'   => esc_html__( 'Cyrillic subset', 'cleopa' ),
                        'type'    => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'subset_greek' => array(
                    'settings' => array(
                        'transport' => 'refresh',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox'),
                    ),
                    'controls' => array(
                        'label'   => esc_html__( 'Greek subset', 'cleopa' ),
                        'type'    => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'subset_vietnamese' => array(
                    'settings' => array(
                        'transport' => 'refresh',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox'),
                    ),
                    'controls' => array(
                        'label'   => esc_html__( 'Vietnamese subset', 'cleopa' ),
                        'type'    => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'font_color_focus' => array(
                    'settings' => array(),
                    'controls' => array(
                        'type'    => 'Cleopa_Customize_Control_Focus',
                        'choices' => array(
                            'type_color' => esc_html__('Edit font color', 'cleopa'),
                        ),
                    ),
                ),
            ),
        );
    }

    public function woocommerce()
    {
        return array(
            'title' => esc_html__('Shop', 'cleopa'),
            'priority' => 15,
            'sections' => apply_filters('nbt_woocommerce_array',
                array(
                    'product_category' => array(
                        'title' => esc_html__('Product Category', 'cleopa'),
                    ),
                    'product_details' => array(
                        'title' => esc_html__('Product Details', 'cleopa'),
                    ),
                    'other_wc_pages' => array(
                        'title' => esc_html__('Other Pages', 'cleopa'),
                    ),
                )
            ),
            'options' => apply_filters('woocommerce_hook', array(
                'nbcore_pa_title_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Product category title', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_shop_title' => array(
                    'settings' => array(
                        'default' => esc_html__('Shop', 'cleopa'),
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'wp_kses_post'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Shop page title', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'text',
                    ),
                ),
                'nbcore_wc_breadcrumb' => array(
                    'settings' => array(
                        'default' => true,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show breadcrumb ?', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_pa_layout_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Product category layout', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_shop_sidebar' => array(
                    'settings' => array(
                        'default' => 'right-sidebar',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Sidebar Layout', 'cleopa'),
                        'section' => 'product_category',
                        'description' => esc_html__('Sidebar Position for product category and shop page', 'cleopa'),
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'left-sidebar' => get_template_directory_uri() . '/assets/images/options/2cl.png',
                            'no-sidebar' => get_template_directory_uri() . '/assets/images/options/1c.png',
                            'right-sidebar' => get_template_directory_uri() . '/assets/images/options/2cr.png',
                        ),
                    ),
                ),
                'nbcore_shop_content_width' => array(
                    'settings' => array(
                        'default' => '70',
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('WooCommerce content width', 'cleopa'),
                        'description' => esc_html__('This options also effect Cart page', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => '%',
                            'min' => '60',
                            'max' => '80',
                            'step' => '1'
                        ),
                        'condition' => array(
                            'element' => 'nbcore_shop_sidebar',
                            'value'   => '!no-sidebar',
                        )
                    ),
                ),
                'shop_sticky_sidebar' => array(
                    'settings' => array(
                        'default' => false,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Sticky Sidebar', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_product_list' => array(
                    'settings' => array(
                        'default' => 'grid-type',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Product List', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'grid-type' => get_template_directory_uri() . '/assets/images/options/grid.png',
                            'grid-type-2' => get_template_directory_uri() . '/assets/images/options/grid.png',
                            'list-type' => get_template_directory_uri() . '/assets/images/options/list.png',
                        ),
                    ),
                ),
                //TODO nbcore_product_list depencies
                'nbcore_grid_product_description' => array(
                    'settings' => array(
                        'default' => false,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Product Description', 'antimall'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Switch',
                        'condition' => array(
                            'element' => 'nbcore_product_list',
                            'value'   => 'grid-type',
                        )
                    ),
                ),
                'nbcore_loop_columns' => array(
                    'settings' => array(
                        'default' => 'three-columns',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Products per row', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'two-columns' => get_template_directory_uri() . '/assets/images/options/2-columns.png',
                            'three-columns' => get_template_directory_uri() . '/assets/images/options/3-columns.png',
                            'four-columns' => get_template_directory_uri() . '/assets/images/options/4-columns.png',
                        ),
                        'condition' => array(
                            'element' => 'nbcore_product_list',
                            'value'   => 'grid-type',
                        )
                    ),
                ),
                'nbcore_pa_other_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Other', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_shop_banner' => array(
                    'settings' => array(
                        'default' => '',
                    ),
                    'controls' => array(
                        'label' => esc_html__('Shop Banner', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'WP_Customize_Cropped_Image_Control',
                        'flex_width'  => true,
                        'flex_height' => true,
                        'width' => 2000,
                        'height' => 1000,
                    ),
                ),
                'nbcore_shop_action' => array(
                    'settings' => array(
                        'default' => true,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show shop action', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Switch'
                    ),
                ),
                'nbcore_products_per_page' => array(
                    'settings' => array(
                        'default' => '12',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Products per Page', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'number',
                        'input_attrs' => array(
                            'min'   => 1,
                            'step'  => 1,
                        ),
                    ),
                ),
                'nbcore_wc_sale' => array(
                    'settings' => array(
                        'default' => 'style-1',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Choose sale tag style', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            'style-1' => esc_html__('Style 1', 'cleopa'),
                            'style-2' => esc_html__('Style 2', 'cleopa'),
                        ),
                    ),
                ),
                'product_category_wishlist' => array(
                    'settings' => array(
                        'default' => false,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Wishlist button', 'cleopa'),
                        'description' => esc_html__('This feature need YITH WooCommerce Wishlist plugin to be installed and activated', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'product_category_quickview' => array(
                    'settings' => array(
                        'default' => false,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Quickview button', 'cleopa'),
                        'description' => esc_html__('This feature need YITH WooCommerce Quick View plugin to be installed and activated', 'cleopa'),
                        'section' => 'product_category',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_pd_layout_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Layout', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_pd_details_title' => array(
                    'settings' => array(
                        'default' => true,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Enable Product title', 'cleopa'),
                        'description' => esc_html__('Default product title is not display if the Page title is showing. Enable this to displaying both.', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_pd_details_sidebar' => array(
                    'settings' => array(
                        'default' => 'right-sidebar',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Product details sidebar', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'left-sidebar' => get_template_directory_uri() . '/assets/images/options/2cl.png',
                            'no-sidebar' => get_template_directory_uri() . '/assets/images/options/1c.png',
                            'right-sidebar' => get_template_directory_uri() . '/assets/images/options/2cr.png',
                        ),
                    ),
                ),
                'nbcore_pd_details_width' => array(
                    'settings' => array(
                        'default' => '70',
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Product details content width', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => '%',
                            'min' => '60',
                            'max' => '80',
                            'step' => '1'
                        ),
                        'condition' => array(
                            'element'   => 'nbcore_pd_details_sidebar',
                            'value'     => '!no-sidebar',
                        )
                    ),
                ),
                'product_sticky_sidebar' => array(
                    'settings' => array(
                        'default' => false,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Sticky sidebar', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_pd_meta_layout' => array(
                    'settings' => array(
                        'default' => 'left-images',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Product meta layout', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'left-images' => get_template_directory_uri() . '/assets/images/options/left-image.png',
                            'right-images' => get_template_directory_uri() . '/assets/images/options/right-image.png',
                            'wide' => get_template_directory_uri() . '/assets/images/options/wide.png',
                        ),
                    ),
                ),
                'nbcore_add_cart_style' => array(
                    'settings' => array(
                        'default' => 'style-1',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Add to cart input style', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            'style-1' => esc_html__('Style 1', 'cleopa'),
                            'style-2' => esc_html__('Style 2', 'cleopa'),
                        ),
                    ),
                ),
                'nbcore_pd_show_social' => array(
                    'settings' => array(
                        'default' => true,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show social share?', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_pd_gallery_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Product Gallery', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_pd_images_width' => array(
                    'settings' => array(
                        'default' => '50',
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Product images width', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Slider',
                        'choices' => array(
                            'unit' => '%',
                            'min' => '30',
                            'max' => '60',
                            'step' => '1'
                        ),
                        'condition' => array(
                            'element' => 'nbcore_pd_meta_layout',
                            'value'   => '!wide',
                        )
                    ),
                ),
                'nbcore_pd_featured_autoplay' => array(
                    'settings' => array(
                        'default' => false,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Featured Images Autoplay', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_pd_thumb_pos' => array(
                    'settings' => array(
                        'default' => 'bottom-thumb',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Small thumb position', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'bottom-thumb' => get_template_directory_uri() . '/assets/images/options/bottom-thumb.png',
                            'left-thumb' => get_template_directory_uri() . '/assets/images/options/left-thumb.png',
                            'inside-thumb' => get_template_directory_uri() . '/assets/images/options/inside-thumb.png',
                        ),
                    ),
                ),
                'nbcore_pd_info_tab_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Information tab', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_info_style' => array(
                    'settings' => array(
                        'default' => 'accordion-tabs',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Tab style', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            'horizontal-tabs' => esc_html__('Horizontal', 'cleopa'),
                            'accordion-tabs' => esc_html__('Accordion', 'cleopa'),
                        ),
                    ),
                ),
                'nbcore_reviews_form' => array(
                    'settings' => array(
                        'default' => 'split',
                        'transport' => 'postMessage',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Reviews form style', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            'split' => esc_html__('Split', 'cleopa'),
                            'full-width' => esc_html__('Full Width', 'cleopa'),
                        ),
                    ),
                ),
                'nbcore_reviews_round_avatar' => array(
                    'settings' => array(
                        'default' => false,
                        'transport' => 'postMessage',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Round reviewer avatar', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_other_products_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Related & Cross-sells products', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Heading',
                    ),
                ),
                'nbcore_show_upsells' => array(
                    'settings' => array(
                        'default' => true,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show upsells products?', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_pd_upsells_columns' => array(
                    'settings' => array(
                        'default' => '3',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Upsells Products per row', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            '2' => esc_html__('2 Products', 'cleopa'),
                            '3' => esc_html__('3 Products', 'cleopa'),
                            '4' => esc_html__('4 Products', 'cleopa'),
                        ),
                    ),
                ),
                'nbcore_upsells_limit' => array(
                    'settings' => array(
                        'default' => '6',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Upsells Products limit', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'number',
                        'input_attrs' => array(
                            'min' => '2',
                            'step' => '1'
                        ),
                    ),
                ),
                'nbcore_show_related' => array(
                    'settings' => array(
                        'default' => true,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show related product?', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_pd_related_columns' => array(
                    'settings' => array(
                        'default' => '3',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Related Products per row', 'cleopa'),
                        'section' => 'product_details',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            '2' => esc_html__('2 Products', 'cleopa'),
                            '3' => esc_html__('3 Products', 'cleopa'),
                            '4' => esc_html__('4 Products', 'cleopa'),
                        ),
                    ),
                ),
                'nbcore_cart_intro' => array(
                    'settings' => array(),
                    'controls' => array(
                        'label' => esc_html__('Cart', 'cleopa'),
                        'section' => 'other_wc_pages',
                        'type' => 'Cleopa_Customize_Control_Heading'
                    ),
                ),
                'nbcore_cart_layout' => array(
                    'settings' => array(
                        'default' => 'cart-layout-1',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Cart page layout', 'cleopa'),
                        'section' => 'other_wc_pages',
                        'type' => 'Cleopa_Customize_Control_Radio_Image',
                        'choices' => array(
                            'cart-layout-1' => get_template_directory_uri() . '/assets/images/options/cart-style-1.png',
                            'cart-layout-2' => get_template_directory_uri() . '/assets/images/options/cart-style-2.png',
                        ),
                    ),
                ),
                'nbcore_show_to_shop' => array(
                    'settings' => array(
                        'default' => true,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show Continue shopping button', 'cleopa'),
                        'section' => 'other_wc_pages',
                        'type' => 'Cleopa_Customize_Control_Switch',
                    ),
                ),
                'nbcore_show_cross_sells' => array(
                    'settings' => array(
                        'default' => true,
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_checkbox')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Show cross sells', 'cleopa'),
                        'section' => 'other_wc_pages',
                        'type' => 'Cleopa_Customize_Control_Switch'
                    ),
                ),
                'nbcore_cross_sells_per_row' => array(
                    'settings' => array(
                        'default' => '4',
                        'sanitize_callback' => array('Cleopa_Customize_Sanitize', 'sanitize_selection')
                    ),
                    'controls' => array(
                        'label' => esc_html__('Products per row', 'cleopa'),
                        'section' => 'other_wc_pages',
                        'type' => 'Cleopa_Customize_Control_Select',
                        'choices' => array(
                            '3' => esc_html__('3 products', 'cleopa'),
                            '4' => esc_html__('4 products', 'cleopa'),
                            '5' => esc_html__('5 products', 'cleopa'),
                        ),
                        'condition' => array(
                            'element' => 'nbcore_show_cross_sells',
                            'value'   => 1,
                        )
                    ),
                ),
                'nbcore_cross_sells_limit' => array(
                    'settings' => array(
                        'default' => '6',
                        'sanitize_callback' => 'absint'
                    ),
                    'controls' => array(
                        'label' => esc_html__('Cross sells Products limit', 'cleopa'),
                        'section' => 'other_wc_pages',
                        'type' => 'Cleopa_Customize_Control_Number',
                        'input_attrs' => array(
                            'min' => '3',
                            'step' => '1'
                        ),
                        'condition' => array(
                            'element' => 'nbcore_show_cross_sells',
                            'value'   => 1,
                        )
                    ),
                ),
            )),
        );
    }
}