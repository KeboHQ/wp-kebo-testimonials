<?php
/* 
 * Widget to display the Testimonials
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Register the Testimonials Widget
 */
function kbte_testimonials_register_widget() {

    register_widget( 'Kbte_Testimonials_Widget' );
        
}
add_action( 'widgets_init', 'kbte_testimonials_register_widget' );

/*
 * Class to handle Widget Output
 */
class Kbte_Testimonials_Widget extends WP_Widget {

    /**
     * Default Widget Options
     */
    public $default_options = array(
        'title' => null,
        'form_id' => null, // should automatically be generated
        'form_fields' => array( 'name', 'review' ),
    );
    
    /**
     * Has the Admin javascript been printed?
     */
    static $printed_admin_js;
    
    static $printed_frontend_js;
    
    /**
     * Setup the Widget
     */
    function Kbte_Testimonials_Widget() {

        $widget_ops = array(
            'classname' => 'kbte_testimonials_widget',
            'description' => __( 'Displays Testimonials.', 'kbte' )
        );

        $this->WP_Widget(
            false,
            __( 'Kebo Testimonials', 'kbte' ),
            $widget_ops
        );
        
    }
    
    /**
     * Outputs Content
     */
    function widget( $args, $instance ) {
        
        extract( $args, EXTR_SKIP );
        
        // Add Defaults.
        $instance = wp_parse_args( $instance, $this->default_options );
        
        $classes[] = 'ktestimonialform';
        if ( is_rtl() ) {
            $classes[] = 'rtl';
        }
                 
        // Create new Form
        $kbte_form = new Kebo_Form();
            
        $kbte_form->set_ID( $instance['form_id'] );
            
        // Fetch existing form fields
        $kbte_form->load_form();
        
        if ( 'true' == $kbte_form->is_saved || 'true' == $kbte_form->is_error ) {
            
            $form_fields = $kbte_form->form_fields;
            
        } else {
            
            $form_fields = kbte_get_default_form_fields();
            
        }
        
        // If no Form ID was set, something has gone wrong.
        if ( ! isset( $instance['form_id'] ) ) {
            
            return __('Oops, there has been an error.', 'kbte') . ' ' . __('No Form ID was set.', 'kbte');
            
        }
        
        if ( isset( $form_fields ) && is_array( $form_fields ) ) {
        
            // Remove unwanted Form Fields.
            foreach ( $form_fields as $field ) {

                if ( ! in_array( $field['name'], $instance['form_fields'] ) ) {

                    unset( $form_fields[ $field['name'] ] );

                }

            }
        
        }
        
        // If no Form Fields were set, something has gone wrong.
        if ( ! isset( $form_fields ) ) {
            
            return __('Oops, there has been an error.', 'kbte') . ' ' . __('No Form Fields were set.', 'kbte');
            
        }
        
        $options = kbte_get_plugin_options();
        
        $antispam = $options['testimonials_antispam_features'];
        
        /**
         * Setup an instance of the View class.
         * Allow customization using a filter.
         */
        $view = new Kebo_View(
            apply_filters(
                'kbte_testimonials_widget_form_view_dir',
                KBTE_PATH . 'views/form',
                $widget_id
            )
        );
            
        $view
            ->set_view( 'form' )
            ->set( 'widget_id', $widget_id )
            ->set( 'classes', $classes )
            ->set( 'instance', $instance )
            ->set( 'antispam', $antispam )
            ->set( 'fields', $form_fields )
            ->set( 'form_id', $instance['form_id'] )
            ->set( 'is_saved', $kbte_form->is_saved )
            ->set( 'is_error', $kbte_form->is_error )
            ->set( 'before_widget', $before_widget )
            ->set( 'before_title', $before_title )
            ->set( 'title', $instance['title'] )
            ->set( 'after_title', $after_title )
            ->set( 'after_widget', $after_widget )
            ->set( 'view', $view )
            ->render();
        
        // Reset saved status, or error status, if needed

        $kbte_form->is_saved = 'false';
        $kbte_form->is_error = 'false';
        
        /*
         * Clear error values, as we have already displayed them
         */
        foreach ( $form_fields as $field ) {
            
            $form_fields[ $field['name'] ]['error'] = null;
                
        }
        
        $kbte_form->set_fields( $form_fields );
            
        $kbte_form->save_form();
        
        /*
         * Ensure relevant styles/scripts are added to page.
         */
        add_action( 'wp_footer', array( $this, 'printed_frontend_js' ) );
        wp_enqueue_script( 'kbte-foundation-abide' );
        wp_enqueue_style( 'kbte-front' );
        
    }
    
    /*
     * Outputs Options Form
     */
    function form( $instance ) {

        // Add defaults.
        $instance = wp_parse_args( $instance, $this->default_options );
        
        ?>
        <label for="<?php echo $this->get_field_id('title'); ?>">
            <p><?php _e('Title', 'kbte'); ?>: <input style="width: 100%;" type="text" value="<?php echo $instance['title']; ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>"></p>
        </label>

        <div class="kfield">

            <label for="<?php echo $this->get_field_id('field_title'); ?>">
                <p><?php _e('Title Field', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_title']; ?>" name="<?php echo $this->get_field_name('field_title'); ?>" id="<?php echo $this->get_field_id('field_title'); ?>"></p>
            </label>

            <label for="<?php echo $this->get_field_id('field_title_required'); ?>">
                <p><?php _e('Required', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_title_required']; ?>" name="<?php echo $this->get_field_name('field_title_required'); ?>" id="<?php echo $this->get_field_id('field_title_required'); ?>"></p>
            </label>
            
        </div>

        <div class="kfield">

            <label for="<?php echo $this->get_field_id('field_name'); ?>">
                <p><?php _e('Name Field', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_name']; ?>" name="<?php echo $this->get_field_name('field_name'); ?>" id="<?php echo $this->get_field_id('field_name'); ?>" checked="checked" disabled="disabled"></p>
            </label>

            <label for="<?php echo $this->get_field_id('field_name_required'); ?>">
                <p><?php _e('Required', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_name_required']; ?>" name="<?php echo $this->get_field_name('field_name_required'); ?>" id="<?php echo $this->get_field_id('field_name_required'); ?>" checked="checked" disabled="disabled"></p>
            </label>
            
        </div>

        <div class="kfield">

            <label for="<?php echo $this->get_field_id('field_url'); ?>">
                <p><?php _e('URL Field', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_url']; ?>" name="<?php echo $this->get_field_name('field_url'); ?>" id="<?php echo $this->get_field_id('field_url'); ?>"></p>
            </label>

            <label for="<?php echo $this->get_field_id('field_url_required'); ?>">
                <p><?php _e('Required', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_url_required']; ?>" name="<?php echo $this->get_field_name('field_url_required'); ?>" id="<?php echo $this->get_field_id('field_url_required'); ?>"></p>
            </label>
            
        </div>

        <div class="kfield">

            <label for="<?php echo $this->get_field_id('field_email'); ?>">
                <p><?php _e('Email Field', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_email']; ?>" name="<?php echo $this->get_field_name('field_email'); ?>" id="<?php echo $this->get_field_id('field_email'); ?>"></p>
            </label>

            <label for="<?php echo $this->get_field_id('field_email_required'); ?>">
                <p><?php _e('Required', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_email_required']; ?>" name="<?php echo $this->get_field_name('field_email_required'); ?>" id="<?php echo $this->get_field_id('field_email_required'); ?>"></p>
            </label>
            
        </div>

        <div class="kfield">

            <label for="<?php echo $this->get_field_id('field_review'); ?>">
                <p><?php _e('Review Field', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_review']; ?>" name="<?php echo $this->get_field_name('field_review'); ?>" id="<?php echo $this->get_field_id('field_review'); ?>" checked="checked" disabled="disabled"></p>
            </label>

            <label for="<?php echo $this->get_field_id('field_review_required'); ?>">
                <p><?php _e('Required', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_review_required']; ?>" name="<?php echo $this->get_field_name('field_review_required'); ?>" id="<?php echo $this->get_field_id('field_review_required'); ?>" checked="checked" disabled="disabled"></p>
            </label>
            
        </div>

        <div class="kfield">

            <label for="<?php echo $this->get_field_id('field_rating'); ?>">
                <p><?php _e('Rating Field', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_rating']; ?>" name="<?php echo $this->get_field_name('field_rating'); ?>" id="<?php echo $this->get_field_id('field_rating'); ?>"></p>
            </label>

            <label for="<?php echo $this->get_field_id('field_rating_required'); ?>">
                <p><?php _e('Required', 'kbte'); ?>: <input type="checkbox" value="<?php echo $instance['field_rating_required']; ?>" name="<?php echo $this->get_field_name('field_rating_required'); ?>" id="<?php echo $this->get_field_id('field_rating_required'); ?>"></p>
            </label>
            
        </div>

        <input id="<?php echo $this->get_field_id('form_id'); ?>" type="hidden" name="<?php echo $this->get_field_name('form_id'); ?>" value="<?php echo $instance['form_id']; ?>">
        <?php
        
    }
    
    /*
     * Validates and Updates Options
     */
    function update( $new_instance, $old_instance ) {

        $instance = array();

        // Use old figures in case they are not updated.
        $instance = $old_instance;
        
        // Update text inputs and remove HTML.
        $instance['title'] = wp_filter_nohtml_kses( $new_instance['title'] );
        
        if ( ! isset( $instance['form_id'] ) || empty( $instance['form_id'] ) ) {
        
            // Get a unique ID
            $kbte_form = new Kebo_Form;
            
            $instance['form_id'] = $kbte_form->new_ID();
            
            $default_fields = kbte_get_default_form_fields();
            
            $kbte_form->set_fields( $default_fields );
            
            $kbte_form->save_form();
        
        }

        return $instance;
        
    }
    
    /*
     * Ensures frontend JavaScript is only printed once.
     */
    static function printed_frontend_js() {
        
        if ( true === self::$printed_frontend_js ) {
            return;
        }
        
        self::$printed_frontend_js = true;
        
        // Begin Output Buffering
        ob_start();
        ?>

        <script type="text/javascript">
            
            jQuery( document ).ready( function() {
                jQuery( document ).foundation();
            });
            
        </script>

        <?php
        // End Output Buffering and Clear Buffer
        $output = ob_get_contents();
        ob_end_clean();
        
        echo $output;
        
    }
        
}