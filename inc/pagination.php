<?php
/*
 * Pagination for the Testimonials Archive page.
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Display Pagination links for Archive pages.
 */
function kbte_pagination_nav( $nav_class ) {

    global $wp_query;

    $total_pages = $wp_query->max_num_pages;

    if ( $total_pages > 1 ) {

        $current_page = max( 1, get_query_var( 'paged' ) );
        $nav_class .= 'kcentered';
        
        ?>

        <div class="<?php echo $nav_class; ?>">  

            <?php
            echo paginate_links( array(
                'base' => get_pagenum_link( 1 ) . '%_%',
                'format' => 'page/%#%',
                'current' => $current_page,
                'total' => $total_pages,
                'prev_next' => true,
                'prev_text' => __('&laquo; Prev', 'kbte'),
                'next_text' => __('Next &raquo;', 'kbte'),
                'type' => 'list', // plain, array, list
                'add_args' => false,
                'add_fragment' => ''
            ));
            ?>

        </div>

        <?php
    }
}
