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
    $columns['title'] = __('Title');
    $columns['details'] = __('Details', 'kbte');
    $columns['rating'] = __('Rating', 'kbte');
    $columns['date'] = __('Date');
    
    return $columns;
    
}	
add_filter( 'manage_edit-kbte_testimonials_columns', 'kbte_testimonials_admin_columns' );

/*
 * Adds which columns should be sortable.
 */
function kbte_testimonials_sortable_admin_columns( $columns ) {
    
    // Add Required Columns
    $columns['title'] = 'title';
    $columns['rating'] = 'rating';
    $columns['date'] = 'date';
    
    return $columns;
    
}	
add_filter( 'manage_edit-kbte_testimonials_sortable_columns', 'kbte_testimonials_sortable_admin_columns' );

/*
 * Adds data to the custom admin columns.
 */
function kbte_testimonials_admin_column_values( $column, $post_id ) {
    
    global $post;
    
    switch ( $column ) {
        
        case 'details' :
            
            // Prepare Meta
            $name = kbte_get_review_name();
            $email = kbte_get_review_email();
            $url = kbte_get_review_url();
            
            // Output Name
            if ( ! empty ( $name ) && ! empty ( $url ) ) {
                
                echo '<a href="' . $url .'" target="_blank">' . $name . '</a><br>';
                
            } elseif ( ! empty ( $name ) ) {
                
                echo '<span>' . $name . '</span><br>';
                
            }
            
            // Output Email
            if ( ! empty( $email ) ) {
                
                echo '<a href="mailto:' . $email .'">' . $email . '</a>';
                
            }
            
            if ( empty ( $name ) && empty ( $email ) ) {
                
                echo '-';
                
            }
            
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
    
    if ( 'rating' == $vars['orderby'] ) {
	$vars = array_merge( $vars, array(
            'meta_key' => '_kbte_testimonials_meta_rating',
            'orderby' => 'meta_value_num'
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

        // Edit the Query Vars for the Admin Post Lists
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