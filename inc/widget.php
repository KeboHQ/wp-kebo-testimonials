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
        
        /**
         * Setup an instance of the View class.
         * Allow customization using a filter.
         */
        $view = new Kbso_View(
            apply_filters(
                'kbte_testimonials_widget_form_view_dir',
                KBTE_PATH . 'views/form',
                $widget_id
            )
        );
        
        $classes[] = 'kform';
        if ( is_rtl() ) {
            $classes[] = 'rtl';
        }
        
        $fields = kbte_get_default_form_fields();
            
        $view
            ->set_view( 'form' )
            ->set( 'widget_id', $widget_id )
            ->set( 'classes', $classes )
            ->set( 'instance', $instance )
            ->set( 'fields', $fields )
            ->set( 'before_widget', $before_widget )
            ->set( 'before_title', $before_title )
            ->set( 'title', $instance['title'] )
            ->set( 'after_title', $after_title )
            ->set( 'after_widget', $after_widget )
            ->set( 'view', $view )
            ->render();
        

        
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