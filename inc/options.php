<?php
/* 
 * Options API
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Register the form setting for our kebo_options array.
 */
function kbte_plugin_options_init() {
    
    // Get Options
    $options = kbte_get_plugin_options();
    
    register_setting(
            'kbte_options', // Options group
            'kbte_plugin_options', // Database option
            'kbte_plugin_options_validate' // The sanitization callback,
    );

    /**
     * Section - Share Links
     */
    add_settings_section(
            'kbte_testimonials_general', // Unique identifier for the settings section
            __('General Options', 'kbte'), // Section title
            '__return_false', // Section callback (we don't want anything)
            'kbte-testimonials' // Menu slug
    );
    
    /**
     * Field - Activate Feature
     */
    add_settings_field(
            'testimonials_general_visual_style', // Unique identifier for the field for this section
            __('Visual Style', 'kbte'), // Setting field label
            'kbte_options_render_visual_style_dropdown', // Function that renders the settings field
            'kbte-testimonials', // Menu slug
            'kbte_testimonials_general', // Settings section.
            array( // Args to pass to render function
                'name' => 'testimonials_general_visual_style',
                'help_text' => __('Set to none to prevent the default stylesheet being enqueued.', 'kbte')
            ) 
    );
    
    /**
     * Field - Text Intro
     */
    add_settings_field(
            'testimonials_general_page_title', // Unique identifier for the field for this section
            __('Page Title', 'kbte'), // Setting field label
            'kbte_options_render_text_input', // Function that renders the settings field
            'kbte-testimonials', // Menu slug
            'kbte_testimonials_general', // Settings section.
            array( // Args to pass to render function
                'name' => 'testimonials_general_page_title',
                'help_text' => __('Title of Testimonials page.', 'kbte')
            )
    );
    
    /**
     * Field - Text Intro
     */
    add_settings_field(
            'testimonials_general_page_slug', // Unique identifier for the field for this section
            __('Page Slug', 'kbte'), // Setting field label
            'kbte_options_render_text_input', // Function that renders the settings field
            'kbte-testimonials', // Menu slug
            'kbte_testimonials_general', // Settings section.
            array( // Args to pass to render function
                'name' => 'testimonials_general_page_slug',
                'help_text' => __('Slug of Testimonials page.', 'kbte')
            )
    );

}
add_action( 'admin_init', 'kbte_plugin_options_init' );

/**
 * Change the capability required to save the 'kbte_options' options group.
 */
function kbte_plugin_option_capability( $capability ) {
    
    return 'manage_options';
    
}
add_filter( 'option_page_capability_kbte_options', 'kbte_plugin_option_capability' );

/**
 * Returns the options array for kebo.
 */
function kbte_get_plugin_options() {
    
    $saved = (array) get_option( 'kbte_plugin_options' );
    
    $defaults = array(
        // Section - Testimonials - General
        'testimonials_general_visual_style' => 'default',
        'testimonials_general_page_title' => __('Testimonials', 'kbte'),
        'testimonials_general_page_slug' => 'testimonials',
    );

    $defaults = apply_filters( 'kbte_get_plugin_options', $defaults );

    $options = wp_parse_args( $saved, $defaults );
    $options = array_intersect_key( $options, $defaults );

    return $options;
    
}

/**
 * Renders the text input setting field.
 */
function kbte_options_render_text_input( $args ) {
    
    $options = kbte_get_plugin_options();
    
    $name = esc_attr( $args['name'] );
    
    $help_text = ( $args['help_text'] ) ? esc_html( $args['help_text'] ) : null;
        
    ?>
    <label class="description" for="<?php echo $name; ?>">
    <input type="text" name="kbte_plugin_options[<?php echo $name; ?>]" id="<?php echo $name; ?>" value="<?php echo esc_attr( $options[ $name ] ); ?>" />
    </label>
    <?php if ( $help_text ) { ?>
        <span class="howto"><?php echo esc_html( $help_text ); ?></span>
    <?php } ?>
    <?php
        
}

/**
 * Returns an array of radio options for Yes/No.
 */
function kbte_options_radio_buttons() {
    
    $radio_buttons = array(
        'yes' => array(
            'value' => 'yes',
            'label' => __('On', 'kbso')
        ),
        'no' => array(
            'value' => 'no',
            'label' => __('Off', 'kbso')
        ),
    );

    return apply_filters( 'kbte_options_radio_buttons', $radio_buttons );
    
}

/**
 * Returns an array of select inputs for the Visual Style dropdown.
 */
function kbte_options_visual_style_dropdown() {
    
    $dropdown = array(
        'default' => array(
            'value' => 'default',
            'label' => __('Default', 'kbte')
        ),
        'none' => array(
            'value' => 'none',
            'label' => __('None', 'kbte')
        ),
    );

    return apply_filters( 'kbte_options_visual_style_dropdown', $dropdown );
    
}

/**
 * Renders the Theme dropdown.
 */
function kbte_options_render_visual_style_dropdown( $args ) {
    
    $options = kbte_get_plugin_options();
    
    $name = esc_attr( $args['name'] );
    
    ?>
    <select id="<?php echo $name; ?>[<?php echo $dropdown['value']; ?>]" name="kbte_plugin_options[<?php echo $name; ?>]">
    <?php
    foreach ( kbte_options_visual_style_dropdown() as $dropdown ) {
        
        ?>
        <option value="<?php echo esc_attr( $dropdown['value'] ); ?>" <?php selected( $dropdown['value'], $options[ $name ] ); ?>>
            <?php echo esc_html( $dropdown['label'] ); ?>
        </option>
        <?php
        
    }
    ?>
    </select>    
    <?php
        
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 */
function kbte_plugin_options_validate( $input ) {
    
    $options = kbte_get_plugin_options();
    
    $output = array();
    
    if ( isset( $input['testimonials_general_page_title'] ) && ! empty( $input['testimonials_general_page_title'] ) ) {
	$output['testimonials_general_page_title'] = sanitize_title( $input['testimonials_general_page_title'] );
    }
    
    if ( isset( $input['testimonials_general_page_slug'] ) && ! empty( $input['testimonials_general_page_slug'] ) ) {
	$output['testimonials_general_page_slug'] = wp_unique_post_slug( $input['testimonials_general_page_slug'] );
        flush_rewrite_rules();
    }
    
    // Combine Inputs with currently Saved data, for multiple option page compability
    $options = wp_parse_args( $input, $options );
    
    return apply_filters( 'kbte_plugin_options_validate', $options, $output );
    
}