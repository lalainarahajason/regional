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
class Stories {

    /**
     * @see Todolist\Models\HooksInterface
     */
    public function __construct(){
        add_action( "init", [ $this, 'initPostType' ] );
    }

    /**
     * @filter stories_rewrite_cpt_todo
     * @filter stories_register_ PostType::CPT_AGENDA_post_type
     * @see App\Helpers\PostType
     */
    public function initPostType(){

		    $labels = array(
			    'name'                  => _x( 'Stories', 'Post Type General Name', 'text_domain' ),
			    'singular_name'         => _x( 'Story', 'Post Type Singular Name', 'text_domain' ),
			    'menu_name'             => __( 'Stories', 'text_domain' ),
			    'name_admin_bar'        => __( 'Stories', 'text_domain' ),
			    'archives'              => __( 'Item Archives', 'text_domain' ),
			    'attributes'            => __( 'Item Attributes', 'text_domain' ),
			    'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			    'all_items'             => __( 'All Items', 'text_domain' ),
			    'add_new_item'          => __( 'Add New Item', 'text_domain' ),
			    'add_new'               => __( 'Add New', 'text_domain' ),
			    'new_item'              => __( 'New Item', 'text_domain' ),
			    'edit_item'             => __( 'Edit Item', 'text_domain' ),
			    'update_item'           => __( 'Update Item', 'text_domain' ),
			    'view_item'             => __( 'View Item', 'text_domain' ),
			    'view_items'            => __( 'View Items', 'text_domain' ),
			    'search_items'          => __( 'Search Item', 'text_domain' ),
			    'not_found'             => __( 'Not found', 'text_domain' ),
			    'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			    'featured_image'        => __( 'Featured Image', 'text_domain' ),
			    'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			    'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			    'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			    'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			    'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			    'items_list'            => __( 'Items list', 'text_domain' ),
			    'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			    'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		    );
		    $args = array(
			    'label'                 => __( 'Story', 'text_domain' ),
			    'description'           => __( 'Post Type Description', 'text_domain' ),
			    'labels'                => $labels,
			    'supports'              => array( 'title', 'editor' ),
			    //'taxonomies'            => array( 'category', 'post_tag' ),
			    'hierarchical'          => false,
			    'public'                => true,
			    'show_ui'               => true,
			    'show_in_menu'          => true,
			    'menu_position'         => 5,
			    'show_in_admin_bar'     => true,
			    'show_in_nav_menus'     => true,
			    'show_in_rest'      => true,
			    'can_export'            => true,
			    'has_archive'           => true,
			    'exclude_from_search'   => false,
			    'publicly_queryable'    => true,
			    'capability_type'       => 'page',
		    );
		    register_post_type( 'stories', $args );
    }
}