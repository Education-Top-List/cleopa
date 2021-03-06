<?php
class Cleopa_Customize_Control_Typography extends WP_Customize_Control {
    public $type = 'typography';

    public $dependency;

    public function enqueue()
    {
        static $enqueued;

        //TODO min css and js
        if( !isset($enqueued) ) {
            wp_enqueue_script(
                'chosen',
                get_template_directory_uri() . '/assets/vendor/chosen/chosen.jquery.min.js',
                array('jquery'),
                '1.6.2',
                true
            );
            wp_enqueue_style(
                'chosen',
                get_template_directory_uri() . '/assets/vendor/chosen/chosen.css',
                array(),
                '1.6.2'
            );
            wp_enqueue_script(
                'nb-customize-typography',
                get_template_directory_uri() . '/assets/netbase/js/admin/typography.min.js',
                array('jquery', 'chosen'),
                Cleopa_VER,
                true
            );
            wp_localize_script('nb-customize-typography', 'nbUpload', array(
                'form_heading' => esc_html__('Select Font', 'cleopa'),
                'button' => esc_html__('Select', 'cleopa'),
                'error_message' => esc_html__('Please choose only ONE font', 'cleopa'),
            ));

            $enqueued = true;
        }

    }

    public function render_content()
    {
        $font_args = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value();
        $google_fonts = Cleopa_Helper::google_fonts();
        $standard_fonts = array(
                'Verdana', 'Georgia', 'Courier New', 'Arial', 'Tahoma', 'Trebuchet MS'
        ); ?>

        <div class="customize-control-content customize-control-typography" id="nb-<?php echo esc_attr($this->type)?>-<?php echo esc_attr($this->id)?>">
            <?php if( !empty($this->label) ): ?>
            <span class="customize-control-title">
                <?php echo esc_html($this->label); ?>
            </span>
            <?php endif;
            if( !empty($this->description) ): ?>
            <span class="description customize-control-description">
                <?php echo esc_html($this->description); ?>
            </span>
            <?php endif; ?>
            <div class="nb-select-type-wrap">
                <select name="select-font-type" class="nb-select">
                    <option value="google" <?php selected($font_args[0], 'google'); ?>><?php esc_html_e('Google fonts', 'cleopa'); ?></option>
                    <option value="custom" <?php selected($font_args[0], 'custom'); ?>><?php esc_html_e('Custom fonts', 'cleopa'); ?></option>
                    <option value="standard" <?php selected($font_args[0], 'standard'); ?>><?php esc_html_e('Standard fonts', 'cleopa'); ?></option>
                </select>
            </div>
            <div class="google-fonts-wrap">
                <select name="google-fonts-select" class="nb-select">
                <?php
                foreach($google_fonts as $k => $v) {
                    printf('<option value="%s" %s>%s</option>', $k, selected($font_args[1], $k, false), $k);
                } ?>
                </select>
            </div>
            <div class="nb-custom-fonts">
                <button type="button" class="button upload-font"><?php esc_html_e('Select fonts', 'cleopa'); ?></button>
                <p class="custom-font-message">
                    <span class="custom-font-info"><?php esc_html_e('Info:', 'cleopa'); ?></span>
                </p>
            </div>
            <div class="nb-select-standard-wrap">
                <select name="standard-fonts-select" class="nb-select">
                    <?php
                    foreach($standard_fonts as $font) {
                        printf('<option value="%s" %s>%s</option>', $font, selected($font_args[1], $font, false), $font);
                    }
                    ?>
                </select>
            </div>
            <input class="font-holder" type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $font_args ) ); ?>" data-dependency="<?php echo esc_attr($this->dependency); ?>"/>
        </div>
        <?php
    }
}