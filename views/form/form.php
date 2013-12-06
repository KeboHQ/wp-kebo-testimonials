<?php
/**
 * Template file to show Testimonials Form
 */
?>

<?php echo apply_filters( 'kbte_testimonials_form_before_widget', $before_widget, $instance, $widget_id ); ?>

<?php do_action( 'kbte_before_testimonials_form', $instance, $widget_id ); ?>

<?php

/**
 * If the Title has been set, output it.
 */
if ( ! empty( $title ) ) {

    /**
     * Render the Widget Title
     */
    $view
        ->set_view( '_title' )
        ->render();
    
}

?>

<form id="form_kbte_review" class="ktestimonialform" method="post" enctype="multipart/form-data" action="" data-abide>

<div class="name-field">
    <label for="field_name"><?php esc_html_e('Name', 'kbte'); ?> <small><?php esc_html_e('required', 'kbte'); ?></small></label>
    <input id="field_name" name="item_meta[8]" type="text" required>
    <small class="error"><?php esc_html_e('A name is required.', 'kbte'); ?></small>
</div>

<div class="email-field">
    <label for="field_email"><?php esc_html_e('Email', 'kbte'); ?> <small><?php esc_html_e('required', 'kbte'); ?></small></label>
    <input id="field_email" name="item_meta[9]" type="email" required>
    <small class="error"><?php esc_html_e('A valid email address is required.', 'kbte'); ?></small>
</div>

<div class="email-field" style="margin-bottom: 20px;">
    <label for="field_message"><?php esc_html_e('Message', 'kbte'); ?> <small><?php esc_html_e('required', 'kbte'); ?></small></label>
    <textarea id="field_message" name="item_meta[10]" type="text" required></textarea>
    <small class="error"><?php esc_html_e('A message is required.', 'kbte'); ?></small>
</div>

<button id="submit" type="submit"><?php esc_html_e('Submit'); ?></button>

</form>

<?php do_action( 'kbte_after_testimonials_form', $instance, $widget_id ); ?>

<?php echo apply_filters( 'kbte_testimonials_form_before_widget', $after_widget, $instance, $widget_id ); ?>