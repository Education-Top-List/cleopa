<?php

if ( !class_exists('Netbase_Plugins_Updater') ) {
    class Netbase_Plugins_Updater
    {

        private $authorize_token;

        private $plugin_params;

        private $plugins_data;

        public function __construct()
        {
            return $this;
        }

        public function set_token($token)
        {
            $this->authorize_token = $token;
        }

        public function set_plugin_params($plugin_params)
        {
            $this->plugin_params = $plugin_params;
        }

        private function get_repository_info($options = array(), $per_page = 3)
        {
            $request_uri = sprintf('https://gitlab.com/api/v4/projects/%s/repository/tags?per_page=%s&private_token=%s', $options['repository'], $per_page, $this->authorize_token);
            $response = json_decode(wp_remote_retrieve_body(wp_remote_get($request_uri)), true);

            return $response;
        }

        private function set_plugin_properties()
        {
            $plugins_data = array();

            foreach ($this->plugin_params as $current_plugin) {
                if (!file_exists($current_plugin['file'])) continue;

                $plugin = get_plugin_data($current_plugin['file']);
                $actived = is_plugin_active(plugin_basename($current_plugin['file']));

                // Set plugin data
                $plugins_data[$current_plugin['plugin_name']]['name'] = $plugin["Name"];
                $plugins_data[$current_plugin['plugin_name']]['file'] = $current_plugin['file'];
                $plugins_data[$current_plugin['plugin_name']]['basename'] = plugin_basename($current_plugin['file']);
                $plugins_data[$current_plugin['plugin_name']]['active'] = $actived ? $actived : 0;
                $plugins_data[$current_plugin['plugin_name']]['repository'] = $current_plugin['repository'];
                $plugins_data[$current_plugin['plugin_name']]['PluginURI'] = $plugin["PluginURI"];
                $plugins_data[$current_plugin['plugin_name']]['current_version'] = $plugin["Version"];
                $plugins_data[$current_plugin['plugin_name']]['Description'] = $plugin["Description"];
                $plugins_data[$current_plugin['plugin_name']]['Author'] = $plugin["Author"];
                $plugins_data[$current_plugin['plugin_name']]['AuthorURI'] = $plugin["AuthorURI"];
                $plugins_data[$current_plugin['plugin_name']]['TextDomain'] = $plugin["TextDomain"];
                $plugins_data[$current_plugin['plugin_name']]['DomainPath'] = $plugin["DomainPath"];
                $plugins_data[$current_plugin['plugin_name']]['Network'] = $plugin["Network"];

                // Get info of last 3 version
                $versions = array();
                $github_response = $this->get_repository_info($current_plugin);

                // Parser Markdown of gitlab to HTML (strong, ul)
                $array_pattern = array(
                    '/\*\*(.*?)\*\*/',
                    '/\*+(.*)?/i',
                    '/(\<\/ul\>\n(.*)\<ul\>*)+/'
                );
                $array_replace = array(
                    '<strong>$1</strong>',
                    '<ul><li>$1</li></ul>',
                    ''
                );

                // Check has response
                if (count($github_response) > 0 && !isset($github_response['message'])) {
                    foreach ($github_response as $response) {
                        $version = array();
                        $version['release_version'] = $response['name'];
                        $version['release_time'] = $response['commit']['created_at'];
                        $version['release_description'] = preg_replace( $array_pattern, $array_replace, $response['release']['description'] );

                        $versions[] = $version;
                    }
                }
                $plugins_data[$current_plugin['plugin_name']]['versions'] = $versions;
            }

            $this->plugins_data = $plugins_data;
        }

        public function initialize()
        {
            $this->set_plugin_properties();
            add_filter('pre_set_site_transient_update_plugins', array($this, 'modify_transient'), 10, 1);
            // add_filter('upgrader_source_selection', array($this, 'rename_github_zip'), 1, 4);
            add_filter('plugins_api', array($this, 'plugin_popup'), 10, 3);
            add_filter('upgrader_post_install', array($this, 'after_install'), 10, 3);
        }

        public function modify_transient($transient)
        {
            $checked = isset($transient->checked) ? $transient->checked : array();
            if ($checked) {
                foreach ($this->plugins_data as $current_plugin) {
                    $current_basename = $current_plugin['basename'];
                    $versions = $current_plugin['versions'];

                    // Check exists plugin + has versions
                    if (!file_exists($current_plugin['file']) ||
                        !count($versions) > 0) continue;

                    // Check new version
                    $out_of_date = version_compare($versions[0]['release_version'], $checked[$current_basename], 'gt');
                    if ($out_of_date) {

                        // Check file download: Get last 3 jobs. If no job, do nothing.
                        $jobs_uri = sprintf('https://gitlab.com/api/v4/projects/%s/jobs?scope[]=success&per_page=3&private_token=%s', $current_plugin['repository'], $this->authorize_token);
                        $jobs = json_decode(wp_remote_retrieve_body(wp_remote_get($jobs_uri)), true);
                        if(!$jobs) continue;

                        $new_file = sprintf('%s/artifacts/download?private_token=%s', $jobs[0]['web_url'], $this->authorize_token);
                        $slug = current(explode('/', $current_basename));

                        $plugin = array(
                            'url' => $current_plugin["PluginURI"],
                            'slug' => $slug,
                            'package' => $new_file,
                            'new_version' => $versions[0]['release_version']
                        );

                        $transient->response[$current_basename] = (object)$plugin;
                    }
                }
            }

            return $transient;
        }

        public function rename_github_zip($source, $remote_source, $upgrade_meta, $extra_params)
        {
            if (isset($extra_params['plugin'])) {

                $plugin_source = explode('/', $extra_params['plugin']);
                $plugin_name = $plugin_source[0];

                if (isset($this->plugins_data[$plugin_name])) {

                    if (strpos($source, $plugin_name) === false)
                        return $source;

                    $path_parts = pathinfo($source);
                    $newsource = trailingslashit($path_parts['dirname']) . trailingslashit($plugin_name);
                    rename($source, $newsource);
                    return $newsource;
                }
                return $source;
            }
            return $source;
        }

        public function after_install($response, $hook_extra, $result)
        {
            if (isset($this->plugins_data[$result['destination_name']])) {

                $plugin_data_after_install = $this->plugins_data[$result['destination_name']];

                global $wp_filesystem;

                $install_directory = plugin_dir_path($plugin_data_after_install['file']);
                $wp_filesystem->move($result['destination'], $install_directory);
                $result['destination'] = $install_directory;

                if ($plugin_data_after_install['active']) {
                    activate_plugin($plugin_data_after_install['basename']);
                }
            }

            return $result;
        }

        public function plugin_popup($result, $action, $args)
        {
            // Check If there is a slug
            if (!empty($args->slug) && isset($this->plugins_data[$args->slug])) {
                $current_plugin = $this->plugins_data[$args->slug];

                // Check it's our slug
                if ($args->slug == current(explode('/', $current_plugin['basename']))) {
                    $download_link = wp_nonce_url('update-core.php', 'upgrade-plugin', 'action=upgrade-plugin&plugin=' . $current_plugin['basename'] . '&_wpnonce');

                    // Get last update
                    $last_update = $current_plugin['versions'][0];

                    // Set it to an array
                    $plugin = array(
                        'name' => $current_plugin["name"],
                        'slug' => $args->slug,
                        'version' => $last_update['release_version'],
                        'author' => $current_plugin["Author"],
                        'author_profile' => $current_plugin["AuthorURI"],
                        'last_updated' => $last_update['release_time'],
                        'homepage' => $current_plugin["PluginURI"],
                        'short_description' => $current_plugin["Description"],
                        'sections' => array(
                            'Description' => $current_plugin["Description"],
                            'Updates' => $last_update['release_description'],
                        ),
                        'download_link' => $download_link
                    );
                    return (object)$plugin; // Return the data
                }
            }
            return $result; // Otherwise return default
        }
    }
}

if( !function_exists('NB_Plugins_Updater') ) {
    function NB_Plugins_Updater() {
        return new Netbase_Plugins_Updater();
    }
}

add_action( 'admin_init', 'nb_plugin_nb_fw_updating', 10, 999999 );
function nb_plugin_nb_fw_updating() {
    $plugins_data = array(
        array(
            'file'          => NB_PLUGINS_PATH. 'nb-fw\nb-fw.php',
            'plugin_name'   => 'nb-fw',
            'repository'    => '8887450',
        ),
    );
    $updater = NB_Plugins_Updater();
    $updater->set_plugin_params($plugins_data);
    $updater->set_token('g_Pxrcytn3XB3z6-kvnz');
    $updater->initialize();
}