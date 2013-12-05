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
    $columns['details'] = __( 'Details', 'kbte' );
    $columns['title'] = __( 'Title', 'kbte' );
    $columns['rating'] = __( 'Rating', 'kbte' );
    $columns['date'] = __( 'Date', 'kbte' );
    
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

/*
 * Adds data to the custom admin columns.
 */
function kbte_testimonials_admin_column_values( $column, $post_id ) {
    
    global $post;
    
    $kbte_custom_meta = get_post_meta( $post->ID, 'kbte_testimonials_post_meta', true );
    
    switch ( $column ) {

        case 'details' :
            echo '<a href="' . kbte_get_review_url() .'" target="_blank">' . kbte_get_review_name() . '</a>';
            echo '<br>';
            echo '<a href="mailto:' . kbte_get_review_email() .'">' . kbte_get_review_email() . '</a>';
            echo '<br>';
        break;
    
        case 'rating' :
            if ( kbte_get_review_rating() ) {
                echo kbte_get_review_rating_stars();
            } else {
                echo __('Not Rated', 'kbte');
            }
        break;

    }
    
}
add_action( 'manage_kbte_testimonials_posts_custom_column' , 'kbte_testimonials_admin_column_values', 10, 2 );

/*
 * Adds custom Orderby data
 */
function kbte_testimonials_admin_column_orderby( $vars ) {
    
    if ( !is_admin() ) {
        return $vars;
    }
    
    if ( ! isset( $vars['orderby'] ) ) {
        return $vars;
    }
    
    if ( 'title' == $vars['orderby'] ) {
	$vars = array_merge( $vars, array(
            'orderby' => 'title'
	));
    }
    
    return $vars;
    
}
add_filter( 'request', 'kbte_testimonials_admin_column_orderby' );

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