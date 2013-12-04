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
        
            <?php
            $options = kbte_get_plugin_options();
            if ( ! empty( $options['testimonials_general_page_title'] ) ) {
            ?>
            <header class="page-header">
                <h1 class="page-title">
                    <?php echo $options['testimonials_general_page_title']; ?>
                </h1>
            </header><!-- .page-header -->
            <?php } ?>

            <?php //echo wpautop( $options['feature_testimonials_intro_text'] ); ?>

            <?php if ( have_posts() ) : ?>

                <div class="ktestimonials-container">

                    <?php while ( have_posts()) : the_post(); ?>
                    
                        <?php
                        $custom_post_meta = get_post_meta( get_the_ID(), 'kbte_testimonials_post_meta', true );
    
                        // Defaults if not set
                        $name = ( isset( $custom_post_meta['reviewer_name'] ) ) ? $custom_post_meta['reviewer_name'] : '' ;
                        $url = ( isset( $custom_post_meta['reviewer_url'] ) ) ? $custom_post_meta['reviewer_url'] : '' ;
                        $rating = ( isset( $custom_post_meta['reviewer_rating'] ) ) ? $custom_post_meta['reviewer_name'] : 0 ;
                        ?>

                        <div id="post-<?php the_ID(); ?>" <?php post_class('ktestimonial'); ?> style="width: 100%; overflow: hidden;">

                            <div itemscope itemtype="http://schema.org/Review">
                                
                                <div itemprop="itemReviewed" itemscope itemtype="http://schema.org/WebPage">
                                    Title: <span itemprop="name"><?php the_title(); ?></span>
                                </div>
                                
                                <?php
                                // check if the post has a Post Thumbnail assigned to it.
                                if ( has_post_thumbnail() ) {
                                    the_post_thumbnail( 'thumbnail' );
                                }
                                ?>
                                
                                <div itemprop="author" itemscope itemtype="http://schema.org/Person">
                                    Author: <span itemprop="name">Peter Booker</span>
                                </div>
                                
                                <div itemprop="reviewBody">
                                    <?php the_content(); ?>
                                </div>
                                
                            </div> 

                        </div><!-- #post-<?php the_ID(); ?> -->

                    <?php endwhile; ?>
                        
                </div><!-- .ktestimonials-container -->
                        
                <?php kbte_pagination_nav(); ?>

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