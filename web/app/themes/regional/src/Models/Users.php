<?php

namespace App\Models;

class Users {

    public static function new_users() {
        global $wpdb;
        $sql = "SELECT *  FROM $wpdb->users as u 
        LEFT JOIN $wpdb->usermeta as m ON m.user_id = u.ID 
        WHERE m.meta_key = 'inactive_user' AND m.meta_value = 1";

        return $wpdb->get_results($sql, ARRAY_A);
    }

    /* public static function delete_user($user_id){
        \wp_delete_user($user_id);
    }*/

    /**
     * Activate new user
     *
     * @param $user_id
     * @param $country
     * @param $degree
     */
    public static function activate_user($user_id, $country, $degree) {

        $user = get_user_by("id", (int) $user_id);
        if($user) {
            $new_pwd = wp_generate_password( $length = 12, $include_standard_special_chars = true );
            wp_set_password( $new_pwd, $user_id );
            $user->password = $new_pwd;
            $send = self::send_new_account_email($user, $country, $degree);
            if($send) {
                delete_user_meta($user_id, 'inactive_user');
            }
        }

    }

    /**
     * Send user account by email
     *
     * @param $message
     * @param $user
     * @param string $country
     * @param string $degree
     * @return bool
     */
    public static function send_new_account_email($user , $country = "", $degree="") {
        add_filter( "wp_mail_content_type", function(){
            return "text/html";
        });
        $site_url = str_replace('/wp', '', site_url());
        $login_url = $site_url . '/connexion';
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $message = get_field("emails_content_new_user_email", "option");
        $message = str_replace("#user#", $user->first_name, $message);
        $message = str_replace('#email#', $user->user_email, $message);
        $message = str_replace('#password#', $user->password, $message);
        $message = str_replace('#login_url#', $login_url, $message);
        $message = str_replace('#country#', $country, $message);
        $message = str_replace('#degree#', $degree, $message);
        $headers = 'From: noreply@regional-alumni-network.eu.com';
        wp_mail($user->user_email,"Your credentials details", $message, $headers);

        return true;
    }
}