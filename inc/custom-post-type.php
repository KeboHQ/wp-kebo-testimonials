<?php
/*
 * Registers the Testimonials Custom Post Type
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Register the Testimonials CPT (Custom Post Type)
 */
function kbte_create_testimonials_cpt() {
    
    $options = kbte_get_plugin_options();
    
    /*
     * Set the Labels to be used for the CPT
     */
    $labels = array(
        'name' => __('Testimonials', 'kbte'),
        'menu_name' => __('Testimonials', 'kbte'),
        'singular_name' => __('Testimonial', 'kbte'),
        'all_items' => __('All Testimonials', 'kbte'),
        'add_new' => _x('Add New', 'kbte'),
        'add_new_item' => __('Add New Testimonial', 'kbte'),
        'edit' => __('Edit', 'kbte'),
        'edit_item' => __('Edit Testimonial', 'kbte'),
        'new_item' => __('New Testimonial', 'kbte'),
        'view' => __('View', 'kbte'),
        'view_item' => __('View Testimonial', 'kbte'),
        'search_items' => __('Search Testimonials', 'kbte'),
        'not_found' => __('No Testimonials Found', 'kbte'),
        'not_found_in_trash' => __('No Testimonials Found in Trash', 'kbte'),
        'parent' => __('Parent Testimonial', 'kbte'),
        'parent_item_colon' => __('Testimonial:', 'kbte')
    );
    
    /*
     * Prepare the args used to register the CPT
     */
    $args = array(
        'labels' => $labels,
        'description' => __('Testimonials', 'kbte'),
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'show_in_menu' => true, // Visible as Top Level Menu
        'show_in_admin_bar',
        'menu_position' => 99, // 99+ for bottom of list
        'capability_type' => 'post',
        'hierarchical' => false,
        // Can Contain 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions'
        'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
        'taxonomies' => array(''),
        'menu_icon' => '',
        'rewrite' => array(
            'slug' => $options['testimonials_archive_page_slug'], // TODO: change to dynamic $slug
            'feeds' => true, // rss feeds
            'pages' => true, // prepares for pagination
            'with_front' => false // use url prefix like /blog etc.
        ),
        'has_archive' => true,
        'can_export' => true // can be exported
    );

    // Register the CPT
    register_post_type( 'kbte_testimonials', $args );
    
}
add_action( 'init', 'kbte_create_testimonials_cpt' );

/*
 * Register the Testimonials Post Status (Spam)
 */
function kbte_create_testimonials_status() {

    $args = array(
        'label' => _x( 'Spam', 'kbte' ),
        'label_count' => _n_noop( 'Spam <span class="count">(%s)</span>', 'Spam <span class="count">(%s)</span>', 'kbte' ),
        'public' => false,
        'show_in_admin_all_list' => false,
        'show_in_admin_status_list' => true,
        'exclude_from_search' => false,
    );
    
    register_post_status( 'kbte_spam', $args );

}
add_action( 'init', 'kbte_create_testimonials_status' );

/*
 * Load custom Post Status (kbte_spam) for New Post and Edit Post Screen
 */
function kbte_testimonials_load_custom_status_post() {

    global $post;
    $complete = '';
    $label = '';
    
    if( $post->post_type == 'kbte_testimonials' ) {
        
          if( $post->post_status == 'kbte_spam' ) {
              
               $complete = ' selected=\"selected\"';
               $label = '<span id=\"post-status-display\"> ' . __('Spam', 'kbte') . '</span>';
               
          }
          
          ?>
          <script type="text/javascript">
              
            jQuery(document).ready( function($) {
                
                 $("select#post_status").append("<option value=\"kbte_spam\" <?php echo $complete; ?>>Spam</option>");
                 $(".misc-pub-section label").append("<?php echo $label; ?>");
                 
            });
          
          </script>
          <?php
          
     }

}
add_action( 'admin_footer-post.php', 'kbte_testimonials_load_custom_status_post' );
add_action( 'admin_footer-post-new.php', 'kbte_testimonials_load_custom_status_post' );

/*
 * Load custom Post Status (kbte_spam) for Post Listing Quick Edit
 */
function kbte_testimonials_load_custom_status_edit() {

    global $post;
    
    if( $post->post_type == 'kbte_testimonials' ) {
          
          ?>
          <script type="text/javascript">

            jQuery(document).ready( function($){

                $(".inline-edit-status select ").append("<option value=\"kbte_spam\"><?php _e('Spam', 'kbte'); ?></option>");

            });

          </script>
          <?php
          
     }

}

add_action( 'admin_footer-edit.php', 'kbte_testimonials_load_custom_status_edit' );