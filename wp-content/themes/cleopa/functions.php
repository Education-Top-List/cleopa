<?php

/**
 * Netbaseteam core
 */
require get_template_directory() . '/netbase-core/core.php';
add_filter( 'widget_text', 'do_shortcode');

   // Keep old Editor
add_filter('use_block_editor_for_post', '__return_false');