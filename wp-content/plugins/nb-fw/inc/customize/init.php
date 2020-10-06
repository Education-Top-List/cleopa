<?php

if ( ! class_exists( 'NetbaseCustomizeClass', false ) ) {

	define('NBT_LOAD_CUSTOMIZE_FROM_HEAD', 0);

	define('NBT_LOAD_CUSTOMIZE_FROM_CSS_FILE', 1);

	define('NBT_REAL_PATH_TEMPLATE', realpath(get_template_directory()));

	define('NBT_CSS_CUSTOMIZE_PATH', '/assets/netbase/css/');

	define('NBT_CSS_CUSTOMIZE_NAME', 'customize.css');

	define('NBT_TIMEOUT_TRANSIENT_CUSTOMIZE', 60*60*24); // 24 hour

	class NetbaseCustomizeClass {

		function __construct() {
			add_action('customize_controls_enqueue_scripts', array( $this, 'setup_scripts_script'));
			add_action('wp_ajax_check_load_css', array($this, 'ajax_check_load_css'));
			add_action('wp_ajax_nopriv_check_load_css', array($this, 'ajax_check_load_css'));
			add_action('customize_save_after', array($this, 'customize_save_css'));

			if(empty(get_transient('current_load_css'))) {
				set_transient('current_load_css', NBT_LOAD_CUSTOMIZE_FROM_HEAD, NBT_TIMEOUT_TRANSIENT_CUSTOMIZE);
			}
			if(empty(get_transient('change_customize_css'))) {
				set_transient('change_customize_css', 0, NBT_TIMEOUT_TRANSIENT_CUSTOMIZE);
			}
		}

		public function customize_save_css() {
			set_transient('change_customize_css', 1, NBT_TIMEOUT_TRANSIENT_CUSTOMIZE);
		}

		function setup_scripts_script() {
			wp_register_script( 'ajax_object', plugins_url( '/assests/js/customize.js', __FILE__ ) );

			$translation_array = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			);
			wp_localize_script( 'ajax_object', 'object_name', $translation_array );

			wp_enqueue_script( 'ajax_object' );
		}

		public function save_css_customize() {
			$result = 'error';
			$style = get_option('customize_save_css');
			if($style!=false) {
				$MY_PATH =  NBT_REAL_PATH_TEMPLATE . NBT_CSS_CUSTOMIZE_PATH;
				$css_file = $MY_PATH . NBT_CSS_CUSTOMIZE_NAME;
				if(wp_is_writable($MY_PATH)) {
					$handle = fopen($css_file, 'w+');
					if (fwrite($handle, $style) === FALSE) {
						$result = esc_html__("Cannot write to file css", "customize-wp");
					} else {
						$result = '';
					}
					fclose($handle);
				} else {
					$result = esc_html__("You need to have writeable permission in the theme", "customize-wp");
				}
			}

			return $result;
		}

		public function ajax_check_load_css() {
			$status = esc_html__('You are loading the css directly in head', 'customize-wp');
			$button = esc_html__('Load css from file', 'customize-wp');
			$alert = '';
			$success = 1;
			$my_transient = get_transient('current_load_css');
			$result = NBT_LOAD_CUSTOMIZE_FROM_HEAD;
			if(empty($my_transient)) {
				set_transient('current_load_css', $result, NBT_TIMEOUT_TRANSIENT_CUSTOMIZE);
			} else {
				$result = $my_transient;
				if(!file_exists(NBT_REAL_PATH_TEMPLATE . NBT_CSS_CUSTOMIZE_PATH . NBT_CSS_CUSTOMIZE_NAME)) {
					$result = NBT_LOAD_CUSTOMIZE_FROM_HEAD;
				}
			}
			if(isset($_POST['type_current'])) {
				$result = $_POST['type_current'];
				if($result==NBT_LOAD_CUSTOMIZE_FROM_HEAD) {
					$result = NBT_LOAD_CUSTOMIZE_FROM_CSS_FILE;
					set_transient('current_load_css', $result, NBT_TIMEOUT_TRANSIENT_CUSTOMIZE);
				} elseif($result==NBT_LOAD_CUSTOMIZE_FROM_CSS_FILE) {
					$result = NBT_LOAD_CUSTOMIZE_FROM_HEAD;
					set_transient('current_load_css', $result, NBT_TIMEOUT_TRANSIENT_CUSTOMIZE);
					$alert = $this->save_css_customize();
				} else {
					$result = -1;
				}
			}
			if($result==NBT_LOAD_CUSTOMIZE_FROM_CSS_FILE) {
				$status = esc_html__('You are loading the css in file saved', 'customize-wp');
				$button = esc_html__('Load css from tag head', 'customize-wp');
				if(!file_exists(NBT_REAL_PATH_TEMPLATE . NBT_CSS_CUSTOMIZE_PATH . NBT_CSS_CUSTOMIZE_NAME)) {
					$alert = $this->save_css_customize();
				}
			}
			wp_send_json(array('success' => ($alert!=''?0:1), 'result' => $result, 'status' => $status, 'button' => $button, 'message' => $alert));
			exit;
		}
	}

	new NetbaseCustomizeClass();

}