<?php

namespace App\Models\Taxonomies;

use App\Models\HooksInterface;
use App\Helpers\Taxonomy;
use App\Helpers\PostType;

/**
 * Catégorie opportunities
 *
 * @author Lebo Studio
 * @version 1.0.0
 * @since 1.0.0
 */


class Jobs implements HooksInterface{

    public $taxonomies;
    public $object_type;

    public function hooks(){
        $this->taxonomies = [Taxonomy::JOBS];
        add_action( "init", [$this, 'initTaxonomy'] );
    }

    public function get_taxonomies()
    {
        return $this->taxonomies;
    }

    public function initTaxonomy() {

        if( is_array($this->get_taxonomies()) )
        {
            foreach ($this->taxonomies as $taxonomy)
            {

                $name 	= \strtolower(str_replace('_', '', $taxonomy));

                $type = substr($name, -1);

                // Check plural or singular
                $plural = $type === 's' ? $name : $name.'s';



                $labels = array(
                    'name'				=> _x("Categories", 'taxonomy general name'),
                    'singular_name'		=> _x("Category",'taxonomy singular name'),
                    'search_items'		=> __('Search '.$plural),
                    'all_items'			=> __('All '.$plural),
                    'parent_item'		=> __('Parent '.$name),
                    'parent_item_colon'	=> __('Parent '.$name.':'),
                    'edit_item'			=> __('Edit '.$name),
                    'update_item'		=> __('Update '.$name),
                    'add_new_item'		=> __('Add New '.$name),
                    'new_item_name'		=> __('New '.$name.' Name'),
                    'menu_name'			=> __(ucwords($name))
                );


                $args = array_merge(
                    array(
                        'label'				=> "Categorie",
                        'labels'			=> "Categories",
                        'public'			=> true,
                        'show_ui'			=> true,
                        'show_in_nav_menus'	=> true,
                        'hierarchical'      => true,
                        'show_in_rest'      => true,
                        '_builtin'			=> false
                    )
                );
                register_taxonomy($name, "jobs", $args );
            }
        }
    }

}
