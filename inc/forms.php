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
function kbte_testimonials_check_for_form_data() {
    
    /*
     * Check for Form Submission
     */
    if ( isset( $_POST['kbte_form'] ) && isset( $_POST['_kbte_id'] ) && is_numeric( $_POST['_kbte_id'] ) ) {
        
        $form_id = absint( $_POST['_kbte_id'] );
    
        $kbte_form = new Kebo_Form();
        
        $kbte_form->set_ID( $form_id );

        $kbte_form->load_form();

        $kbte_form->validate_input();

        if ( ! $kbte_form->have_errors() ) {

            $post_id = $kbte_form->save_data();

            return $post_id;

        }
        
    }
    
}
add_action( 'init', 'kbte_testimonials_check_for_form_data' );