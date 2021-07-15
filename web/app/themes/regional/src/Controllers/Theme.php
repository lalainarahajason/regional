<?php

namespace App\Controllers;

use App\Models\HooksFrontInterface;
use App\Models\Singleton;
use Timber\Timber;

class Theme implements HooksFrontInterface
{

    use Singleton;

    public $site_url;

    public function __construct(){
        $this->site_url = str_replace('/wp', '', site_url());
    }

    public function hooks()
    {
        // TODO: Implement hooks() method.
        add_action('init', [$this, 'viewsLocation']);
        add_action('init', [$this, 'loginUser']);
        add_action('after_setup_theme', [$this, 'setupTheme']);
        add_action('timber_context', [$this, 'opslebTimberContext']);
        add_filter('show_admin_bar', '__return_false');
        add_filter('the_title', [$this, 'filterPageHeaderTitle']);
        add_action('enqueue_block_editor_assets', [ $this, 'enqueuEditorAssets' ]);

        add_action('wp_logout', [$this, 'auto_redirect_after_logout'], 10);
        // add_action('wp_login', [$this, 'auto_redirect_login'], 10, 2);
        // Contact form 7
        add_action('wpcf7_before_send_mail', array( $this, 'regional_before_sent_mail'), 10,3);
        // add_filter('wpcf7_validate_text', 'cf7_check_password_field', 20, 2); // text field
        // add_filter('wpcf7_validate_text*', 'cf7_check_password_field', 20, 2); // Req. text field

        add_action("template_redirect", [$this, "restrict_posts_access"]);
    }

    public function auto_redirect_login($user, $data) {}

    public function restrict_posts_access() {
        $restrict = ["opportunities", "skills", "stories"];
        $object = get_queried_object();
        if(is_singular()){
            if(in_array($object->post_type, $restrict) && ! is_user_logged_in()) {
                $redirect = get_the_permalink($object->ID);
                wp_safe_redirect($this->site_url . "/connexion" );
                exit;
            }
        }
    }

    public function auto_redirect_after_logout() {
        wp_safe_redirect( $this->site_url );
        exit;
    }

    public function regional_before_sent_mail($contact_form, &$abort, $object) {

        $submission = \WPCF7_Submission::get_instance();
        $form_id = $contact_form->id();

        if($submission) {
            $data= $submission->get_posted_data();
            // Verify if user does not exists
            if(false == email_exists( $data["your-email"] )){
                $user_pwd = wp_generate_password( $length = 12, $include_standard_special_chars = true );
                $user_email = $data["your-email"];
                $userdata = array(
                    'user_login'	=>  wp_slash($user_email),
                    'user_pass'		=>  $user_pwd,
                    'user_email'	=>  wp_slash($user_email),
                    "last_name"     =>  wp_slash($data["last-name"]),
                    "first_name"    =>  wp_slash($data["first-name"]),
                    'role'			=>	"subscriber",
                );
                $user_id = wp_insert_user( $userdata );

                // add user meta data
                $except = array("last-name", "first-name", "your-email");
                $data_email = array();

                foreach($data as $key => $value) {
                    if(!in_array(strtolower($key), $except)){
                        $data_email[$key] = $value;
                        $userkey = is_array($key) && $key != "your-email" ? strtolower($data[$key]): strtolower($key);
                        add_user_meta($user_id, "register_" . $userkey, $value);
                    }
                }

                if ( is_wp_error( $user_id  ) ) {
                    //if there is any error abort the current process
                    $abort = true;
                    $object->set_response($user_id->get_error_message(), 'zeal-user-reg-cf7');

                } else {

                    add_user_meta($user_id, "inactive_user", 1);
                    $this->send_subscribe_email($user_id);
                }
            } else {
                $abort = true;
                $object->set_response("This user already exists. Please enter another email.",'zeal-user-reg-cf7');
            }
        }

        //$submission->skip_mail = true;
        return $contact_form;

    }

    public function send_subscribe_email($user_id) {
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $headers = 'From: noreply@regional-alumni-network.eu.com';
        $user = get_user_by("id", $user_id);
        add_filter( "wp_mail_content_type", function(){
            return "text/html";
        });
        $message = get_field("emails_content_firstregistration", "option");
        $message = str_replace("#user#", $user->first_name, $message);
        $message = str_replace("#email#", $user->user_email, $message);
        wp_mail($user->user_email,"Welcome to $blogname",$message, $headers);
    }



    /**
     * Login user
     */
    public function loginUser()
    {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if(isset($_POST["login-user"])){
            $email = $_POST['email'];
            $password = $_POST['password'];

            if($email != "" && $password != "" ){


                $creds = array(
                    "user_login" => strtolower($email),
                    "user_password" => $password,
                    "remember" => true
                );
                $user = wp_signon( $creds, false );
                if ( !is_wp_error($user) ) {

                    do_action('wp_login', $user->ID);
                    wp_set_current_user($user->ID,$user->login);
                    wp_set_auth_cookie($user->ID, true);
                    do_action( 'wp_login', $user->user_login, $user );



                } /* else {

                    $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'login_fails=1';
                    wp_redirect($url);
                    exit();
                }*/
            } else {
                $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'login_fails=1';
                wp_redirect($url);
                exit();
            }
        }
        if(isset($_POST['forgot-password'])) {
            $email = $_POST['email'];
            if(is_email(strtolower($email)) && email_exists(strtolower($email)) ){

                $user = get_user_by("email", $email);
                $is_inactive = get_user_meta($user->ID, "inactive_user");
                if($is_inactive) {

                    $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'recover=2';

                } else {
                    // Create random password
                    $new_password = wp_generate_password( 12, true, false );
                    add_filter( "wp_mail_content_type", function(){
                        return "text/html";
                    });
                    $user = get_user_by("email", $email);
                    if($user) {
                        $login_url = $this->site_url . '/connexion';
                        $message = get_field("emails_content_forgot_password", "option");
                        $message = str_replace("#user#", $user->first_name, $message);
                        $message = str_replace("#email#", $email, $message);
                        $message = str_replace("#password#", $new_password, $message);
                        $message = str_replace('#login_url#', $login_url, $message);
                        $headers = 'From: noreply@regional-alumni-network.eu.com';
                        wp_mail($email, "Recover password", $message, $headers);
                    }

                    $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'recover=1';
                }

            }


            wp_redirect($url);
            exit();
        }
    }

    public function enqueueEditorAssets()
    {
        // Theme Gutenberg blocks CSS.
        $css_dependencies = [
            'wp-block-library-theme',
            'wp-block-library'
        ];
        wp_enqueue_style(
            'twp-editor-css',
            THEME_DIR_URI . '/assets/css/dist.css',
            $css_dependencies,
            filemtime(THEME_DIR_PATH . '/assets/css/dist.css'),
            false
        );


    }


    /**
     * This method format the page header title
     * @param $title
     *
     * @return string
     */
    public function filterPageHeaderTitle($title)
    {
        if (is_admin()) {
            return str_replace("|", "", $title);
        }

            $pos = strpos($title, '|');
        if ($pos!==false) {
            $delimiter = explode('|', $title);
            if (isset($delimiter[1])) {
                return "<span class='h-stroke block'>$delimiter[0]</span><span class='text-white block'>$delimiter[1]</span>";
            }
        }

        return $title;
    }

    /**
     * Default timber location
     * @todo create config for templates path
     * @return void
     */

    public function viewsLocation()
    {
        $timber = new Timber();
        Timber::$locations = THEME_DIR_PATH . '/src/Views/';
    }

    /**
     * Add theme support
     * - Custom logo
     */
    public function setupTheme()
    {

        //add_theme_support('post-thumbnails');
        add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script']);
        global $content_width;
        if (!isset($content_width)) {
            $content_width = 1240;
        }
    }

    /**
     * This function extends timber context
     * @param $context
     *
     * @return mixed
     */
    public function opslebTimberContext($context)
    {
        $context['options'] = get_fields('options');
        $context['clients'] = Timber::get_posts(['post_type' => 'partenaires']);
        $context['headerMenu'] = new \Timber\Menu('primary-menu');
        $context['footerMenu'] = new \Timber\Menu('footer-menu');
        $context['is_single'] = is_single();
        $context['is_archive'] = is_archive();
        $context['is_page'] = is_page();
        $context['is_single_stories'] = is_singular('stories');
        $context['current_language'] = ICL_LANGUAGE_CODE;
        return $context;
    }
}
