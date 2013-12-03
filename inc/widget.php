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
        'accounts' => null,
        'title' => null,
        'type' => 'tweets',
    );
    
    /**
     * Has the Admin javascript been printed?
     */
    static $printed_admin_js;
    
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
        
        // TODO output widget html
        
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

        <label class="count" for="<?php echo $this->get_field_id('count'); ?>">
            <p><?php _e('Number to show', 'kbte'); ?>: <input style="width: 28px;" type="text" value="<?php echo $instance['count']; ?>" name="<?php echo $this->get_field_name('count'); ?>" id="<?php echo $this->get_field_id('count'); ?>"> <span><?php _e('Range 1-50', 'kbte') ?></span></p>
        </label>
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
        $instance['title'] = wp_filter_nohtml_kses($new_instance['title']);

        // Check 'count' is numeric.
        if ( is_numeric($new_instance['count'] ) ) {

            // If 'count' is above 50 reset to 50.
            if ( 50 <= intval( $new_instance['count'] ) ) {
                $new_instance['count'] = 50;
            }

            // If 'count' is below 1 reset to 1.
            if ( 1 >= intval($new_instance['count'] ) ) {
                $new_instance['count'] = 1;
            }

            // Update 'count' using intval to remove decimals.
            $instance['count'] = intval( $new_instance['count'] );
        }

        return $instance;
        
    }
        
}