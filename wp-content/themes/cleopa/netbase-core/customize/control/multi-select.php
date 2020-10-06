<?php

class Cleopa_Customize_Control_Multi_Select extends WP_Customize_Control {

    /**
     * Declare the control type.
     *
     * @access public
     * @var string
     */
    public $type = 'checkbox-list';

    /**
     * Enqueue scripts and styles for the custom control.
     *
     * @access public
     */
    public function enqueue() {
        static $enqueued;

        if (!isset($enqueued)) {

            $enqueued = true;
        }
    }

    /**
     * Render the control to be displayed in the Customizer.
     */
    public function render_content() {

        if ( empty( $this->choices ) )
            return;
		?>

        <?php if ( !empty( $this->label ) ) : ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php endif; ?>

        <?php if ( !empty( $this->description ) ) : ?>
            <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
        <?php endif; ?>
        
        <select <?php $this->link(); ?>  multiple="multiple">
			<?php
			foreach ( $this->choices as $value => $label )
				echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
			?>
		</select>
    <?php }

}
?>