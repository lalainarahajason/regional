<?php
use App\Controllers\Theme;
/**
 * Home page
 * @package opsleb
 *
 */
$theme = new Theme();
$sidebar_title      = "News <br/> Announcements | & Events";

$context            = Timber::get_context();
$context['post']    = new Timber\Post();
$context['partners'] = get_field('partners', $context['post']->ID);
$context['back_link'] = '/projects';
$countries = get_the_terms($context['post']->ID, 'countries');
if($countries) {
    $context['post']->countries = $countries[0];
}

/* is post is translated ? */
$current_post = $context['post']->ID;
$context['is_translated'] = apply_filters('wpml_element_has_translations', null, $current_post, 'projects');
Timber::render('single-projects.twig', $context);
