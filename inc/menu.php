<?php
/* 
 * Settings Menu Page
 */

if ( ! defined( 'KBTE_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Register plugin settings page inside the Testimonials menu.
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
add_action( 'admin_menu', 'kbte_testimonials_settings_page' );

/**
 * Renders the Testimonials Settings page.
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

/*
 * Register plugin help page inside the Testimonials menu.
 */
function kbte_testimonials_help_page() {

    add_submenu_page(
            'edit.php?post_type=kbte_testimonials', // Parent
            __('Help - Testimonials', 'kbte'), // Page Title
            __('Help', 'kbte'), // Menu Title
            'manage_options', // Capability
            'kbte-help', // Menu Slug
            'kbte_testimonials_help_page_render' // Render Function
    );

}
add_action( 'admin_menu', 'kbte_testimonials_help_page' );

/**
 * Renders the Testimonials Help page.
 */
function kbte_testimonials_help_page_render() {
    
    $options = kbte_get_plugin_options();
    
    ?>
    <div class="wrap">
        
        <?php screen_icon('options-general'); ?>
        <h2><?php _e('Testimonials - Help', 'kbte'); ?></h2>
        
        <p>Welcome to the Kebo Testimonials help page. This page is here you help you get setup and running as fast as possible with the Testimonials plugin.</p>
        
        <p><i>This plugin has just been released and is still very early in its lifespan, but I am already confident that it has a solid range of features and are implemented to a high standard.<br>
                If you have any problems, or would like to request features please do not hesitate to let me know on the plugins <a href="http://wordpress.org/support/plugin/kebo-te" target="_blank">support forum</a>.</i></p>
        
        <h3><?php _e('Getting Started', 'kbte'); ?></h3>
        
        <p>This list will help you get to grips with how the plugin works and will display on your website.</p>
        
        <p>All the settings discussed are available on the plugin <a href="<?php echo admin_url('edit.php?post_type=kbte_testimonials&page=kbte-testimonials'); ?>">settings page</a>.</p>
        
        <h4>1) <?php _e('Page Slug', 'kbte'); ?></h4>
        
        <p>Set the <strong>'Page Slug'</strong> option for the Testimonials archive page. This is the URL for the page where your Testimonials will be displayed.<br>
            The slug is currently set to <strong><?php echo esc_attr( $options['testimonials_archive_page_slug'] ); ?></strong> which will make it available at: <a href="<?php echo esc_url( site_url( '/' . $options['testimonials_archive_page_slug'] . '/' ) ); ?>" target="_blank"><?php echo esc_url( site_url( '/' . $options['testimonials_archive_page_slug'] . '/' ) ); ?></a>.</p>
        
        <h4>2) <?php _e('Testimonials Per Page', 'kbte'); ?></h4>
        
        <p>Set the <strong>'Testimonials Per Page'</strong> option, which sets how many Testimonials show on each page.<br>
        Setting this to -1 will show all Testimonials on a single page.</p>
            
        <h4>3) <?php _e('Add your first Testimonial', 'kbte'); ?></h4>
        
        <p>You can add Testimonials manually by clicking on the '<a href="<?php echo admin_url('post-new.php?post_type=kbte_testimonials'); ?>">Add New</a>' link under the '<?php _e('Testimonials', 'kbte'); ?>' item on the main menu to the left.<br>
        It is slightly different to default posts, as there is a '<?php _e('Testimonial Details', 'kbte'); ?>' box in the right column which allows you to add custom data to your Testimonials, e.g. Name, Email, Rating.</p>
        
        <h4>4) <?php _e('Add Content to the Testimonials Page', 'kbte'); ?></h4>
        
        <p>If you want to add other content to the Testimonials page, there is an option on the plugin <a href="<?php echo admin_url('edit.php?post_type=kbte_testimonials&page=kbte-testimonials'); ?>">settings page</a> called <strong>'Content Before Testimonials'</strong>, with a text editor like you use for Posts and Pages.<br>
        Any content you add to this will be displayed above the Testimonials. You might want to explain that these are Testimonials by your customers or link to a page where the viewer can submit a Testimonial.</p>
        
        <h4>5) <?php _e('Allow visitors to submit Testimonials', 'kbte'); ?></h4>
        
        <p>Now you can add Testimonials manually, why not let your visitors/customers do that themselves? You can easily put Testimonial Forms on your website two ways:<br><br>
        <strong>Widgets</strong> - From the <a href="<?php echo admin_url('widgets.php'); ?>">Widgets</a> screen you can add the <?php _e('Kebo Testimonials', 'kbte'); ?> widget to any of your Widget areas. The widget options let you choose which fields are visible and which of these are required.<br>
        <strong>Shortcodes</strong> - You can also add Testimonial Forms into any of your pages and/or posts, by adding the Shortcode <strong>[kebo_testimonials_form]</strong>.</p>
        
        <h3><?php _e('More Information and Support', 'kbte'); ?></h3>
        
        <p>If you would like more information or need support you can find it on the <a href="http://wordpress.org/plugins/kebo-testimonials/" target="_blank">wordpress.org page</a>.</p>
        
    </div>

    <?php
    
}