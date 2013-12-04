<?php
/* 
 * Kebo Testimonials - Misc/Helper Functions
 */

/*
 * Flush Rewrite Rules
 * Used when changing the Testimonials page slug or installing/uninstalling the plugin.
 */
function kbte_flush_rewrite_rules() {
    
    global $wp_rewrite;
    
    $wp_rewrite->flush_rules();
    
}