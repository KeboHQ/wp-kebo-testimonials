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
function kbte_testimonials_template_redirect( $template, $query ) {

    if ( 'kbte_testimonials' == $query->query_vars['post_type'] ) {

        if ( ! is_single() ) {

            if ( file_exists( get_stylesheet_directory() . '/archive-kbte_testimonials.php' ) )
                $template = get_stylesheet_directory() . '/archive-kbte_testimonials.php';
            elseif ( file_exists( get_template_directory() . '/archive-kbte_testimonials.php' ) )
                $template = get_template_directory() . '/archive-kbte_testimonials.php';
            else
                $template = KEBO_PLUGIN_PATH . 'templates/archive-kbte_testimonials.php';

        } else {

            if ( file_exists( get_stylesheet_directory() . '/single-kbte_testimonials.php' ) )
                $template = get_stylesheet_directory() . '/single-kbte_testimonials.php';
            elseif ( file_exists( get_template_directory() . '/single-kbte_testimonials.php' ) )
                $template = get_template_directory() . '/single-kbte_testimonials.php';
            else
                $template = KEBO_PLUGIN_PATH . 'templates/archive-kbte_testimonials.php';

        }
        
    }

    return $template;
    
}
add_filter( 'template_include', 'kbte_testimonials_template_redirect' );