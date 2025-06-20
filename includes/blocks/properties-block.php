<?php
/**
 * Properties Block
 *
 * @package PTRE_Plugin
 * @subpackage PTRE_Plugin/includes/blocks
 */

/**
 * Render callback for the Properties Block.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int $post_id The post ID this block is saved to.
 */
function ptre_plugin_properties_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
    ob_start();

    // CRITICAL DEBUG: Add visible test output to verify block rendering
    echo '<div style="background: red; color: white; padding: 10px; margin: 10px; border: 2px solid black;">
        <h3>PROPERTIES BLOCK IS RENDERING!</h3>
        <p>If you see this, the block render callback is working.</p>
        <p>Block registered on acf/init hook - ACF should be ready!</p>
    </div>';

    // Since blocks are now registered on acf/init hook, ACF should be ready
    // Simple check for safety, but should not be needed
    if ( ! function_exists( 'acf_is_ready' ) || ! acf_is_ready() ) {
        error_log( 'PTRE Properties Block: ACF not ready. Aborting ACF data retrieval.' );
        echo '<div style="background: yellow; color: black; padding: 10px; margin: 10px;">
            <h3>ACF NOT READY</h3>
            <p>ACF functions are being called before ACF is fully initialized. No ACF data will be displayed.</p>
        </div>';
        // Do not return here, allow the rest of the block's HTML to render if it doesn't rely on ACF data
    } else {
        // Use Post ID 11 for the static homepage content
        $page_id = 11;

        error_log( 'PTRE Properties Block: Render callback executed. Page ID: ' . $page_id ); // Debugging

        if ( have_rows( 'properties', $page_id ) ) :
            error_log( 'PTRE Properties Block: have_rows returned TRUE.' ); // Debugging
            while ( have_rows( 'properties', $page_id ) ) : the_row();
                $title = get_sub_field( 'title' );
                $button_text = get_sub_field( 'button_text' );
                $button_url = get_sub_field( 'button_url' );

                error_log( 'PTRE Properties Block: Title: ' . ( $title ? $title : 'EMPTY' ) ); // Debugging
                ?>
                <section class="properties-section" data-aos="fade-up" data-aos-delay="200">
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
                                // Display properties using shortcode
                                if ( shortcode_exists( 'properties' ) ) {
                                    echo do_shortcode( '[properties limit="6" orderby="date" order="DESC"]' );
                                } else {
                                    echo '<p>Properties shortcode not available.</p>';
                                }
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
            error_log( 'PTRE Properties Block: have_rows returned FALSE. No properties content found for Post ID ' . $page_id ); // Debugging
            if ( $is_preview ) {
                echo '<p>Properties Block: No content found for Post ID ' . esc_html( $page_id ) . '.</p>';
            }
        endif;
    }

    return ob_get_clean();
}