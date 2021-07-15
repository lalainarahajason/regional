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
$context['title']   = "Search for jobs &<br/> internships";
// Posts attached to this category
//$query = $GLOBALS['wp_query']->query_vars;
$query['post_type'] = "jobs";
$context['posts'] = new \Timber\PostQuery();
$context['sidebar_paragraph'] = get_field('stories_archive-description', 'option');

// Term childrens
// Term childrens
$filters = array(
    'taxonomy' => 'filters',
    'hide_empty' => 1,
);
$context['countries'] = get_terms('jobs',array(
    'taxonomy' => 'countries',
    'hide_empty' => 1
));

$context['degrees'] = get_terms('jobs',array(
    'taxonomy' => 'degrees',
    'hide_empty' => 1
));
$context['terms_childrens'] = get_terms($filters);
$context['form'] = $_POST;
$context['sidebar_title'] = $theme->filterPageHeaderTitle($sidebar_title);
Timber::render('stories.twig', $context);