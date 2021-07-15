<?php
use App\Controllers\Theme;
/**
 * Home page
 * @package opsleb
 *
 */
$theme = new Theme();
$context            = Timber::get_context();
$context['post']    = new Timber\Post();
/* is post is translated ? */
$current_post = $context['post']->ID;
$context['is_translated'] = apply_filters('wpml_element_has_translations', null, $current_post, 'opportunities');
$context['champs'] = get_field('champs', $current_post);
dump($context['champs']);
Timber::render('single-activities.twig', $context);
