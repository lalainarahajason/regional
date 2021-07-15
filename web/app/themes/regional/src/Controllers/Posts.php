<?php

namespace App\Controllers;

class Posts
{

    public function __construct()
    {
        add_filter('pre_get_posts', [$this, 'filterPostsQuery']);
        add_filter('posts_where', [$this, 'filterPostsWhere'], 10, 2);
    }

    public function filterPostsQuery($query)
    {
        if (is_admin()) {
            return;
        }
        if ($query->is_post_type_archive && $query->is_main_query()) {


            if (isset($_POST) && !in_array("keyword", array_keys($_POST))) {
                $tax_query = [];
                $tax_query['relation'] = 'AND';
                foreach ($_POST as $key => $value) {
                    if ($value !== "" && $key !== "keyword") {
                        $tax_query[] = array(
                            'taxonomy' => $key,
                            'field' => 'slug',
                            'terms' =>  [$value]
                        );
                    }
                }

                //$query->set('post_type', 'opportunities');
                $query->set('tax_query', $tax_query);
            }
        }
        if ($query->is_main_query()) {
            if (!is_post_type_archive("stories")) {
                $query->set("posts_per_page", -1);
            } else {
                $query->set("posts_per_page", 9);
            }
        }
        return $query;
    }

    /**
     * SEARCH BY POST TITLE
     * @param $where
     * @param $query
     * @return mixed|string
     */
    public function filterPostsWhere($where, $query): string
    {
        global $wpdb;

        if (isset($_POST['keyword']) && $query->is_main_query()) {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($wpdb->esc_like($_POST['keyword'])) . '%\'';
        }
        return $where;
    }
}