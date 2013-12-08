<?php
/*
 * Kebo Akismet Class.
 * General Class to handle Akismet Integration.
 */

/**
 * Kebo_Akismet
 */
if ( ! class_exists( 'Kebo_Akismet' ) ) {
    
    class Kebo_Akismet {
        
        /*
         * Is Akismet installed?
         */
        public $installed;
        
        /*
         * Is Akismet active?
         */
        public $active;
        
        /*
         * Returns if is installed or not
         */
        public function is_installed() {
            
            return $this->installed;
        
        }
        
        /*
         * Returns if is active or not
         */
        public function is_active() {
            
            return $this->active;
        
        }
        
        /*
         * Init Scripts
         */
        public function __construct() {
            
            $this->installed = $this->check_installed();
            
            $this->active = $this->check_active();
            
        }
        
        /*
         * Check if Akismet is installed
         */
        public function check_installed() {
            
            if ( function_exists( 'akismet_get_key' ) && function_exists( 'akismet_verify_key' ) ) {
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        }
        
        /*
         * Check if Akismet is active (valid API key)
         */
        public function check_active() {
            
            $api_key = akismet_get_key();
            
            if ( $api_key ) {
                
                $check = akismet_verify_key( $key );
                
            }
            
            return $check;
            
            if ( 'failed' != $check[1] ) {
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        }
        
        public function stuff() {
            
            
        
        }
        
    }
    
}