<?php
/* 
 * Customisations to the Admin Dashboard
 */

/**
 * Adds Testimonials CPT Count to the At a Glance Widget.
 */
function kbte_testimonials_dashboard_post_count( $items ) {

    $post_count = wp_count_posts('kbte_testimonials');
    
    ?>
    <li class="testimonial-count">
        <a href="<?php echo esc_url( admin_url('edit.php?post_type=kbte_testimonials') ); ?>"><?php echo sprintf( __('%d Testimonials', 'kbte'), $post_count->publish ); ?></a>
    </li>
    <?php
    
}
add_filter( 'dashboard_glance_items', 'kbte_testimonials_dashboard_post_count' );