<?php
/*
 * Uninstall - Remove all traces of the plugin from the WordPress install.
 */

// Check for Un-Install constant.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

if ( is_multisite() ) {

    global $wpdb;

    // Store Network Site ID so we can get back later.
    $current_blog = get_current_blog_id();

    // Get a list of all Blog IDs, ignore network admin with ID of 1.
    $blogs = $wpdb->get_results("
        SELECT blog_id
        FROM {$wpdb->blogs}
        WHERE site_id = '{$wpdb->siteid}'
        AND spam = '0'
        AND deleted = '0'
        AND archived = '0'
        AND blog_id != '{$current_blog}'
    ");

    foreach ( $blogs as $blog ) {

        switch_to_blog( $blog->blog_id );

        // Delete the Option we registered.
        delete_option( 'kbte_plugin_options' );

        // Delete all Posts with our Custom Post Type
        $args = array(
            'post_type' => 'kbte_testimonials',
            'post_status' => 'any',
            'posts_per_page' => -1,
        );

        // Query for posts
        $kbte_posts = new WP_Query( $args );

        if ( ! isset( $kbte_posts ) || ! is_object( $kbte_posts->posts ) ) {
            return;
        }

        // Loop each post and delete
        foreach ( $kbte_posts->posts as $post ) {

            // Ensure it is the correct post type
            if ( 'kbte_testimonials' == $post->post_type ) {

                wp_delete_post( $post->ID, true );

            }

        }
        
    }

    // Go back to Network Site
    switch_to_blog( $current_blog );
    
} else {

    // Delete the Option we registered.
    delete_option( 'kbte_plugin_options' );
    
    // Delete all Posts with our Custom Post Type
    $args = array(
	'post_type' => 'kbte_testimonials',
        'post_status' => 'any',
        'posts_per_page' => -1,
    );
    
    // Query for posts
    $kbte_posts = new WP_Query( $args );
    
    if ( ! isset( $kbte_posts ) || ! is_object( $kbte_posts->posts ) ) {
        return;
    }
    
    // Loop each post and delete
    foreach ( $kbte_posts->posts as $post ) {
        
        // Ensure it is the correct post type
        if ( 'kbte_testimonials' == $post->post_type ) {
            
            wp_delete_post( $post->ID, true );
            
        }
        
    }
    
}