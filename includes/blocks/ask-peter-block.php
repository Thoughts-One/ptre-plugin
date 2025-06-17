<?php
/**
 * Ask Peter Block
 *
 * @package PTRE_Plugin
 * @subpackage PTRE_Plugin/includes/blocks
 */

/**
 * Render callback for the Ask Peter Block.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int $post_id The post ID this block is saved to.
 */
function ptre_plugin_ask_peter_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
    ob_start();

    // Check if ACF is properly initialized before proceeding
    if ( ! function_exists( 'get_field' ) || ! function_exists( 'have_rows' ) || ! function_exists( 'get_sub_field' ) ) {
        error_log( 'PTRE Ask Peter Block: ACF functions not available, returning fallback content.' ); // Debugging
        if ( $is_preview ) {
            echo '<div class="ask-peter-placeholder"><p>Ask Peter Block: ACF not initialized</p></div>';
        }
        return ob_get_clean();
    }

    // Use Post ID 11 for the static homepage content
    $page_id = 11;

    error_log( 'PTRE Ask Peter Block: Render callback executed. Page ID: ' . $page_id ); // Debugging

    if ( have_rows( 'ask_peter', $page_id ) ) :
        error_log( 'PTRE Ask Peter Block: have_rows returned TRUE.' ); // Debugging
        while ( have_rows( 'ask_peter', $page_id ) ) : the_row();
            $title = get_sub_field( 'title' );
            $content_text = get_sub_field( 'content' );
            $button_text = get_sub_field( 'button_text' );
            $button_url = get_sub_field( 'button_url' );

            error_log( 'PTRE Ask Peter Block: Title: ' . ( $title ? $title : 'EMPTY' ) ); // Debugging
            ?>
            <section class="ask-peter" data-aos="fade-up" data-aos-delay="200">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-10 col-12 text-center">
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
                    </div>
                </div>
            </section>
            <?php
        endwhile;
    else :
        error_log( 'PTRE Ask Peter Block: have_rows returned FALSE. No ask peter content found for Post ID ' . $page_id ); // Debugging
        if ( $is_preview ) {
            echo '<p>Ask Peter Block: No content found for Post ID ' . esc_html( $page_id ) . '.</p>';
        }
    endif;

    return ob_get_clean();
}