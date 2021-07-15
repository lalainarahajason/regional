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
$context['posts'] = new \Timber\PostQuery(array(
    "showposts" => -1
));

// Cover field
$couverture = get_field('couverture', get_queried_object());

if ($couverture) {
    $couvertureID = $couverture["ID"];
} else {
    $couvertureID = Taxonomy::DEFAULT_COVER;
}
$context['couverture_src'] = wp_get_attachment_image($couvertureID, "full", "", ["class" => "w-full"]);
// Term childrens
$type_children_args = array(
    'taxonomy' =>  get_query_var('post_type') === "" ? "category" : $context["current_taxonomy"]->query_var,
    'hide_empty' => 0,
    "exclude" => [1]
);

$context['terms_childrens'] = Timber::get_terms($type_children_args);
Timber::render('category.twig', $context);