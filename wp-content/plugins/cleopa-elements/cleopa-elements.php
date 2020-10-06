<?php
/*
Plugin Name: Netbase Cleopa
Plugin URI: http://netbaseteam.com/
Description: Portfolio Plugin for NBCORE theme.
Version: 1.1.2
Author: NBTeam
Author URI: http://themeforest.net/user/netbaseteam?ref=pencidesign
*/
define( 'NB_FW_PLUGIN_PATHS', trailingslashit( str_replace('nb-fw', '', plugin_dir_path( __FILE__ ) ) ) );
define('NB_FW_PATHS', plugin_dir_path(__FILE__));
define('NB_FW_NAMES', plugin_basename(__FILE__));
require_once(NB_FW_PATHS . 'widgets/widget.php');
NBT_Widget::init();