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
// $query = $GLOBALS['wp_query']->query_vars;

$context['posts'] = new \Timber\PostQuery();
$context['sidebar_paragraph'] = get_field('stories_archive-description', 'option');

// Term childrens
$filters = array(
    'taxonomy' => 'filters',
    'hide_empty' => 1,
);

$context['countries'] = get_terms(array(
  'taxonomy' => 'countries',
  'hide_empty' => 1
));

$context['degrees'] = get_terms(array(
    'taxonomy' => 'degrees',
    'hide_empty' => 1
));
$context['terms_childrens'] = get_terms($filters);
$context['form'] = $_POST;
$context['sidebar_title'] = $theme->filterPageHeaderTitle($sidebar_title);
$context['translation'] = array(
    'more_filter' => __('More filter', 'regional')
);
Timber::render('stories.twig', $context);