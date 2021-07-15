<?php

namespace App\Controllers;

use App\Models\HooksInterface;
use App\Models\Singleton;

class Acf implements HooksInterface
{

    use Singleton;

    public function hooks(){
        // actions and filters
        add_filter('acf/settings/save_json', [$this, 'acf_json_save']);
        add_filter('acf/settings/load_json', [$this, 'acf_json_load']);
        add_action('init', [$this, 'acf_init']);
    }

    /**
     * Functions to load
     * when wp init
     * @return void
     */
    public function acf_init()
    {
        if( function_exists('acf_add_options_page') ) {

            acf_add_options_page(array(
                'page_title' => 'Theme General Settings',
                'menu_title' => 'Theme Settings',
                'menu_slug' => 'hopes-general-settings',
                'capability' => 'edit_posts',
                'redirect' => false
            ));
        }
    }

    /**
     * This method returns url path of acf json directory
     * @param $path
     * @return string
     */
    public function acf_json_save($path)
    {
        // update path
        $path = THEME_STYLESHEET_URI . '/acf-json';

        // return
        return $path;
    }

    /**
     * This method loads acf json from directory
     * @param $pathS
     * @return array
     */
    public function acf_json_load($paths)
    {
        // remove original path (optional)
        unset($paths[0]);
        // append path
        $paths[] = THEME_STYLESHEET_URI . '/acf-json';

        return $paths;
    }
}