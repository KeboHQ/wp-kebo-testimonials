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
    if ( isset( $_POST['kbte_form'] ) && isset( $_POST['_kbte_id'] ) ) {

        $form_id = sanitize_text_field( $_POST['_kbte_id'] );

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
add_action('init', 'kbte_testimonials_check_for_form_data');

/*
 * Sends email to admin when new testimonials are received
 */
function kbte_testimonials_new_entry_saved( $post_id ) {
    
    // If an admin email is set, send a notification email.
    if ( false !== ( $admin_email = get_option( 'admin_email' ) ) ) {
    
        $headers[] = 'From: ' . get_option( 'blogname' ) .' <admin@' . get_option( 'home' ) . '>';

        $to = $admin_email;

        $subject = 'New Testimonial Received - (' . get_option( 'blogname' ) . ')';

        $post_url = 'post.php?post=' . $post_id . '&action=edit';

        $message = 'You have received a new Testimonial on your WordPress site at ' . get_option( 'home' ) . '. To view this Testimonial please <a href="' . admin_url( $post_url ) . '" target="_blank">click here</a>.';

        wp_mail( $to, $subject, $message, $headers );
    
    }
    
}
add_action( 'kbte_testimonials_testimonial_saved', 'kbte_testimonials_new_entry_saved', 1 );

/**
 * Validates Form Submission
 * @param type $fields
 * @return array
 */
function kbte_testimonials_form_validation( $fields ) {

    /*
     * Loop fields and validate each one
     * TODO: No need for switch statement, change to if.
     */
    foreach ( $fields as $field ) {

        switch ( $field['name'] ) {

            case 'title':

                $fields[ $field['name'] ]['value'] = ( isset( $_POST['kbte_form']['title'] ) ) ? sanitize_text_field( trim( $_POST['kbte_form']['title'] ) ) : '';

                if ( empty( $fields[ $field['name'] ]['value'] ) && true == $fields[ $field['name'] ]['required'] ) {

                    $fields[ $field['name'] ]['error'] = 'required';
                    
                }

                break;

            case 'name':

                $fields[ $field['name'] ]['value'] = ( isset( $_POST['kbte_form']['name'] ) ) ? sanitize_text_field( trim( $_POST['kbte_form']['name'] ) ) : '';

                if ( empty( $fields[ $field['name'] ]['value'] ) && true == $fields[ $field['name'] ]['required'] ) {

                    $fields[ $field['name'] ]['error'] = 'required';
                    
                }

                break;

            case 'url':

                $fields[ $field['name'] ]['value'] = ( isset( $_POST['kbte_form']['url'] ) ) ? esc_url_raw( trim( $_POST['kbte_form']['url'] ) ) : '';

                if ( ! filter_var( $fields[ $field['name'] ]['value'], FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED ) ) {

                    $fields[ $field['name'] ]['error'] = 'invalid';
                    
                } elseif ( empty( $fields[ $field['name'] ]['value'] ) && true == $fields[ $field['name'] ]['required'] ) {

                    $fields[ $field['name'] ]['error'] = 'required';
                    
                }

                break;

            default:

            case 'email':

                $fields[ $field['name'] ]['value'] = ( isset( $_POST['kbte_form']['email'] ) ) ? sanitize_email( trim( $_POST['kbte_form']['email'] ) ) : '';

                if ( ! is_email( $fields[ $field['name'] ]['value'] ) ) {

                    $fields[ $field['name'] ]['error'] = 'invalid';
                    
                } elseif ( empty( $fields[ $field['name'] ]['value'] ) && true == $fields[ $field['name'] ]['required'] ) {

                    $fields[ $field['name'] ]['error'] = 'required';
                    
                }

                break;

            default:

            case 'review':

                // Don't allow HTML in Reviews
                // Could use wp_kses_data() to match comment checking
                $allowed_html = array();
                
                $fields[ $field['name'] ]['value'] = ( isset( $_POST['kbte_form']['review'] ) ) ? wp_kses( trim( $_POST['kbte_form']['review'] ), $allowed_html ) : '';

                if ( empty( $fields[ $field['name'] ]['value'] ) && true == $fields[ $field['name'] ]['required'] ) {

                    $fields[ $field['name'] ]['error'] = 'required';
                    
                }

                break;

            default:

            case 'rating':

                $fields[ $field['name'] ]['value'] = ( isset( $_POST['kbte_form']['rating'] ) ) ? absint( trim( $_POST['kbte_form']['rating'] ) ) : '';
                
                if ( ! is_numeric( $fields[ $field['name'] ]['value'] ) ) {

                    $fields[ $field['name'] ]['value'] = null;
                    $fields[ $field['name'] ]['error'] = 'invalid';
                    
                } elseif ( empty($fields[ $field['name'] ]['value'] ) && true == $fields[ $field['name'] ]['required'] ) {

                    $fields[ $field['name'] ]['error'] = 'required';
                    
                } elseif ( 1 > $fields[ $field['name'] ]['value'] && 5 < $fields[ $field['name'] ]['value'] ) {
                    
                    $fields[ $field['name'] ]['value'] = null;
                    $fields[ $field['name'] ]['error'] = 'invalid';
                    
                }

                break;

            default:

                $fields[ $field['name'] ]['value'] = ( isset( $_POST['kbte_form']['review'] ) ) ? wp_strip_all_tags( trim( $_POST['kbte_form']['review'] ) ) : '';

                if ( empty( $fields[ $field['name'] ]['value'] ) && true == $fields[ $field['name'] ]['required'] ) {

                    $fields[ $field['name'] ]['error'] = 'required';
                    
                }

                break;
                
        }
        
    }
    
    return $fields;
    
}
