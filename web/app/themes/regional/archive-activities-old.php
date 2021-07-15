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
$context['title']   = "Scholarship & Study <br/> Opportunities";
// Posts attached to this category
//$query = $GLOBALS['wp_query']->query_vars;
$query['post_type'] = "activities";
$context['posts'] = new \Timber\PostQuery($query);
$context['sidebar_paragraph'] = get_field('stories_archive-description', 'option');

// Term childrens
$filters = array(
    'taxonomy' => 'filters',
    'hide_empty' => 1,
);

dump($query);

$context['terms_childrens'] = get_terms($filters);
$context['form'] = $_POST;
$context['sidebar_title'] = $theme->filterPageHeaderTitle($sidebar_title);
Timber::render('category.twig', $context);