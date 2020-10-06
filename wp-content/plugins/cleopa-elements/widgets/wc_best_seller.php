<?php
class WC_Best_Seller extends WP_Widget {
    function __construct()
    {
        parent::__construct(
            'nbcore_bestseller',
            esc_html__('Foody - Bestseller', 'core-wp'),
            array( 'description' => esc_html__( 'Simple widget to display best seller products', 'core-wp' ), )
        );
    }

    public function widget($args, $instance)
    {
        extract($args);

        $title = apply_filters('widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);
        $posts_per_page = empty( $instance['posts_per_page'] ) ? 5 : ( int ) $instance['posts_per_page'];

        echo wp_kses_post( $before_widget );

        if ( ! empty( $title ) ) {
            echo wp_kses_post( $before_title . $title . $after_title );
        }

        $meta_query = WC()->query->get_meta_query();

        $atts = array(
            'orderby' => 'title',
            'order'   => 'asc'
        );

        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $posts_per_page,
            'meta_key'            => 'total_sales',
            'orderby'             => 'meta_value_num',
            'meta_query'          => $meta_query
        );

        $products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, $atts));

        if ( $products->have_posts() ) {
            echo apply_filters( 'woocommerce_before_widget_product_list', '<ul class="product_list_widget">' );

            while ( $products->have_posts() ) {
                $products->the_post();
                wc_get_template( 'content-widget-product.php', array( 'show_rating' => true ) );
            }

            echo apply_filters( 'woocommerce_after_widget_product_list', '</ul>' );
        }

        wp_reset_postdata();

        echo wp_kses_post( $after_widget );
    }

    public function form($instance)
    {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Best sellers', 'core-wp' );
        $posts_per_page = ! empty( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'core-wp' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php esc_attr_e( 'Posts per page:', 'core-wp' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="text" value="<?php echo esc_attr( $posts_per_page ); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['posts_per_page'] = strip_tags($new_instance['posts_per_page']);

        return $instance;
    }
}