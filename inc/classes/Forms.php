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
            
            self::$id_counter++;
            
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
            
            $this->is_saved = false;
            
        }
        
        /*
         * Get Form Fields
         */
        public function get_fields() {
            
            if ( isset( $this->form_id ) ) {
            
                $form_fields = $this->form_fields;
                
                return $form_fields[ $this->form_id ];
            
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
            
                return;
            
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
            
            if ( false === $saved_form_data ) {
                
                $form_data[ $this->form_id ] = $this_form;
                
                update_option( 'kebo_form_data', $form_data );
                
            } else {
                
                $saved_form_data[ $this->form_id ] = $this_form;
                
                update_option( 'kebo_form_data', $saved_form_data );
                
            }
            
        }
        
        /*
         * Validate Fields
         * Uses common names
         */
        public function validate_fields( $fields ) {
            
            switch ( $fields->name ) {
                
                case 'title':
                    
                    $fields->value = ( isset( $_POST['kbte_form']['title'] ) ) ? sanitize_text_field( $_POST['kbte_form']['title'] ) : '' ;
                    
                    if ( ! empty( $fields->value ) && true == $fields->required ) {
                        
                        $fields->error = 'required';
                        
                    }
                    
                    break;
                
                case 'name':
                    
                    $fields->value = ( isset( $_POST['kbte_form']['name'] ) ) ? sanitize_text_field( $_POST['kbte_form']['name'] ) : '' ;
                    
                    if ( ! empty( $fields->value ) && true == $fields->required ) {
                        
                        $fields->error = 'required';
                        
                    }
                    
                    break;
                
                case 'url':
                    
                    $fields->value = ( isset( $_POST['kbte_form']['url'] ) ) ? sanitize_text_field( $_POST['kbte_form']['url'] ) : '' ;
                    
                    if ( ! filter_var( $fields->value, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED ) ) {
                        $fields->error = 'invalid';
                    } elseif ( ! empty( $fields->value ) && true == $fields->required ) {
                        
                        $fields->error = 'required';
                        
                    }
                    
                    break;
                
                default:
                    
                case 'email':
                    
                    $fields->value = ( isset( $_POST['kbte_form']['email'] ) ) ? sanitize_email( $_POST['kbte_form']['email'] ) : '' ;
                    
                    if ( ! is_email( $fields->value ) ) {
                        
                        $fields->error = 'invalid';
                        
                    } elseif ( ! empty( $fields->value ) && true == $fields->required ) {
                        
                        $fields->error = 'required';
                        
                    }
                    
                    break;
                
                default:
                    
                case 'review':
                    
                    $fields->value = ( isset( $_POST['kbte_form']['review'] ) ) ? wp_strip_all_tags( $_POST['kbte_form']['review'] ) : '' ;
                    
                    if ( ! empty( $fields->value ) && true == $fields->required ) {
                        
                        $fields->error = 'required';
                        
                    }
                    
                    break;
                
                default:
                    
                case 'rating':
                    
                    $fields->value = ( isset( $_POST['kbte_form']['rating'] ) ) ? absint( $_POST['kbte_form']['rating'] ) : '' ;
                    
                    if ( ! is_numeric( $fields->value ) ) {
                        
                        $fields->error = 'invalid';
                        
                    } elseif ( ! empty( $fields->value ) && true == $fields->required ) {
                        
                        $fields->error = 'required';
                        
                    }
                    
                    break;
                
                default:
                    
                    $fields->value = ( isset( $_POST['kbte_form']['review'] ) ) ? wp_strip_all_tags( $_POST['kbte_form']['review'] ) : '' ;
                    
                    if ( ! empty( $fields->value ) && true == $fields->required ) {
                        
                        $fields->error = 'required';
                        
                    }
                    
                    break;
                
            }
            
            return $fields;
            
        }
        
        /*
         * Validate POST Data
         */
        public function validate_input() {
            
            /*
             * Check the nonce
             */
            if ( ! wp_verify_nonce( $_POST['_kbte_form'], 'kbte_form_submit') ) {
                
                $this->is_error = true;
                
                return;
                
            }
            
            /*
             * Check if the form was submitted too fast for a human.
             */
            if ( time() < ( $_POST['_kbte_time'] + 3 ) ) {
                
                $this->is_spam = true;
                
            }
            
            /*
             * Check the POST came from our site.
             */
            if ( ( isset( $_SERVER['HTTP_REFERER'] ) && stristr( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) ) ) {
                
                $this->is_spam = true;
                
            }
            
            if ( is_array( $this->form_fields ) ) {
                
                foreach ( $this->form_fields as $field ) {

                    if ( empty( $field->value ) && true == $field->required ) {
                        $field->error = 'required';
                    }
                    
                    if ( empty( $field->value ) && true == $field->required ) {
                        $field->error = 'required';
                    }

                }
                
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
            if ( true == $this->is_spam ) {
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
            if ( true !== $this->is_error ) {
            
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
                
                $this->is_saved = true;
                
            } else {
                
                // was error
                $this->is_saved = false;
                
                return;
                
            }
            
            foreach ( $fields as $key => $field ) {
                $fields[ $key ]['value'] = '';
                $fields[ $key ]['error'] = '';
            }
            
            $this->set_fields( $fields );
            
            $this->save_form();
            
        }
        
    }
    
}