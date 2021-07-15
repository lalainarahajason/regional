<?php
use App\Controllers\Theme;
/**
 * Home page
 * @package opsleb
 *
 */
$theme = new Theme();
$sidebar_title      = "";
$previous_page_name = "hopes stories";

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
$context['previous_page_name'] = $previous_page_name;
$context['hide_back_to'] = true;
$context['back_link'] = '/stories';
$context['js_back_to'] = true;

/**
 * @TODO
 * This context should by called from twig
 * post.meta('profile')
 */
$context['profile'] = get_field('profile', $current_post);

/* is post is translated ? */
$context['is_translated'] = apply_filters('wpml_element_has_translations', null, $current_post, 'stories');


Timber::render('single-stories.twig', $context);
