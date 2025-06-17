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

    // Check if ACF is properly initialized before proceeding
    if ( ! function_exists( 'get_field' ) || ! function_exists( 'have_rows' ) || ! function_exists( 'get_sub_field' ) ) {
        error_log( 'PTRE Hero Section Block: ACF functions not available, returning fallback content.' ); // Debugging
        if ( $is_preview ) {
            echo '<div class="hero-section-placeholder"><p>Hero Section Block: ACF not initialized</p></div>';
        }
        return ob_get_clean();
    }

    // Use Post ID 11 for the static homepage content
    $page_id = 11;

    error_log( 'PTRE Hero Section Block: Render callback executed. Page ID: ' . $page_id ); // Debugging
    error_log( 'PTRE Hero Section Block: Passed post_id parameter: ' . $post_id ); // Debugging
    error_log( 'PTRE Hero Section Block: Global $post object: ' . print_r( $GLOBALS['post'], true ) ); // Debugging
    error_log( 'PTRE Hero Section Block: ACF function exists check - acf_get_value: ' . ( function_exists( 'acf_get_value' ) ? 'YES' : 'NO' ) ); // Debugging
    error_log( 'PTRE Hero Section Block: ACF function exists check - get_field: ' . ( function_exists( 'get_field' ) ? 'YES' : 'NO' ) ); // Debugging
    error_log( 'PTRE Hero Section Block: ACF function exists check - have_rows: ' . ( function_exists( 'have_rows' ) ? 'YES' : 'NO' ) ); // Debugging

    error_log( 'PTRE Hero Section Block: About to call have_rows() with page_id: ' . $page_id ); // Debugging
    if ( have_rows( 'hero-section', $page_id ) ) :
        error_log( 'PTRE Hero Section Block: have_rows() call completed successfully.' ); // Debugging
        error_log( 'PTRE Hero Section Block: have_rows returned TRUE.' ); // Debugging
        while ( have_rows( 'hero-section', $page_id ) ) :
            error_log( 'PTRE Hero Section Block: About to call the_row()' ); // Debugging
            the_row();
            error_log( 'PTRE Hero Section Block: the_row() completed, now getting sub fields' ); // Debugging
            
            error_log( 'PTRE Hero Section Block: About to call get_sub_field(headline)' ); // Debugging
            $headline    = get_sub_field( 'headline' );
            error_log( 'PTRE Hero Section Block: About to call get_sub_field(subhead)' ); // Debugging
            $subhead     = get_sub_field( 'subhead' );
            error_log( 'PTRE Hero Section Block: About to call get_sub_field(content)' ); // Debugging
            $content_text = get_sub_field( 'content' );
            error_log( 'PTRE Hero Section Block: About to call get_sub_field(button_text)' ); // Debugging
            $button_text = get_sub_field( 'button_text' );
            error_log( 'PTRE Hero Section Block: About to call get_sub_field(button_url)' ); // Debugging
            $button_url  = get_sub_field( 'button_url' );
            error_log( 'PTRE Hero Section Block: All sub fields retrieved successfully' ); // Debugging

            error_log( 'PTRE Hero Section Block: Headline: ' . ( $headline ? $headline : 'EMPTY' ) ); // Debugging
            error_log( 'PTRE Hero Section Block: Subhead: ' . ( $subhead ? $subhead : 'EMPTY' ) ); // Debugging
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
                error_log( 'PTRE Hero Section Block: About to call get_field(banner_image) with page_id: ' . $page_id ); // Debugging
                $banner_image = get_field( 'banner_image', $page_id );
                error_log( 'PTRE Hero Section Block: get_field(banner_image) completed' ); // Debugging
                error_log( 'PTRE Hero Section Block: Banner image type: ' . gettype( $banner_image ) ); // Debugging
                error_log( 'PTRE Hero Section Block: Banner image value: ' . print_r( $banner_image, true ) ); // Debugging
                
                if ( $banner_image ) :
                    // Handle both array (ACF image object) and string (URL) formats
                    $image_url = '';
                    if ( is_array( $banner_image ) && isset( $banner_image['url'] ) ) {
                        $image_url = $banner_image['url'];
                        error_log( 'PTRE Hero Section Block: Banner Image URL (from array): ' . $image_url ); // Debugging
                    } elseif ( is_string( $banner_image ) ) {
                        $image_url = $banner_image;
                        error_log( 'PTRE Hero Section Block: Banner Image URL (from string): ' . $image_url ); // Debugging
                    }
                    
                    if ( $image_url ) : ?>
                        <div class="hero-image" style="background-image: url('<?php echo esc_url( $image_url ); ?>');"></div>
                    <?php endif;
                endif; ?>
            </section>
            <?php
        endwhile;
    else :
        error_log( 'PTRE Hero Section Block: have_rows returned FALSE. No hero section content found for Post ID ' . $page_id ); // Debugging
        error_log( 'PTRE Hero Section Block: Attempting alternative - using get_the_ID() instead of hardcoded page_id' ); // Debugging
        $current_post_id = get_the_ID();
        error_log( 'PTRE Hero Section Block: get_the_ID() returned: ' . $current_post_id ); // Debugging
        
        // Try with current post ID as fallback
        if ( $current_post_id && have_rows( 'hero-section', $current_post_id ) ) :
            error_log( 'PTRE Hero Section Block: have_rows with get_the_ID() returned TRUE' ); // Debugging
            while ( have_rows( 'hero-section', $current_post_id ) ) : the_row();
                $headline = get_sub_field( 'headline' );
                error_log( 'PTRE Hero Section Block: Fallback - Retrieved headline: ' . ( $headline ? $headline : 'EMPTY' ) ); // Debugging
                ?>
                <section class="hero-section fallback">
                    <div class="container">
                        <div class="hero-content">
                            <h1><?php echo esc_html( $headline ? $headline : 'Fallback Hero Section' ); ?></h1>
                            <p>Using current post ID: <?php echo esc_html( $current_post_id ); ?></p>
                        </div>
                    </div>
                </section>
                <?php
            endwhile;
        else :
            error_log( 'PTRE Hero Section Block: Both hardcoded page_id and get_the_ID() failed to find hero section content' ); // Debugging
            // Fallback for preview or if no rows are found
            if ( $is_preview ) {
                echo '<p>Hero Section Block: No content found for Post ID ' . esc_html( $page_id ) . '. Current Post ID: ' . esc_html( $current_post_id ) . '</p>';
            }
        endif;
    endif;

    return ob_get_clean();
}