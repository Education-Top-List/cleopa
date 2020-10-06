<?php

if ( !class_exists('Netbase_Themes_Updater') ) {
    class Netbase_Themes_Updater
    {
        private $authorize_token;

        private $theme_params;

        private $themes_data;

        public function __construct()
        {
            return $this;
        }

        public function set_token($token)
        {
            $this->authorize_token = $token;
        }

        public function set_theme_params($theme_params)
        {
            $this->theme_params = $theme_params;
        }

        private function get_repository_info($options = array(), $per_page = 3)
        {
            $request_uri = sprintf('https://gitlab.com/api/v4/projects/%s/repository/tags?per_page=%s&private_token=%s', $options['repository'], $per_page, $this->authorize_token);
            $response = json_decode(wp_remote_retrieve_body(wp_remote_get($request_uri)), true);

            return $response;
        }

        private function set_theme_properties(){
            $themes_data = array();

            foreach ($this->theme_params as $current_theme) {
                if (!file_exists(get_theme_root() .'/'. $current_theme['theme_slug'] .'/style.css')) continue;

                // Set plugin data
                $theme = wp_get_theme( $current_theme['theme_slug'] );

                $themes_data[$current_theme['theme_slug']]['name'] = $theme->get( 'Name' );
                $themes_data[$current_theme['theme_slug']]['theme_root'] = get_theme_root();
                $themes_data[$current_theme['theme_slug']]['ThemeURI'] = $theme->get( 'ThemeURI' );
                $themes_data[$current_theme['theme_slug']]['Author'] = $theme->get( 'Author' );
                $themes_data[$current_theme['theme_slug']]['AuthorURI'] = $theme->get( 'AuthorURI' );
                $themes_data[$current_theme['theme_slug']]['Description'] = $theme->get( 'Description' );
                $themes_data[$current_theme['theme_slug']]['repository'] = $current_theme['repository'];
                $themes_data[$current_theme['theme_slug']]['current_version'] = $theme->get( 'Version' );

                // Get info of last 3 version
                $versions = array();
                $github_response = $this->get_repository_info($current_theme);

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
                $themes_data[$current_theme['theme_slug']]['versions'] = $versions;
            }

            $this->themes_data = $themes_data;
        }

        public function initialize()
        {
            $this->set_theme_properties();
            add_filter('pre_set_site_transient_update_themes', array($this, 'modify_theme_transient'), 10, 1);
            // add_filter('upgrader_source_selection', array($this, 'rename_theme_package_zip'), 1, 4);
        }

        public function modify_theme_transient($transient)
        {
            $checked = isset($transient->checked) ? $transient->checked : array();
            if ($checked) {
                foreach ($this->themes_data as $theme_slug => $current_theme) {
                    $versions = $current_theme['versions'];

                    // Check exists plugin + has versions
                    if (!file_exists(get_theme_root() .'/'. $theme_slug .'/style.css') ||
                        !count($versions) > 0) continue;

                    $out_of_date = version_compare($versions[0]['release_version'], $checked[$theme_slug], 'gt');
                    if ($out_of_date) {

                        // Check file download: Get last 3 jobs. If no job, do nothing.
                        $jobs_uri = sprintf('https://gitlab.com/api/v4/projects/%s/jobs?scope[]=success&per_page=3&private_token=%s', $current_theme['repository'], $this->authorize_token);
                        $jobs = json_decode(wp_remote_retrieve_body(wp_remote_get($jobs_uri)), true);
                        if(!$jobs) continue;

                        $new_file = sprintf('%s/artifacts/download?private_token=%s', $jobs[0]['web_url'], $this->authorize_token);

                        $upgrade_theme_data = array(
                            'url' => $current_theme['ThemeURI'],
                            'theme' => $theme_slug,
                            'new_version' => $versions[0]['release_version'],
                            'package' => $new_file,
                        );

                        $transient->response[$theme_slug] = $upgrade_theme_data;
                    }
                }
            }
            return $transient;
        }

        public function rename_theme_package_zip($source, $remote_source, $thiz, $extra_params)
        {
            if (isset($extra_params['theme'])) {
                $theme_source = str_replace($remote_source, '', $source);
                if (strpos($theme_source, $this->theme_slug) === false)
                    return $source;

                $path_parts = pathinfo($source);

                $newsource = trailingslashit($path_parts['dirname']) . trailingslashit($this->theme_slug);
                rename($source, $newsource);
                return $newsource;
            }
            return $source;

        }
    }
}

if( !function_exists('NB_Themes_Updater') ) {
    function NB_Themes_Updater() {
        return new Netbase_Themes_Updater();
    }
}