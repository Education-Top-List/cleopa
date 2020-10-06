<?php
/*
Plugin Name: Netbase Framework
Plugin URI: http://netbaseteam.com/
Description: Portfolio Plugin for NBCORE theme.
Version: 1.4.1
Author: NBTeam
Text Domain: nb-fw
Author URI: http://themeforest.net/user/netbaseteam
*/

define( 'NB_PLUGINS_PATH', trailingslashit( str_replace('nb-fw', '', plugin_dir_path( __FILE__ ) ) ) );
define('NB_FW_PATH', plugin_dir_path(__FILE__));
define('NB_FW_NAME', plugin_basename(__FILE__));
define( 'NB_FW_URL', trailingslashit( plugins_url( 'nb-fw' ) ) );


require_once(NB_FW_PATH . 'inc/customize/init.php');
require_once(NB_FW_PATH . 'inc/custom-metadata/custom-metadata.php');
require_once(NB_FW_PATH . 'inc/custom-template-tags.php');
// require_once(NB_FW_PATH . 'inc/deploy-plugins/deploy-plugins.php');
// require_once(NB_FW_PATH . 'inc/deploy-themes/deploy-themes.php');
require_once(NB_FW_PATH . 'inc/merlin/merlin.php');

class Netbase_Framework
{
    function __construct() {

        add_action( 'wp_enqueue_scripts', array( $this, 'nb_fw_enqueue_scripts' ) );
    }
    
    function nb_fw_enqueue_scripts() {

        wp_enqueue_style( 'nb-fw-font-icon', NB_FW_URL . 'inc/src/nb-fw-font-icon.css', array(), '20182108' );
    }
}

new Netbase_Framework();
