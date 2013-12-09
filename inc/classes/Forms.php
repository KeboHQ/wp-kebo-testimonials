<?php
/*
 * Kebo Form Class.
 * General Class to handle Forms in WordPress.
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Kebo_Form
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
         * Plugin Prefix
         */
        public $prefix;
        
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
         * Was the form successfully saved?
         */
        public $is_saved;
        
        /*
         * Get new ID
         */
        public function new_ID() {
            
            self::$id_counter = uniqid();
            
            $this->form_id = self::$id_counter;
            
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
            
            if ( 'true' == $this->is_error ) {
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        }
        
        /*
         * Default Actions
         */
        public function __construct() {
            
            $this->is_error = 'false';
            
            $this->is_spam = 'false';
            
            $this->is_saved = 'false';
            
        }
        
        /*
         * Get Form Fields
         */
        public function get_fields() {
            
            if ( isset( $this->form_id ) ) {
                
                return $this->form_fields;
            
            } else {
                
                return false;
                
            }
            
        }
        
        /*
         * Set Form Fields
         */
        public function set_fields( $fields ) {
            
            $this->form_fields = $fields;
            
            return;
            
        }
        
        /*
         * Load Form Fields from Option
         */
        public function load_form() {
            
            if ( false !== ( $form_data = get_option( 'kebo_form_data' ) ) && isset( $this->form_id ) ) {
            
                $this->form_fields = $form_data[ $this->form_id ]['fields'];
                
                $this->is_error = $form_data[ $this->form_id ]['options']['is_error'];
                
                $this->is_saved = $form_data[ $this->form_id ]['options']['is_saved'];
            
                return true;
            
            } else {
                
                return false;
                
            }
            
        }
        
        /*
         * Save Form Fields to Option
         */
        public function save_form() {
            
            $this_form = array();
            
            $this_form['fields'] = $this->form_fields;
            
            $this_form['options'] = array(
                'is_error' => $this->is_error,
                'is_saved' => $this->is_saved,
            );
            
            $saved_form_data = get_option( 'kebo_form_data' );
            
            if ( false == $saved_form_data || empty( $saved_form_data ) ) {
                
                $form_data[ $this->form_id ] = $this_form;
                
                update_option( 'kebo_form_data', $form_data );
                
            } else {
                
                $saved_form_data[ $this->form_id ] = $this_form;
                
                update_option( 'kebo_form_data', $saved_form_data );
                
            }
            
        }
        
        /*
         * Validate POST Data
         */
        public function validate_input() {
            
            /*
             * Check the nonce
             */
            if ( ! wp_verify_nonce( $_POST['_kbte_form'], 'kbte_form_submit' ) ) {

                $this->is_error = 'true';

            }

            /*
             * Check if the form was submitted too fast for a human.
             * current time is less than form time + 3 seconds.
             */
            if ( time() < ( $_POST['_kbte_time'] + 3 ) ) {

                $this->is_spam = 'true';

            }

            /*
             * Check the POST came from our site.
             */
            if ( ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['HTTP_REFERER'] ) && false === strpos( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) ) ) {

                $this->is_spam = 'true';

            }
            
            $this->form_fields = kbte_testimonials_form_validation( $this->form_fields );
            
            foreach ( $this->form_fields as $field ) {
                
                if ( isset( $field['error'] ) && ! empty( $field['error'] ) ) {
                    
                    $this->is_error = 'true';
                    
                }
                
            }
            
            $this->save_form();
            
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
            
            /*
             * Check for spam
             */
            if ( 'true' == $this->is_spam ) {
                $status = 'kbte_spam';
            }
            
            /*
             * Check if is User
             */
            if ( 0 != $user_id ) {
                
                // If current user is not logged in, set as the first Administrator
                $args = array(
                    'role' => 'Administrator',
                    'blog_id' => 33,
                    'number' => 1,
                );
                $user_query = new WP_User_Query( $args );
                
                $author = $user_query->results[0]->data->ID;
                
            }
            
            $post_data = array(
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_author' => $author, // Administrator is creating the page
                'post_title' => ( isset( $fields['title']['value'] ) ) ? $fields['title']['value'] : __('No Title - Name: ', 'kbte') . $fields['name']['value'],
                'post_content' => wp_strip_all_tags( $fields['review']['value'] ),
                'post_status' => $status,
                'post_type' => 'kbte_testimonials'
            );
            
            /*
             * Check for WP Error
             */
            if ( 'true' != $this->is_error ) {
            
                $post_id = wp_insert_post( $post_data, true );
            
            } else {
                
                return;
                
            }

            if ( ! is_wp_error( $post_id ) ) {
                
                // successful
                if ( ! empty( $post_meta['reviewer_name'] ) || ! empty( $post_meta['reviewer_url'] ) || ! empty( $post_meta['reviewer_email'] ) ) {
                    
                    update_post_meta( $post_id, '_kbte_testimonials_meta_details', $post_meta );
                
                }
                
                if ( ! empty( $fields['rating']['value'] ) ) {
                    
                    update_post_meta( $post_id, '_kbte_testimonials_meta_rating', $fields['rating']['value'] );
                    
                }
                
                $this->is_saved = 'true';
                
                /*
                 * Action on successful testimonial save
                 */
                do_action( 'kbte_testimonials_testimonial_saved', $post_data, $post_meta, $fields, $post_id );
                
            } else {
                
                // was error
                $this->is_saved = 'false';
                
                return;
                
            }
            
            /*
             * Clear form values after data is saved
             */
            foreach ( $fields as $field ) {
                
                $fields[ $field['name'] ]['value'] = null;
                $fields[ $field['name'] ]['error'] = null;
                
            }
            
            $this->set_fields( $fields );
            
            $this->save_form();
            
        }
        
    }
    
}