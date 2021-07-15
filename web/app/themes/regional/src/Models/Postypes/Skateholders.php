<?php

namespace App\Models\Postypes;

use App\Models\HooksInterface;
use App\Helpers\PostType;

/**
 * Agenda
 *
 * @author Lebo Studio
 * @version 1.0.0
 * @since 1.0.0
 */
class Skateholders implements HooksInterface
{

    /**
     * @see Todolist\Models\HooksInterface
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

        $name   = PostType::CPT_SKATEHOLDERS;
        $type = substr($name, -1);
        $plural = "Stakeholders";
        $singular = "Stakeholder";

        $labels = array(
            'name'              => _x($plural, 'post type general name'),
            'singular'          => _x($name, 'post type singular name'),
            'add_new'           => _x('Add New', strtolower($singular)),
            'add_new_item'      => __('Add New '.$singular),
            'edit_item'         => __('Edit '.$name),
            'new_item'          => __('New '.$singular),
            'all_items'         => __('All '.$plural),
            'view_item'         => __('View '.$singular),
            'search_items'      => __('Search '.$plural),
            'not_found'         => __('No '.strtolower($plural).' found'),
            'not_found_in_trash'=> __('No '.strtolower($plural).' found in Trash'),
            'parent_item_colon' => '',
            'menu_name'         => $plural
        );

        $args = array(
            'label'             => $plural,
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'supports'          => array('title','editor', 'thumbnail'),
            'show_in_nav_menus' => true,
            'has_archive'       => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug'=>'stakeholders')
            //'_builtin'            => false
        );

        register_post_type(PostType::CPT_SKATEHOLDERS, apply_filters("skateholders_register_" . PostType::CPT_SKATEHOLDERS . "_post_type", $args));
    }
}
