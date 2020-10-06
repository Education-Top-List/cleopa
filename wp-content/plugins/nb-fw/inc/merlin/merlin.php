<?php
require_once(NB_FW_PATH . 'inc/merlin/vendor/autoload.php');
/**
 * Merlin WP
 * Better WordPress Theme Onboarding
 *
 * The following code is a derivative work from the
 * Envato WordPress Theme Setup Wizard by David Baker.
 *
 * @package   Merlin WP
 * @version   1.0.0-rc.1
 * @link      https://merlinwp.com/
 * @author    Richard Tabor, from ThemeBeans.com
 * @copyright Copyright (c) 2017, Merlin WP of Inventionn LLC
 * @license   Licensed GPLv3 for open source use
 */

if ( ! class_exists( 'Merlin', false ) ) {

	define('MERLIN_EXTEND_PATH', plugin_dir_path(__FILE__));

	ini_set('max_execution_time', 300);

	if (!function_exists('write_log')) {

		function write_log($log) {
			if (true === WP_DEBUG) {
				if (is_array($log) || is_object($log)) {
					error_log(print_r($log, true));
				} else {
					error_log($log);
				}
			}
		}

	}

/**
 * Merlin.
 */
class Merlin {
	/**
	 * Current theme.
	 *
	 * @var object WP_Theme
	 */
	protected $theme;

	/**
	 * Current step.
	 *
	 * @var string
	 */
	protected $step = '';

	/**
	 * Steps.
	 *
	 * @var    array
	 */
	protected $steps = array();

	/**
	 * TGMPA instance.
	 *
	 * @var    object
	 */
	protected $tgmpa;

	/**
	 * Importer.
	 *
	 * @var    array
	 */
	protected $importer;

	/**
	 * WP Hook class.
	 *
	 * @var Merlin_Hooks
	 */
	protected $hooks;

	/**
	 * Holds the verified import files.
	 *
	 * @var array
	 */
	public $import_files;

	/**
	 * The base import file name.
	 *
	 * @var string
	 */
	public $import_file_base_name;

	/**
	 * Helper.
	 *
	 * @var    array
	 */
	protected $helper;

	/**
	 * Updater.
	 *
	 * @var    array
	 */
	protected $updater;

	/**
	 * The text string array.
	 *
	 * @var array $strings
	 */
	protected $strings = null;

	protected $plugins = null;

	/**
	 * The location where Merlin is located within the theme.
	 *
	 * @var string $directory
	 */
	protected $directory = null;

	/**
	 * Top level admin page.
	 *
	 * @var string $merlin_url
	 */
	protected $merlin_url = null;

	/**
	 * The URL for the "Learn more about child themes" link.
	 *
	 * @var string $child_action_btn_url
	 */
	protected $child_action_btn_url = null;

	/**
	 * Turn on help mode to get some help.
	 *
	 * @var string $help_mode
	 */
	protected $help_mode = false;

	/**
	 * Turn on dev mode if you're developing.
	 *
	 * @var string $dev_mode
	 */
	protected $dev_mode = false;

	/**
	 * The URL for the "Learn more about child themes" link.
	 *
	 * @var string $branding
	 */
	protected $branding = false;

	/**
	 * Setup plugin version.
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function version() {

		if ( ! defined( 'MERLIN_VERSION' ) ) {
			define( 'MERLIN_VERSION', '1.0.0-rc.1' );
		}
	}

	/**
	 * Class Constructor.
	 *
	 * @param array $config Package-specific configuration args.
	 * @param array $strings Text for the different elements.
	 */
	function __construct( $config = array(), $strings = array(), $plugins = array() ) {

		$this->version();

		$config = wp_parse_args( $config, array(
			'directory'            => '',
			'merlin_url'           => 'merlin',
			'child_action_btn_url' => '',
			'help_mode'            => '',
			'dev_mode'             => '',
			'branding'             => '',
		) );

		// Set config arguments.
		$this->directory            = $config['directory'];
		$this->merlin_url           = $config['merlin_url'];
		$this->child_action_btn_url = $config['child_action_btn_url'];
		$this->help_mode            = $config['help_mode'];
		$this->dev_mode             = $config['dev_mode'];
		$this->branding             = $config['branding'];

		// Strings passed in from the config file.
		$this->strings = $strings;

		$this->plugins = $plugins;

		// Retrieve a WP_Theme object.
		$this->theme = wp_get_theme();
		$this->slug  = strtolower( preg_replace( '#[^a-zA-Z]#', '', $this->theme->get( 'Name' ) ) );

		// Is Dev Mode turned on?
		if ( true !== $this->dev_mode ) {

			// Has this theme been setup yet?
			$already_setup = get_option( 'merlin_' . $this->slug . '_completed' );

			// Return if Merlin has already completed it's setup.
			if ( $already_setup ) {
				return;
			}
		}

		require_once MERLIN_EXTEND_PATH . 'includes/class-tgm-plugin-activation.php';
		// Get TGMPA.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$this->tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();
		}

		add_action( 'admin_init', array( $this, 'required_classes' ) );
		add_action( 'admin_init', array( $this, 'redirect' ), 30 );
		add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
		add_action( 'admin_init', array( $this, 'steps' ), 30, 0 );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_page' ), 30, 0 );
		add_action( 'admin_footer', array( $this, 'svg_sprite' ) );
		add_filter( 'tgmpa_load', array( $this, 'load_tgmpa' ), 10, 1 );
		add_action( 'tgmpa_register', array($this, 'register_required_plugins') );
		add_action( 'wp_ajax_merlin_content', array( $this, '_ajax_content' ), 10, 0 );
		add_action( 'wp_ajax_merlin_plugins', array( $this, '_ajax_plugins' ), 10, 0 );
		add_action( 'wp_ajax_merlin_child_theme', array( $this, 'generate_child' ), 10, 0 );
		add_action( 'wp_ajax_merlin_activate_license', array( $this, 'activate_license' ), 10, 0 );
		add_action( 'wp_ajax_merlin_update_selected_import_data_info', array( $this, 'update_selected_import_data_info' ), 10, 0 );
		add_action( 'wp_ajax_merlin_import_finished', array( $this, 'import_finished' ), 10, 0 );
		add_action( 'wp_ajax_merlin_after_import_finished', array( $this, 'after_import_finished' ), 10, 0 );
		add_action( 'upgrader_post_install', array( $this, 'post_install_check' ), 10, 2 );
		add_filter( 'pt-importer/new_ajax_request_response_data', array( $this, 'pt_importer_new_ajax_request_response_data' ) );
		add_action( 'import_end', array( $this, 'after_content_import_setup' ) );
		add_action( 'import_start', array( $this, 'before_content_import_setup' ) );
		add_action( 'admin_init', array( $this, 'register_import_files' ) );
		// add_action('admin_enqueue_scripts', array($this, 'admin_merlin_scripts_enqueue'));

		add_action( 'activated_plugin', array( $this, 'detect_plugin_activation' ), 10, 2 );
	}

	function detect_plugin_activation( $plugin, $network_activation ) {
		if( $plugin == 'nb-fw/nb-fw.php' ) {
			$merlin_printcart_redirected = get_option('merlin_printcart_redirected');
			
			if( ! $merlin_printcart_redirected ) {
				update_option('merlin_printcart_redirected', true);
				// wp_safe_redirect( admin_url('themes.php?page=merlin') );
				// die();

				wp_register_script( 'redirect-page', plugins_url( '/assets/js/redirect-page.js', __FILE__ ) );

				$translation_array = array(
					'import_merlin_url' => admin_url( 'themes.php?page=' . $this->merlin_url )
				);
				wp_localize_script( 'redirect-page', 'object_merlin', $translation_array );

				wp_enqueue_script( 'redirect-page' );
			}
		}
	}

	function admin_merlin_scripts_enqueue() {
		if(!get_option( 'merlin_' . $this->slug . '_redirected' )) {

			wp_register_script( 'redirect-page', plugins_url( '/assets/js/redirect-page.js', __FILE__ ) );

			$translation_array = array(
				'import_merlin_url' => admin_url( 'themes.php?page=' . $this->merlin_url )
			);
			wp_localize_script( 'redirect-page', 'object_merlin', $translation_array );

			wp_enqueue_script( 'redirect-page' );
		}
	}

	function register_required_plugins()
	{
		if(!isset($this->plugins)) {
			$this->plugins = apply_filters('core_tgmpa_array', $this->plugins);
		}

		$config = array(
            'id'           => 'core-wp',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
        );

		tgmpa( $this->plugins, $config );
	}

	/**
	 * Require necessary classes.
	 */
	function required_classes() {

		if ( ! class_exists( '\WP_Importer' ) ) {
			require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}

		require_once MERLIN_EXTEND_PATH . 'includes/class-merlin-downloader.php';

		$logger = new ProteusThemes\WPContentImporter2\WPImporterLogger();

		$this->importer = new ProteusThemes\WPContentImporter2\Importer( array( 'fetch_attachments' => true ), $logger );

		require_once MERLIN_EXTEND_PATH . 'includes/class-merlin-widget-importer.php';

		if ( ! class_exists( 'WP_Customize_Setting' ) ) {
			require_once ABSPATH . 'wp-includes/class-wp-customize-setting.php';
		}

		require_once MERLIN_EXTEND_PATH . 'includes/class-merlin-customizer-option.php';
		require_once MERLIN_EXTEND_PATH . 'includes/class-merlin-customizer-importer.php';

		require_once MERLIN_EXTEND_PATH . 'includes/class-merlin-redux-importer.php';

		require_once MERLIN_EXTEND_PATH . 'includes/class-merlin-hooks.php';

		$this->hooks = new Merlin_Hooks();

		if ( class_exists( 'EDD_Theme_Updater_Admin' ) ) {
			$this->updater = new EDD_Theme_Updater_Admin();
		}

		if ( true === $this->help_mode ) {
			require MERLIN_EXTEND_PATH . 'includes/class-merlin-helper.php';
			$this->helper = new Merlin_Helper();
		}
	}

	/**
	 * Set redirection transient.
	 */
	public function switch_theme() {
		if ( ! is_child_theme() ) {
			set_transient( $this->theme->template . '_merlin_redirect', 1 );
		}
	}

	/**
	 * Redirection transient.
	 */
	public function redirect() {

		if ( ! get_transient( $this->theme->template . '_merlin_redirect' ) ) {
			return;
		}

		delete_transient( $this->theme->template . '_merlin_redirect' );

		wp_safe_redirect( admin_url( 'themes.php?page= ' . $this->merlin_url ) );

		exit;
	}

	/**
	 * Conditionally load TGMPA
	 *
	 * @param string $status User's manage capabilities.
	 */
	public function load_tgmpa( $status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}

	/**
	 * Determine if the user already has theme content installed.
	 * This can happen if swapping from a previous theme or updated the current theme.
	 * We change the UI a bit when updating / swapping to a new theme.
	 *
	 * @access public
	 */
	protected function is_possible_upgrade() {
		return false;
	}

	/**
	 * After a theme update, we clear the slug_merlin_completed option.
	 * This prompts the user to visit the update page again.
	 *
	 * @param string $return To end or not.
	 * @param string $theme  The current theme.
	 */
	public function post_install_check( $return, $theme ) {

		if ( is_wp_error( $return ) ) {
			return $return;
		}

		if ( $theme !== $this->theme->stylesheet ) {
			return $return;
		}

		update_option( 'merlin_' . $this->slug . '_completed', false );

		return $return;
	}

	/**
	 * Add the admin menu item, under Appearance.
	 */
	public function add_admin_menu() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		$this->hook_suffix = add_theme_page(
			esc_html( $strings['admin-menu'] ), esc_html( $strings['admin-menu'] ), 'manage_options', $this->merlin_url, array( $this, 'admin_page' )
		);
	}

	/**
	 * Add the admin page.
	 */
	public function admin_page() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Do not proceed, if we're not on the right page.
		if ( empty( $_GET['page'] ) || $this->merlin_url !== $_GET['page'] ) {
			return;
		}

		if ( ob_get_length() ) {
			ob_end_clean();
		}

		$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

		// Use minified libraries if dev mode is turned on.
		$suffix = ( ( true == $this->dev_mode ) ) ? '' : '.min';

		wp_enqueue_style( 'bootstrap', plugins_url( '/assets/css/bootstrap.min.css', __FILE__ ), array( 'wp-admin' ), MERLIN_VERSION);

		wp_enqueue_style( 'scrollbar', plugins_url( '/assets/css/perfect-scrollbar.css', __FILE__ ), array( 'wp-admin' ), MERLIN_VERSION);

		wp_enqueue_style( 'animate', plugins_url( '/assets/css/animate.css', __FILE__ ), array( 'wp-admin' ), MERLIN_VERSION);
		// Enqueue styles.
		wp_enqueue_style( 'merlin', plugins_url( '/assets/css/merlin' . $suffix . '.css', __FILE__ ), array( 'wp-admin' ), MERLIN_VERSION );

		wp_enqueue_script( 'scrollbar', plugins_url( '/assets/js/perfect-scrollbar.min.js', __FILE__ ), array( 'jquery-core' ), MERLIN_VERSION );

		// Enqueue javascript.
		wp_enqueue_script( 'merlin', plugins_url( '/assets/js/merlin' . $suffix . '.js', __FILE__ ), array( 'jquery-core' ), MERLIN_VERSION );

		$texts = array(
			'something_went_wrong' => esc_html__( 'Something went wrong. Please refresh the page and try again!', 'merlin-wp' ),
		);

		// Localize the javascript.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			// Check first if TMGPA is included.
			wp_localize_script( 'merlin', 'merlin_params', array(
				'tgm_plugin_nonce' => array(
					'update'  => wp_create_nonce( 'tgmpa-update' ),
					'install' => wp_create_nonce( 'tgmpa-install' ),
				),
				'tgm_bulk_url'     => $this->tgmpa->get_tgmpa_url(),
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'wpnonce'          => wp_create_nonce( 'merlin_nonce' ),
				'texts'            => $texts,
			) );
		} else {
			// If TMGPA is not included.
			wp_localize_script( 'merlin', 'merlin_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wpnonce' => wp_create_nonce( 'merlin_nonce' ),
				'texts'   => $texts,
			) );
		}

		ob_start();

		/**
		 * Start the actual page content.
		 */
		$this->header(); ?>

		<div class="merlin__wrapper">

			<div class="merlin__content merlin__content--<?php echo esc_attr( strtolower( $this->steps[ $this->step ]['name'] ) ); ?>">

				<?php
				// Content Handlers.
				$show_content = true;

				if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
					$show_content = call_user_func( $this->steps[ $this->step ]['handler'] );
				}

				if ( $show_content ) {
					$this->body();
				}
				?>

				<?php $this->step_output(); ?>

			</div>

			<?php echo sprintf( '<a class="return-to-dashboard" href="%s">%s</a>', esc_url( admin_url( '/' ) ), esc_html( $strings['return-to-dashboard'] ) ); ?>

		</div>

		<?php $this->footer(); ?>

		<?php
		exit;
	}

	/**
	 * Output the header.
	 */
	protected function header() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Get the current step.
		$current_step = strtolower( $this->steps[ $this->step ]['name'] );
		?>

		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<?php printf( esc_html( $strings['title%s%s%s%s'] ), '<ti', 'tle>', esc_html( $this->theme->name ), '</title>' ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_print_scripts' ); ?>
			<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="merlin__body merlin__body--<?php echo esc_attr( $current_step ); ?>">
			<?php
		}

	/**
	 * Output the content for the current step.
	 */
	protected function body() {
		isset( $this->steps[ $this->step ] ) ? call_user_func( $this->steps[ $this->step ]['view'] ) : false;
	}

	/**
	 * Output the footer.
	 */
	protected function footer() {

		// Is help_mode set in the merlin-config.php file?
		if ( true === $this->help_mode ) :
			$current_step = strtolower( $this->steps[ $this->step ]['name'] );
			$this->helper->helper_wizard( $current_step );
		endif;

		if ( true === $this->help_mode || true === $this->branding ) :
			?>
			<a class="merlin--icon" target="_blank" href="https://merlinwp.com">
				<?php 
				// echo wp_kses( $this->svg( array( 'icon' => 'merlin' ) ), $this->svg_allowed_html() ); ?>
			</a>
		<?php endif; ?>

	</body>
	<?php do_action( 'admin_footer' ); ?>
	<?php do_action( 'admin_print_footer_scripts' ); ?>
	</html>
	<?php
}

	/**
	 * SVG
	 */
	public function svg_sprite() {

		// Define SVG sprite file.
		$svg = get_parent_theme_file_path( $this->directory . '/assets/images/sprite.svg' );

		// If it exists, include it.
		if ( file_exists( $svg ) ) {
			require_once apply_filters( 'merlin_svg_sprite', $svg );
		}
	}

	/**
	 * Return SVG markup.
	 *
	 * @param array $args {
	 *     Parameters needed to display an SVG.
	 *
	 *     @type string $icon  Required SVG icon filename.
	 *     @type string $title Optional SVG title.
	 *     @type string $desc  Optional SVG description.
	 * }
	 * @return string SVG markup.
	 */
	public function svg( $args = array() ) {

		// Make sure $args are an array.
		if ( empty( $args ) ) {
			return __( 'Please define default parameters in the form of an array.', 'merlin-wp' );
		}

		// Define an icon.
		if ( false === array_key_exists( 'icon', $args ) ) {
			return __( 'Please define an SVG icon filename.', 'merlin-wp' );
		}

		// Set defaults.
		$defaults = array(
			'icon'        => '',
			'title'       => '',
			'desc'        => '',
			'aria_hidden' => true, // Hide from screen readers.
			'fallback'    => false,
		);

		// Parse args.
		$args = wp_parse_args( $args, $defaults );

		// Set aria hidden.
		$aria_hidden = '';

		if ( true === $args['aria_hidden'] ) {
			$aria_hidden = ' aria-hidden="true"';
		}

		// Set ARIA.
		$aria_labelledby = '';

		if ( $args['title'] && $args['desc'] ) {
			$aria_labelledby = ' aria-labelledby="title desc"';
		}

		// Begin SVG markup.
		$svg = '<svg class="icon icon--' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

		// If there is a title, display it.
		if ( $args['title'] ) {
			$svg .= '<title>' . esc_html( $args['title'] ) . '</title>';
		}

		// If there is a description, display it.
		if ( $args['desc'] ) {
			$svg .= '<desc>' . esc_html( $args['desc'] ) . '</desc>';
		}

		$svg .= '<use xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use>';

		// Add some markup to use as a fallback for browsers that do not support SVGs.
		if ( $args['fallback'] ) {
			$svg .= '<span class="svg-fallback icon--' . esc_attr( $args['icon'] ) . '"></span>';
		}

		$svg .= '</svg>';

		return $svg;
	}

	/**
	 * Adds data attributes to the body, based on Customizer entries.
	 */
	public function svg_allowed_html() {

		$array = array(
			'svg' => array(
				'class'       => array(),
				'aria-hidden' => array(),
				'role'        => array(),
			),
			'use' => array(
				'xlink:href' => array(),
			),
		);

		return apply_filters( 'merlin_svg_allowed_html', $array );

	}

	/**
	 * Loading merlin-spinner.
	 */
	public function loading_spinner() {

		// Define the spinner file.
		$spinner = $this->directory . '/assets/images/spinner';

		// Retrieve the spinner.
		get_template_part( apply_filters( 'merlin_loading_spinner', $spinner ) );

	}

	/**
	 * Setup steps.
	 */
	function steps() {

		$this->steps = array(
			'welcome' => array(
				'name'    => esc_html__( 'Welcome', 'merlin-wp' ),
				'view'    => array( $this, 'welcome' ),
				'handler' => array( $this, 'welcome_handler' ),
			),
		);

		// Show the plugin importer, only if TGMPA is included.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$this->steps['plugins'] = array(
				'name' => esc_html__( 'Plugins', 'merlin-wp' ),
				'view' => array( $this, 'plugins' ),
			);
		}

		// Show the content importer, only if there's demo content added.
		if ( ! empty( $this->import_files ) ) {
			$this->steps['content'] = array(
				'name' => esc_html__( 'Content', 'merlin-wp' ),
				'view' => array( $this, 'content' ),
			);
		}

		// $this->steps['child'] = array(
		// 	'name' => esc_html__( 'Child', 'merlin-wp' ),
		// 	'view' => array( $this, 'child' ),
		// );

		$this->steps['ready'] = array(
			'name' => esc_html__( 'Ready', 'merlin-wp' ),
			'view' => array( $this, 'ready' ),
		);

		$this->steps = apply_filters( $this->theme->template . '_merlin_steps', $this->steps );
	}

	/**
	 * Output the steps
	 */
	protected function step_output() {
		$ouput_steps  = $this->steps;
		$array_keys   = array_keys( $this->steps );
		$current_step = array_search( $this->step, $array_keys, true );

		array_shift( $ouput_steps );
		?>

		<ol class="dots">

			<?php
			foreach ( $ouput_steps as $step_key => $step ) :

				$class_attr = '';
				$show_link  = false;

				if ( $step_key === $this->step ) {
					$class_attr = 'active';
				} elseif ( $current_step > array_search( $step_key, $array_keys, true ) ) {
					$class_attr = 'done';
					$show_link  = true;
				}
				?>

				<li class="<?php echo esc_attr( $class_attr ); ?>">
					<a href="<?php echo esc_url( $this->step_link( $step_key ) ); ?>" title="<?php echo esc_attr( $step['name'] ); ?>"></a>
				</li>

			<?php endforeach; ?>

		</ol>

		<?php
	}

	/**
	 * Get the step URL.
	 *
	 * @param string $step Name of the step, appended to the URL.
	 */
	protected function step_link( $step ) {
		return add_query_arg( 'step', $step );
	}

	/**
	 * Get the next step link.
	 */
	protected function step_next_link() {
		$keys = array_keys( $this->steps );
		$step = array_search( $this->step, $keys, true ) + 1;

		return add_query_arg( 'step', $keys[ $step ] );
	}

	/**
	 * Introduction step
	 */
	protected function welcome() {
		update_option('merlin_' . $this->slug . '_redirected', true);
		// Has this theme been setup yet? Compare this to the option set when you get to the last panel.
		$already_setup = get_option( 'merlin_' . $this->slug . '_completed' );

		// Theme Name.
		$theme = ucfirst( $this->theme );

		// Remove "Child" from the current theme name, if it's installed.
		$theme = str_replace( ' Child','', $theme );

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = ! $already_setup ? $strings['welcome-header%s'] : $strings['welcome-header-success%s'];
		$paragraph = ! $already_setup ? $strings['welcome%s'] : $strings['welcome-success%s'];
		$start     = $strings['btn-start'];
		$no        = $strings['btn-no'];
		?>

		<div class="merlin__content--transition">

			<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/images/popup-printcart-Tf.png" />

			<h1><?php echo esc_html( sprintf( $header, $theme ) ); ?></h1>

			<p><?php echo esc_html( sprintf( $paragraph, $theme ) ); ?></p>

		</div>

		<footer class="merlin__content__footer">
			<a href="<?php echo esc_url( wp_get_referer() && ! strpos( wp_get_referer(), 'update.php' ) ? wp_get_referer() : admin_url( '/' ) ); ?>" class="merlin__button merlin__button--skip"><?php echo esc_html( $no ); ?></a>
			<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--next merlin__button--proceed merlin__button--colorchange"><?php echo esc_html( $start ); ?> >></a>
			<?php wp_nonce_field( 'merlin' ); ?>
		</footer>

		<?php
	}

	/**
	 * Handles save button from welcome page.
	 * This is to perform tasks when the setup wizard has already been run.
	 */
	protected function welcome_handler() {

		check_admin_referer( 'merlin' );

		return false;
	}

	/**
	 * Child theme generator.
	 */
	protected function child() {

		// Variables.
		$is_child_theme     = is_child_theme();
		$child_theme_option = get_option( 'merlin_' . $this->slug . '_child' );
		$theme              = $child_theme_option ? wp_get_theme( $child_theme_option )->name : $this->theme . ' Child';
		$action_url         = $this->child_action_btn_url;

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = ! $is_child_theme ? $strings['child-header'] : $strings['child-header-success'];
		$action    = $strings['child-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$paragraph = ! $is_child_theme ? $strings['child'] : $strings['child-success%s'];
		$install   = $strings['btn-child-install'];
		?>

		<div class="merlin__content--transition">

			<?php echo wp_kses( $this->svg( array( 'icon' => 'child' ) ), $this->svg_allowed_html() ); ?>

			<svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
				<circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
			</svg>

			<h1><?php echo esc_html( $header ); ?></h1>

			<p id="child-theme-text"><?php echo esc_html( sprintf( $paragraph, $theme ) ); ?></p>

			<a class="merlin__button merlin__button--knockout merlin__button--no-chevron" href="<?php echo esc_url( $action_url ); ?>" target="_blank"><?php echo esc_html( $action ); ?></a>

		</div>

		<footer class="merlin__content__footer">

			<?php if ( ! $is_child_theme ) : ?>

				<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--skip merlin__button--proceed"><?php echo esc_html( $skip ); ?></a>

				<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--next button-next" data-callback="install_child">
					<span class="merlin__button--loading__text"><?php echo esc_html( $install ); ?></span><?php echo $this->loading_spinner(); ?>
				</a>

				<?php else : ?>
					<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--next merlin__button--proceed merlin__button--colorchange"><?php echo esc_html( $next ); ?></a>
				<?php endif; ?>
				<?php wp_nonce_field( 'merlin' ); ?>
			</footer>
			<?php
		}

	/**
	 * Theme plugins
	 */
	protected function plugins() {

		// Variables.
		$url    = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'merlin' );
		$method = '';
		$fields = array_keys( $_POST );
		$creds  = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields );

		tgmpa_load_bulk_installer();

		if ( false === $creds ) {
			return true;
		}

		if ( ! WP_Filesystem( $creds ) ) {
			request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
			return true;
		}

		// Are there plugins that need installing/activating?
		$plugins = $this->get_tgmpa_plugins();
		$count   = count( $plugins['all'] );
		$class   = $count ? null : 'no-plugins';

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = $count ? $strings['plugins-header'] : $strings['plugins-header-success'];
		$paragraph = $count ? $strings['plugins'] : $strings['plugins-success%s'];
		$action    = $strings['plugins-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$install   = $strings['btn-plugins-install'];
		?>

		<div class="merlin__content--transition">

			<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/images/plugin.png" />

			<h1><?php 
			if($count) {
				echo esc_html( sprintf($header, $count) );
			} else {
				echo esc_html( $header );
			}
			?></h1>

			<p><?php echo esc_html( $paragraph ); ?></p>

			<?php if ( $count ) { ?>
				<a id="merlin__drawer-trigger" class="merlin__button merlin__button--knockout"><span><?php echo esc_html( $action ); ?></span><span class="chevron"></span></a>
			<?php } ?>

		</div>

		<form action="" method="post">

			<?php if ( $count ) : ?>

				<ul class="merlin__drawer merlin__drawer--install-plugins">

					<?php foreach ( $plugins['all'] as $slug => $plugin ) : ?>

						<li data-slug="<?php echo esc_attr( $slug ); ?>">

							<?php echo esc_html( $plugin['name'] ); ?>

							<span>
								<?php
								$keys = array();

								if ( isset( $plugins['install'][ $slug ] ) ) {
									$keys[] = esc_html__( 'Install', 'merlin-wp' );
								}
								if ( isset( $plugins['update'][ $slug ] ) ) {
									$keys[] = esc_html__( 'Update', 'merlin-wp' );
								}
								if ( isset( $plugins['activate'][ $slug ] ) ) {
									$keys[] = esc_html__( 'Activate', 'merlin-wp' );
								}
								echo implode( esc_html__( 'and', 'merlin-wp' ) , $keys );
								?>

							</span>

							<div class="spinner"></div>

						</li>
					<?php endforeach; ?>

				</ul>

			<?php endif; ?>

			<footer class="merlin__content__footer <?php echo esc_attr( $class ); ?>">
				<?php if ( $count ) : ?>
					<a id="close" href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--skip merlin__button--closer merlin__button--proceed"><?php echo esc_html( $skip ); ?></a>
					<a id="skip" href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--skip merlin__button--proceed"><?php echo esc_html( $skip ); ?></a>
					<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--next button-next" data-callback="install_plugins">
						<span class="merlin__button--loading__text"><?php echo esc_html( $install ); ?></span><?php echo $this->loading_spinner(); ?>
					</a>
					<?php else : ?>
						<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--next merlin__button--proceed merlin__button--colorchange"><?php echo esc_html( $next ); ?></a>
					<?php endif; ?>
					<?php wp_nonce_field( 'merlin' ); ?>
				</footer>
			</form>

			<?php
		}

	/**
	 * Page setup
	 */
	protected function content() {
		$import_info = $this->get_import_data_info();

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = $strings['import-header'];
		$paragraph = $strings['import'];
		$action    = $strings['import-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$import    = $strings['btn-import'];
		?>

		<div class="merlin__content--transition">
			<div class="step1">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/images/step1-content.png" />

				<h1><?php echo esc_html( $strings['import-header-1'] ); ?></h1>

				<p><a href="#" class="see_home_layout"><?php echo esc_html( sprintf($strings['import-1'], count( $this->import_files )) ); ?> >></a></p>
			</div>
			<div class="step2">
				<a href="#" class="btn-close"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/images/close.png" /></a>

				<h1><?php echo esc_html( $strings['import-header-2'] ); ?></h1>

				<p><?php echo esc_html( $strings['import-2'] ); ?></p>
				
				<div class="themes-wrap row">
					<?php if ( 0 < count( $this->import_files ) ) : ?>
						<?php foreach ( $this->import_files as $index => $import_file ) : ?>
							<?php
							$img_src          = isset( $import_file['import_preview_image_url'] ) ? $import_file['import_preview_image_url'] : '';
							$demo_preview_url = isset( $import_file['preview_url'] ) ? $import_file['preview_url'] : '';
							?>
							<div class="col-md-4 col-sm-6 col-xs-12 gallery-item">
								<div class="header-box ">
									<div class="pr-dot">
										<span></span>
										<span></span>
										<span></span>
									</div>
									<div class="bg-image bg-image1" style="background-image:url(<?php echo esc_url( $img_src ); ?>)"></div>
									<div class="block-btn">
										<a href="<?php echo esc_url( $demo_preview_url ); ?>" class="btn-link btn-link1" target="_blank"><span>View demo</span></a>
										<a href="javascript:;" class="btn-link btn-link2 btn-select-theme" data-theme="<?php echo esc_attr( $index ); ?>"><span>Select</span></a>
									</div>
								</div>
								<p class="title"><?php echo esc_html( $import_file['import_file_name'] ); ?></p>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

			</div>
			<div class="step3">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/images/import-data.png" />

				<h1><?php echo esc_html( $strings['import-header-3'] ); ?></h1>

				<p><?php echo esc_html( $strings['import-3'] ); ?></p>
			</div>
			<div class="step4">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/images/import-data.png" />

				<h1><?php echo esc_html( $strings['import-header-4'] ); ?></h1>

				<p><?php echo esc_html( $strings['import-4'] ); ?></p>

				<span class="label"><?php echo esc_html( $strings['process-4'] ); ?></span>

				<div class="base-process">
					<div class="fill"></div>
				</div>

				<span class="current-process">Importing files</span>
				
				<div class="block-select-import">
					<?php if ( 1 < count( $this->import_files ) ) : ?>
						<p><?php esc_html_e( 'Select which demo data you want to import:', 'merlin-wp' ); ?></p>
						<select class="js-merlin-demo-import-select">
							<?php foreach ( $this->import_files as $index => $import_file ) : ?>
								<?php
								$img_src          = isset( $import_file['import_preview_image_url'] ) ? $import_file['import_preview_image_url'] : '';
								$import_notice    = isset( $import_file['import_notice'] ) ? $import_file['import_notice'] : '';
								$demo_preview_url = isset( $import_file['preview_url'] ) ? $import_file['preview_url'] : '';
								?>

								<option value="<?php echo esc_attr( $index ); ?>" data-img-src="<?php echo esc_url( $img_src ); ?>" data-notice="<?php echo esc_html( $import_notice ); ?>" data-preview-url="<?php echo esc_url( $demo_preview_url ); ?>"><?php echo esc_html( $import_file['import_file_name'] ); ?></option>

							<?php endforeach; ?>
						</select>
					<?php endif; ?>

					<a id="merlin__drawer-trigger" class="merlin__button merlin__button--knockout"><span><?php echo esc_html( $action ); ?></span><span class="chevron"></span></a>
				</div>
			</div>
		</div>

		<form action="" method="post">

			<ul class="merlin__drawer merlin__drawer--import-content js-merlin-drawer-import-content">
				<?php echo $this->get_import_steps_html( $import_info ); ?>
			</ul>

			<footer class="merlin__content__footer">

				<a id="close" href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--skip merlin__button--closer merlin__button--proceed"><?php echo esc_html( $skip ); ?></a>

				<a id="skip" href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--skip merlin__button--proceed"><?php echo esc_html( $skip ); ?></a>

				<a href="javascript:void(0);" class="merlin__button merlin__button__2 merlin__button--next button-disabled" data-callback="install_content">
					<span class="merlin__button--loading__text"><?php echo esc_html( $import ); ?></span><?php echo $this->loading_spinner(); ?>
				</a>

				<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--next button-next" data-callback="install_content">
					<span class="merlin__button--loading__text"><?php echo esc_html( $import ); ?> >></span><?php echo $this->loading_spinner(); ?>
				</a>

				<?php wp_nonce_field( 'merlin' ); ?>
			</footer>
		</form>

		<?php
	}

	/**
	 * Final step
	 */
	protected function ready() {

		// Author name.
		$author = $this->theme->author;

		// Theme Name.
		$theme = ucfirst( $this->theme );

		// Remove "Child" from the current theme name, if it's installed.
		$theme = str_replace( ' Child', '', $theme );

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = $strings['ready-header'];
		$paragraph = $strings['ready%s'];
		$action    = $strings['ready-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$big_btn   = $strings['ready-big-button'];

		// Links.
		$link_1 = $strings['ready-link-1'];
		$link_2 = $strings['ready-link-2'];
		$link_3 = $strings['ready-link-3'];

		$allowed_html_array = array(
			'a' => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
			),
		);

		update_option( 'merlin_' . $this->slug . '_completed', time() );
		?>

		<div class="merlin__content--transition">

			<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/images/import-success.png" />

			<h1><?php echo esc_html( $header ); ?></h1>

			<p><?php wp_kses( printf( $paragraph, $author ), $allowed_html_array ); ?></p>

		</div>

		<footer class="merlin__content__footer merlin__content__footer--fullwidth">

			<a id="merlin__drawer-trigger" class="merlin__button merlin__button--knockout"><span><?php echo esc_html( $action ); ?></span><span class="chevron"></span></a>

			<ul class="merlin__drawer merlin__drawer--extras">

				<li><?php echo wp_kses( $link_1, $allowed_html_array ); ?></li>
				<li><?php echo wp_kses( $link_2, $allowed_html_array ); ?></li>
				<li><?php echo wp_kses( $link_3, $allowed_html_array ); ?></li>

			</ul>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="merlin__button merlin__button--blue merlin__button--fullwidth merlin__button--popin" target="_blank"><?php echo esc_html( $big_btn ); ?> >></a>	

		</footer>

		<?php
	}

	/**
	 * Get registered TGMPA plugins
	 *
	 * @return    array
	 */
	protected function get_tgmpa_plugins() {
		$plugins = array(
			'all'      => array(), // Meaning: all plugins which still have open actions.
			'install'  => array(),
			'update'   => array(),
			'activate' => array(),
		);
		
		foreach ( $this->tgmpa->plugins as $slug => $plugin ) {
			if ( $this->tgmpa->is_plugin_active( $slug ) && false === $this->tgmpa->does_plugin_have_update( $slug ) ) {
				continue;
			} else {
				$plugins['all'][ $slug ] = $plugin;
				if ( ! $this->tgmpa->is_plugin_installed( $slug ) ) {
					$plugins['install'][ $slug ] = $plugin;
				} else {
					if ( false !== $this->tgmpa->does_plugin_have_update( $slug ) ) {
						$plugins['update'][ $slug ] = $plugin;
					}
					if ( $this->tgmpa->can_plugin_activate( $slug ) ) {
						$plugins['activate'][ $slug ] = $plugin;
					}
				}
			}
		}

		return $plugins;
	}

	public function get_realpath_theme($path) {
		$real_path = parse_url($path, PHP_URL_PATH);
		return $_SERVER['DOCUMENT_ROOT'] . $real_path;
	}

	/**
	 * Generate the child theme via AJAX.
	 */
	public function generate_child() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$success = $strings['child-json-success%s'];
		$already = $strings['child-json-already%s'];

		// $name = $this->theme . (strpos($this->theme,' Child')==false?' Child':'');

		if(!empty(get_transient('url_path_child_theme'))) {
			$name = pathinfo(get_transient('url_path_child_theme'), PATHINFO_FILENAME);
			$slug = sanitize_title( $name );
			$path           = get_theme_root() . '/' . $slug;

			if ( ! file_exists( $path ) ) {

				WP_Filesystem();

				global $wp_filesystem;

			// $wp_filesystem->mkdir( $path );
			// $wp_filesystem->put_contents( $path . '/style.css', $this->generate_child_style_css( $this->theme->template, $this->theme->name, $this->theme->author, $this->theme->version ) );
			// $wp_filesystem->put_contents( $path . '/functions.php', $this->generate_child_functions_php( $this->theme->template ) );

			// if(!empty(get_transient('local_path_child_theme_js'))) {
			// 	$folder_js = $path . '/js';
			// 	if ( ! file_exists( $folder_js ) ) {
			// 		$wp_filesystem->mkdir( $folder_js );
			// 		$wp_filesystem->put_contents( $folder_js . '/customize.js', $this->generate_child_functions_js( get_transient('local_path_child_theme_js') ) );
			// 	}
			// }

			// if(!empty(get_transient('import_preview_image_url'))) {
			// 	$downloader = new Merlin_Downloader();
			// 	$downloader->set_download_directory_path($path.'/');
			// 	$downloader->download_file(get_transient('import_preview_image_url'),'screenshot.jpg');
			// }

				$downloader = new Merlin_Downloader();
				$path_des = get_theme_root() . '/';
				$downloader->set_download_directory_path($path_des);
				$filepath = $downloader->download_file(get_transient('url_path_child_theme'),$slug.'.zip');

				if(file_exists($filepath) == false) {
					write_log('URL child theme can not download!');
				} else {
					$unzipfile = unzip_file( $filepath, $path_des);
					if (!$unzipfile) {
						write_log('Error unzip file child theme!');
					} else {
						$wp_filesystem->delete($filepath, true);
					}
				}

				$allowed_themes          = get_option( 'allowedthemes' );
				$allowed_themes[ $slug ] = true;
				update_option( 'allowedthemes', $allowed_themes );

			} else {

				if ( $this->theme->template !== $slug ) :
					update_option( 'merlin_' . $this->slug . '_child', $name );
					switch_theme( $slug );
				endif;

			// wp_send_json(
			// 	array(
			// 		'done'    => 1,
			// 		'message' => sprintf( esc_html( $success ), $slug
			// 	),
			// 	)
			// );
			}
			if ( $this->theme->template !== $slug ) :
				update_option( 'merlin_' . $this->slug . '_child', $name );
				switch_theme( $slug );
			endif;
		}
	}

	public function generate_child_functions_js( $file ) {

		$output = "";

		if(!empty($file)) {
			$output = $this->thim_file_get_contents($file);
		}

		// Let's remove the tabs so that it displays nicely.
		$output = trim( preg_replace( '/\t+/', '', $output ) );

		// Filterable return.
		return $output;
	}

	/**
	 * Content template for the child theme functions.php file.
	 *
	 * @link https://gist.github.com/richtabor/688327dd103b1aa826ebae47e99a0fbe
	 *
	 * @param string $slug Parent theme slug.
	 */
	public function generate_child_functions_php( $slug ) {

		$slug_no_hyphens = strtolower( preg_replace( '#[^a-zA-Z]#', '', $slug ) );

		$output = "
		<?php
		/**
			 * Theme functions and definitions.
			 * This child theme was generated by Merlin WP.
			 *
			 * @link https://developer.wordpress.org/themes/basics/theme-functions/
			 */

		/*
			 * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then
			 * you will have to make sure to maintain all of the parent theme dependencies.
			 *
			 * Make sure you're using the correct handle for loading the parent theme's styles.
			 * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
			 * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
			 *
			 * @link https://codex.wordpress.org/Child_Themes
			 */
		function {$slug_no_hyphens}_child_enqueue_styles() {
			wp_enqueue_style( '{$slug}-style' , get_template_directory_uri() . '/style.css' );
			wp_enqueue_style( '{$slug}-child-style',
			get_stylesheet_directory_uri() . '/style.css',
			array( '{$slug}-style' ),
			wp_get_theme()->get('Version')
			);
		}

		add_action(  'wp_enqueue_scripts', '{$slug_no_hyphens}_child_enqueue_styles' );\n
		";

		if(!empty(get_transient('local_path_child_theme_fun'))) {
			$output = $this->thim_file_get_contents(get_transient('local_path_child_theme_fun'));
		} else {
			// Let's remove the tabs so that it displays nicely.
			$output = trim( preg_replace( '/\t+/', '', $output ) );
		}
		
		// Filterable return.
		return apply_filters( 'merlin_generate_child_functions_php', $output, $slug );
	}

	/**
	 * Content template for the child theme functions.php file.
	 *
	 * @link https://gist.github.com/richtabor/7d88d279706fc3093911e958fd1fd791
	 *
	 * @param string $slug    Parent theme slug.
	 * @param string $parent  Parent theme name.
	 * @param string $author  Parent theme author.
	 * @param string $version Parent theme version.
	 */
	public function generate_child_style_css( $slug, $parent, $author, $version ) {

		$output = "
		/**
		* Theme Name: {$parent} Child
		* Description: This is a child theme of {$parent}.
		* Author: {$author}
		* Template: {$slug}
		* Version: {$version}
			*/\n
		";
		// write_log('Path child theme style: '.get_transient('local_path_child_theme_style'));
		if(!empty(get_transient('local_path_child_theme_style'))) {
			$output.=$this->thim_file_get_contents(get_transient('local_path_child_theme_style'));
		}

		// Let's remove the tabs so that it displays nicely.
		$output = trim( preg_replace( '/\t+/', '', $output ) );

		return apply_filters( 'merlin_generate_child_style_css', $output, $slug, $parent, $version );
	}

	/**
	 * Do plugins' AJAX
	 *
	 * @internal    Used as a calback.
	 */
	function _ajax_plugins() {

		if ( ! check_ajax_referer( 'merlin_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
			exit( 0 );
		}

		$json = array();
		$tgmpa_url = $this->tgmpa->get_tgmpa_url();
		$plugins = $this->get_tgmpa_plugins();

		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating', 'merlin-wp' ),
				);
				break;
			}
		}

		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( 'Updating', 'merlin-wp' ),
				);
				break;
			}
		}

		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing', 'merlin-wp' ),
				);
				break;
			}
		}

		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) );
			wp_send_json( $json );
		} else {
			$this->plugin_activation('mailchimp-for-wp/mailchimp-for-wp.php');
			$this->plugin_activation('megamenu/megamenu.php');
			$this->plugin_activation('woocommerce/woocommerce.php');
			$this->plugin_activation('web-to-print-online-designer/nbdesigner.php');
			$this->plugin_activation('revslider/revslider.php');
			wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success', 'merlin-wp' ) ) );
		}

		exit;
	}

	/**
	 * Do content's AJAX
	 *
	 * @internal    Used as a callback.
	 */
	function _ajax_content() {
		static $content = null;

		$selected_index = 0;
		if(isset($_POST['selected_index'])) {
			$selected_index = $_POST['selected_index'];
			set_transient('selected_index_theme', $_POST['selected_index'], 60*60*1);
		} else {
			if(!empty(get_transient('selected_index_theme'))) {
				$selected_index = get_transient('selected_index_theme');
			}
		}
		// write_log('test selected_index: '.$selected_index);
		$selected_import = intval( $selected_index );

		if ( null === $content ) {
			$content = $this->get_import_data( $selected_import );
		}

		if ( ! check_ajax_referer( 'merlin_nonce', 'wpnonce' ) || empty( $_POST['content'] ) && isset( $content[ $_POST['content'] ] ) ) {
			wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'Invalid content!', 'merlin-wp' ) ) );
		}

		$json = false;
		$this_content = $content[ $_POST['content'] ];
		$_SESSION['current_content_process'] = $_POST['content'];

		if ( isset( $_POST['proceed'] ) ) {
			if ( is_callable( $this_content['install_callback'] ) ) {
				$logs = call_user_func( $this_content['install_callback'], $this_content['data'] );
				if ( $logs ) {
					$json = array(
						'done'    => 1,
						'message' => $this_content['success'],
						'debug'   => '',
						'logs'    => $logs,
						'errors'  => '',
					);
				}
			}
		} else {

			$json = array(
				'url'      => admin_url( 'admin-ajax.php' ),
				'action'   => 'merlin_content',
				'proceed'  => 'true',
				'content'  => $_POST['content'],
				'_wpnonce' => wp_create_nonce( 'merlin_nonce' ),
				'message'  => $this_content['installing'],
				'logs'     => '',
				'errors'   => '',
			);
		}

		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) );
			wp_send_json( $json );
		} else {
			wp_send_json( array(
				'error'   => 1,
				'message' => esc_html__( 'Error', 'merlin-wp' ),
				'logs'    => '',
				'errors'  => '',
			) );
		}
	}


	/**
	 * Get import data from the selected import.
	 * Which data does the selected import have for the import.
	 *
	 * @param int $selected_import_index The index of the predefined demo import.
	 *
	 * @return bool|array
	 */
	public function get_import_data_info( $selected_import_index = 0 ) {
		$import_data = array(
			// 'post'      => false,
			// 'product'      => false,
			'content'      => false,
			// 'menu'      => false,
			'widgets'      => false,
			'options'      => false,
			'sliders'      => false,
			'sliders2'      => false,
			'redux'        => false,
			'after_import' => false,
		);

		if ( empty( $this->import_files[ $selected_import_index ] ) ) {
			return false;
		}

		if (
			! empty( $this->import_files[ $selected_import_index ]['local_import_file_data'] )
		) {
			$import_data['content'] = true;
		}

		// if (
		// 	! empty( $this->import_files[ $selected_import_index ]['local_import_file_menu'] )
		// ) {
		// 	$import_data['menu'] = true;
		// }

		// if (
		// 	! empty( $this->import_files[ $selected_import_index ]['local_import_file_posts'] )
		// ) {
		// 	$import_data['post'] = true;
		// }

		// if (
		// 	! empty( $this->import_files[ $selected_import_index ]['local_import_file_products'] )
		// ) {
		// 	$import_data['product'] = true;
		// }

		if (
			! empty( $this->import_files[ $selected_import_index ]['import_widget_file_url'] ) ||
			! empty( $this->import_files[ $selected_import_index ]['local_import_widget_file'] )
		) {
			$import_data['widgets'] = true;
		}

		if (
			! empty( $this->import_files[ $selected_import_index ]['import_customizer_file_url'] ) ||
			! empty( $this->import_files[ $selected_import_index ]['local_import_customizer_file'] )
		) {
			$import_data['options'] = true;
		}

		if (
			! empty( $this->import_files[ $selected_import_index ]['import_rev_slider_file_url'] ) ||
			! empty( $this->import_files[ $selected_import_index ]['local_import_rev_slider_file'] )
		) {
			$import_data['sliders'] = true;
		}

		if (
			! empty( $this->import_files[ $selected_import_index ]['local_import_rev_slider_file2'] )
		) {
			$import_data['sliders2'] = true;
		}

		if (
			! empty( $this->import_files[ $selected_import_index ]['import_redux'] ) ||
			! empty( $this->import_files[ $selected_import_index ]['local_import_redux'] )
		) {
			$import_data['redux'] = true;
		}

		if ( false !== has_action( 'merlin_after_all_import' ) ) {
			$import_data['after_import'] = true;
		}

		return $import_data;
	}


	/**
	 * Get the import files/data.
	 *
	 * @param int $selected_import_index The index of the predefined demo import.
	 *
	 * @return    array
	 */
	protected function get_import_data( $selected_import_index = 0 ) {
		$content = array();

		$import_files = $this->get_import_files_paths( $selected_import_index );

		// if ( ! empty( $import_files['posts'] ) ) {
		// 	$content['post'] = array(
		// 		'title'             => esc_html__( 'Post', 'merlin-wp' ),
		// 		'description'       => esc_html__( 'Demo post data.', 'merlin-wp' ),
		// 		'pending'           => esc_html__( 'Pending', 'merlin-wp' ),
		// 		'installing'        => esc_html__( 'Installing', 'merlin-wp' ),
		// 		'success'           => esc_html__( 'Success', 'merlin-wp' ),
		// 		'checked'           => $this->is_possible_upgrade() ? 0 : 1,
		// 		'install_callback'  => array( $this->importer, 'import' ),
		// 		'data'              => $import_files['posts'],
		// 	);
		// }

		// if ( ! empty( $import_files['products'] ) ) {
		// 	$content['product'] = array(
		// 		'title'             => esc_html__( 'Product', 'merlin-wp' ),
		// 		'description'       => esc_html__( 'Demo product data.', 'merlin-wp' ),
		// 		'pending'           => esc_html__( 'Pending', 'merlin-wp' ),
		// 		'installing'        => esc_html__( 'Installing', 'merlin-wp' ),
		// 		'success'           => esc_html__( 'Success', 'merlin-wp' ),
		// 		'checked'           => $this->is_possible_upgrade() ? 0 : 1,
		// 		'install_callback'  => array( $this->importer, 'import' ),
		// 		'data'              => $import_files['products'],
		// 	);
		// }

		// if ( ! empty( $import_files['menus'] ) ) {
		// 	$content['menu'] = array(
		// 		'title'             => esc_html__( 'Menu', 'merlin-wp' ),
		// 		'description'       => esc_html__( 'Demo menu data.', 'merlin-wp' ),
		// 		'pending'           => esc_html__( 'Pending', 'merlin-wp' ),
		// 		'installing'        => esc_html__( 'Installing', 'merlin-wp' ),
		// 		'success'           => esc_html__( 'Success', 'merlin-wp' ),
		// 		'checked'           => $this->is_possible_upgrade() ? 0 : 1,
		// 		'install_callback'  => array( $this->importer, 'import' ),
		// 		'data'              => $import_files['menus'],
		// 	);
		// }

		if ( ! empty( $import_files['content'] ) ) {
			$content['content'] = array(
				'title'             => esc_html__( 'Content', 'merlin-wp' ),
				'description'       => esc_html__( 'Demo content data.', 'merlin-wp' ),
				'pending'           => esc_html__( 'Pending', 'merlin-wp' ),
				'installing'        => esc_html__( 'Installing', 'merlin-wp' ),
				'success'           => esc_html__( 'Success', 'merlin-wp' ),
				'checked'           => $this->is_possible_upgrade() ? 0 : 1,
				'install_callback'  => array( $this->importer, 'import' ),
				'data'              => $import_files['content'],
			);
		}

		if ( ! empty( $import_files['widgets'] )  ) {
			$content['widgets'] = array(
				'title'            => esc_html__( 'Widgets', 'merlin-wp' ),
				'description'      => esc_html__( 'Sample widgets data.', 'merlin-wp' ),
				'pending'          => esc_html__( 'Pending', 'merlin-wp' ),
				'installing'       => esc_html__( 'Installing', 'merlin-wp' ),
				'success'          => esc_html__( 'Success', 'merlin-wp' ),
				'install_callback' => array( 'Merlin_Widget_Importer', 'import' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				'data'             => $import_files['widgets'],
			);
		}

		if ( ! empty( $import_files['sliders'] )  ) {
			$content['sliders'] = array(
				'title'            => esc_html__( 'Revolution Slider', 'merlin-wp' ),
				'description'      => esc_html__( 'Sample Revolution sliders data.', 'merlin-wp' ),
				'pending'          => esc_html__( 'Pending', 'merlin-wp' ),
				'installing'       => esc_html__( 'Installing', 'merlin-wp' ),
				'success'          => esc_html__( 'Success', 'merlin-wp' ),
				'install_callback' => array( $this, 'import_revolution_sliders' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				'data'             => $import_files['sliders'],
			);
		}

		if ( ! empty( $import_files['sliders2'] )  ) {
			$content['sliders2'] = array(
				'title'            => esc_html__( 'Revolution Slider', 'merlin-wp' ),
				'description'      => esc_html__( 'Sample Revolution sliders data.', 'merlin-wp' ),
				'pending'          => esc_html__( 'Pending', 'merlin-wp' ),
				'installing'       => esc_html__( 'Installing', 'merlin-wp' ),
				'success'          => esc_html__( 'Success', 'merlin-wp' ),
				'install_callback' => array( $this, 'import_revolution_sliders' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				'data'             => $import_files['sliders2'],
			);
		}

		if ( ! empty( $import_files['options'] )  ) {
			$content['options'] = array(
				'title'            => esc_html__( 'Options', 'merlin-wp' ),
				'description'      => esc_html__( 'Sample theme options data.', 'merlin-wp' ),
				'pending'          => esc_html__( 'Pending', 'merlin-wp' ),
				'installing'       => esc_html__( 'Installing', 'merlin-wp' ),
				'success'          => esc_html__( 'Success', 'merlin-wp' ),
				'install_callback' => array( 'Merlin_Customizer_Importer', 'import' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				'data'             => $import_files['options'],
			);
			set_transient('selected_options_theme', $import_files['options'], 60*60*1);
		}

		if ( ! empty( $import_files['redux'] )  ) {
			$content['redux'] = array(
				'title'            => esc_html__( 'Redux Options', 'merlin-wp' ),
				'description'      => esc_html__( 'Redux framework options.', 'merlin-wp' ),
				'pending'          => esc_html__( 'Pending', 'merlin-wp' ),
				'installing'       => esc_html__( 'Installing', 'merlin-wp' ),
				'success'          => esc_html__( 'Success', 'merlin-wp' ),
				'install_callback' => array( 'Merlin_Redux_Importer', 'import' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				'data'             => $import_files['redux'],
			);
		}

		if ( false !== has_action( 'merlin_after_all_import' ) ) {
			$content['after_import'] = array(
				'title'            => esc_html__( 'After import setup', 'merlin-wp' ),
				'description'      => esc_html__( 'After import setup.', 'merlin-wp' ),
				'pending'          => esc_html__( 'Pending', 'merlin-wp' ),
				'installing'       => esc_html__( 'Installing', 'merlin-wp' ),
				'success'          => esc_html__( 'Success', 'merlin-wp' ),
				'install_callback' => array( $this->hooks, 'after_all_import_action' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				'data'             => $selected_import_index,
			);
		}

		$content = apply_filters( 'merlin_get_base_content', $content, $this );

		return $content;
	}

	/**
	 * Import revolution slider.
	 *
	 * @param string $file Path to the revolution slider zip file.
	 */
	public function import_revolution_sliders( $file ) {
		if ( ! class_exists( 'RevSlider', false ) ) {
			return 'failed';
		}

		$importer = new RevSlider();
		$response = $importer->importSliderFromPost(true, true, $file);

		if (defined('DOING_AJAX') && DOING_AJAX) {
			return 'true';
		}
	}

	/**
	 * Change the new AJAX request response data.
	 *
	 * @param array $data The default data.
	 *
	 * @return array The updated data.
	 */
	public function pt_importer_new_ajax_request_response_data( $data ) {
		write_log('Current content process: '.$_SESSION['current_content_process']);
		$data['url']      = admin_url( 'admin-ajax.php' );
		$data['message']  = esc_html__( 'Installing', 'merlin-wp' );
		$data['proceed']  = 'true';
		$data['action']   = 'merlin_content';
		$data['content']  = (isset($_SESSION['current_content_process'])?$_SESSION['current_content_process']:'content');
		$data['_wpnonce'] = wp_create_nonce( 'merlin_nonce' );
		$data['hash']     = md5( rand() ); // Has to be unique (check JS code catching this AJAX response).

		return $data;
	}

	/**
	 * After content import setup code.
	 */
	public function after_content_import_setup() {
		// Set static homepage.
		$front_title = 'Home';
		if(!empty(get_transient('title_home_page'))) {
			$front_title = get_transient('title_home_page');
		}
		$homepage = get_page_by_title( apply_filters( 'merlin_content_home_page_title', $front_title ) );

		if ( $homepage ) {
			update_option( 'page_on_front', $homepage->ID );
			update_option( 'show_on_front', 'page' );
		}

		// Set static blog page.
		$blogpage = get_page_by_title( apply_filters( 'merlin_content_blog_page_title', 'Blog' ) );

		if ( $blogpage ) {
			update_option( 'page_for_posts', $blogpage->ID );
			update_option( 'show_on_front', 'page' );
		}
	}

	/**
	 * Before content import setup code.
	 */
	public function before_content_import_setup() {
		global $wpdb;
		
		$this->generate_child();

		// date_default_timezone_set('Asia/Ho_Chi_Minh');
		if(!empty(get_transient('local_import_megamenu_themes'))) {
			
			$megamenu_options = get_transient('local_import_megamenu_themes');

			//update megamenu_themes option
			$megamenu_themes = $this->thim_file_get_contents($megamenu_options['megamenu_themes']);
			
			$test = $wpdb->replace($wpdb->prefix.'options', 
				array( 
					'option_name' => 'megamenu_themes',
					'option_value' => $megamenu_themes, 
					'autoload' => 'yes'
				), 
				array( 
					'%s',
					'%s',
					'%s' 
				) 
			);

			//update megamenu_settings option
			$megamenu_settings = $this->thim_file_get_contents($megamenu_options['megamenu_settings']);
			$test = $wpdb->replace($wpdb->prefix.'options', 
				array( 
					'option_name' => 'megamenu_settings',
					'option_value' => $megamenu_settings, 
					'autoload' => 'yes'
				), 
				array( 
					'%s',
					'%s', 
					'%s' 
				) 
			);

			do_action( "megamenu_after_save_settings" );

			do_action( "megamenu_delete_cache" );
			// write_log('Result copy style 1: '.$test.' from '.get_transient('local_import_megamenu_themes').' at '.date('d/m/Y h:i:s a', time()));
		}

		if(!empty(get_transient('local_import_icon_font_file'))) {
			if ( class_exists( 'AIO_Icon_Manager' ) ) {
				try {
					$path = get_transient('local_import_icon_font_file');
					$aim = new AIO_Icon_Manager();
					$unzipped   = $aim->zip_flatten( $path, array( '\.eot', '\.svg', '\.ttf', '\.woff', '\.json', '\.css' ) );
					// if we were able to unzip the file and save it to our temp folder extract the svg file
					if ( $unzipped ) {

						$response = wp_remote_fopen( trailingslashit( $aim->paths['tempurl'] ) . $aim->svg_file );

						$json = file_get_contents( trailingslashit( $aim->paths['tempdir'] ) . $aim->json_file );
						if ( empty( $response ) ) {
							$response = file_get_contents( trailingslashit( $aim->paths['tempdir'] ) . $aim->svg_file );
						}

						if ( ! is_wp_error( $json ) && ! empty( $json ) ) {
							$xml             = simplexml_load_string( $response );
							$font_attr       = $xml->defs->font->attributes();
							$aim->font_name = (string) $font_attr['id'];

							$font_folder = trailingslashit( $aim->paths['fontdir'] ) . $aim->font_name;
							if ( is_dir( $font_folder ) ) {
								$kq = $aim->create_config();
							}
						}
					}
				} catch (Exception $e) {
					write_log('Caught exception: '.$e->getMessage());
				}
			}
		}

		// Update the Hello World! post by making it a draft.
		$hello_world = get_page_by_title( 'Hello World!', OBJECT, 'post' );

		if ( ! empty( $hello_world ) ) {
			$hello_world->post_status = 'draft';
			wp_update_post( $hello_world );
		}

		$this->setting_default();
	}

	/**
	 * Register the import files via the `merlin_import_files` filter.
	 */
	public function register_import_files() {
		$this->import_files = $this->validate_import_file_info( apply_filters( 'merlin_import_files', array() ) );
	}

	/**
	 * Filter through the array of import files and get rid of those who do not comply.
	 *
	 * @param  array $import_files list of arrays with import file details.
	 * @return array list of filtered arrays.
	 */
	public function validate_import_file_info( $import_files ) {
		$filtered_import_file_info = array();

		foreach ( $import_files as $import_file ) {
			if ( ! empty( $import_file['import_file_name'] ) ) {
				$filtered_import_file_info[] = $import_file;
			}
		}

		return $filtered_import_file_info;
	}

	/**
	 * Set the import file base name.
	 * Check if an existing base name is available (saved in a transient).
	 */
	public function set_import_file_base_name() {
		$existing_name = get_transient( 'merlin_import_file_base_name' );

		if ( ! empty( $existing_name ) ) {
			$this->import_file_base_name = $existing_name;
		}
		else {
			$this->import_file_base_name = date( 'Y-m-d__H-i-s' );
		}

		set_transient( 'merlin_import_file_base_name', $this->import_file_base_name, MINUTE_IN_SECONDS );
	}

	/**
	 * Get the import file paths.
	 * Grab the defined local paths, download the files or reuse existing files.
	 *
	 * @param int $selected_import_index The index of the selected import.
	 *
	 * @return array
	 */
	public function get_import_files_paths( $selected_import_index ) {
		$selected_import_data = empty( $this->import_files[ $selected_import_index ] ) ? false : $this->import_files[ $selected_import_index ];

		// write_log($selected_import_data);

		if ( empty( $selected_import_data ) ) {
			return array();
		}

		// Set the base name for the import files.
		$this->set_import_file_base_name();

		$base_file_name = $this->import_file_base_name;
		$import_files   = array(
			'content' => '',
			'widgets' => '',
			'options' => '',
			'redux'   => array(),
			'sliders' => '',
			'sliders2' => '',
		);

		$downloader = new Merlin_Downloader();

		if (isset($selected_import_data['product_variation'] )) {
			if ( ! empty( $selected_import_data['product_variation'] ) ) {
				set_transient('product_variation', $selected_import_data['product_variation'], 60*60*1);
			}
		}

		if (isset($selected_import_data['url_path_child_theme'] )) {
			if ( ! empty( $selected_import_data['url_path_child_theme'] ) ) {
				set_transient('url_path_child_theme', $selected_import_data['url_path_child_theme'], 60*60*1);
			}
		}

		if (isset($selected_import_data['local_import_icon_font_file'] )) {
			if ( ! empty( $selected_import_data['local_import_icon_font_file'] ) ) {
				set_transient('local_import_icon_font_file', $selected_import_data['local_import_icon_font_file'], 60*60*1);
			}
		}

		if (isset($selected_import_data['local_import_widget_nav_menu_file'] )) {
			if ( ! empty( $selected_import_data['local_import_widget_nav_menu_file'] ) ) {
				set_transient('local_import_widget_nav_menu_file', $selected_import_data['local_import_widget_nav_menu_file'], 60*60*1);
			}
		}

		if ( ! empty( $selected_import_data['local_import_megamenu_themes'] ) ) {
			set_transient('local_import_megamenu_themes', $selected_import_data['local_import_megamenu_themes'], 60*60*1);
		}

		if ( ! empty( $selected_import_data['term_meta_key'] ) ) {
			set_transient('term_meta_key', $selected_import_data['term_meta_key'], 60*60*1);
		}

		if ( ! empty( $selected_import_data['menu_settings'] ) ) {
			set_transient('menu_settings', $selected_import_data['menu_settings'], 60*60*1);
		}

		if ( ! empty( $selected_import_data['import_preview_image_url'] ) ) {
			set_transient('import_preview_image_url', $selected_import_data['import_preview_image_url'], 60*60*1);
		}

		if ( ! empty( $selected_import_data['title_home_page'] ) ) {
			set_transient('title_home_page', $selected_import_data['title_home_page'], 60*60*1);
		}

		if ( ! empty( $selected_import_data['local_path_child_theme_fun'] ) ) {
			set_transient('local_path_child_theme_fun', $selected_import_data['local_path_child_theme_fun'], 60*60*1);
		}

		if ( ! empty( $selected_import_data['local_path_child_theme_style'] ) ) {
			set_transient('local_path_child_theme_style', $selected_import_data['local_path_child_theme_style'], 60*60*1);
		}

		if ( ! empty( $selected_import_data['local_path_child_theme_js'] ) ) {
			set_transient('local_path_child_theme_js', $selected_import_data['local_path_child_theme_js'], 60*60*1);
		}

		if ( ! empty( $selected_import_data['local_import_file_data'] ) && file_exists( $selected_import_data['local_import_file_data'] ) ) {
			$import_files['content'] = $selected_import_data['local_import_file_data'];
		}

		if (isset($selected_import_data['local_import_product_cat'])) {
			if(is_file($selected_import_data['local_import_product_cat'])) {
				set_transient('local_import_product_cat', $selected_import_data['local_import_product_cat'], 60*60*1);
			}
		}
		
		if (isset($selected_import_data['local_import_solutions_core'])) {
			if(is_file($selected_import_data['local_import_solutions_core'])) {
				set_transient('local_import_solutions_core', $selected_import_data['local_import_solutions_core'], 60*60*1);
					// write_log('Copy file: '.$selected_import_data['local_import_solutions_core']);
			}
		}

		// Get widgets file as well. If defined!
		if ( ! empty( $selected_import_data['import_widget_file_url'] ) ) {
			// Set the filename string for widgets import file.
			$widget_filename = 'widgets-' . $base_file_name . '.json';

			// Retrieve the content import file.
			$import_files['widgets'] = $downloader->fetch_existing_file( $widget_filename );

			// Download the file, if it's missing.
			if ( empty( $import_files['widgets'] ) ) {
				$import_files['widgets'] = $downloader->download_file( $selected_import_data['import_widget_file_url'], $widget_filename );
			}

			// Reset the variable, if there was an error.
			if ( is_wp_error( $import_files['widgets'] ) ) {
				$import_files['widgets'] = '';
			}
		}
		else if ( ! empty( $selected_import_data['local_import_widget_file'] ) ) {
			if ( file_exists( $selected_import_data['local_import_widget_file'] ) ) {
				$import_files['widgets'] = $selected_import_data['local_import_widget_file'];
			}
		}

		// Get customizer import file as well. If defined!
		if ( ! empty( $selected_import_data['import_customizer_file_url'] ) ) {
			// Setup filename path to save the customizer content.
			$customizer_filename = 'options-' . $base_file_name . '.dat';

			// Retrieve the content import file.
			$import_files['options'] = $downloader->fetch_existing_file( $customizer_filename );

			// Download the file, if it's missing.
			if ( empty( $import_files['options'] ) ) {
				$import_files['options'] = $downloader->download_file( $selected_import_data['import_customizer_file_url'], $customizer_filename );
			}

			// Reset the variable, if there was an error.
			if ( is_wp_error( $import_files['options'] ) ) {
				$import_files['options'] = '';
			}
		}
		else if ( ! empty( $selected_import_data['local_import_customizer_file'] ) ) {
			if ( file_exists( $selected_import_data['local_import_customizer_file'] ) ) {
				$import_files['options'] = $selected_import_data['local_import_customizer_file'];
			}
		}

		// Get revolution slider import file as well. If defined!
		if ( ! empty( $selected_import_data['import_rev_slider_file_url'] ) ) {
			// Setup filename path to save the customizer content.
			$rev_slider_filename = 'slider-' . $base_file_name . '.zip';

			// Retrieve the content import file.
			$import_files['sliders'] = $downloader->fetch_existing_file( $rev_slider_filename );

			// Download the file, if it's missing.
			if ( empty( $import_files['sliders'] ) ) {
				$import_files['sliders'] = $downloader->download_file( $selected_import_data['import_rev_slider_file_url'], $rev_slider_filename );
			}

			// Reset the variable, if there was an error.
			if ( is_wp_error( $import_files['sliders'] ) ) {
				$import_files['sliders'] = '';
			}
		}
		else if ( ! empty( $selected_import_data['local_import_rev_slider_file'] ) ) {
			if ( file_exists( $selected_import_data['local_import_rev_slider_file'] ) ) {
				$import_files['sliders'] = $selected_import_data['local_import_rev_slider_file'];
			}
		}

		if ( ! empty( $selected_import_data['local_import_rev_slider_file2'] ) ) {
			if ( file_exists( $selected_import_data['local_import_rev_slider_file2'] ) ) {
				$import_files['sliders2'] = $selected_import_data['local_import_rev_slider_file2'];
			}
		}

		// Get redux import file as well. If defined!
		if ( ! empty( $selected_import_data['import_redux'] ) ) {
			$redux_items = array();

			// Setup filename paths to save the Redux content.
			foreach ( $selected_import_data['import_redux'] as $index => $redux_item ) {
				$redux_filename = 'redux-' . $index . '-' . $base_file_name . '.json';

				// Retrieve the content import file.
				$file_path = $downloader->fetch_existing_file( $redux_filename );

				// Download the file, if it's missing.
				if ( empty( $file_path ) ) {
					$file_path = $downloader->download_file( $redux_item['file_url'], $redux_filename );
				}

				// Reset the variable, if there was an error.
				if ( is_wp_error( $file_path ) ) {
					$file_path = '';
				}

				$redux_items[] = array(
					'option_name' => $redux_item['option_name'],
					'file_path'   => $file_path,
				);
			}

			// Download the Redux import file.
			$import_files['redux'] = $redux_items;
		}
		else if ( ! empty( $selected_import_data['local_import_redux'] ) ) {
			$redux_items = array();

			// Setup filename paths to save the Redux content.
			foreach ( $selected_import_data['local_import_redux'] as $redux_item ) {
				if ( file_exists( $redux_item['file_path'] ) ) {
					$redux_items[] = $redux_item;
				}
			}

			// Download the Redux import file.
			$import_files['redux'] = $redux_items;
		}

		return $import_files;
	}


	/**
	 * AJAX callback for the 'merlin_update_selected_import_data_info' action.
	 */
	public function update_selected_import_data_info() {
		$selected_index = ! isset( $_POST['selected_index'] ) ? false : intval( $_POST['selected_index'] );

		if ( false === $selected_index ) {
			wp_send_json_error();
		}

		$import_info = $this->get_import_data_info( $selected_index );
		$import_info_html = $this->get_import_steps_html( $import_info );

		wp_send_json_success( $import_info_html );
	}

	/**
	 * Get the import steps HTML output.
	 *
	 * @param array $import_info The import info to prepare the HTML for.
	 *
	 * @return string
	 */
	public function get_import_steps_html( $import_info ) {
		ob_start();
		?>
		<?php foreach ( $import_info as $slug => $available ) : ?>
			<?php
			if ( ! $available ) {
				continue;
			}
			?>

			<li class="merlin__drawer--import-content__list-item status status--Pending" data-content="<?php echo esc_attr( $slug ); ?>">
				<input type="checkbox" name="default_content[<?php echo esc_attr( $slug ); ?>]" class="checkbox" id="default_content_<?php echo esc_attr( $slug ); ?>" value="1" checked>
				<label for="default_content_<?php echo esc_attr( $slug ); ?>">
					<i></i><span><?php echo esc_html( ucfirst( str_replace( '_', ' ', $slug ) ) ); ?></span>
				</label>
			</li>

		<?php endforeach; ?>
		<?php

		return ob_get_clean();
	}


	/**
	 * AJAX call for cleanup after the importing steps are done -> import finished.
	 *
	 * @return bool
	 */
	public function import_finished() {
		global $wpdb;

		if(!empty(get_transient('menu_settings'))) {

			$menu_settings 			= get_transient('menu_settings');
			$menu_location_array 	= array();
			foreach($menu_settings as $menu_location => $menu_name) {
				
				// Assign menus to their locations.
				$current_menu = get_term_by( 'name', $menu_name, 'nav_menu' );
				$menu_location_array[$menu_location] = $current_menu->term_id;

			}
			set_theme_mod( 'nav_menu_locations', $menu_location_array);
		}

		//import Max Megamenu
		if( class_exists( 'Mega_Menu' ) ) {

			$sidebar_widgets    = get_option( 'sidebars_widgets' );

			if( isset( $sidebar_widgets[ 'mega-menu' ] ) ) {

				$mm_sidebar_widgets = $sidebar_widgets[ 'mega-menu' ];


				$mm_imported_widgets  = $this->get_imported_megamenu_data( $mm_sidebar_widgets );
				$new_mm_widget_id       = $this->rebuild_mmm_widget_id( $mm_sidebar_widgets , $mm_imported_widgets );

                    //update megamenu grid type post meta
				$this->update_megamenu_grid_type_post_meta( $new_mm_widget_id );
			}
		}

		if(!empty(get_transient('local_import_product_cat')) && !empty(get_transient('title_home_page'))) {
			$this->set_image_product_cat(get_transient('title_home_page'), get_transient('local_import_product_cat'));
		}

		if(!empty(get_transient('local_import_solutions_core'))) {
			$this->set_default_solution_core(get_transient('local_import_solutions_core'));
		}

		if(!empty(get_transient('local_import_widget_nav_menu_file'))) {
			$this->set_widget_nav_menu(get_transient('local_import_widget_nav_menu_file'));
		}

		if(!empty(get_transient('product_variation')) && class_exists( 'WooCommerce' )) {
			$this->input_price_matrix(get_transient('product_variation'));
		}

		$arr_page_setup = array('woocommerce_cart_page_id' => 'Cart', 'woocommerce_checkout_page_id' => 'Checkout', 'woocommerce_myaccount_page_id' => 'My account', 'woocommerce_terms_page_id' => 'Terms of Service', 'woocommerce_shop_page_id' => 'Shop', 'yith_wcwl_wishlist_page_id' => 'Wishlist');
		foreach ($arr_page_setup as $key => $value) {
			$page = get_page_by_title(trim($value));
			if($page) {
				$kq = $wpdb->replace($wpdb->prefix.'options', 
					array( 
						'option_name' => $key,
						'option_value' => $page->ID, 
						'autoload' => 'yes'
					), 
					array( 
						'%s',
						'%s', 
						'%s' 
					) 
				);
			}
		}

		save_mod_rewrite_rules();
		
		delete_transient( 'local_import_megamenu_themes' );
		delete_transient( 'mega_style_path' );

		delete_transient( 'selected_options_theme' );
		delete_transient( 'menu_settings' );
		delete_transient( 'local_import_product_cat' );
		delete_transient( 'title_home_page' );
		delete_transient( 'local_import_solutions_core' );
		delete_transient( 'merlin_import_file_base_name' );
		delete_transient( 'selected_index_theme' );
		delete_transient( 'url_path_child_theme' );

		wp_send_json_success();
	}

	function input_price_matrix($product_variation) {
		if (isset($product_variation['price_matrix'])) {
			$this->create_all_variation($product_variation['price_matrix']);
		}
		if (isset($product_variation['color_swatches'])) {
			$this->create_all_variation($product_variation['color_swatches'], 'cs');
		}
	}

	function create_all_variation($slug = '', $type = 'pm') {
		if($slug!='') {
			$post_id = get_page_by_path( $slug, OBJECT, 'product' )->ID;
			$post_id = intval( $post_id );
			write_log($post_id);
			if ( $post_id ) {
				$product    = wc_get_product( $post_id );
				$attributes = wc_list_pluck( array_filter( $product->get_attributes(), 'wc_attributes_array_filter_variation' ), 'get_slugs' );
				if ( ! empty( $attributes ) ) {
			// Get existing variations so we don't create duplicates.
					$existing_variations = array_map( 'wc_get_product', $product->get_children() );
					$existing_attributes = array();

					foreach ( $existing_variations as $existing_variation ) {
						$existing_attributes[] = $existing_variation->get_attributes();
					}

					$added               = 0;
					$possible_attributes = array_reverse( wc_array_cartesian( $attributes ) );

					foreach ( $possible_attributes as $possible_attribute ) {
						if ( in_array( $possible_attribute, $existing_attributes ) ) {
							continue;
						}
						$variation = new WC_Product_Variation();
						$variation->set_parent_id( $post_id );
						$variation->set_attributes( $possible_attribute );
						$price = random_int(1, 100);
						$variation->set_price($price);
						$variation->set_regular_price($price);
						if($type=='cs') {
							$variation->set_stock_quantity(random_int(1, 100));
							$variation->set_stock_status();
						}

						do_action( 'product_variation_linked', $variation->save() );

						if ( ( $added ++ ) > 49 ) {
							break;
						}
					}
					write_log("Number added: ".$added);
				}
				$data_store = $product->get_data_store();
				$data_store->sort_all_product_variations( $product->get_id() );
			}
		}
	}

	function setting_default() {
		global $wpdb;
		$arr_setting_default = array(
			"permalink_structure" => "/%postname%/",
			"woocommerce_currency" => "USD",
			"nbdesigner_class_design_button_detail" => "start-design bt-4", 
			"nbdesigner_class_design_button_catalog" => "start-design bt-4", 
			"woocommerce_store_address" => "Số 201 Bạch Mai - Hai Bà Trưng - Hà Nội", 
			"woocommerce_store_city" => "Hà Nội",
			"woocommerce_default_country" => "VN", 
			"woocommerce_store_postcode" => "100000", 
			"woocommerce_all_except_countries" => "a:0:{}", 
			"woocommerce_specific_allowed_countries" => "a:0:{}", 
			"woocommerce_specific_ship_to_countries" => "a:0:{}", 
			"woocommerce_bacs_settings" => "a:11:{s:7:\"enabled\";s:3:\"yes\";s:5:\"title\";s:20:\"Direct bank transfer\";s:11:\"description\";s:176:\"Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.\";s:12:\"instructions\";s:0:\"\";s:15:\"account_details\";s:0:\"\";s:12:\"account_name\";s:0:\"\";s:14:\"account_number\";s:0:\"\";s:9:\"sort_code\";s:0:\"\";s:9:\"bank_name\";s:0:\"\";s:4:\"iban\";s:0:\"\";s:3:\"bic\";s:0:\"\";}",
			"woocommerce_cheque_settings" => "a:4:{s:7:\"enabled\";s:3:\"yes\";s:5:\"title\";s:14:\"Check payments\";s:11:\"description\";s:98:\"Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.\";s:12:\"instructions\";s:0:\"\";}",
			"woocommerce_cod_settings" => "a:6:{s:7:\"enabled\";s:3:\"yes\";s:5:\"title\";s:16:\"Cash on delivery\";s:11:\"description\";s:28:\"Pay with cash upon delivery.\";s:12:\"instructions\";s:28:\"Pay with cash upon delivery.\";s:18:\"enable_for_methods\";a:0:{}s:18:\"enable_for_virtual\";s:3:\"yes\";}",
			"woocommerce_paypal_settings" => "a:23:{s:7:\"enabled\";s:3:\"yes\";s:5:\"title\";s:6:\"PayPal\";s:11:\"description\";s:85:\"Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.\";s:5:\"email\";s:25:\"thanhminh9108@yopmail.com\";s:8:\"advanced\";s:0:\"\";s:8:\"testmode\";s:2:\"no\";s:5:\"debug\";s:2:\"no\";s:16:\"ipn_notification\";s:3:\"yes\";s:14:\"receiver_email\";s:25:\"thanhminh9108@yopmail.com\";s:14:\"identity_token\";s:0:\"\";s:14:\"invoice_prefix\";s:3:\"WC-\";s:13:\"send_shipping\";s:3:\"yes\";s:16:\"address_override\";s:2:\"no\";s:13:\"paymentaction\";s:4:\"sale\";s:10:\"page_style\";s:0:\"\";s:9:\"image_url\";s:0:\"\";s:11:\"api_details\";s:0:\"\";s:12:\"api_username\";s:0:\"\";s:12:\"api_password\";s:0:\"\";s:13:\"api_signature\";s:0:\"\";s:20:\"sandbox_api_username\";s:0:\"\";s:20:\"sandbox_api_password\";s:0:\"\";s:21:\"sandbox_api_signature\";s:0:\"\";}",
			"woocommerce_gateway_order" => "a:4:{s:4:\"bacs\";i:0;s:6:\"cheque\";i:1;s:3:\"cod\";i:2;s:6:\"paypal\";i:3;}",
		);
		foreach ($arr_setting_default as $key => $value) {
			$kq2 = $wpdb->replace($wpdb->prefix.'options', 
				array( 
					'option_name' => $key,
					'option_value' => $value, 
					'autoload' => 'yes'
				), 
				array( 
					'%s',
					'%s', 
					'%s' 
				) 
			);
		}
	}

	function recurse_copy($src,$dst) { 
		$dir = opendir($src); 
		@mkdir($dst); 
		while(false !== ( $file = readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) { 
					$this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
				} 
				else { 
					copy($src . '/' . $file,$dst . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
	}

	function plugin_activation( $plugin ) {
		if( ! function_exists('activate_plugin') ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if( ! is_plugin_active( $plugin ) ) {
			activate_plugin( $plugin );
		}
	}

	function km_get_wordpress_uploads_directory_path() {
		$upload_dir = wp_upload_dir();
		return trailingslashit( $upload_dir['basedir'] );
	}

	/**
     * Get all imported megamenu data
     * @param  array $mm_imported_widgets
     */
	function get_imported_megamenu_data( $mm_sidebar_widgets ) {

		global $wpdb;

		$mm_imported_widgets = array();

		$megamenu_meta = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_megamenu'" );

		foreach ( $megamenu_meta as $meta ) {

			$meta_value = unserialize( $meta->meta_value );

			if( isset( $meta_value['type'] ) && $meta_value['type'] == 'grid' ) {

				foreach( $meta_value['grid'] as $i => $grids ) {                

					foreach( $grids['columns'] as $j => $column ) {

						foreach ( $column[ 'items' ] as $k => $item ) {                         

                            // $meta_value['grid'][$i]['columns'][$j]['items'][$k]['id'] = $new_widget_id;

							$mm_imported_widgets[] = $item[ 'id' ];
						}
					}
				}
			}
		}

		return $mm_imported_widgets;
	}

    /**
     * Rebuild max megamenu metadata after imported
     * @param  [array] $mm_sidebar_widgets     
     * @param  [array] $mm_imported_widgets 
     * @return [array]                        
     */
    function rebuild_mmm_widget_id( $mm_sidebar_widgets, $mm_imported_widgets) {

    	$mm_sidebar_widgets_by_key      = array();
    	$mm_imported_widgets_by_key    = array();
    	$rebuild_array                  = array();

    	foreach($mm_sidebar_widgets as $value) {
    		$exploded_widgets = explode('-', $value);
    		$mm_sidebar_widgets_by_key[$exploded_widgets[0]][] = $exploded_widgets[1];
    	}

    	foreach($mm_imported_widgets as $value) {
    		$exploded_widgets = explode('-', $value);
    		$mm_imported_widgets_by_key[$exploded_widgets[0]][] = $exploded_widgets[1];
    	}

    	foreach($mm_sidebar_widgets_by_key as $key => $a) {
    		rsort($a);
    		$mm_sidebar_widgets_by_key[$key] = $a;
    	}

    	foreach($mm_imported_widgets_by_key as $key => $a) {
    		rsort($a);
    		$mm_imported_widgets_by_key[$key] = $a;
    	}

    	foreach ($mm_imported_widgets_by_key as $key => $values) {

    		foreach($values as $index => $value ) {
    			$widget_key = $key . '-' . $value;
    			$new_value = $key . '-' . $mm_sidebar_widgets_by_key[$key][$index];
    			$rebuild_array[$widget_key] = $new_value;        
    		}
    	}

    	return $rebuild_array;
    }

    /**
     * Update incorrect wiget name in megamenu post metadata
     */
    function update_megamenu_grid_type_post_meta( $new_mm_widget_id ) {

    	global $wpdb;

    	$megamenu_meta = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_megamenu'" );

    	foreach ( $megamenu_meta as $meta ) {

    		$meta_value = unserialize( $meta->meta_value );

    		if( isset( $meta_value['type'] ) && $meta_value['type'] == 'grid' ) {

    			foreach( $meta_value['grid'] as $i => $grids ) {                

    				foreach( $grids['columns'] as $j => $column ) {

    					foreach ( $column[ 'items' ] as $k => $item ) {                         

    						$meta_value['grid'][$i]['columns'][$j]['items'][$k]['id'] = isset($new_mm_widget_id[ $item[ 'id' ] ]) ? $new_mm_widget_id[ $item[ 'id' ] ] : $item[ 'id' ];
    					}
    				}
    			}

                // update grid post meta data
    			update_post_meta( $meta->post_id, '_megamenu', $meta_value);
    		}
    	}
    }

    /**
 * Get data from a file with WP_Filesystem
 *
 * @param $file
 *
 * @return bool
 */
    function thim_file_get_contents( $file ) {
    	WP_Filesystem();
    	global $wp_filesystem;
    	return $wp_filesystem->get_contents( $file );
    }

    function set_widget_nav_menu($file) {
    	global $wpdb;
    	$content = "a:";
    	@$widget_nav_menu_settings = $this->thim_file_get_contents($file['widget_nav_menu']);
    	if ($widget_nav_menu_settings) {
    		$widget_nav_menu_settings = explode("\n", $widget_nav_menu_settings);
    		$widget_nav_menu_settings = array_filter($widget_nav_menu_settings);
    		$content.=(count($widget_nav_menu_settings)+1).':{';
    		for ($i = 0; $i < count($widget_nav_menu_settings); $i ++) {
    			$key_nav = explode(',', $widget_nav_menu_settings[$i]);
    			if (count($key_nav)>1) {
    				$slug_term = trim(strtolower(str_replace(' ', '_', trim($key_nav[1]))));
    				$term = get_term_by('slug', $slug_term, 'nav_menu');
    				if(count($term)>0) {
    					$content.="i:".($i+1).";a:2:{s:5:\"title\";s:".strlen(trim($key_nav[0])).":\"".trim($key_nav[0])."\";s:8:\"nav_menu\";i:".$term->term_taxonomy_id.";}";
    				}
    			}
    		}
    	}
    	if($content!="a:") {
    		$content2 = $content."s:12:\"_multiwidget\";i:1;}";
    		write_log($content2);
    		$kq = $wpdb->replace($wpdb->prefix.'options', 
    			array( 
    				'option_name' => 'widget_nav_menu',
    				'option_value' => $content2, 
    				'autoload' => 'yes'
    			), 
    			array( 
    				'%s',
    				'%s', 
    				'%s' 
    			) 
    		);
    		// $kq = update_option('widget_nav_menu', $content2);
    		write_log($kq);
    	}
    	return true;
    }

    function set_image_product_cat( $title, $file ) {
    	global $wpdb;
    	$page_home = '';
    	$begin = '[vc_printshop_our_services add_vc_our_services_category="%5B%7B%22';
    	$content = '';
    	$limit = 0;
    	$taxonomy = 'product_cat';
    	$meta_key = 'thumbnail_id';
    	if(!empty(get_transient('term_meta_key'))) {
    		$meta_key = get_transient('term_meta_key');
    	}
    	if($meta_key=='brands_thumbnail') {
    		$taxonomy = 'product_brand';
    	}
    	$page = get_page_by_title(trim($title));
    	if ($page) {
    		$page_home = $wpdb->get_var( $wpdb->prepare( "SELECT post_content FROM $wpdb->posts WHERE ID = %s", $page->ID ));
    	}

    	@$product_cat_settings = $this->thim_file_get_contents($file);
    	if ($product_cat_settings) {
    		$product_cat_settings = explode("\n", $product_cat_settings);
    		$product_cat_settings = array_filter($product_cat_settings);
    		for ($i = 0; $i < count($product_cat_settings); $i ++) {
    			$key_nav = explode(',', $product_cat_settings[$i]);
    			if (count($key_nav)>1) {
    				$slug_term = trim(strtolower(str_replace(' ', '_', trim($key_nav[0]))));
    				$post_title = trim($key_nav[1]);
    				if (count($key_nav)==4) {
    					$term2 = term_exists( trim($key_nav[3]), $taxonomy );
    					// write_log($term2);
    					if ( 0 == $term2 || null == $term2 ) {
    						$kq = wp_insert_term(trim($key_nav[3]), $taxonomy);
    					}
    				}
    				$term = get_term_by('slug', $slug_term, $taxonomy);
    				if(count($term)>0) {
    					write_log($term);
    					if($term->term_taxonomy_id!=null) {
    						$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='attachment'", $post_title ));
    						if(!is_null($post_id)) {
    							$kq2 = $wpdb->delete( $wpdb->prefix.'termmeta', array( 'term_id' => $term->term_taxonomy_id, 'meta_key' => $meta_key ) );
    							$kq = $wpdb->replace($wpdb->prefix.'termmeta', 
    								array( 
    									'term_id' => $term->term_taxonomy_id,
    									'meta_key' => $meta_key, 
    									'meta_value' => $post_id 
    								), 
    								array( 
    									'%d',
    									'%s', 
    									'%d' 
    								) 
    							);
    						}
    						if (count($key_nav)==3 && $page_home!='') {
    							$limit = intval($key_nav[2]);
    							if ($limit!=0) {
    								$content.='add_vc_our_services_category%22%3A%22'.$term->term_id.($limit==$i+1?'%22%7D%5D':'%22%7D%2C%7B%22');
    							}
    						}
    					}
    				}
    			}
    		}
    	}
    	if($content!='' && $page_home!='') {
    		$content_home = $begin.$content.'" add_vc_our_services_limit="'.$limit.'"]';
    		$post_content = preg_replace("/\[vc_printshop_our_services.*?add_vc_our_services_limit=\"\d+\"\]/", $content_home, $page_home);
    		$kq3 = $wpdb->update($wpdb->prefix.'posts', array('post_content'=>$post_content), array('ID'=>$page->ID));
    	}
    	return true;
    }

    function set_default_solution_core($file) {
    	global $wpdb;
    	@$solution_core_settings = $this->thim_file_get_contents($file);
    	if ($solution_core_settings) {
    		$kq = $wpdb->replace($wpdb->prefix.'options', 
    			array( 
    				'option_name' => 'solutions_core_settings',
    				'option_value' => $solution_core_settings, 
    				'autoload' => 'yes'
    			), 
    			array( 
    				'%s',
    				'%s', 
    				'%s' 
    			) 
    		);
    		$kq2 = $wpdb->replace($wpdb->prefix.'options', 
    			array( 
    				'option_name' => 'nbdesigner_position_button_product_detail',
    				'option_value' => 4, 
    				'autoload' => 'yes'
    			), 
    			array( 
    				'%s',
    				'%d', 
    				'%s' 
    			) 
    		);
    	}
    	return true;
    }

}
// new Merlin();
}

// TODO put require config in theme
$file_config = get_template_directory() . '/netbase-core/import/merlin-config.php';
if (file_exists($file_config)) {
	require $file_config;
}