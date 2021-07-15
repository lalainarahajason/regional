<?php

namespace App\Controllers;

use App\Models\HooksFrontInterface;
use App\Models\Singleton;

class Assets implements HooksFrontInterface
{

    use Singleton;

    public function hooks()
    {
        // actions and filters
        add_action('wp_enqueue_scripts', [$this, 'registerStyles']);
        add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
    }

    public function registerStyles()
    {
        //wp_register_style('style-css', THEME_DIR_URI, [], filemtime( THEME_DIR_PATH . '/css/dist.css'), 'all');
        wp_register_style('swiper-css', THEME_DIR_URI .'/assets/css/swiper.min.css', [], false, 'all');
        wp_register_style('dist-css', THEME_DIR_URI .'/assets/css/dist.css', [], false, 'all');
        wp_register_style('regional-css', THEME_DIR_URI .'/regional.css', [], false, 'all');
        wp_register_style('regional-style', THEME_DIR_URI .'/style.css', [], false, 'all');

        // Default app style file
        //wp_enqueue_style('style-css');

        // App styles
        wp_enqueue_style('swiper-css');
        wp_enqueue_style('dist-css');
        wp_enqueue_style('regional-css');
        wp_enqueue_style('regional-style');
    }

    public function registerScripts()
    {
        // De register jquery
        wp_deregister_script('jquery');

        // Enqueue jquery
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
        wp_register_script('gsap-js', THEME_DIR_URI . '/assets/js/gsap.min.js', [], filemtime(THEME_DIR_PATH . '/assets/js/gsap.min.js'), true);
        wp_register_script('scrollmagic-js', THEME_DIR_URI . '/assets/js/scrollmagic.min.js', [], filemtime(THEME_DIR_PATH . '/assets/js/scrollmagic.min.js'), true);
        wp_register_script('anim-gsap-js', THEME_DIR_URI . '/assets/js/animation.gsap.min.js', [], filemtime(THEME_DIR_PATH . '/assets/js/animation.gsap.min.js'), true);
        wp_register_script('indicators-js', THEME_DIR_URI . '/assets/js/add.indicators.min.js', [], filemtime(THEME_DIR_PATH . '/assets/js/add.indicators.min.js'), true);

        wp_register_script('swiper-js', THEME_DIR_URI . '/assets/js/swiper.min.js', [], filemtime(THEME_DIR_PATH . '/assets/js/swiper.min.js'), true);
        wp_register_script('app-js', THEME_DIR_URI . '/assets/js/app.js', [], filemtime(THEME_DIR_PATH . '/assets/js/app.js'), true);
        wp_enqueue_script('gsap-js');
        wp_enqueue_script('scrollmagic-js');
        wp_enqueue_script('anim-gsap-js');
        wp_enqueue_script('indicators-js');
        wp_enqueue_script('swiper-js');
        wp_enqueue_script('app-js');
    }
}
