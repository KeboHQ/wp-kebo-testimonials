<?php
/* 
 * Kebo testimonials - Post Meta
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * 
 */
function kbte_testimonials_add_client_meta() {
    
    add_meta_box(
        'kbte_testimonials_post_meta',
        __('Testimonial Details', 'kbte'),
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
    $email = ( isset( $custom_post_meta['reviewer_email'] ) ) ? $custom_post_meta['reviewer_email'] : '' ;
    $url = ( isset( $custom_post_meta['reviewer_url'] ) ) ? $custom_post_meta['reviewer_url'] : '' ;
    $rating = ( isset( $custom_post_meta['reviewer_rating'] ) ) ? $custom_post_meta['reviewer_rating'] : null ;
    ?>
    <div class="kpostmeta">
        
        <p>
            <label for="kbte_reviewer_name"><strong><?php echo __('Name: (optional)', 'kbte'); ?></strong></label>
        </p>
        
        <p>
            <input type="text" id="kbte_reviewer_name" name="kbte_reviewer_name" value="<?php echo $name; ?>" />
        </p>
        
        <p>
            <label for="kbte_reviewer_email"><strong><?php echo __('Email: (optional)', 'kbte'); ?></strong></label>
        </p>
        
        <p>
            <input type="text" id="kbte_reviewer_email" name="kbte_reviewer_email" value="<?php echo $email; ?>" />
        </p>
        
        <p>
            <label for="kbte_reviewer_url"><strong><?php echo __('URL: (optional)', 'kbte'); ?></strong></label>
        </p>
        
        <p>
            <input type="text" id="kbte_reviewer_url" name="kbte_reviewer_url" value="<?php echo $url; ?>" />
        </p>
        
        <p>
            <label><strong><?php echo __('Rating: (optional)', 'kbte'); ?></strong></label>
        </p>
        
        <div class="krating">
            
            <input type="radio" id="kbte_rating_5" class="krating-input" name="kbte_reviewer_rating" value="5" <?php checked( $rating, 5 ); ?>>
            <label for="kbte_rating_5" class="krating-star"></label>
            
            <input type="radio" id="kbte_rating_4" class="krating-input" name="kbte_reviewer_rating" value="4" <?php checked( $rating, 4 ); ?>>
            <label for="kbte_rating_4" class="krating-star"></label>
            
            <input type="radio" id="kbte_rating_3" class="krating-input" name="kbte_reviewer_rating" value="3" <?php checked( $rating, 3 ); ?>>
            <label for="kbte_rating_3" class="krating-star"></label>
            
            <input type="radio" id="kbte_rating_2" class="krating-input" name="kbte_reviewer_rating" value="2" <?php checked( $rating, 2 ); ?>>
            <label for="kbte_rating_2" class="krating-star"></label>
            
            <input type="radio" id="kbte_rating_1" class="krating-input" name="kbte_reviewer_rating" value="1" <?php checked( $rating, 1 ); ?>>
            <label for="kbte_rating_1" class="krating-star"></label>
            
        </div>
        
        <?php wp_nonce_field( 'kebo_testimonials_meta-site', 'kbte-testimonials-meta' ); ?>
        
    </div>
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
                if ( isset( $_POST['kbte_reviewer_name'] ) && ! empty( $_POST['kbte_reviewer_name'] ) ) {
                    
                    $data['reviewer_name'] = $_POST['kbte_reviewer_name'];
                    
                }
                
                // Store data in post meta table if present in post data
                if ( isset( $_POST['kbte_reviewer_email'] ) && ! empty( $_POST['kbte_reviewer_email'] ) && is_email( $_POST['kbte_reviewer_email'] ) ) {
                    
                    $data['reviewer_email'] = $_POST['kbte_reviewer_email'];
                    
                }
                
                // Store data in post meta table if present in post data
                if ( isset( $_POST['kbte_reviewer_url'] ) && ! empty( $_POST['kbte_reviewer_url'] ) && filter_var( $_POST['kbte_reviewer_url'], FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED ) ) {
                    
                    $data['reviewer_url'] = $_POST['kbte_reviewer_url'];
                    
                }
                
                // Store data in post meta table if present in post data
                if ( isset( $_POST['kbte_reviewer_rating'] ) && ! empty( $_POST['kbte_reviewer_rating'] ) ) {
                    
                    $data['reviewer_rating'] = absint( $_POST['kbte_reviewer_rating'] );
                    
                }
                
                update_post_meta( $post_id, 'kbte_testimonials_post_meta', $data );

            }
            
        }
        
    }
    
}
add_action( 'save_post', 'kbte_save_testimonials_client_details', 10, 2 );