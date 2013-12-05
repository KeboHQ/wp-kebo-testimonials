<?php
/* 
 * Kebo Testimonials - Misc/Helper Functions
 */

/**
 * Flush Rewrite Rules.
 * Use if slug changes or plugin is installed/uninstalled.
 */
function kbte_flush_rewrite_rules() {
    
    global $pagenow, $wp_rewrite;

    if ( 'options-general.php' != $pagenow ) {
        return;
    }
    
    /*
     * If the plugin settings have been updated flush rewrite rules
     */
    if ( isset( $_GET['page'] ) && ( 'kbte-testimonials' == $_GET['page'] ) && isset( $_GET['settings-updated'] ) ) {
        $wp_rewrite->flush_rules();
    }
    
}
add_filter( 'admin_init', 'kbte_flush_rewrite_rules' );

/*
 * Helper Function - Returns Reviewer Name
 */
function kbte_get_reviewer_name() {
    
    global $post;
    
    $kbte_custom_meta = get_post_meta( $post->ID, 'kbte_testimonials_post_meta', true );
    
    $name = ( isset( $kbte_custom_meta['reviewer_name'] ) ) ? $kbte_custom_meta['reviewer_name'] : '' ;
    
    return esc_html( $name );
    
}

/*
 * Helper Function - Returns Reviewer URL
 */
function kbte_get_reviewer_url() {
    
    global $post;
    
    $kbte_custom_meta = get_post_meta( $post->ID, 'kbte_testimonials_post_meta', true );
    
    $url = ( isset( $kbte_custom_meta['reviewer_url'] ) ) ? $kbte_custom_meta['reviewer_url'] : '' ;
    
    return esc_url( $url );
    
}

/*
 * Helper Function - Returns Reviewer Rating
 */
function kbte_get_reviewer_rating() {
    
    global $post;
    
    $kbte_custom_meta = get_post_meta( $post->ID, 'kbte_testimonials_post_meta', true );
    
    $rating = ( isset( $kbte_custom_meta['reviewer_rating'] ) ) ? $kbte_custom_meta['reviewer_rating'] : null ;
    
    return absint( $rating );
    
}