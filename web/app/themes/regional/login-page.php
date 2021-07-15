<?php /* Template Name: Registration page */

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
$context['registration'] = true;
$context['login_fails'] = (isset($_GET['login_fails'])) ?? 1;
$context['email_fails'] = (isset($_GET['email_fails'])) ?? 1;
$context['recover'] = isset($_GET['recover']) ? $_GET['recover'] : 0 ;

Timber::render('page.twig', $context);