<?php

namespace App\Controllers;

use App\Controllers\UsersListTable;
use App\Models\HooksAdminInterface;
use App\Models\Singleton;
use App\Models\Users;
use Timber\Timber;

class Admin implements HooksAdminInterface
{

    use Singleton;

    public function hooks()
    {
        add_action('init', [$this, 'initAdmin']);
        // Remove dashboard widget
        add_action('wp_dashboard_setup', [$this, 'removeDashboardMetaboxes'], 9999);
        // One column layout dashboard
        add_filter('get_user_option_screen_layout_dashboard', [$this,'oneColumnLayoutDashboard']);
        add_filter('screen_layout_columns', [$this, 'screenLayoutColumn']);
        add_filter('screen_options_show_screen', [$this, 'removeDashboardHelpTab']);
        add_filter('upload_mimes', [$this, 'ccMimesTypes']);
        add_action('init', [$this, 'registerMenus']);

        add_filter( 'manage_users_columns', [$this, 'add_user_columns'] );
        add_filter( 'manage_users_custom_column', [$this, 'add_user_column_data'], 10, 3 );

        // Add new admin menu
        add_action("admin_menu", [$this, 'new_users_menu']);

        add_action("init", [$this, 'new_users_process']);

    }

    /**
     * Process new user action
     */
    public function new_users_process() {
        $data = $_GET;
        if(isset($data['page']) && $data['page'] === 'regional-new-users') {
            $action = !isset($data['action']) ? null : $data['action'];
            $user_id = !isset($data['user']) ? null : $data['user'];
            $country = !isset($data['country']) ? null : $data['country'];
            $degree = !isset($data['degree']) ? null : $data['degree'];
            switch ($action) {
                case 'activate':
                    Users::activate_user($user_id, $country, $degree);
                    break;
                case 'delete' :
                    \wp_delete_user($user_id);
                    break;

            }
        }
    }

    public function new_users_menu() {
        $current_page = add_menu_page( __('New users', 'regional'), __('New users', 'regional'), 'manage_categories', 'regional-new-users', array( $this, 'new_users_list' ) );

    }

    public function new_users_list() {

        $new_users = Users::new_users();
        $users = array_map(function($user){
            $data = get_user_by("id", $user["ID"]);
            $country = get_user_meta($user["ID"], "register_country");
            $degree = get_user_meta($user["ID"], "register_degreetype");
            if($degree){
                $data->degree = join("", $degree[0]);
            }
            if($country) {
                $data->country = join(" ", $country[0]);
            }

            return $data;
        }, $new_users);



        /** @var TYPE_NAME $data */
        $data = array_map(function($user){
            $user_data = (array) $user->data;
            $user_data["activate"] = "<a href='admin.php?page=regional-new-users&user=". $user_data['ID'] ."&action=activate&country=" .$user_data['country']."&degree=".$user_data['degree']."' >Activate</a>";
            $user_data["delete"] = "<a href='admin.php?page=regional-new-users&user=". $user_data['ID'] ."&action=delete' >Delete</a>";
            return $user_data;
        }, $users);


        $model = array(
            //'cb'		=> '<input type="checkbox" />', // to display the checkbox.
            //'ID'	=> __( 'ID', 'extranet'),
            'user_login'	=> __( 'Login', 'extranet' ),
            'user_registered' => __('Registered on', 'extranet'),
            'degree' => __('Degree', 'extranet'),
            'country' => __('Country', 'extranet'),
            'activate' => __('Activate', 'extranet'),
            'delete' => __('Delete', 'delete')
        );
        $sortable = array(
            /* 'user_registered' => array('Registered on',false),
            'degree'       => array('Degree',false),
            'country' => array('Country', false)*/
        );
        $users_list_table = new UsersListTable($data, $model, $sortable);
    ?>
        <div class="wrap">
            <h2> <?php _e('New users list', 'regional'); ?> </h2>
            <p><?php _e('Below the list of new users, you can approve the registration or delete the user permanently', 'regional')?></p>
            <form id="nds-user-list-form" action="?page=sendinblue"  method="get">

                <?php $users_list_table->prepare_items(); ?>
                <?php $users_list_table->display(); ?>
            </form>
        </div>
    <?php
    }

    /**
     * add columns to User panel list page
     * @param $mimes array
     * @return array
     */
    function add_user_columns($column) {
        $column['country'] = 'Country';
        $column['degree'] = 'Degree';

        return $column;
    }

    /**
     * add data to columns user panel list page
     * @param $mimes array
     * @return array
     */
    function add_user_column_data( $val, $column_name, $user_id ) {
        $country    = get_user_meta($user_id, "register_country", true);
        $degree     = get_user_meta($user_id, "register_degreetype", true);
        switch ($column_name) {
            case 'country' :
                return isset($country[0]) ? $country[0] : "";
                break;
            case 'degree' :
                return isset($degree[0]) ? $degree[0] : "";
                break;
            default:
        }
        return;
    }


    /**
     * Allow svg to wp media upload
     * @param $mimes array
     * @return array
     */
    public function ccMimesTypes($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     * Init admin
     * @return void
     */
    public function initAdmin()
    {
        remove_action('welcome_panel', 'wp_welcome_panel');
    }

    /**
     * Remove dashboard metaboxes
     * @return void
     */
    public function removeDashboardMetaboxes()
    {
        global $wp_meta_boxes;
        $wp_meta_boxes['dashboard']['normal']['core'] = array();
        $wp_meta_boxes['dashboard']['side']['core'] = array();
    }

    /**
     *
     * @param $cols
     * @return int
     */
    public function oneColumnLayoutDashboard($cols)
    {
        return 1;
    }

    /**
     * @param $visible
     * @return bool
     */
    public function removeDashboardHelpTab($visible)
    {
        global $current_screen;
        $current_screen->remove_help_tabs();
    }

    public function screenLayoutColumn($columns)
    {
        $columns['dashboard'] = 1;
        return $columns;
    }

    /**
     * Register wordpress Menus
     */
    public function registerMenus()
    {
        register_nav_menus(
            array(
                'primary-menu' => __('Header Menu'),
                'footer-menu' => __('Footer Menu')
            )
        );
    }


}
