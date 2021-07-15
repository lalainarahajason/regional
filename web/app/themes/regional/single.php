<?php
use App\Controllers\Theme;

/**
 * Home page
 * @package opsleb
 *
 */
$theme = new Theme();

$context            = Timber::get_context();

$context["object"] = get_queried_object();

/* Skateholders dialogues */
if (is_single()) {
    $sidebar_title      = "News <br/> Announcements | & Events";
    $context['page_type'] = "single";
    $context['back_link'] = "/category/news-events";
}
if (is_singular('skateholders')) {
    $sidebar_title      = "Stakeholder | dialogues";
    $context['page_type'] = "skateholders";
    $context['back_link'] = get_post_type_archive_link('skateholders');
    $context['hide_bg'] = true;
}

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


/* WPML */
$context['current_language'] = ICL_LANGUAGE_CODE;
/* is post is translated ? */
$context['is_translated'] = apply_filters('wpml_element_has_translations', null, $current_post, 'post');

$context['champs'] = get_field('champs', $current_post);


Timber::render('single-activities.twig', $context);
