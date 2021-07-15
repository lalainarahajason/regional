<?php
// echo phpinfo();
/**
 * Home page
 * @package opsleb
 *
 */
    use Timber\Timber;
    $context = Timber::get_context();
    $context['posts'] = Timber::get_posts(array('post_type' => 'post', 'showposts' => 4));
    $activities = array(
        'post_type' => 'post',
        'showposts' => -1,
    );
    $context['activities'] = Timber::get_posts($activities);

    Timber::render('home.twig', $context);
