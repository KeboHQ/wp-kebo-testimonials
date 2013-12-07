<?php
/*
 * Class to handle Form Data processing.
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Kebo_Data
 */
if ( ! class_exists( 'Kebo_Form' ) ) {
    
    class Kebo_Form {
        
        /*
         * Counter to calculate new IDs
         */
        static $id_counter = 0;
        
        /*
         * Form ID
         */
        public $form_id;
        
        /*
         * Store Form Fields
         */
        public $form_fields = array();
        
        /*
         * Have errors occured?
         */
        public $is_error;
        
        /*
         * Is this spam?
         */
        public $is_spam;
        
        /*
         * Get new ID
         */
        public function get_new_ID() {
            
            self::$id_counter++;
            
            return self::$id_counter;
            
        }
        
        /*
         * Set ID
         */
        public function set_ID( $form_id ) {
            
            $this->form_id = $form_id;
            
        }
        
        /*
         * Get ID
         */
        public function get_ID() {
            
            return $this->form_id;
            
        }
        
        /*
         * Check for Errors
         */
        public function have_errors() {
            
            if ( $this->is_error ) {
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        }
        
        /*
         * Default Actions
         */
        public function __construct() {
            
            $this->is_error = false;
            
            $this->is_spam = false;
            
        }
        
        /*
         * Load POST Data
         */
        public function load_data( $fields ) {
            
            $this->form_fields = $fields;
            
            return;
            
        }
        
        /*
         * Validate POST Data
         */
        public function validate_input() {
            
            // check nonce
            if ( ! wp_verify_nonce( $_POST['_kbte_form'], 'kbte_form_submit') ) {
                
                $this->is_error = true;
                
                return;
                
            }
            
            if ( time() < ( $_POST['_kbte_time'] + 5 ) ) {
                
                $this->is_spam = true;
                
            }
            
            $this->form_fields['title']['value'] = ( isset( $_POST['kbte_form']['title'] ) ) ? sanitize_text_field( $_POST['kbte_form']['title'] ) : '' ;
            
            $this->form_fields['name']['value'] = ( isset( $_POST['kbte_form']['name'] ) ) ? sanitize_text_field( $_POST['kbte_form']['name'] ) : '' ;
            
            $this->form_fields['url']['value'] = ( isset( $_POST['kbte_form']['url'] ) ) ? esc_url( sanitize_text_field( $_POST['kbte_form']['url'] ) ) : '' ;
            
            $this->form_fields['email']['value'] = ( isset( $_POST['kbte_form']['email'] ) && is_email( $_POST['kbte_form']['email'] ) ) ? sanitize_email( $_POST['kbte_form']['email'] ) : '' ;
            
            $this->form_fields['review']['value'] = ( isset( $_POST['kbte_form']['review'] ) ) ? wp_strip_all_tags( $_POST['kbte_form']['review'] ) : '' ;
            
            return;
            
        }
        
        /*
         * Create Post (CPT) in DB
         */
        public function save_data() {
            
            $fields = $this->form_fields;
            
            $status = 'pending';
            $author = 1;
            $user_id = get_current_user_id();
            
            $post_meta = array(
                'reviewer_name' => $fields['name']['value'],
                'reviewer_url' => $fields['url']['value'],
                'reviewer_email' => $fields['email']['value'],
            );
            
            $rating = 5;
            
            /*
             * Check for spam
             */
            if ( $this->is_spam ) {
                $status = 'kbte_spam';
            }
            
            /*
             * Check if is User
             */
            if ( 0 != $user_id ) {
                $author = $user_id;
            }
            
            $post = array(
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_author' => $author, // Administrator is creating the page
                'post_title' => wp_strip_all_tags( $fields['title']['value'] ),
                'post_content' => wp_strip_all_tags( $fields['review']['value'] ),
                'post_status' => $status,
                'post_type' => 'kbte_testimonials'
            );
            
            /*
             * Check for WP Error
             */
            if ( false == $this->is_error ) {
            
                $post_id = wp_insert_post( $post, true );
            
            } else {
                
                return;
                
            }

            if ( ! is_wp_error( $post_id ) ) {
                
                // successful
                if ( ! empty( $post_meta['reviewer_name'] ) || ! empty( $post_meta['reviewer_url'] ) || ! empty( $post_meta['reviewer_email'] ) ) {
                    
                    update_post_meta( $post_id, '_kbte_testimonials_meta_details', $post_meta );
                
                }
                
                if ( ! empty( $rating ) ) {
                    
                    update_post_meta( $post_id, '_kbte_testimonials_meta_rating', $rating );
                    
                }
                
            } else {
                
                // was error
                return;
                
            }
            
        }
        
    }
    
}