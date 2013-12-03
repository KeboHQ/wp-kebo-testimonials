<?php
/* 
 * Customisations to the Admin Testimonials Listing.
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Customise the Title Placeholder for Testimonials.
 */
function kbte_testimonials_title_placeholder( $title ) {

    // Gets current post type
    $screen = get_current_screen();
    $post_type = $screen->post_type;
    
    // Only change for the right post type.
    if ( 'kbte_testimonials' == $post_type ) {
        
        $title = apply_filters( 'kbte_testimonials_title_placeholder', 'Enter client name(s) here', $post_type );
        
    }

    return $title;
    
}
add_filter( 'enter_title_here', 'kbte_testimonials_title_placeholder' );

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