<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cleopa
 */

?>

		</div><!-- #content -->
		<footer id="colophon" class="site-footer">
            <?php if(cleopa_get_options('nbcore_show_footer_top')):
				if (is_active_sidebar( 'footer-top-1' ) || is_active_sidebar( 'footer-top-2' ) || is_active_sidebar( 'footer-top-3' ) || is_active_sidebar( 'footer-top-4' ) ):
					$top_layout = cleopa_get_options('nbcore_footer_top_layout');
					?>
					<div class="footer-top-section<?php echo ($top_layout ? (' ' . $top_layout) : ''); ?>">
						<div class="container">
							<div class="row">
								<?php
								switch ($top_layout) {
									case 'layout-1':
										echo '<div class="col-12">';
										dynamic_sidebar('footer-top-1');
										echo '</div>';
										break;
									case 'layout-2':
										echo '<div class="col-6">';
										dynamic_sidebar('footer-top-1');
										echo '</div>';
										echo '<div class="col-6">';
										dynamic_sidebar('footer-top-2');
										echo '</div>';
										break;
									case 'layout-3':
										echo '<div class="col-8">';
										dynamic_sidebar('footer-top-1');
										echo '</div>';
										echo '<div class="col-4">';
										dynamic_sidebar('footer-top-2');
										echo '</div>';
										break;
									case 'layout-4':
										echo '<div class="col-4">';
										dynamic_sidebar('footer-top-1');
										echo '</div>';
										echo '<div class="col-8">';
										dynamic_sidebar('footer-top-2');
										echo '</div>';
										break;
									case 'layout-5':
										echo '<div class="col-4">';
										dynamic_sidebar('footer-top-1');
										echo '</div>';
										echo '<div class="col-4">';
										dynamic_sidebar('footer-top-2');
										echo '</div>';
										echo '<div class="col-4">';
										dynamic_sidebar('footer-top-3');
										echo '</div>';
										break;
									case 'layout-6':
										echo '<div class="col-6">';
										dynamic_sidebar('footer-top-1');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-top-2');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-top-3');
										echo '</div>';
										break;
									case 'layout-7':
										echo '<div class="col-3">';
										dynamic_sidebar('footer-top-1');
										echo '</div>';
										echo '<div class="col-6">';
										dynamic_sidebar('footer-top-2');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-top-3');
										echo '</div>';
										break;
									case 'layout-8':
										echo '<div class="col-3">';
										dynamic_sidebar('footer-top-1');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-top-2');
										echo '</div>';
										echo '<div class="col-6">';
										dynamic_sidebar('footer-top-3');
										echo '</div>';
										break;
									default :
										echo '<div class="col-3">';
										dynamic_sidebar('footer-top-1');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-top-2');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-top-3');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-top-4');
										echo '</div>';
								}
								?>
							</div>
						</div>
					</div>
				<?php endif;
			endif;?>
            <?php if(cleopa_get_options('nbcore_show_footer_bot')):
				if (is_active_sidebar( 'footer-bot-1' ) || is_active_sidebar( 'footer-bot-2' ) || is_active_sidebar( 'footer-bot-3' ) || is_active_sidebar( 'footer-bot-4' ) ):
					$bot_layout = cleopa_get_options('nbcore_footer_bot_layout'); ?>
					<div class="footer-bot-section<?php echo ($bot_layout ? (' ' . $bot_layout) : ''); ?>">
						<div class="container">
							<div class="row">
								<?php
								switch ($bot_layout) {
									case 'layout-1':
										echo '<div class="col-12">';
										dynamic_sidebar('footer-bot-1');
										echo '</div>';
										break;
									case 'layout-2':
										echo '<div class="col-6">';
										dynamic_sidebar('footer-bot-1');
										echo '</div>';
										echo '<div class="col-6">';
										dynamic_sidebar('footer-bot-2');
										echo '</div>';
										break;
									case 'layout-3':
										echo '<div class="col-8">';
										dynamic_sidebar('footer-bot-1');
										echo '</div>';
										echo '<div class="col-4">';
										dynamic_sidebar('footer-bot-2');
										echo '</div>';
										break;
									case 'layout-4':
										echo '<div class="col-4">';
										dynamic_sidebar('footer-bot-1');
										echo '</div>';
										echo '<div class="col-8">';
										dynamic_sidebar('footer-bot-2');
										echo '</div>';
										break;
									case 'layout-5':
										echo '<div class="col-4">';
										dynamic_sidebar('footer-bot-1');
										echo '</div>';
										echo '<div class="col-4">';
										dynamic_sidebar('footer-bot-2');
										echo '</div>';
										echo '<div class="col-4">';
										dynamic_sidebar('footer-bot-3');
										echo '</div>';
										break;
									case 'layout-6':
										echo '<div class="col-6">';
										dynamic_sidebar('footer-bot-1');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-bot-2');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-bot-3');
										echo '</div>';
										break;
									case 'layout-7':
										echo '<div class="col-3">';
										dynamic_sidebar('footer-bot-1');
										echo '</div>';
										echo '<div class="col-6">';
										dynamic_sidebar('footer-bot-2');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-bot-3');
										echo '</div>';
										break;
									case 'layout-8':
										echo '<div class="col-3">';
										dynamic_sidebar('footer-bot-1');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-bot-2');
										echo '</div>';
										echo '<div class="col-6">';
										dynamic_sidebar('footer-bot-3');
										echo '</div>';
										break;
									default :
										echo '<div class="col-3">';
										dynamic_sidebar('footer-bot-1');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-bot-2');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-bot-3');
										echo '</div>';
										echo '<div class="col-3">';
										dynamic_sidebar('footer-bot-4');
										echo '</div>';
								}
								?>
							</div>
						</div>
					</div>
				<?php endif;
			endif;
			$left_content = cleopa_get_options('nbcore_footer_abs_left_content');
			$right_content = cleopa_get_options('nbcore_footer_abs_right_content');
			if( ( $left_content && '' !== $left_content ) || ( $right_content && '' !== $right_content ) ):
			?>
				<div class="footer-abs-section">
					<div class="container">
						<div class="row">
							<?php
							if( ( $left_content && '' !== $left_content ) && ( $right_content && '' !== $right_content ) ) {
								echo '<p class="footer-abs-left">' . $left_content . '</p>';
								echo '<p class="footer-abs-right">' . $right_content . '</p>';
							} elseif( ( !$left_content || '' == $left_content ) && ( $right_content && '' !== $right_content ) ) {
								echo '<p class="footer-abs-middle">' . $right_content . '</p>';
							} elseif( ( $left_content && '' !== $left_content ) && ( !$right_content || '' == $right_content ) ) {
								echo '<p class="footer-abs-middle">' . $left_content . '</p>';
							}
							?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</footer><!-- #colophon -->
    <?php
    if('floating' === cleopa_get_options('share_buttons_position')) {
        if(cleopa_get_options('nbcore_blog_single_show_social') && function_exists('nbcore_share_social')) {
            $style = cleopa_get_options('share_buttons_style');
			$position = cleopa_get_options('share_buttons_position');
			nbcore_share_social($style,$position);
        }
    }
    if(cleopa_get_options('show_back_top')) {
        cleopa_back_to_top();
    }
    ?>
	</div><!-- #site-wrapper -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
