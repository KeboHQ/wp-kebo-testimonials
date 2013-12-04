<?php
/* 
 * Customisations to the Admin Testimonials Listing.
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Edit the Admin List Titles for Testimonials.
 */
function kbte_testimonials_admin_columns( $columns ) {
    
    // Remove All Columns
    unset( $columns );
    
    // Add Required Columns
    $columns['cb'] = '<input type="checkbox" />';
    $columns['title'] = __( 'Client Name(s)', 'kebo' );
    $columns['date'] = __( 'Date', 'kebo' );
    
    return $columns;
    
}	
add_filter( 'manage_edit-kbte_testimonials_columns', 'kbte_testimonials_admin_columns' );

/*
 * Adds which columns should be sortable.
 */
function kbte_testimonials_sortable_admin_columns( $columns ) {
    
    // Add Required Columns
    $columns['title'] = 'title';
    $columns['date'] = 'date';
    
    return $columns;
    
}	
add_filter( 'manage_edit-kbte_testimonials_sortable_columns', 'kbte_testimonials_sortable_admin_columns' );

/**
 * Custom Post Type Archive Pagination Limits.
 */
function kbte_testimonials_admin_query( $query ) {

    // Testimonials Admin Query
    if ( is_admin() && $query->is_main_query() ) {

        // Set Testimonials per Page as per user option
        if ( isset( $query->query_vars['post_type'] ) && ( 'kbte_testimonials' == $query->query_vars['post_type'] ) ) {

            // Orders by the Menu Order attribute
            //$query->set('orderby', 'menu_order');
            // Ascending order (1 first, etc).
            //$query->set('order', 'ASC');

            return;
            
        }

    }

}
add_filter( 'pre_get_posts', 'kbte_testimonials_admin_query', 1 );