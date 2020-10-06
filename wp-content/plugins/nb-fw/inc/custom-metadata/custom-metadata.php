<?php

class Custom_Medata {
    public function __construct()
    {
        $this->define_const();
        $this->include_vendor();

        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin'));

    }

    public function define_const()
    {
        if(!defined('CUSTOM_METADATA_EXTEND_PATH')) {
            define('CUSTOM_METADATA_EXTEND_PATH', plugin_dir_path(__FILE__));
        }
        if(!defined('CUSTOM_METADATA_NAME')) {
            define('CUSTOM_METADATA_NAME', plugin_basename(__FILE__));
        }
        if(!defined('CUSTOM_METADATA_URL')) {
            define('CUSTOM_METADATA_URL', plugin_dir_url(__FILE__));
        }
    }

    public function include_vendor()
    {
        require_once CUSTOM_METADATA_EXTEND_PATH . 'vendor/cmb2/init.php';
        require_once CUSTOM_METADATA_EXTEND_PATH . 'vendor/cmb2-conditionals/cmb2-conditionals.php';
        require_once CUSTOM_METADATA_EXTEND_PATH . 'vendor/cmb2-tabs/cmb2-tabs.php';
        require_once CUSTOM_METADATA_EXTEND_PATH . 'metaboxes/metaboxes.php';
    }

    public function enqueue_admin()
    {
        wp_enqueue_script('core-admin', CUSTOM_METADATA_URL . 'assets/admin/main.js', array('jquery', 'cmb2-conditionals'), '1.0.0', true);
        wp_enqueue_style('core-admin', CUSTOM_METADATA_URL . 'assets/admin/main.css', array(), '1.0.0', 'all');
    }
}

new Custom_Medata();