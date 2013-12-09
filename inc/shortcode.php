<?php
/* 
 * Shortcode to display the Testimonials
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Register Shortcode to output Testimonials
 */
function kbte_testimonials_shortcode( $atts ) {
    
    /*
     * Prepare Args
     */
    extract( shortcode_atts( array(
        'foo' => 'no foo',
        'baz' => 'default baz'
    ), $atts ) );
    
    $output = 'TODO: Replicate Widget as Shortcode';
    
    // TODO: Replicate Widget in Shortcode
    
    return $output;
    
}
add_shortcode( 'kebo_testimonials', 'kbte_testimonials_shortcode' );