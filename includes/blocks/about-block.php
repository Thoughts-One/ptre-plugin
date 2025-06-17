<?php
/**
 * About Block
 *
 * @package PTRE_Plugin
 * @subpackage PTRE_Plugin/includes/blocks
 */

/**
 * Render callback for the About Block.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int $post_id The post ID this block is saved to.
 */
function ptre_plugin_about_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
    ob_start();

    // Check if ACF is properly initialized before proceeding
    if ( ! function_exists( 'get_field' ) ) {
        error_log( 'PTRE About Block: ACF class not found or not initialized, returning fallback content.' ); // Debugging
        if ( $is_preview ) {
            echo '<div class="about-placeholder"><p>About Block: ACF not fully initialized</p></div>';
        }
        return ob_get_clean();
    }

    // Use Post ID 11 for the static homepage content
    $page_id = 11;

    error_log( 'PTRE About Block: Render callback executed. Page ID: ' . $page_id ); // Debugging

    if ( have_rows( 'about', $page_id ) ) :
        error_log( 'PTRE About Block: have_rows returned TRUE.' ); // Debugging
        while ( have_rows( 'about', $page_id ) ) : the_row();
            $image = get_sub_field( 'image' );
            $title = get_sub_field( 'title' );
            $content_text = get_sub_field( 'content' );
            $button_text = get_sub_field( 'button_text' );
            $button_url = get_sub_field( 'button_url' );

            error_log( 'PTRE About Block: Title: ' . ( $title ? $title : 'EMPTY' ) ); // Debugging
            ?>
            <section class="about-section" data-aos="fade-up" data-aos-delay="200">
                <div class="container">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-lg-5 col-md-5 col-12 order-2 order-lg-1 text-center text-lg-start">
                            <?php if ( $title ) : ?>
                                <h2><?php echo esc_html( $title ); ?></h2>
                            <?php endif; ?>
                            <?php if ( $content_text ) : ?>
                                <?php echo wp_kses_post( $content_text ); ?>
                            <?php endif; ?>
                            <?php if ( $button_text && $button_url ) : ?>
                                <a href="<?php echo esc_url( $button_url ); ?>" class="btn-default"><?php echo esc_html( $button_text ); ?></a>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 order-1 order-lg-2">
                            <?php if ( $image ) : ?>
                                <img src="<?php echo esc_url( $image ); ?>"/>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
            <?php
        endwhile;
    else :
        error_log( 'PTRE About Block: have_rows returned FALSE. No about content found for Post ID ' . $page_id ); // Debugging
        if ( $is_preview ) {
            echo '<p>About Block: No content found for Post ID ' . esc_html( $page_id ) . '.</p>';
        }
    endif;

    return ob_get_clean();
}