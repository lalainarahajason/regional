<?php
use App\Controllers\Theme;
/**
 * Home page
 * @package opsleb
 *
 */
$theme = new Theme();
$sidebar_title      = "Scholarship & study | opportunities";

$context            = Timber::get_context();
$context['post']    = new Timber\Post();
$current_post       = $context['post']->ID;
$query_latest       =  ['post_type'=>'post', 'post__not_in' => [$current_post], 'showposts' => 3];
$latest             = Timber::get_posts($query_latest);

$context['latest']  = array_map(function ($post) {
    $category = get_the_category($post->ID);
    if ($category) {
        $post->category = $category[0];
        $post->category->category_link = get_category_link($post->category);
    }
    return $post;
}, $latest);
$context['sidebar_title'] = $theme->filterPageHeaderTitle($sidebar_title);
$context['back_link'] = "/opportunities";

/* is post is translated ? */
$current_post = $context['post']->ID;
$context['is_translated'] = apply_filters('wpml_element_has_translations', null, $current_post, 'opportunities');
Timber::render('single-opportunities.twig', $context);
