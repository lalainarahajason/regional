<?php

// Autoload classes
require_once(dirname(__FILE__) . '/vendor/autoload.php');

// add user class
require_once(ABSPATH.'wp-admin/includes/user.php');

if (!defined('THEME_DIR_PATH')) {
    define('THEME_DIR_PATH', untrailingslashit(get_template_directory()));
}

if (!defined('THEME_DIR_URI')) {
    define('THEME_DIR_URI', untrailingslashit(get_template_directory_uri()));
}

if (!defined('THEME_STYLESHEET_URI')) {
    define('THEME_STYLESHEET_URI', untrailingslashit(get_stylesheet_directory()));
}

if (!defined('SITE_URI')) {
    define('SITE_URI', site_url());
}

use App\App;
use App\Controllers\Theme;
use App\Controllers\Assets;
use \App\Controllers\Acf;
use \App\Controllers\Admin;
use \App\Controllers\Posts;
use App\Models\Postypes\Stories;
use App\Models\Postypes\Opportunities;
use App\Models\Postypes\Projects;
use App\Models\Postypes\Jobs;
use App\Models\Postypes\Partners;
use App\Models\Postypes\Skills;
use App\Controllers\Blocks;
use App\Models\Taxonomies\Opportunities as Opportunities_taxonomy;
use App\Models\Taxonomies\Jobs as Jobs_taxonomy;
use App\Models\Taxonomies\Stories as Stories_taxonomy;
use App\Models\Taxonomies\Skills as Skills_taxonomy;

/**
 * Load services
 */
$actions = [
    new Theme(),
    new Admin(),
    new Assets(),
    new Acf(),
    new Stories,
    new Opportunities(),
    new Jobs(),
    //new Jobs(),
    new Skills(),
    new Projects(),
    new Partners(),
    new Blocks(),
    new Jobs_taxonomy(),
    new Opportunities_taxonomy(),
    new Stories_taxonomy(),
    new Skills_taxonomy(),
    new Posts()
];

$app = new App($actions);

$app->execute();



/**
 * Add this to Theme class
 */
add_action('after_setup_theme', 'setupTheme');
function setupTheme()
{
    add_theme_support('align-wide');
    add_theme_support('menus');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_editor_style('style-editor.css');

    add_image_size('download-thumbnail', 90, 90, true);
    add_image_size('factsheet-thumbnail', 248, 176, true);
    add_image_size('project-thumbnail', 461, 324, true);
    add_image_size('discover', 721, 424, true);
}

/* This post is also available in ... */
function icl_post_languages()
{
    $languages = icl_get_languages('skip_missing=0');
    if (count($languages)) {
        $output = '';
        foreach ($languages as $l) {
            if ($l['active']) {
                $output .= '<span class="text-white text-center text-opacity-50 block text-xs">'.$l['native_name'].'</span>';
            } else {
                $output .= '<a class="hover:text-blue text-white text-center block text-xs" href="'.$l['url'].'">'.$l['native_name'].'</a>';
            }
        }
        $langs[] = $output;
        echo join(' ', $langs);
    }
}

// add_filter('pre_get_posts', 'filterPostsQuery');
function filterPostsQuery($query)
{
    if (is_admin()) {
        return;
    }
    if ($query->is_main_query()) {
        if (is_archive("stories")) {
            $query->set("posts_per_page", 10);
        } else {
            $query->set("posts_per_page", -1);
        }
    }



    return $query;
}
/**
* Contact form
 */
add_filter('wpcf7_autop_or_not', '__return_false');
add_action("wpcf7_before_send_mail", "wpcf7_hopes_action");
function wpcf7_hopes_action($contact_form) {
    // get the contact form object
    $wpcf = WPCF7_ContactForm::get_current();
    $form_id = $contact_form->posted_data['_wpcf7'];

    // if you wanna check the ID of the Form $wpcf->id

    /*
    if () {
        // If you want to skip mailing the data, you can do it...
        $wpcf->skip_mail = true;
    }*/

    $submission = WPCF7_Submission::get_instance();
    if ( $submission ) {
        $posted_data = $submission->get_posted_data();
    }

    return $wpcf;
}

// Hook sender email

// Hooking up our functions to WordPress filters
// add_filter( 'wp_mail_from', 'wpb_sender_email' );
add_filter( 'wp_mail_from_name', 'wpb_sender_name' );
function wpb_sender_email( $original_email_address ) {
    return get_option("admin_email");
}

// Function to change sender name
function wpb_sender_name( $from_name ) {
    return 'Regional';
}

/***** timber translation */
add_filter('get_twig', 'add_to_twig');

function add_to_twig($twig) {

    $twig->addExtension(new Twig_Extension_StringLoader());

    $twig->addFilter(new Twig_SimpleFilter('translate', 'create_translated_string'));
    return $twig;
}

function create_translated_string($text) {
    return __($text, 'regional');
}