<?php
$text_section_content = cleopa_get_options('nbcore_header_text_section');
$social_section_content = (cleopa_get_options('nbcore_header_facebook') || cleopa_get_options('nbcore_header_twitter') || cleopa_get_options('nbcore_header_linkedin') || cleopa_get_options('nbcore_header_instagram') || cleopa_get_options('nbcore_header_blog') || cleopa_get_options('nbcore_header_pinterest') || cleopa_get_options('nbcore_header_ggplus'));
?>
<?php if($social_section_content ||  is_active_sidebar('header-top-1') || is_active_sidebar('header-top-2')): ?>
	<div class="top-section-wrap">
		<div class="container">
			<div class="top-section">
				<?php if(is_active_sidebar('header-top-1')): ?>
					<div class="flex-section">
						<?php dynamic_sidebar('header-top-1'); ?>
					</div>
				<?php endif; ?>
				<?php if($social_section_content || is_active_sidebar('header-top-2')) : ?>
					<div class="flex-section">
						<?php dynamic_sidebar('header-top-2');
						if($social_section_content): ?>
							<div class="socials-section">
								<?php cleopa_social_section(); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<div class="middle-section-wrap">
    <div class="container">
        <div class="middle-section">
            <div class="flex-section logo-section">
                <?php cleopa_get_site_logo(); ?>
            </div>
			<div class="flex-section right-section">
				<div class="flex-section menu-section">
					<div class="flex-section icon-header-wrap">
						<?php if($text_section_content): ?>
							<div class="text-section">
								<div class="text-section-i">
									<?php echo($text_section_content); ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="search-section">
							<?php cleopa_search_section(true); ?>
						</div>
						<?php cleopa_header_acc_section(); ?>
					</div>
					<div class="flex-section main-menu-section">
						<?php cleopa_main_nav(); ?>
					</div>
				</div>
				<?php if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))): ?>
					<div class="flex-section icon-cart-wrap">
						<?php cleopa_header_woo_section(false); ?>
					</div>
				<?php endif; ?>
			</div>
        </div>
    </div>
</div>