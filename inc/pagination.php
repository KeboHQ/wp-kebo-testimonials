<?php
/*
 * Pagination for the Testimonials Archive page.
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Display Pagination links for Archive pages.
 * Taken from the Twenty Fourteen theme
 */
function kbte_pagination_nav() {
    
    // Don't print empty markup if there's only one page.
    if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
        return;
    }

    $paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
    $pagenum_link = html_entity_decode( get_pagenum_link() );
    $query_args = array();
    $url_parts = explode( '?', $pagenum_link );

    if ( isset( $url_parts[1] ) ) {
        wp_parse_str( $url_parts[1], $query_args );
    }

    $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
    $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

    $format = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
    $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

    // Set up paginated links.
    $links = paginate_links( array(
        'base' => $pagenum_link,
        'format' => $format,
        'total' => $GLOBALS['wp_query']->max_num_pages,
        'current' => $paged,
        'mid_size' => 1,
        'add_args' => array_map( 'urlencode', $query_args ),
        'prev_text' => __( '&laquo; Previous', 'kbte' ),
        'next_text' => __( 'Next &raquo;', 'kbte' ),
    ) );

    if ( $links ) {

    ?>
    <div class="knav" role="navigation">
        
        <h1 class="screen-reader-text"><?php _e( 'Testimonials navigation', 'kbte' ); ?></h1>
        
        <div class="kpagination">
            <?php echo $links; ?>
        </div><!-- .pagination -->
        
    </div><!-- .navigation -->
    <?php
    
    }
    
}