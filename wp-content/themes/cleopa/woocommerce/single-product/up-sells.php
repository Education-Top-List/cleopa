<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$product_list = cleopa_get_options('nbcore_product_list') ? cleopa_get_options('nbcore_product_list') : 'grid-type';
if ( $upsells ) : ?>

	<section class="up-sells upsells">

		<h2><?php esc_html_e( 'You may also like&hellip;', 'cleopa' ) ?></h2>

		<div class="products swiper-container">
            <div class="swiper-wrapper">
			<?php foreach ( $upsells as $upsell ) : ?>

				<?php
				 	$post_object = get_post( $upsell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

				?>

				<div <?php post_class('swiper-slide'); ?>>
					<?php wc_get_template('netbase/content-product/' . esc_attr($product_list) . '.php'); ?>
				</div>

			<?php endforeach; ?>
            </div>
		</div>
        <div class="swiper-pagination"></div>
	</section>

<?php endif;

wp_reset_postdata();
