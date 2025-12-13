<?php
/**
 * Custom Taxonomies
 *
 * Register custom taxonomies for the theme
 *
 * @package Flavor_Starter
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Article Type Taxonomy
 */
function flavor_register_article_type_taxonomy() {

    $labels = array(
        'name'                       => _x('Article Types', 'Taxonomy General Name', 'flavor-starter'),
        'singular_name'              => _x('Article Type', 'Taxonomy Singular Name', 'flavor-starter'),
        'menu_name'                  => __('Article Types', 'flavor-starter'),
        'all_items'                  => __('All Article Types', 'flavor-starter'),
        'parent_item'                => __('Parent Article Type', 'flavor-starter'),
        'parent_item_colon'          => __('Parent Article Type:', 'flavor-starter'),
        'new_item_name'              => __('New Article Type Name', 'flavor-starter'),
        'add_new_item'               => __('Add New Article Type', 'flavor-starter'),
        'edit_item'                  => __('Edit Article Type', 'flavor-starter'),
        'update_item'                => __('Update Article Type', 'flavor-starter'),
        'view_item'                  => __('View Article Type', 'flavor-starter'),
        'separate_items_with_commas' => __('Separate article types with commas', 'flavor-starter'),
        'add_or_remove_items'        => __('Add or remove article types', 'flavor-starter'),
        'choose_from_most_used'      => __('Choose from the most used', 'flavor-starter'),
        'popular_items'              => __('Popular Article Types', 'flavor-starter'),
        'search_items'               => __('Search Article Types', 'flavor-starter'),
        'not_found'                  => __('Not Found', 'flavor-starter'),
        'no_terms'                   => __('No article types', 'flavor-starter'),
        'items_list'                 => __('Article types list', 'flavor-starter'),
        'items_list_navigation'      => __('Article types list navigation', 'flavor-starter'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'article-type'),
    );

    register_taxonomy('article_type', array('post'), $args);
}
add_action('init', 'flavor_register_article_type_taxonomy', 0);

/**
 * Register Region Taxonomy
 */
function flavor_register_region_taxonomy() {

    $labels = array(
        'name'                       => _x('Regions', 'Taxonomy General Name', 'flavor-starter'),
        'singular_name'              => _x('Region', 'Taxonomy Singular Name', 'flavor-starter'),
        'menu_name'                  => __('Regions', 'flavor-starter'),
        'all_items'                  => __('All Regions', 'flavor-starter'),
        'parent_item'                => __('Parent Region', 'flavor-starter'),
        'parent_item_colon'          => __('Parent Region:', 'flavor-starter'),
        'new_item_name'              => __('New Region Name', 'flavor-starter'),
        'add_new_item'               => __('Add New Region', 'flavor-starter'),
        'edit_item'                  => __('Edit Region', 'flavor-starter'),
        'update_item'                => __('Update Region', 'flavor-starter'),
        'view_item'                  => __('View Region', 'flavor-starter'),
        'separate_items_with_commas' => __('Separate regions with commas', 'flavor-starter'),
        'add_or_remove_items'        => __('Add or remove regions', 'flavor-starter'),
        'choose_from_most_used'      => __('Choose from the most used', 'flavor-starter'),
        'popular_items'              => __('Popular Regions', 'flavor-starter'),
        'search_items'               => __('Search Regions', 'flavor-starter'),
        'not_found'                  => __('Not Found', 'flavor-starter'),
        'no_terms'                   => __('No regions', 'flavor-starter'),
        'items_list'                 => __('Regions list', 'flavor-starter'),
        'items_list_navigation'      => __('Regions list navigation', 'flavor-starter'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'region'),
    );

    register_taxonomy('region', array('post'), $args);
}
add_action('init', 'flavor_register_region_taxonomy', 0);

/**
 * Insert default terms for Article Type taxonomy
 */
function flavor_insert_default_article_types() {

    // Check if terms already exist
    if (term_exists('News', 'article_type')) {
        return;
    }

    $article_types = array(
        'News'               => 'Breaking news and current events',
        'Opinion'            => 'Opinion pieces and editorials',
        'Investigation'      => 'In-depth investigative journalism',
        'In-Depth Analysis'  => 'Comprehensive analysis and context',
        'Feature'            => 'Feature stories and long-form journalism',
        'Breaking'           => 'Breaking news alerts',
    );

    foreach ($article_types as $name => $description) {
        wp_insert_term(
            $name,
            'article_type',
            array(
                'description' => $description,
                'slug'        => sanitize_title($name),
            )
        );
    }
}
add_action('init', 'flavor_insert_default_article_types', 1);

/**
 * Insert default terms for Region taxonomy
 */
function flavor_insert_default_regions() {

    // Check if terms already exist
    if (term_exists('Africa', 'region')) {
        return;
    }

    $regions = array(
        'Africa'       => 'African countries and territories',
        'Middle East'  => 'Middle Eastern countries',
        'Asia'         => 'Asian countries and territories',
        'Europe'       => 'European countries',
        'Americas'     => 'North and South America',
        'Global'       => 'Global issues and international coverage',
    );

    foreach ($regions as $name => $description) {
        wp_insert_term(
            $name,
            'region',
            array(
                'description' => $description,
                'slug'        => sanitize_title($name),
            )
        );
    }
}
add_action('init', 'flavor_insert_default_regions', 1);

/**
 * Add custom columns to Article Type admin page
 */
function flavor_article_type_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['name'] = $columns['name'];
    $new_columns['description'] = __('Description', 'flavor-starter');
    $new_columns['count'] = $columns['posts'];

    return $new_columns;
}
add_filter('manage_edit-article_type_columns', 'flavor_article_type_columns');

/**
 * Add custom columns to Region admin page
 */
function flavor_region_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['name'] = $columns['name'];
    $new_columns['description'] = __('Description', 'flavor-starter');
    $new_columns['count'] = $columns['posts'];

    return $new_columns;
}
add_filter('manage_edit-region_columns', 'flavor_region_columns');
