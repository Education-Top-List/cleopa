<?php
if ( ! class_exists( 'Merlin' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and other settings for Merlin WP.
 */
$wizard = new Merlin(
	// Configure Merlin with custom settings.
	$config = array(
		'directory'                => '', // Location where the 'merlin' directory is placed.
		'merlin_url'               => 'merlin', // Customize the page URL where Merlin WP loads.
		'child_action_btn_url'     => 'https://codex.wordpress.org/Child_Themes',  // The URL for the 'child-action-link'.
		'help_mode'                => false, // Set to true to turn on the little wizard helper.
		'dev_mode'                 => true, // Set to true if you're testing or developing.
		'branding'                 => true, // Set to false to remove Merlin WP's branding.
	),
	// Text strings.
	$strings = array(
		'admin-menu'               => esc_html__( 'Theme Setup' , 'cleopa' ),
		'title%s%s%s%s' 		       => esc_html__( '%s%s Themes &lsaquo; Theme Setup: %s%s' , 'cleopa' ),

		'return-to-dashboard'      => esc_html__( 'Return to the dashboard' , 'cleopa' ),

		'btn-skip'                 => esc_html__( 'Skip' , 'cleopa' ),
		'btn-next'                 => esc_html__( 'Next' , 'cleopa' ),
		'btn-start'                => esc_html__( 'Start' , 'cleopa' ),
		'btn-no'                   => esc_html__( 'Cancel' , 'cleopa' ),
		'btn-plugins-install'      => esc_html__( 'Install' , 'cleopa' ),
		'btn-child-install'        => esc_html__( 'Install' , 'cleopa' ),
		'btn-content-install'      => esc_html__( 'Install' , 'cleopa' ),
		'btn-import'               => esc_html__( 'Import' , 'cleopa' ),

		'welcome-header%s'         => esc_html__( 'Welcome to %s' , 'cleopa' ),
		'welcome-header-success%s' => esc_html__( 'Hi. Welcome back' , 'cleopa' ),
		'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.' , 'cleopa' ),
		'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.' , 'cleopa' ),

		'child-header'             => esc_html__( 'Install Child Theme' , 'cleopa' ),
		'child-header-success'     => esc_html__( 'You\'re good to go!' , 'cleopa' ),
		'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.' , 'cleopa' ),
		'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.' , 'cleopa' ),
		'child-action-link'        => esc_html__( 'Learn about child themes' , 'cleopa' ),
		'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.' , 'cleopa' ),
		'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.' , 'cleopa' ),

		'plugins-header'           => esc_html__( 'Install %d plugins' , 'cleopa' ),
		'plugins-header-success'   => esc_html__( 'You\'re up to speed!' , 'cleopa' ),
		'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.' , 'cleopa' ),
		'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.' , 'cleopa' ),
		'plugins-action-link'      => esc_html__( 'Advanced' , 'cleopa' ),

		'import-header-1'            => esc_html__( 'Choose your own theme' , 'cleopa' ),
		'import-1'                   => esc_html__( 'See %d+ home layouts' , 'cleopa' ),
		'import-header-2'            => esc_html__( 'Choose Your Theme' , 'cleopa' ),
		'import-2'                   => esc_html__( 'There are many unique and sophisticated theme are available on Printcart.com that you can choose the one is suitable for your printing business' , 'cleopa' ),
		'import-header-3'            => esc_html__( 'Import data' , 'cleopa' ),
		'import-3'                   => esc_html__( 'You will get the demo data of the theme which you chose and you can customize it if you want' , 'cleopa' ),
		'import-header-4'            => esc_html__( 'Importing...' , 'cleopa' ),
		'import-4'                   => esc_html__( 'Please be patient. It will be ended soon.
			Do not quit or shut down your browser' , 'cleopa' ),
		'process-4'                => esc_html__( 'Importing core-wp data' , 'cleopa' ),

		'import-header'            => esc_html__( 'Import Content' , 'cleopa' ),
		'import'                   => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.' , 'cleopa' ),
		'import-action-link'       => esc_html__( 'Advanced' , 'cleopa' ),

		'ready-header'             => esc_html__( 'Import Successfully!!!' , 'cleopa' ),
		'ready%s'                  => esc_html__( 'Your site has been set up successfully. Enjoy your new site by %s' , 'cleopa' ),
		'ready-action-link'        => esc_html__( 'Extras' , 'cleopa' ),
		'ready-big-button'         => esc_html__( 'View your site' , 'cleopa' ),

		'ready-link-1'             => wp_kses( sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'cleopa' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
		'ready-link-2'             => wp_kses( sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'http://cmsmart.net', esc_html__( 'Get Theme Support', 'cleopa' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
		'ready-link-3'             => wp_kses( sprintf( '<a href="'.admin_url( 'customize.php' ).'" target="_blank">%s</a>', esc_html__( 'Start Customizing', 'cleopa' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
	),
$plugins = array(
	array(
		'name' 		=> esc_html__('Woocommerce', 'cleopa'),
		'slug' 		=> 'woocommerce',
		'required' 	=> true,
		'version' 	=> '4.4.1',
	),
	array(
		'name' 		=> esc_html__('Contact Form 7', 'cleopa'),
		'slug' 		=> 'contact-form-7',
		'required' 	=> false,
		'version' 	=> '5.0.5',
	),
	array(
		'name' 		=> esc_html__('MailChimp for WordPress', 'cleopa'),
		'slug' 		=> 'mailchimp-for-wp',
		'required' 	=> false,
		'version' 	=> '4.3.1',
	),
	array(
		'name' 		=> esc_html__('YITH WooCommerce Wishlist', 'cleopa'),
		'slug' 		=> 'yith-woocommerce-wishlist',
		'required' 	=> true,
		'version' 	=> '3.0.13',
	),
	array(
		'name' 		=> esc_html__('YITH WooCommerce Quick View', 'cleopa'),
		'slug' 		=> 'yith-woocommerce-quick-view',
		'required' 	=> true,
		'version' 	=> '1.4.3',
	),
	array(
		'name' 		=> esc_html__('Advanced Custom Fields', 'cleopa'),
		'slug' 		=> 'advanced-custom-fields',
		'required' 	=> true,
		'version' 	=> '2.7.7',
	),
    array(
        'name' => esc_html__('WPBakery Visual Composer', 'cleopa'),
        'slug' => 'js_composer',
        'required' => true,
        'version' => '6.3.0',
        'source' => esc_url('http://demo9.cmsmart.net/plugins/cleopa/js_composer.zip'),
    ),
    array(
        'name' => esc_html__('Ultimate Addons for WPBakery Page Builder', 'cleopa'),
        'slug' => 'Ultimate_VC_Addons',
        'required' => true,
        'version' => '3.19.6 ',
        'source' => esc_url('http://demo9.cmsmart.net/plugins/cleopa/Ultimate_VC_Addons.zip'),
    ),
    array(
        'name' 		=> esc_html__('Slider Revolution', 'cleopa'),
        'slug' 		=> 'revslider',
        'required' 	=> false,
        'version' 	=> '6.2.19',
        'source' 	=>esc_url('http://demo9.cmsmart.net/plugins/cleopa/revslider.zip'),
	),
	array(
		'name'              => 'WooPanel',
		'slug'              => 'woopanel',
		'required'          => false,
		'version'           => '1.2.7',
		'source'            => esc_url('http://demo9.cmsmart.net/plugins/printshop-solution/woopanel.zip'),
	),
    array(
        'name' 		=> esc_html__('Cleopa Elements', 'cleopa'),
        'slug' 		=> 'cleopa-elements',
        'required' 	=> false,
        'version' 	=> '1.1.2',
		'source' 	=>esc_url('http://demo9.cmsmart.net/plugins/cleopa/cleopa-elements.zip'),
    ),
)
);

function princart_local_import_files() {
	return array(
		array(
			'import_file_name'             		=> 'Cleopa',
			'local_import_file_data'            => trailingslashit( get_template_directory() ) . 'import-files/ocdi/cleopa/content.xml',
			'local_import_widget_file'     		=> trailingslashit( get_template_directory() ) . 'import-files/ocdi/cleopa/widgets.wie',
			'local_import_customizer_file' 		=> trailingslashit( get_template_directory() ) . 'import-files/ocdi/cleopa/customizer.dat',
			'local_import_rev_slider_file' 		=> trailingslashit( get_template_directory() ) . 'import-files/ocdi/cleopa/natural-beauty.zip',
			'import_preview_image_url'     		=> trailingslashit( get_template_directory_uri() ) . 'import-files/ocdi/cleopa/screenshot.png',
			'import_notice'                		=> esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'cleopa' ),
			'title_home_page'                  	=> 'Home',
			'menu_settings'                 	=> array(
				'primary' 		=> 'Main Menu',
				'footer'		=> 'Footer Menu'
												),
		),
        array(
            'import_file_name'             		=> 'Essential Oil',
            'url_path_child_theme'            => 'http://demo9.cmsmart.net/childthemes-cleopa/nb-essential-oil.zip',
            'local_import_file_data'            => trailingslashit( get_template_directory() ) . 'import-files/ocdi/essentialoil/content.xml',
            'local_import_widget_file'     		=> trailingslashit( get_template_directory() ) . 'import-files/ocdi/essentialoil/widgets.wie',
            'local_import_customizer_file' 		=> trailingslashit( get_template_directory() ) . 'import-files/ocdi/essentialoil/customizer.dat',
            'local_import_rev_slider_file' 		=> trailingslashit( get_template_directory() ) . 'import-files/ocdi/essentialoil/slideoil.zip',
            'import_preview_image_url'     		=> trailingslashit( get_template_directory_uri() ) . 'import-files/ocdi/essentialoil/screenshot.png',
            'import_notice'                		=> esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'cleopa' ),
            'title_home_page'                  	=> 'Home',
            'menu_settings'                 	=> array(
				'primary' 		=> 'main menu',
				'footer'		=> 'Footer Menu'
            ),
        ),
        array(
            'import_file_name'             		=> 'Functional Food',
            'url_path_child_theme'            => 'http://demo9.cmsmart.net/childthemes-cleopa/nb-functional-food.zip',
            'local_import_file_data'            => trailingslashit( get_template_directory() ) . 'import-files/ocdi/functionalfood/content.xml',
            'local_import_widget_file'     		=> trailingslashit( get_template_directory() ) . 'import-files/ocdi/functionalfood/widgets.wie',
            'local_import_customizer_file' 		=> trailingslashit( get_template_directory() ) . 'import-files/ocdi/functionalfood/customizer.dat',
            'local_import_rev_slider_file' 		=> trailingslashit( get_template_directory() ) . 'import-files/ocdi/functionalfood/slider1.zip',
            'import_preview_image_url'     		=> trailingslashit( get_template_directory_uri() ) . 'import-files/ocdi/functionalfood/screenshot.png',
            'import_notice'                		=> esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'cleopa' ),
            'title_home_page'                  	=> 'Home',
            'menu_settings'                 	=> array(
				'primary' 		=> 'main-menu',
				'footer'		=> 'Footer Menu'
            ),
        )
	);
}
add_filter( 'merlin_import_files', 'princart_local_import_files' );