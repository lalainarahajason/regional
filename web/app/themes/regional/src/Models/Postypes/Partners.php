<?php

namespace App\Models\Postypes;

use App\Models\HooksAdminInterface;
use App\Helpers\PostType;

/**
 * Agenda
 *
 * @author Lebo Studio
 * @version 1.0.0
 * @since 1.0.0
 */
class Partners implements HooksAdminInterface
{

    /**
     * @see Todolist\Models\HooksAdminInterface
     */
    public function hooks()
    {
        add_action("init", [ $this, 'initPostType' ]);
    }

    /**
     * @filter stories_rewrite_cpt_todo
     * @filter stories_register_ PostType::CPT_AGENDA_post_type
     * @see App\Helpers\PostType
     */
    public function initPostType()
    {

        $name   = PostType::CPT_PARTNERS;
        $type = substr($name, -1);
        $plural = $type === 's' ? $name : $name.'s';


        $labels = array(
            'name'              => _x($plural, 'post type general name'),
            'singular'          => _x($name, 'post type singular name'),
            'add_new'           => _x('Add New', strtolower($name)),
            'add_new_item'      => __('Add New '.$name),
            'edit_item'         => __('Edit '.$name),
            'new_item'          => __('New '.$name),
            'all_items'         => __('All '.$plural),
            'view_item'         => __('View '.$name),
            'search_items'      => __('Search '.$plural),
            'not_found'         => __('No '.strtolower($plural).' found'),
            'not_found_in_trash'=> __('No '.strtolower($plural).' found in Trash'),
            'parent_item_colon' => '',
            'menu_name'         => $plural
        );

        $args = array(
            'label'             => $plural,
            'labels'            => $labels,
            'public'            => false,
            'show_ui'           => true,
            'supports'          => array('title', 'thumbnail'),
            'show_in_nav_menus' => true,
            'has_archive'       => true,
            'show_in_rest'      => true,
            //'rewrite'           => array('slug')
            //'_builtin'            => false
        );
        register_post_type(PostType::CPT_PARTNERS, apply_filters("partners_register_" . PostType::CPT_PARTNERS . "_post_type", $args));
    }
}
