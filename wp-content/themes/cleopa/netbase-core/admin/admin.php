<?php

class Cleopa_Admin
{
    protected $plugins;

    protected $tgmpa;

    public function __construct()
    {
        $this->tgmpa = isset($GLOBALS['tgmpa']) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();

        add_action('admin_enqueue_scripts', array($this, 'admin_scripts_enqueue'));
        add_action( 'tgmpa_register', array($this, 'register_required_plugins') );
        add_action('wp_ajax_nbt_install_framework', array($this, 'ajax_install_framework'));
        add_action('wp_ajax_nbt_active_framework', array($this, 'ajax_active_framework'));
    }

    public function admin_scripts_enqueue()
    {
        global $pagenow;
        if(is_customize_preview()){
            wp_enqueue_style('fontello-admin', get_template_directory_uri() . '/assets/vendor/fontello/fontello.css', array(), NBT_VER);
        }

       // wp_enqueue_style('fontello', get_template_directory_uri() . '/assets/vendor/fontello/fontello.css', array(), NBT_VER);

            if (!get_transient('nbt_first_time_setup')) {
                wp_enqueue_script('switch-theme', get_template_directory_uri() . '/assets/src/js/admin/switch-theme.js', array('jquery'), NBT_VER);
                wp_enqueue_style('switch-theme', get_template_directory_uri() . '/assets/netbase/css/admin/switch-theme.min.css', array(), NBT_VER);

                wp_localize_script('switch-theme', 'nbt', array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'wp_nonce' => wp_create_nonce('nbt_nonce'),
                ));
            }
        
    }


    public function register_required_plugins()
    {
        if(!isset($this->plugins)) {
            $this->plugins = apply_filters('core_tgmpa_array', array(
                array(
                    'name' => 'Netbase Framework',
                    'slug' => 'nb-fw',
                    'required' => true,
                    'version' => '1.2.0',
                    'source' => get_template_directory() . '/inc/plugins/nb-fw.zip',
                ),
            ));
        }

        $config = array(
            'id'           => 'cleopa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
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

    public function ajax_install_framework()
    {
        if (!check_ajax_referer('nbt_nonce', 'wpnonce') || empty($_POST['slug'])) {
            exit(0);
        }

        $json = array();
        $tgmpa_url = $this->tgmpa->get_tgmpa_url();

        if($_POST['slug'] === 'install') {
            $json = array(
                'url' => $tgmpa_url,
                'plugin' => array('nb-fw'),
                'tgmpa-page' => $this->tgmpa->menu,
                '_wpnonce' => wp_create_nonce('bulk-plugins'),
                'action' => 'tgmpa-bulk-install',
                'action2' => - 1,
                'message' => esc_html__('Installing', 'cleopa'),
            );
        } elseif($_POST['slug'] === 'active') {
            $json = array(
                'url' => $tgmpa_url,
                'plugin' => array('nb-fw'),
                'tgmpa-page' => $this->tgmpa->menu,
                '_wpnonce' => wp_create_nonce('bulk-plugins'),
                'action' => 'tgmpa-bulk-activate',
                'action2' => - 1,
                'message' => esc_html__('Activating', 'cleopa'),
            );
        }

        if($json) {
            wp_send_json($json);
        } else {
            wp_send_json(array('done' => 1, 'message' => esc_html__('Success', 'cleopa')));
        }

        exit;
    }
}