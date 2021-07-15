<?php

namespace App\Controllers;

use App\Models\HooksInterface;
use Timber\Timber;

class Blocks implements HooksInterface
{
    public function hooks()
    {
        add_action('acf/init', [$this, 'initBlocksTypes']);
        add_filter('allowed_block_types', [$this, 'removeCoreBlocks']);
        add_shortcode('loginBlock', [$this, 'loginBlockShortcode']);
    }

    public function initBlocksTypes()
    {
        if (function_exists('acf_register_block_type')) {
            // register a testimonial block.
            acf_register_block_type(array(
                'name'              => 'hopes-download',
                'title'             => __('Download Module'),
                'description'       => __('A custom download module.'),
                'render_callback'   => [$this, 'downloadBlock'],
                'category'          => 'formatting',
                'icon'              => 'download',
                'keywords'          => ['download'],
            ));

            // Button block
            acf_register_block_type(array(
                'name'              => 'hopes-buttons',
                'title'             => __('Bouton Module'),
                'description'       => __('A custom button module.'),
                'render_callback'   => [$this, 'buttondBlock'],
                'category'          => 'formatting',
                'icon'              => 'button',
                'keywords'          => ['buttons'],
                'enqueue_assets' => function () {
                    wp_enqueue_style('hopes-buttons', THEME_DIR_URI . '/src/Views/blocks/buttons.css');
                },
                'supports'          => [
                    'align_content' => true,
                    'align' => false,
                ]
            ));

            // Accordion
            acf_register_block_type(array(
                'name'              => 'hopes-accordion',
                'title'             => __('Accordion Module'),
                'description'       => __('A custom accordion module.'),
                'render_callback'   => [$this, 'accordionBlock'],
                'category'          => 'formatting',
                'icon'              => 'aspectRatio',
                'keywords'          => ['accordion'],
                /* 'enqueue_assets' => function () {
                    wp_enqueue_style('hopes-accordion', THEME_DIR_URI . '/src/Views/blocks/accordion.css');
                }*/
            ));

            // Mosaique
            acf_register_block_type(array(
                'name'              => 'hopes-mosaique',
                'title'             => __('Mosaique Module'),
                'description'       => __('A custom mosaic module.'),
                'render_callback'   => [$this, 'mosaicBlock'],
                'category'          => 'formatting',
                'icon'              => 'tableColumnAfter',
                'keywords'          => ['mosaic'],
                'enqueue_assets' => function () {
                    wp_enqueue_style('hopes-mosaic', THEME_DIR_URI . '/src/Views/blocks/mosaic.css');
                }
            ));
            // Factsheet
            acf_register_block_type(array(
                'name'              => 'hopes-factsheet',
                'title'             => __('Factsheet Module'),
                'description'       => __('A custom factsheet module.'),
                'render_callback'   => [$this, 'factsheetBlock'],
                'category'          => 'formatting',
                'icon'              => 'download',
                'keywords'          => ['mosaic'],
                'enqueue_assets' => function () {
                    wp_enqueue_style('hopes-mosaic', THEME_DIR_URI . '/src/Views/blocks/factsheet.css');
                }
            ));
            // Announcements
            acf_register_block(array(
                'name'              => 'hopes-annoucements',
                'title'             => __('Announcements Module'),
                'description'       => __('A custom annoucements module.'),
                'render_callback'   => [$this, 'announcementsBlock'],
                'category'          => 'formatting',
                'icon'              => 'download',
                'keywords'          => ['announcements'],
                'mode' => 'edit',
                'enqueue_assets' => function () {
                    wp_enqueue_style('hopes-mosaic', THEME_DIR_URI . '/src/Views/blocks/announcements.css');
                }
            ));

            // Opportunities search block
            acf_register_block(array(
                'name'              => 'hopes-search-opportunities',
                'title'             => __('Search Opportunities Module'),
                'description'       => __('A custom search opportunities module.'),
                'render_callback'   => [$this, 'searchOpportunitiesBlock'],
                'category'          => 'formatting',
                'icon'              => 'download',
                'keywords'          => ['announcements'],
                'mode' => 'edit',
                'enqueue_assets' => function () {
                    wp_enqueue_style('hopes-mosaic', THEME_DIR_URI . '/src/Views/blocks/search-opportunities.css');
                }
            ));

            // Opportunities search block
            acf_register_block(array(
                'name'              => 'hopes-project',
                'title'             => __('Project Module'),
                'description'       => __('A project module.'),
                'render_callback'   => [$this, 'projectBlock'],
                'category'          => 'formatting',
                'icon'              => 'download',
                'keywords'          => ['project'],
                'mode' => 'edit'
            ));

            // contacts block
            acf_register_block(array(
                'name'              => 'hopes-contacts',
                'title'             => __('Contacts Module'),
                'description'       => __('A Contact list module.'),
                'render_callback'   => [$this, 'contactsBlock'],
                'category'          => 'formatting',
                'icon'              => 'download',
                'keywords'          => ['project'],
                'mode' => 'edit'
            ));

            acf_register_block_type(array(
                'name'              => 'hopes-introduction',
                'title'             => __('Introduction Module'),
                'description'       => __('A custom introduction module.'),
                'render_callback'   => [$this, 'introductionBlock'],
                'category'          => 'formatting',
                'icon'              => 'download',
                'keywords'          => ['introduction'],
            ));

            acf_register_block_type(array(
                'name'              => 'hopes-slides',
                'title'             => __('Slides Module'),
                'description'       => __('A custom slides module.'),
                'render_callback'   => [$this, 'slidesBlock'],
                'category'          => 'formatting',
                'icon'              => 'download',
                'keywords'          => ['slides'],
            ));

            // Login
            acf_register_block_type(array(
                'name'              => 'hopes-account',
                'title'             => __('Account Module'),
                'description'       => __('A custom account module.'),
                'render_callback'   => [$this, 'accountBlock'],
                'category'          => 'formatting',
                'icon'              => 'download',
                'keywords'          => ['slides'],
            ));
        }
    }

    function loginBlockShortcode(){
        $context = Timber::get_context();
        Timber::render('blocks/login.twig', $context);
    }

    function accountBlock($block, $content = '', $is_preview = false, $post_id = 0) {
        $context = Timber::get_context();
        Timber::render('blocks/login.twig', $context);
    }

    function slidesBlock($block, $content = '', $is_preview = false, $post_id = 0) {
        $context = Timber::get_context();
        $terms = get_field('post_type');

        $context["terms"] = array_map(function($term){
            $term_obj = get_term($term);
            $bg = get_field("skills_categories_background_color", $term_obj);
            $cv = get_field("skills_categories_couverture", $term_obj);
            $term_obj->bg = $bg;
            $term_obj->cv = $cv;
            return $term_obj;
        }, $terms);

        Timber::render('blocks/slides.twig', $context);
    }

    /**
     * Download section block
     *
     * @param $block
     * @param string $content
     * @param bool $is_preview
     * @param int $post_id
     *
     * @return bool|string
     */
    public function downloadBlock($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $context = Timber::get_context();
        $context['block_id'] = $block['id'];
        $context['files'] = get_field('files');
        //$context['thumbnail'] = get_field('thumbnail');
        Timber::render('blocks/download.twig', $context);
    }

    /**
     * Download section block
     *
     * @param $block
     * @param string $content
     * @param bool $is_preview
     * @param int $post_id
     *
     * @return bool|string
     */
    public function introductionBlock($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $context = Timber::get_context();
        $context['block_id']        = $block['id'];
        $context['left_content']    = get_field('left_content');
        $context['right_content']   = get_field('right_content');
        $context['media']    = get_field('media');
        $context['background_color'] = get_field('background_color');


        if(is_user_logged_in() && is_page_template('login-page.php')) {

        } else {
            Timber::render('blocks/introduction.twig', $context);
        }

    }


    /**
     * @param $block
     * @param string $content
     * @param false $is_preview
     * @param int $post_id
     */
    public function buttondBlock($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $context = Timber::get_context();
        $context['block_id'] = $block['id'];
        $context['parameters'] = get_field('button-parameters');
        Timber::render('blocks/buttons.twig', $context);
    }

    /**
     * This method build gutenberg accordion block
     * @param $block
     * @param string $content
     * @param false $is_preview
     * @param int $post_id
     */
    public function accordionBlock($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $context = Timber::get_context();
        $context['block_id'] = $block['id'];
        $context['accordions'] = get_field('accordion');
        Timber::render('blocks/accordion.twig', $context);
    }

    /**
     * This method build gutenberg accordion block
     * @param $block
     * @param string $content
     * @param false $is_preview
     * @param int $post_id
     */
    public function mosaicBlock($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $context = Timber::get_context();
        $context['block_id'] = $block['id'];
        $context['content'] = get_field('content');
        $context['image'] = get_field('image');
        $context['bouton'] = get_field('bouton');
        $context['position'] = get_field('position');
        Timber::render('blocks/mosaic.twig', $context);
    }

    /**
     * This method build gutenberg factsheet block
     * @param $block
     * @param string $content
     * @param false $is_preview
     * @param int $post_id
     */
    public function factsheetBlock($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $context = Timber::get_context();
        $context['block_id'] = $block['id'];
        $context['factsheets'] = get_field('factsheets');
        Timber::render('blocks/factsheet.twig', $context);
    }

    /**
     * This method build gutenberg announcements block
     * @param $block
     * @param string $content
     * @param false $is_preview
     * @param int $post_id
     */
    public function announcementsBlock($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $context = Timber::get_context();
        $context['block_id'] = $block['id'];
        $context['files'] = get_field('files');
        $context['title'] = get_field('title');
        $context['contact'] = get_field('contact');
        Timber::render('blocks/announcements.twig', $context);
    }

    public function searchOpportunitiesBlock($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $context = Timber::get_context();
        $context['block_id'] = $block['id'];
        $context['form_title'] = get_field('form-title');
        $context['countries'] = Timber::get_terms(['taxonomy' => 'countries', 'hide_empty' => 0]);
        $context['degrees'] = Timber::get_terms(['taxonomy' => 'degrees', 'hide_empty' => 0]);
        Timber::render('blocks/search-opportunities.twig', $context);
    }

    public function projectBlock($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $context = Timber::get_context();
        $context['block_id'] = $block['id'];
        $query = array(
            "post_type" => "projects",
            "showposts" => 5
        );
        $projects = Timber::get_posts($query);
        $context['projects'] = array_map(function ($project) {
            $countries = get_the_terms($project->ID, 'countries');
            if (isset($countries[0])) {
                $project->countries = $countries[0];
            }
            return $project;
        }, $projects);
        Timber::render('blocks/project.twig', $context);
    }

    /**
     * Contact block
     * @param $block
     * @param string $content
     * @param false $is_preview
     * @param int $post_id
     */
    public function contactsBlock($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $context = Timber::get_context();
        $context['block_id'] = $block['id'];
        Timber::render('blocks/contact.twig', $context);
    }

    /**
     * @param $allowed_blocks
     *
     * @return string[]
     */
    public function removeCoreBlocks($allowed_blocks)
    {
        return array(
            'core/image',
            'core/paragraph',
            'core/heading',
            'core/list',
            'core/quote',
            'core/video',
            'core/spacer',
            'core/columns',
            'core/cover',
            'core-embed/youtube',
            'core-embed/vimeo',
            'core/gallery',
            'core/html',
            'acf/hopes-download',
            'acf/hopes-buttons',
            'acf/hopes-accordion',
            'acf/hopes-mosaique',
            'acf/hopes-factsheet',
            'acf/hopes-annoucements',
            'acf/hopes-search-opportunities',
            'acf/hopes-project',
            'acf/hopes-introduction',
            'acf/hopes-slides',
            'acf/hopes-account'
        );
    }
}
