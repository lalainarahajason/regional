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


class Opportunities implements HooksInterface{

    public $taxonomies;
    public $object_type;

    public function hooks(){
        $this->taxonomies = [Taxonomy::OPPORTUNITIES_COUNTRIES, Taxonomy::OPPORTUNITIES_DEGREES, Taxonomy::OPPORTUNITIES_FILTERS];
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
                    'name'				=> _x(ucwords($plural), 'taxonomy general name'),
                    'singular_name'		=> _x($name,'taxonomy singular name'),
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

                if($name === 'filters'){
                    $labels['menu_name'] = 'More filters';
                } else {
                    $labels['menu_name'] = __(ucwords($name));
                }

                $args = array_merge(
                    array(
                        'label'				=> $plural,
                        'labels'			=> $labels,
                        'public'			=> true,
                        'show_ui'			=> true,
                        'show_in_nav_menus'	=> true,
                        'hierarchical'      => true,
                        'show_in_rest'      => true,
                        '_builtin'			=> false
                    )
                );

                if($name === 'countries'){
                    $this->object_type = ['opportunities', 'projects', 'jobs'];
                } else {
                    $this->object_type = ["opportunities", "jobs"];
                }
                register_taxonomy($name, $this->object_type, $args );
            }
        }



    }

}
