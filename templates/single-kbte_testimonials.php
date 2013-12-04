<?php
/**
 * The template for displaying the Testimonials Archive page.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */
get_header();
?>

<div class="kcontainer" style="overflow: hidden;">

    <div class="ktestimonials">

            <header class="page-header">
                <h1 class="page-title"><?php __('Testimonials', 'kbte'); ?></h1>
            </header><!-- .page-header -->

            <?php if ( have_posts() ) : ?>

                <div class="ktestimonials-container">

                    <?php while ( have_posts()) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('ktestimonial'); ?>>

                            <div class="entry-content">
                                
                                <?php
                                // check if the post has a Post Thumbnail assigned to it.
                                if ( has_post_thumbnail() ) {
                                    the_post_thumbnail( 'thumbnail' );
                                }
                                ?>
                                
                                <h2 class="entry-title"><?php the_title(); ?></h2>

                                <div class="ktestimonial-text">
                                    <?php the_content(); ?>
                                </div>

                            </div><!-- .entry-content -->

                        </article><!-- #post-<?php the_ID(); ?> -->

                    <?php endwhile; ?>
                        
                </div><!-- .ktestimonials-container -->
                        
                <?php kbte_pagination_nav( 'kpagination' ); ?>

            <?php else : ?>
                        
                <?php
                global $wp_post_types;
                $cpt = $wp_post_types['kebo_testimonials'];
                ?>
                <?php if ( current_user_can( 'publish_posts' ) ) : ?>

                    <p><?php printf(__('Ready to create your first %2$s? <a href="%1$s">Get started here</a>.', 'kebo'), admin_url('post-new.php?post_type=kebo_testimonials'), $cpt->labels->singular_name); ?></p>

                <?php else : ?>

                    <p><?php printf(__('Sorry, there are currently no %1$s to display.', 'kebo'), $cpt->labels->name); ?></p>

                <?php endif; ?>

            <?php endif; ?>

    </div><!-- .kcontainer -->
    
</div><!-- .ktestimonials -->

<?php get_footer(); ?>