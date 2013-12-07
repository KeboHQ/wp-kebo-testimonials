<?php
/* 
 * Kebo Testimonials - Process Form POST Data
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Checks for form data, sends to processing class
 */
function kbte_check_for_form_data() {
    
    /*
     * Check for Form Submission
     */
    if ( isset( $_POST['kbte_form'] ) ) {
        
        $fields = kbte_get_default_form_fields();
    
        $kbte_form = new Kebo_Form();

        $kbte_form->load_data( $fields );

        $kbte_form->validate_input();

        if ( ! $kbte_form->have_errors() ) {

            $post_id = $kbte_form->save_data();

            return $post_id;

        } else {



        }
        
    }
    
}
add_action( 'init', 'kbte_check_for_form_data' );