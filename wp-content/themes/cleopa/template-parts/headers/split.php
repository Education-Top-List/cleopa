<?php
$text_section_content = cleopa_get_options('nbcore_header_text_section');
$social_section_content = (cleopa_get_options('nbcore_header_facebook') || cleopa_get_options('nbcore_header_twitter') || cleopa_get_options('nbcore_header_linkedin') || cleopa_get_options('nbcore_header_instagram') || cleopa_get_options('nbcore_header_blog') || cleopa_get_options('nbcore_header_pinterest') || cleopa_get_options('nbcore_header_ggplus'));
$midlayout = 'logo-center';
if ($social_section_content && !$text_section_content){
	$midlayout = 'logo-right';
} elseif (!$social_section_content && $text_section_content){
	$midlayout = 'logo-left';
}
?>
<?php if(is_active_sidebar('header-top-1') || is_active_sidebar('header-top-2')): ?>
	<div class="top-section-wrap">
		<div class="container">
			<div class="top-section">
				<?php if(is_active_sidebar('header-top-1')): ?>
					<div class="flex-section">
						<?php dynamic_sidebar('header-top-1'); ?>
					</div>
				<?php endif; ?>
				<?php if(is_active_sidebar('header-top-2')) : ?>
					<div class="flex-section">
						<?php dynamic_sidebar('header-top-2'); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<div class="middle-section-wrap">
    <div class="container">
        <div class="middle-section <?php echo esc_attr($midlayout); ?>">
			<?php if($social_section_content): ?>
				<div class="flex-section equal-section socials-section">
					<?php cleopa_social_section(); ?>
				</div>
			<?php endif; ?>
            <div class="flex-section logo-section">
                <?php cleopa_get_site_logo(); ?>
            </div>
			<?php if($text_section_content): ?>
				<div class="flex-section equal-section text-section">
					<div class="text-section-i">
						<?php echo($text_section_content); ?>
					</div>
				</div>
			<?php endif; ?>
        </div>
    </div>
</div>
<div class="bot-section-wrap">
    <div class="container">
        <div class="bot-section">
            <div class="flex-section main-menu-section">
                <?php cleopa_main_nav(); ?>
            </div>
            <div class="flex-section icon-header-wrap">
				<div class="search-section">
					<?php cleopa_search_section(true); ?>
				</div>
				<?php cleopa_header_woo_section(true); ?>
            </div>
        </div>
    </div>
</div>