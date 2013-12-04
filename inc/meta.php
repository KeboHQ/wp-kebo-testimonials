<?php
/* 
 * Kebo testimonials - Post Meta
 */

/*
 * 
 */
function kbte_testimonials_add_client_meta() {
    
    add_meta_box(
        'kbte_testimonials_post_meta',
        __('Reviewer Details', 'kbte'),
        'kbte_testimonials_client_details_render',
        'kbte_testimonials',
        'side',
        'core'
    );
    
}
add_action( 'admin_init', 'kbte_testimonials_add_client_meta' );

function kbte_testimonials_client_details_render() {
    
    $custom_post_meta = get_post_meta( get_the_ID(), 'kbte_testimonials_post_meta', true );
    
    // Defaults if not set
    $name = ( isset( $custom_post_meta['reviewer_name'] ) ) ? $custom_post_meta['reviewer_name'] : '' ;
    $url = ( isset( $custom_post_meta['reviewer_url'] ) ) ? $custom_post_meta['reviewer_url'] : '' ;
    $rating = ( isset( $custom_post_meta['reviewer_rating'] ) ) ? $custom_post_meta['reviewer_name'] : 0 ;
    ?>
    <table style="width: 100%;">
        <tr>
            <td>
                <label for="kbte_reviewer_name"><?php echo __('Name: (optional)', 'kbte'); ?></label>
                <input type="text" id="kbte_reviewer_name" name="kbte_reviewer_name" value="<?php echo $name; ?>" style="width:100%;" /><br><br>
                
                <label for="kbte_reviewer_url"><?php echo __('URL: (optional)', 'kbte'); ?></label>
                <input type="text"  id="kbte_reviewer_url" name="kbte_reviewer_url" value="<?php echo $url; ?>" style="width:100%;" /><br><br>
                
                <label for="kbte_reviewer_rating"><?php echo __('Rating: (optional)', 'kbte'); ?></label>
                <input type="text"  id="kbte_reviewer_rating" name="kbte_reviewer_rating" value="<?php echo $rating; ?>" style="width:100%;" />
                <?php wp_nonce_field('kebo_testimonials_meta-site','kbte-testimonials-meta'); ?>
            </td>
        </tr>
    </table>
    <?php
    
}

function kbte_save_testimonials_client_details( $post_id ) {
    
    // Check Post Type
    if ( isset( $_POST['post_type'] ) ) {
        
        if ( 'kbte_testimonials' == $_POST['post_type'] ) {

            // Avoid autosave overwriting meta.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
                return $post_id; 
            
            // Check for valid Nonse.
            $nonce = $_REQUEST['kbte-testimonials-meta'];
            
            if ( wp_verify_nonce( $nonce, 'kebo_testimonials_meta-site' ) ) {

                $data = array();
                
                // Store data in post meta table if present in post data
                if ( isset( $_POST['kbte_reviewer_name'] ) && !empty( $_POST['kbte_reviewer_name'] ) ) {
                    
                    $data['reviewer_name'] = $_POST['kbte_reviewer_name'];
                    
                }
                
                // Store data in post meta table if present in post data
                if ( isset( $_POST['kbte_reviewer_url'] ) && !empty( $_POST['kbte_reviewer_url'] ) && filter_var( $_POST['kbte_reviewer_url'], FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED )) {
                    
                    $data['reviewer_url'] = $_POST['kbte_reviewer_url'];
                    
                }
                
                update_post_meta( $post_id, 'kbte_testimonials_post_meta', $data );

            }
            
        }
        
    }
    
}
add_action( 'save_post', 'kbte_save_testimonials_client_details', 10, 2 );