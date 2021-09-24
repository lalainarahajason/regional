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
$couverture = get_field('project_cover', 'options');

if ($couverture) {
    $couvertureID = $couverture["ID"];
} else {
    $couvertureID = Taxonomy::DEFAULT_COVER;
}
$context['couverture_src'] = wp_get_attachment_image($couvertureID, "full", "", ["class" => "w-full"]);

// Term childrens
$type_children_args = array(
    'taxonomy' => 'activities',
    'hide_empty' => 0,
);

$context['terms'] = Timber::get_terms($type_children_args);
$context["parent_categories"] = isset($_GET['filter']) ? $_GET['filter'] : 'all' ;
$context['countries'] = Timber::get_terms(['taxonomy' => 'countries', 'hide_empty' => 0]);
$context['translation'] = array(
    'more_filter' => __('More filter', 'regional')
);
Timber::render('taxonomy-activities.twig', $context);
