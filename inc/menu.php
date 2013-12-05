<?php
/* 
 * Settings Menu Page
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Register plugin settings page inside the Settings menu.
 */
function kbte_testimonials_settings_page() {

    add_submenu_page(
            'edit.php?post_type=kbte_testimonials', // Parent
            __('Settings', 'kbte'), // Page Title
            __('Settings', 'kbte'), // Menu Title
            'manage_options', // Capability
            'kbte-testimonials', // Menu Slug
            'kbte_testimonials_settings_page_render' // Render Function
    );

}
add_action('admin_menu', 'kbte_testimonials_settings_page');


/**
 * Renders the Twitter Feed Options page.
 */
function kbte_testimonials_settings_page_render() {
    
    ?>
    <div class="wrap">
        
        <?php screen_icon('options-general'); ?>
        <h2><?php _e('Testimonials - Settings', 'kbte'); ?></h2>
        <?php settings_errors( 'kbte-testimonials' ); ?>

        <form method="post" action="options.php">
            <?php
            settings_fields('kbte_options');
            do_settings_sections('kbte-testimonials');
            submit_button();
            ?>
        </form>
        
    </div>

    <?php
    
}