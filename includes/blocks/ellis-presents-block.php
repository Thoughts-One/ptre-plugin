<?php
/**
 * Ellis Presents Block
 *
 * @package PTRE_Plugin
 * @subpackage PTRE_Plugin/includes/blocks
 */

/**
 * Render callback for the Ellis Presents Block.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int $post_id The post ID this block is saved to.
 */
function ptre_plugin_ellis_presents_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
    ob_start();

    // Check if ACF is properly initialized before proceeding
    if ( ! function_exists( 'get_field' ) ) {
        error_log( 'PTRE Ellis Presents Block: ACF class not found or not initialized, returning fallback content.' ); // Debugging
        if ( $is_preview ) {
            echo '<div class="ellis-presents-placeholder"><p>Ellis Presents Block: ACF not fully initialized</p></div>';
        }
        return ob_get_clean();
    }

    // Use Post ID 11 for the static homepage content
    $page_id = 11;

    error_log( 'PTRE Ellis Presents Block: Render callback executed. Page ID: ' . $page_id ); // Debugging

    if ( have_rows( 'ellis_presents', $page_id ) ) :
        error_log( 'PTRE Ellis Presents Block: have_rows returned TRUE.' ); // Debugging
        while ( have_rows( 'ellis_presents', $page_id ) ) : the_row();
            $title = get_sub_field( 'title' );
            $button_text = get_sub_field( 'button_text' );
            $button_url = get_sub_field( 'button_url' );

            error_log( 'PTRE Ellis Presents Block: Title: ' . ( $title ? $title : 'EMPTY' ) ); // Debugging
            ?>
            <section class="ellis-presents-section" data-aos="fade-up" data-aos-delay="200">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <?php if ( $title ) : ?>
                                <h2><?php echo esc_html( $title ); ?></h2>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?php
                            // Display Ellis Presents posts
                            $ellis_posts = get_posts( array(
                                'post_type' => 'ellis-presents',
                                'posts_per_page' => 3,
                                'post_status' => 'publish',
                                'orderby' => 'date',
                                'order' => 'DESC'
                            ) );
                            
                            if ( $ellis_posts ) :
                                echo '<div class="ellis-presents-grid">';
                                foreach ( $ellis_posts as $post ) :
                                    setup_postdata( $post );
                                    $featured_image = get_the_post_thumbnail_url( $post->ID, 'medium' );
                                    ?>
                                    <div class="ellis-presents-item">
                                        <?php if ( $featured_image ) : ?>
                                            <img src="<?php echo esc_url( $featured_image ); ?>" alt="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>">
                                        <?php endif; ?>
                                        <h3><a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"><?php echo esc_html( get_the_title( $post->ID ) ); ?></a></h3>
                                        <div class="excerpt"><?php echo wp_kses_post( get_the_excerpt( $post->ID ) ); ?></div>
                                    </div>
                                    <?php
                                endforeach;
                                echo '</div>';
                                wp_reset_postdata();
                            else :
                                echo '<p>No Ellis Presents posts found.</p>';
                            endif;
                            ?>
                        </div>
                    </div>
                    <?php if ( $button_text && $button_url ) : ?>
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="<?php echo esc_url( $button_url ); ?>" class="btn-default"><?php echo esc_html( $button_text ); ?></a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php
        endwhile;
    else :
        error_log( 'PTRE Ellis Presents Block: have_rows returned FALSE. No ellis presents content found for Post ID ' . $page_id ); // Debugging
        if ( $is_preview ) {
            echo '<p>Ellis Presents Block: No content found for Post ID ' . esc_html( $page_id ) . '.</p>';
        }
    endif;

    return ob_get_clean();
}