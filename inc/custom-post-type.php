<?php
/*
 * Registers the Testimonials Custom Post Type
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Register the Testimonials CPT (Custom Post Type)
 */
function kbte_create_testimonials_cpt() {
    
    /*
     * Set the Labels to be used for the CPT
     */
    $labels = array(
        'name' => __('Testimonials', 'kbte'),
        'menu_name' => __('Testimonials', 'kbte'),
        'singular_name' => __('Testimonial', 'kbte'),
        'all_items' => __('All Testimonials', 'kbte'),
        'add_new' => _x('Add New', 'kbte'),
        'add_new_item' => __('Add New Testimonial', 'kbte'),
        'edit' => __('Edit', 'kbte'),
        'edit_item' => __('Edit Testimonial', 'kbte'),
        'new_item' => __('New Testimonial', 'kbte'),
        'view' => __('View', 'kbte'),
        'view_item' => __('View Testimonial', 'kbte'),
        'search_items' => __('Search Testimonials', 'kbte'),
        'not_found' => __('No Testimonials Found', 'kbte'),
        'not_found_in_trash' => __('No Testimonials Found in Trash', 'kbte'),
        'parent' => __('Parent Testimonial', 'kbte'),
        'parent_item_colon' => __('Testimonial:', 'kbte')
    );
    
    /*
     * Prepare the args used to register the CPT
     */
    $args = array(
        'labels' => $labels,
        'description' => __('Testimonials', 'kbte'),
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'show_in_menu' => true, // Visible as Top Level Menu
        'show_in_admin_bar',
        'menu_position' => 20.83841, // TODO??
        'capability_type' => 'post',
        'hierarchical' => false,
        // Can Contain 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions'
        'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
        'taxonomies' => array(''),
        'menu_icon' => '',
        'rewrite' => array(
            'slug' => 'testimonials', // TODO: change to dynamic $slug
            'feeds' => false, // rss feeds
            'pages' => true, // prepares for pagination
            'with_front' => false // use url prefix like /blog etc.
        ),
        'has_archive' => true,
        'can_export' => true // can be exported
    );

    // Register the CPT
    register_post_type( 'kbte_testimonials', $args );
    
}
add_action( 'init', 'kbte_create_testimonials_cpt' );
