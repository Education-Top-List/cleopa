<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product, $product_list;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
$product_list = $product_list ? $product_list : (cleopa_get_options('nbcore_product_list') ? cleopa_get_options('nbcore_product_list') : 'grid-type');
if (!file_exists(get_template_directory() . '/woocommerce/netbase/content-product/' . esc_attr($product_list) . '.php')){
	$product_list = 'grid-type';
}
?>
<div <?php post_class(); ?>>
    <?php wc_get_template('netbase/content-product/' . esc_attr($product_list) . '.php'); ?>
</div>
