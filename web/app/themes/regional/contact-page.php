<?php

/**
 * Template Name: Contact page
 * Description: A Page Template of contact form.
 */

$context            = Timber::get_context();
$context['post']    = new Timber\Post();
$postID = get_the_title($context['post']->ID);
$context['sidebar_title'] = "<span class='h-stroke block'> $postID </span>";
$context['contacts'] = get_field('blocs_contact', $post->ID);
//$context['sidebar_paragraph'] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus a pulvinar elit. Aenean molestie congue molestie. Interdum et malesuada fames ac ante ipsum primis in faucibus. ";
Timber::render('single.twig', $context);
