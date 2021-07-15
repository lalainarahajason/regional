<?php
// echo phpinfo();
/**
 * Home page
 * @package opsleb
 *
 */
use Timber\Timber;
use App\Helpers\Taxonomy;

// The context
$context = Timber::get_context();

// The current category
$context["current_taxonomy"] = get_queried_object();

// Posts attached to this category

$context['posts'] = Timber::get_posts();

// Cover field
$couverture = get_field('stakeholders_cover', 'options');

if ($couverture) {
    $couvertureID = $couverture["ID"];
} else {
    $couvertureID = Taxonomy::DEFAULT_COVER;
}
$context['couverture_src'] = wp_get_attachment_image($couvertureID, "full", "", ["class" => "w-full"]);

// Term childrens
$type_children_args = array(
    'taxonomy' => 'filters',
    'hide_empty' => 0,
);

$context['terms_childrens'] = Timber::get_terms($type_children_args);
$context["parent_categories"] = isset($_GET['filter']) ? $_GET['filter'] : 'all' ;
$context['countries'] = Timber::get_terms(['taxonomy' => 'countries', 'hide_empty' => 0]);
$context['degrees'] = Timber::get_terms(['taxonomy' => 'degrees', 'hide_empty' => 0]);
$context['form_title'] = 'Scholarship and study opportunities';

if (isset($_GET)) {
    $context['query_parameters'] = $_GET;
}

Timber::render('archive-skateholders.twig', $context);
