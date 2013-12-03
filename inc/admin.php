<?php
/* 
 * Customisations to the Admin Testimonials Listing.
 */

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