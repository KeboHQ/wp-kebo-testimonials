<?php
/**
 * The template for displaying the Testimonials Archive page.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */
get_header();
?>

<div class="kcontainer">

    <div class="ktestimonials">
        
            <?php if ( kbte_get_page_title() ) { ?>
                <h2 class="page-title">
                    <?php echo kbte_get_page_title(); ?>
                </h2>
            <?php } ?>

            <div class="kcontentbefore">
                <?php echo wpautop( kbte_get_page_content_before() ); ?>
            </div>

            <?php if ( have_posts() ) : ?>

                <div class="ktestimonials">
                    
                    <?php
                    $columns = kbte_get_columns();
                    ?>
                    
                    <ul class="small-kebo-grid-<?php echo esc_attr( $columns['small'] ); ?> medium-kebo-grid-<?php echo esc_attr( $columns['medium'] ); ?> large-kebo-grid-<?php echo esc_attr( $columns['large'] ); ?>">

                        <?php while ( have_posts()) : the_post(); ?>

                            <li>
                                
                                <?php
                                // check if post has reviewer name
                                if ( kbte_get_review_name() ) {
                                    $classes = 'ktestimonial has-name';
                                } else {
                                    $classes = 'ktestimonial';
                                }
                                ?>
                        
                                <div id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
                                
                                    <div itemscope itemtype="http://schema.org/Review">

                                        <div class="kbody">
                                        
                                            <div class="kheader">

                                                <div itemprop="itemReviewed" itemscope itemtype="http://schema.org/WebPage">
                                                    <meta itemprop="url" content="<?php echo get_option('siteurl'); ?>">
                                                </div>

                                                <span itemprop="name"><strong><?php the_title(); ?></strong></span>

                                            </div>

                                            <div class="kcontent" itemprop="reviewBody">
                                                <?php the_content(); ?>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="kfooter">
                                            
                                            <div class="krating">
                                            <?php if ( kbte_get_review_rating() ) {
                                                echo kbte_get_review_rating_stars();
                                            } ?>
                                            </div>
                                            
                                            <div class="kauthor" title="<?php echo kbte_get_review_name() . ' - ' . kbte_get_review_url(); ?>">
                                                <span itemprop="author" itemscope itemtype="http://schema.org/Person">
                                                    <a href="<?php echo kbte_get_review_url(); ?>" target="_blank"><span itemprop="name"><?php echo kbte_get_review_name(); ?></span></a>
                                                </span>
                                            </div>
                                            
                                        </div>

                                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                                            <meta itemprop="worstRating" content="1" />
                                            <meta itemprop="ratingValue" content="<?php echo kbte_get_review_rating(); ?>" />
                                            <meta itemprop="bestRating" content="5">
                                        </div>

                                    </div>
                                    
                                </div><!-- #post-<?php the_ID(); ?> -->

                            </li>

                        <?php endwhile; ?>
                        
                    </ul>
                        
                </div><!-- .ktestimonials -->
                
                <?php kbte_pagination_nav(); ?>

            <?php else : ?>
                        
                <?php
                global $wp_post_types;
                $cpt = $wp_post_types['kbte_testimonials'];
                ?>
                <?php if ( current_user_can( 'publish_posts' ) ) : ?>

                    <p><?php printf( __('Ready to create your first %2$s? <a href="%1$s">Get started here</a>.', 'kbte'), admin_url( 'post-new.php?post_type=kbte_testimonials' ), $cpt->labels->singular_name ); ?></p>

                <?php else : ?>

                    <p><?php printf( __('Sorry, there are currently no %1$s to display.', 'kbte'), $cpt->labels->name ); ?></p>

                <?php endif; ?>

            <?php endif; ?>

    </div><!-- .kcontainer -->
    
</div><!-- .ktestimonials -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>