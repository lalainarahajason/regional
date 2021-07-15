<?php
use App\Controllers\Theme;
use App\Helpers\Taxonomy;
use Timber\Timber;
/**
 * Home page
 * @package opsleb
 *
 */
$theme = new Theme();
$sidebar_title      = "Our <br/> Hopes | Stories";

$context            = Timber::get_context();
$context['title']   = "Networking <br /> Stories & Talks";// Posts attached to this category
$query = $GLOBALS['wp_query']->query_vars;
$query['posts_per_page'] = 10;
$context['posts'] = new \Timber\PostQuery($query);
$context['sidebar_paragraph'] = get_field('stories_archive-description', 'option');


// Term childrens
$type_children_args = array(
    'taxonomy' => 'taxstories',
    'hide_empty' => 1,
);

$context['terms_childrens'] = get_terms($type_children_args);

$context['sidebar_title'] = $theme->filterPageHeaderTitle($sidebar_title);
Timber::render('stories.twig', $context);
