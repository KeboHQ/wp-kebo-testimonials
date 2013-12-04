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

    if ( 'admin.php' != $pagenow ) {
        return;
    }
    
    /*
     * If the plugin settings have been updated flush rewrite rules
     */
    if ( isset( $_GET['page'] ) && ( 'kbte-testimonials' == $_GET['page'] ) ) {
        $wp_rewrite->flush_rules();
    }
    
}
add_filter( 'admin_init', 'kbte_flush_rewrite_rules' );