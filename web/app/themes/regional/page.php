<?php
// echo phpinfo();
/**
 * Home page
 * @package opsleb
 *
 */
$context            = Timber::get_context();
$context['post']    = new Timber\Post();
$current_post = $context['post']->ID;
/* WPML */
// $context['current_language'] = ICL_LANGUAGE_CODE;
/* is post is translated ? */
$context['is_translated'] = apply_filters( 'wpml_element_has_translations', NULL, $current_post, 'page' );
Timber::render('page.twig', $context);
