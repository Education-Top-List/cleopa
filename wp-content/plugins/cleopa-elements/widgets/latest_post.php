<?php
// Creating the widget
class NB_latest_post_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
                // Base ID of your widget
                'foodlife_latest_post_widget',
                // Widget name will appear in UI
                __('Foody - Latest Posts Widget', 'nb-fw'),
                // Widget description
                array('description' => __('Latest Posts Widget', 'nb-fw'),)
        );
    }

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $limit = (int) $instance['limit'];
        $words = (int) $instance['words'];
        $readmore = $instance['readmore'];
        $latest_posts = new WP_Query(apply_filters('widget_posts_args', array(
                    'posts_per_page' => $limit,
                    'no_found_rows' => true,
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true
        )));
        if ($latest_posts->have_posts()) :
            echo (string) $args['before_widget'];
            if (!empty($title)):
                echo (string) $args['before_title'] . $title . $args['after_title'];
            endif;
            ?>
            <div class="nb_latest_post">
                <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                    <div class="nb_latest_item">
                        <?php if (has_post_thumbnail() && !post_password_required() && !is_attachment()) :
							$img_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
						?>
                            <a class="nb_post_thumb" href="<?php the_permalink(); ?>">
                                <?php //the_post_thumbnail('thumbnail'); ?>
								<span style="background-image:url(<?php echo $img_src[0]; ?>);"></span>
                            </a>
                        <?php endif; ?>
                        <div class="nb_post_desc">
                            <h4 class="nb_post_title">
                                <a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
                            </h4>
                            <?php
                            if (has_excerpt()) :
                                echo '<p>' . wp_trim_words(get_the_excerpt(), $words, '...') . '</p>';
                            else :
                                echo '<p>' . wp_trim_words(get_the_content(), $words, '...') . '</p>';
                            endif;
                            if (!empty($readmore) && $readmore!=''):
                                echo '<a class="nb_post_lnk" href="'.get_permalink().'">'. $readmore . '</a>';
                            endif;
                            ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php
            echo (string) $args['after_widget'];
        endif;
    }

    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Latest Post', 'nb-fw');
        }
        if (isset($instance['limit'])) {
            $limit = $instance['limit'];
        } else {
            $limit = 5;
        }
        if (isset($instance['words'])) {
            $words = $instance['words'];
        } else {
            $words = 15;
        }
        if (isset($instance['readmore'])) {
            $readmore = $instance['readmore'];
        } else {
            $readmore = '';
        }
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'nb-fw'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('limit')); ?>"><?php _e('Total items:', 'nb-fw'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('limit')); ?>" name="<?php echo esc_attr($this->get_field_name('limit')); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('words')); ?>"><?php _e('Content words limit:', 'nb-fw'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('words')); ?>" name="<?php echo esc_attr($this->get_field_name('words')); ?>" type="text" value="<?php echo esc_attr($words); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('readmore')); ?>"><?php _e('Read more text:', 'nb-fw'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('readmore')); ?>" name="<?php echo esc_attr($this->get_field_name('readmore')); ?>" type="text" value="<?php echo esc_attr($readmore); ?>" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['limit'] = (!empty($new_instance['limit']) ) ? (int) $new_instance['limit'] : '5';
        $instance['words'] = (!empty($new_instance['words']) ) ? (int) $new_instance['words'] : '15';
        $instance['readmore'] = (!empty($new_instance['readmore']) ) ? strip_tags($new_instance['readmore']) : '';

        return $instance;
    }

}
