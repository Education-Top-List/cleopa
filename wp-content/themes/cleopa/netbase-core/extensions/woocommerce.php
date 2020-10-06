<?php
/**
 * Extend and customize WooCommerce
 */
class Cleopa_Extensions_WooCommerce {
	protected static $init = false;
 
	public function __construct()
	{
		//TODO Fix this Cheat
		$product_list = cleopa_get_options('nbcore_product_list');

		remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
		remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
		remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs');
		remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
		remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
		remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		add_filter( 'woocommerce_before_main_content', 'cleopa_page_title', 5 );
		add_filter( 'loop_shop_columns', array($this, 'loop_columns') );
		add_filter( 'loop_shop_per_page', array($this, 'products_per_page'), 20 );
		add_filter( 'woocommerce_pagination_args', array($this, 'woocommerce_pagination') );
		add_filter('woocommerce_product_description_heading', '__return_empty_string');
		add_filter('woocommerce_product_additional_information_heading', '__return_empty_string');
		add_filter('woocommerce_review_gravatar_size', array($this, 'wc_review_avatar_size'));
		add_filter('woocommerce_cross_sells_total', array($this, 'cross_sells_limit'));
		add_filter('woocommerce_upsells_total', array($this, 'upsells_limit'));
		add_filter('yith_add_quick_view_button_html', array($this, 'quickview_button'), 10, 3);
		add_filter('yith_quick_view_loader_gif', '__return_empty_string');
		add_filter( 'option_yith_woocompare_button_text',  array($this, 'compare_button_text'), 99 );
		add_action('woocommerce_after_shop_loop_item', array($this, 'product_action_div_open'), 6);
		add_action('woocommerce_after_shop_loop_item', array($this, 'product_action_div_close'), 50);
		add_action('woocommerce_after_shop_loop_item', array($this, 'wishlist_button'), 20);
		add_action('woocommerce_after_add_to_cart_button', array(__CLASS__, 'wishlist_button_single'), 10);
		add_action('woocommerce_shop_loop_item_title', array($this, 'product_title'), 10);
		add_action('woocommerce_before_main_content', array($this, 'shop_banner'), 15);
		add_action('woocommerce_after_shop_loop_item_title', array(__CLASS__, 'product_desc'), 15);
		add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15);
		add_action('woocommerce_single_product_summary', array($this, 'wide_meta_left_div_open'), 9);
		add_action('woocommerce_single_product_summary', array($this, 'wide_meta_left_div_close'), 24);
		add_action('woocommerce_single_product_summary', array($this, 'wide_meta_right_div_open'), 26);
		add_action('woocommerce_single_product_summary', array($this, 'wide_meta_right_div_close'), 55);
		add_action('woocommerce_share', array($this, 'wc_share_social'));
		add_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 15);
		add_action('wp_footer', array($this, 'add_cart_notice'));

        //TODO Fix this hack?
        add_filter('woocommerce_in_cart_product', array($this, 'remove_wishlist_quickview'), 50, 1 );
        $attrs = cleopa_get_options('nbcore_pa_swatch_style');
		if ($attrs && $attrs!=''):
			$attrs = (array)json_decode($attrs);
		endif;
        if(is_array($attrs))
        {
            foreach($attrs as $key => $val)
            {
                if($val)
                {
                    add_action( $key.'_add_form_fields', array( __CLASS__, 'add_attribute_fields' ) );
                    add_action( $key.'_edit_form_fields', array( __CLASS__, 'edit_attribute_fields' ), 10 );
                    add_action( 'created_term', array( __CLASS__, 'save_attribute_fields' ), 10, 3 );
                    add_action( 'edit_term', array( __CLASS__, 'save_attribute_fields' ), 10, 3 );
                }
            }
        }
		
		add_action( 'wp_ajax_woocommerce_add_to_cart_variable_rc', array( __CLASS__, 'woocommerce_add_to_cart_variable_rc_callback' ));
		add_action( 'wp_ajax_nopriv_woocommerce_add_to_cart_variable_rc', array( __CLASS__, 'woocommerce_add_to_cart_variable_rc_callback' ));
	}

	public function compare_button_text( $button_text ){
        return '<i class="fa fa-refresh"></i><span class="tooltip">'.esc_html($button_text).'</span>';
    }
	
	public static function remove_wishlist_quickview($a){
        add_filter('yith_add_quick_view_button_html',  '__return_empty_string', 50, 3);
    }

	public static function loop_columns()
	{
		return cleopa_get_options('nbcore_loop_columns');
	}

	public static function product_action_div_open()
	{
		echo '<div class="product-action">';
	}

	public static function product_action_div_close()
	{
		echo '</div>';
	}

    public static function wishlist_button()
    {
        if(cleopa_get_options('product_category_wishlist')) {
            if ( class_exists( 'YITH_WCWL' ) ) {
                echo '<div class="wishlist-btn button bt-4">' . do_shortcode( '[yith_wcwl_add_to_wishlist]' ) . '</div>';
            }
        }
	}

    public static function wishlist_button_single()
    {
        if( class_exists( 'YITH_WCWL' ) ) {
            if ( get_option( 'yith_wcwl_button_position' ) == 'shortcode' ) {
                echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
            }
        }
	}

	public static function product_title()
	{
		echo '<h4 class="product-title"><a href="' . esc_url(get_the_permalink()) . '">' . esc_html(get_the_title()) . '</a></h4>';
	}

	public static function product_desc()
	{
		global $product_desc, $product_desc_limit;
		$product_desc = isset($product_desc) ? $product_desc : (cleopa_get_options('nbcore_grid_product_description') ? cleopa_get_options('nbcore_grid_product_description') : '');
		$product_desc_limit = isset($product_desc_limit) ? $product_desc_limit : '';
		if($product_desc):
			if (get_the_excerpt()):
				if ($product_desc_limit):
					echo '<p class="product-description">' . wp_trim_words(get_the_excerpt(),$product_desc_limit, '...') . '</p>';
				else :
					echo '<p class="product-description">' . esc_html(get_the_excerpt()) . '</p>';
				endif;
			else :
				if ($product_desc_limit):
					echo '<p class="product-description">' . wp_trim_words(get_the_content(),$product_desc_limit, '...') . '</p>';
				else :
					echo '<p class="product-description">' . wp_trim_words(get_the_content(),15, '...') . '</p>';
				endif;
			endif;
		endif;
	}

	public static function products_per_page($cols)
	{
		return cleopa_get_options('nbcore_products_per_page');
	}

	public static function woocommerce_pagination()
	{
		return array(
			'prev_text' => '<i class="icon-left-open"></i>',
			'next_text' => '<i class="icon-right-open"></i>',
			'end_size' => 1,
			'mid_size' => 1,
		);
	}

	public static function product_description()
	{
		echo '<p class="product-description">' . esc_html(get_the_excerpt()) . '</p>';
	}

	public static function product_category()
	{
		global $post;
		$terms = get_the_terms( $post->ID, 'product_cat' );
		foreach ($terms as $term) {
			echo '<a class="product-category-link" href="' . esc_url(get_term_link($term->term_id)) . '">' . esc_html($term->name) . '</a>';
		}
	}

	public static function shop_banner()
	{
		if(function_exists( 'is_shop' ) && is_shop()) {
			$shop_banner_url = cleopa_get_options('nbcore_shop_banner');
			if ($shop_banner_url) {
				echo '<div class="shop-banner"><img src="' . esc_url(wp_get_attachment_url(absint($shop_banner_url))) . '" /></div>';
			}
		}
	}

	public static function wc_review_avatar_size()
	{
		return '80';
	}

	public static function wide_meta_left_div_open()
	{
		if('wide' === cleopa_get_options('nbcore_pd_meta_layout')) {
			echo '<div class="pd-meta-left">';
		}
	}

	public static function wide_meta_left_div_close()
	{
		if('wide' === cleopa_get_options('nbcore_pd_meta_layout')) {
			echo '</div>';
		}
	}

	public static function wide_meta_right_div_open()
	{
		if('wide' === cleopa_get_options('nbcore_pd_meta_layout')) {
			echo '<div class="pd-meta-right">';
		}
	}

	public static function wide_meta_right_div_close()
	{
		if('wide' === cleopa_get_options('nbcore_pd_meta_layout')) {
			echo '</div>';
		}
	}

	public static function wc_share_social()
	{
		if(cleopa_get_options('nbcore_pd_show_social')) {
			if (function_exists('nbcore_share_social')){
				$style = cleopa_get_options('share_buttons_style');
				$position = cleopa_get_options('share_buttons_position');
				nbcore_share_social($style,$position);
			}
		}
	}

	public static function cross_sells_limit()
	{
		$cross_sells_limit = cleopa_get_options('nbcore_cross_sells_limit');
		return $cross_sells_limit;
	}

	public static function upsells_limit()
	{
		$upsells_limit = cleopa_get_options('nbcore_upsells_limit');
		return $upsells_limit;
	}

    public static function quickview_button($button, $label, $product)
    {
        $html = '';
        if(cleopa_get_options('product_category_quickview')) {
            global $product;

            $product_id = yit_get_prop( $product, 'id', true );

            $html = '<a href="#" class="button yith-wcqv-button bt-4" data-product_id="' . $product_id . '"><i class="icon-resize"></i><span class="tooltip">' . $label . '</span></a>';

        }
        return $html;
	}

	public function upload_scripts()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
    }
	
	public static function add_attribute_fields() {

        ?>

        <div class="form-field">
            <label><?php esc_html_e( 'Thumbnail', 'cleopa' ); ?></label>
            <div id="product_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
            <div style="line-height: 60px;">
                <input type="hidden" name="is_attribute" value="1">
                <input type="hidden" id="product_attribute_thumbnail_id" name="product_attribute_thumbnail_id" />
                <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'cleopa' ); ?></button>
                <button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'cleopa' ); ?></button>
            </div>
			
			<?php
			$inline_script = '( function( $ ) {'
				. '"use strict";'
				. 'if ( ! $( "#product_attribute_thumbnail_id" ).val() ) {'
					. '$( ".remove_image_button" ).hide();'
				. '}'
				. 'var file_frame;'
				. ' $( document ).on( "click", ".upload_image_button", function( event ) {'
					. 'event.preventDefault();'
					. 'if ( file_frame ) {'
						. 'file_frame.open();'
						. 'return;'
					. '}'
					. 'file_frame = wp.media.frames.downloadable_file = wp.media({'
						. 'title: "' . esc_html__( "Choose an image", 'cleopa' ) . '",'
						. 'button: {'
							. 'text: "' . esc_html__( "Use image", 'cleopa' ) . '"'
						. '},'
						. 'multiple: false'
					. '});'
					. 'file_frame.on( "select", function() {'
						. 'var attachment = file_frame.state().get( "selection" ).first().toJSON();'
						. '$( "#product_attribute_thumbnail_id" ).val( attachment.id );'
						. 'if (typeof attachment.sizes.thumbnail !== "undefined"){'
							. '$( "#product_cat_thumbnail img" ).attr( "src", attachment.sizes.thumbnail.url );'
						. '} else {'
							. '$( "#product_cat_thumbnail img" ).attr( "src", attachment.url );'
						. '}'
						. '$( ".remove_image_button" ).show();'
					. '});'
					. 'file_frame.open();'
				. '});'
				. '$( document ).on( "click", ".remove_image_button", function() {'
					. '$( "#product_cat_thumbnail img" ).attr( "src", "' . esc_js( wc_placeholder_img_src() ) . '" );'
					. '$( "#product_attribute_thumbnail_id" ).val( "" );'
					. '$( ".remove_image_button" ).hide();'
					. 'return false;'
				. '});'
			. '} )( jQuery );';
			wp_enqueue_script( 'cleopa_admin_inline_script' );
			wp_add_inline_script( 'cleopa_admin_inline_script', $inline_script );
			?>
            <div class="clear"></div>
        </div>
    <?php
    }
	
	public static function edit_attribute_fields( $term ) {
        $thumbnail_id = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );

        if ( $thumbnail_id ) {
            $image = wp_get_attachment_thumb_url( $thumbnail_id );
        } else {
            $image = wc_placeholder_img_src();
        }
        ?>

        <tr class="form-field">
            <th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'cleopa' ); ?></label></th>
            <td>
                <div id="product_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
                <div style="line-height: 60px;">
                    <input type="hidden" name="is_attribute" value="1">
                    <input type="hidden" id="product_attribute_thumbnail_id" name="product_attribute_thumbnail_id" value="<?php echo esc_attr($thumbnail_id); ?>" />
                    <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'cleopa' ); ?></button>
                    <button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'cleopa' ); ?></button>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
		<?php
		$inline_script = '( function( $ ) {'
			. '"use strict";'
			. 'if ( "0" === $( "#product_attribute_thumbnail_id" ).val() ) {'
				. '$( ".remove_image_button" ).hide();'
			. '}'
			. 'var file_frame;'
			. '$( document ).on( "click", ".upload_image_button", function( event ) {'
				. 'event.preventDefault();'
				. 'if ( file_frame ) {'
					. 'file_frame.open();'
					. 'return;'
				. '}'
				. 'file_frame = wp.media.frames.downloadable_file = wp.media({'
					. 'title: "' . esc_html__( "Choose an image", 'cleopa' ) . '",'
					. 'button: {'
						. 'text: "' . esc_html__( "Use image", 'cleopa' ) . '"'
					. '},'
					. 'multiple: false'
				. '});'
				. 'file_frame.on( "select", function() {'
					. 'var attachment = file_frame.state().get( "selection" ).first().toJSON();'
					. '$( "#product_attribute_thumbnail_id" ).val( attachment.id );'
					. '$( "#product_cat_thumbnail img" ).attr( "src", attachment.url );'
					. '$( ".remove_image_button" ).show();'
				. '});'
				. 'file_frame.open();'
			. '});'
			. '$( document ).on( "click", ".remove_image_button", function() {'
				. '$( "#product_cat_thumbnail img" ).attr( "src", "' . esc_js( wc_placeholder_img_src() ) . '" );'
				. '$( "#product_attribute_thumbnail_id" ).val( "" );'
				. '$( ".remove_image_button" ).hide();'
				. 'return false;'
			. '});'
		. '} )( jQuery );';
		wp_enqueue_script( 'cleopa_admin_inline_script' );
		wp_add_inline_script( 'cleopa_admin_inline_script', $inline_script );
		?>
    <?php
    }
	
    public function save_attribute_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
        if ( isset( $_POST['product_attribute_thumbnail_id'] ) && isset($_POST['is_attribute']) && $_POST['is_attribute'] == 1 ) {
            update_woocommerce_term_meta( $term_id, 'thumbnail_id', absint( $_POST['product_attribute_thumbnail_id'] ) );
        }
    }
	
	function woocommerce_add_to_cart_variable_rc_callback() {
		
		ob_start();
		
		$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$quantity = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );
		$variation_id = $_POST['variation_id'];
		$variation  = $_POST['variation'];
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
	
		if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation  ) ) {
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );
			if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
				wc_add_to_cart_message( $product_id );
			}
	
			// Return fragments
			WC_AJAX::get_refreshed_fragments();
		} else {
			$this->json_headers();
	
			// If there was an error adding to the cart, redirect to the product page to show any errors
			$data = array(
				'error' => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
				);
			echo json_encode( $data );
		}
		die();
	}

	public static function add_cart_notice()
    {
        global $woocommerce;
        // $url = $woocommerce->cart->get_cart_url();
        ?>
        <div class="cart-notice-wrap">
            <div class="cart-notice">
                <p><?php esc_html_e('Product has been added to cart', 'cleopa'); ?></p>
                <p class="cart-url button"><a href="<?php echo wc_get_cart_url(); ?>"><?php esc_html_e('View Cart', 'cleopa'); ?></a></p>
                <span><i class="icon-cancel-circle"></i></span>
            </div>
        </div>
        <?php
    }
}