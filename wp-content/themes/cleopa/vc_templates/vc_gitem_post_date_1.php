<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$atts = shortcode_atts(
        array(
    'el_style' => 'default',
    'el_align' => '',
    'el_class' => '',
    'css' => ''
        ), $atts, 'vc_gitem_post_date_1'
);
switch ($atts['el_style']):
    case 'style1':
        $post_d = '{{post_date1}}';
        break;
    default :
        $post_d = '{{post_date}}';
        break;
endswitch;
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_blog_date ' . $atts['el_class'] . vc_shortcode_custom_css_class($css, ' '), $this->settings['base'], $atts);


$css_class = 'vc_blog_date vc_gitem-post-data vc_gitem-post-data-source-post_date';
$css_class = implode(' ', array($css_class, $atts['el_class'], 'text-' . $atts['el_align']));
$output .= '<div class="' . esc_attr($css_class) . '" >';
$output .= $post_d;
$output .= '</div>';
return $output;
