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

    // Check Post Type
    if ( 'kbte_testimonials' != get_query_var( 'post_type' ) ) {
        return;
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