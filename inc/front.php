<?php
/* 
 * Customisations to the Front End
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Tells WordPress which template file to use
 */
function kbte_testimonials_template_redirect( $template ) {

    $post_type = get_query_var( 'post_type' );
    
    // Check Post Type
    if ( empty( $post_type ) || 'kbte_testimonials' != $post_type ) {
        return $template;
    }

    /*
     * Check if it is a single Testimonial or not.
     */
    if ( ! is_single() ) {

        // Check the Child Theme
        if ( file_exists( get_stylesheet_directory() . '/archive-kbte_testimonials.php' ) ) {

            $template = get_stylesheet_directory() . '/archive-kbte_testimonials.php';

        }

        // Check the Parent Theme
        elseif ( file_exists( get_template_directory() . '/archive-kbte_testimonials.php' ) ) {

            $template = get_template_directory() . '/archive-kbte_testimonials.php';

        }

        // Use the Plugin Files
        else {

            $template = KBTE_PATH . 'templates/archive-kbte_testimonials.php';

        }

    } else {

        // Check the Child Theme
        if ( file_exists( get_stylesheet_directory() . '/single-kbte_testimonials.php' ) ) {
                
            $template = get_stylesheet_directory() . '/single-kbte_testimonials.php';
                
        }
            
        // Check the Parent Theme
        elseif ( file_exists( get_template_directory() . '/single-kbte_testimonials.php' ) ) {
                
            $template = get_template_directory() . '/single-kbte_testimonials.php';
                
        }
            
        // Use the Plugin Files
        else {
                
            $template = KBTE_PATH . 'templates/single-kbte_testimonials.php';
                
       }

    }

    return $template;
    
}
add_filter( 'template_include', 'kbte_testimonials_template_redirect' );

/**
 * Testimonial Archive Query.
 */
function kbte_testimonials_archive_query( $query ) {

    // Is admin or not main query
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    // Set Testimonials per Page as per user option
    if ( isset( $query->query_vars['post_type'] ) && ( 'kbte_testimonials' == $query->query_vars['post_type'] ) ) {

        $options = kbte_get_plugin_options();
        
        // Orders by the Menu Order attribute
        //$query->set( 'orderby', 'menu_order' );
        // Ascending order (1 first, etc).
        //$query->set( 'order', 'ASC' );
        // User Option for Posts per Page
        $query->set( 'posts_per_archive_page', $options['testimonials_archive_posts_per_page'] );
        
    }
    
    return;

}
add_filter( 'pre_get_posts', 'kbte_testimonials_archive_query', 1 );