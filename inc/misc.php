<?php
/* 
 * Kebo Testimonials - Misc/Helper Functions
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

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
 * Helper Function - Returns Page Title
 */
function kbte_get_page_title() {
    
    $options = kbte_get_plugin_options();
    
    $title = $options['testimonials_archive_page_title'];
    
    return esc_html( $title );
    
}

/*
 * Helper Function - Returns Page Content Before
 */
function kbte_get_page_content_before() {
    
    $options = kbte_get_plugin_options();
    
    $content = $options['testimonials_archive_page_content_before'];
    
    return wp_filter_post_kses( $content );
    
}

/*
 * Helper Function - Returns Reviewer Name
 */
function kbte_get_review_name() {
    
    global $post;
    
    $kbte_custom_meta = get_post_meta( $post->ID, 'kbte_testimonials_post_meta', true );
    
    $name = ( isset( $kbte_custom_meta['reviewer_name'] ) ) ? $kbte_custom_meta['reviewer_name'] : '' ;
    
    return esc_html( $name );
    
}

/*
 * Helper Function - Returns Reviewer Email
 */
function kbte_get_review_email() {
    
    global $post;
    
    $kbte_custom_meta = get_post_meta( $post->ID, 'kbte_testimonials_post_meta', true );
    
    $email = ( isset( $kbte_custom_meta['reviewer_email'] ) ) ? $kbte_custom_meta['reviewer_email'] : '' ;
    
    return esc_html( $email );
    
}

/*
 * Helper Function - Returns Reviewer URL
 */
function kbte_get_review_url() {
    
    global $post;
    
    $kbte_custom_meta = get_post_meta( $post->ID, 'kbte_testimonials_post_meta', true );
    
    $url = ( isset( $kbte_custom_meta['reviewer_url'] ) ) ? $kbte_custom_meta['reviewer_url'] : '' ;
    
    return esc_url( $url );
    
}

/*
 * Helper Function - Returns Reviewer Rating
 */
function kbte_get_review_rating() {
    
    global $post;
    
    $kbte_custom_meta = get_post_meta( $post->ID, 'kbte_testimonials_post_meta', true );
    
    $rating = ( isset( $kbte_custom_meta['reviewer_rating'] ) ) ? $kbte_custom_meta['reviewer_rating'] : null ;
    
    return absint( $rating );
    
}

/*
 * Helper Function - Render Review Rating Stars
 */
function kbte_get_review_rating_stars() {
    
    $total_stars = 5;
    
    $rating = kbte_get_review_rating();
    
    // Begin Output Buffering
    ob_start();
    
    ?>

    <span class="kreviewstars" title="<?php echo sprintf( __('%d out of %d stars', 'kbte'), $rating, $total_stars ); ?>">
        
        <?php for ( $i = 1; $i <= 5; $i++ ) { ?>
        
            <span class="kstar<?php if ( $rating >= $i ) { echo ' active'; } ?>"></span>
            
        <?php } ?>
            
    </span>

    <?php
    
    // End Output Buffering and Clear Buffer
    $output = ob_get_contents();
    ob_end_clean();
        
    return $output;
    
}