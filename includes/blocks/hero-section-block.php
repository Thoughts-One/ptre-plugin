<?php
/**
 * Hero Section Block
 *
 * @package PTRE_Plugin
 * @subpackage PTRE_Plugin/includes/blocks
 */

/**
 * Render callback for the Hero Section Block.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int $post_id The post ID this block is saved to.
 */
function ptre_plugin_hero_section_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
    ob_start();

    // Use Post ID 11 for the static homepage content
    $page_id = 11;

    if ( have_rows( 'hero-section', $page_id ) ) :
        while ( have_rows( 'hero-section', $page_id ) ) : the_row();
            $headline    = get_sub_field( 'headline' );
            $subhead     = get_sub_field( 'subhead' );
            $content_text = get_sub_field( 'content' );
            $button_text = get_sub_field( 'button_text' );
            $button_url  = get_sub_field( 'button_url' );
            ?>
            <section class="hero-section">
                <div class="container">
                    <div class="hero-content">
                        <?php if ( $headline ) : ?>
                            <h1><?php echo esc_html( $headline ); ?></h1>
                        <?php endif; ?>
                        <?php if ( $subhead ) : ?>
                            <h2><?php echo esc_html( $subhead ); ?></h2>
                        <?php endif; ?>
                        <?php if ( $content_text ) : ?>
                            <p><?php echo esc_html( $content_text ); ?></p>
                        <?php endif; ?>
                        <?php if ( $button_text && $button_url ) : ?>
                            <a href="<?php echo esc_url( $button_url ); ?>" class="button"><?php echo esc_html( $button_text ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                $banner_image = get_field( 'banner_image', $page_id );
                if ( $banner_image ) :
                    ?>
                    <div class="hero-image" style="background-image: url('<?php echo esc_url( $banner_image['url'] ); ?>');"></div>
                <?php endif; ?>
            </section>
            <?php
        endwhile;
    else :
        // Fallback for preview or if no rows are found
        if ( $is_preview ) {
            echo '<p>Hero Section Block: No content found for Post ID ' . esc_html( $page_id ) . '.</p>';
        }
    endif;

    return ob_get_clean();
}