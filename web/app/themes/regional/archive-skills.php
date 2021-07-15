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
$context['title']   = "Regional Skills";
// Posts attached to this category
// $query = $GLOBALS['wp_query']->query_vars;
$query = array( "post_type" => "skills");

if(isset($_GET['skill'])) {
    $query["tax_query"] = array(
        array(
            "taxonomy" => "skilltaxonomy",
            "field" => "slug",
            "terms" => [$_GET['skill']]
        )
    );
}
$context['posts'] = new \Timber\PostQuery($query);
$context['sidebar_paragraph'] = get_field('stories_archive-description', 'option');

// Term childrens
/* $filters = array(
    'taxonomy' => 'filters',
    'hide_empty' => 1,
);

$context['countries'] = get_terms(array(
  'taxonomy' => 'countries',
  'hide_empty' => 1
));

$context['posts'] = get_terms(array(
    'taxonomy' => 'degrees',
    'hide_empty' => 1
));
$context['terms_childrens'] = get_terms($filters);
$context['form'] = $_POST;*/

$context['terms'] = get_terms(array(
    'taxonomy' => 'skilltaxonomy',
    'hide_empty' => 1
));
$context['sidebar_title'] = $theme->filterPageHeaderTitle($sidebar_title);
Timber::render('skills.twig', $context);