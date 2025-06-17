<?php
/**
 * Block Name: Hero Section Block
 * Description: Custom block for displaying the Hero Section from ACF Flexible Content.
 * Category: ptre-blocks
 * Icon: star-filled
 * Keywords: hero, section, acf, ptre
 *
 * @package PTRE_Plugin
 */

function ptre_plugin_hero_section_block_render_callback() {
    ob_start(); // Start output buffering

    if (have_rows('hero-section', 11)):
        while (have_rows('hero-section', 11)): the_row();
            $title = get_sub_field('headline');
            $subhead = get_sub_field('subhead');
            $content = get_sub_field('content');
            $banner_image = get_field('banner_image', 11); // Assuming this is a top-level ACF field for the page
            $phone_number = get_field('phone', 'option'); // Fetch phone from theme settings
            $button_text = get_sub_field('button_text');
            $button_url  = get_sub_field('button_url');
        ?>
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12 text-left">
                        <?php if ($title): ?>
                            <h1><?php echo esc_html($title); ?></h1>
                        <?php endif; ?>

                        <?php if ($subhead): ?>
                            <h2><?php echo esc_html($subhead); ?></h2>
                        <?php endif; ?>

                        <?php if ($content): ?>
                            <p><?php echo wp_kses_post($content); ?></p>
                        <?php endif; ?>

                        <div class="cta-buttons">
                            <?php if ($phone_number): ?>
                                <a href="tel:<?php echo preg_replace('/\D/', '', $phone_number); ?>" class="btn-default">
                                    <i class="fa fa-phone"></i> <?php echo esc_html($phone_number); ?>
                                </a>
                            <?php endif; ?>
                            <?php if ($button_url && $button_text): ?>
                                <a href="<?php echo esc_url($button_url); ?>" class="btn-default btn-white">
                                    <?php echo esc_html($button_text); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 text-center">
                        <?php if ($banner_image): ?>
                            <img src="<?php echo esc_url($banner_image); ?>" alt="Hero Image" class="hero-image">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
        endwhile;
    endif;

    return ob_get_clean(); // Return the buffered content
}

// Register the block
add_action('acf/init', function() {
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type(array(
            'name'              => 'ptre-hero-section',
            'title'             => __('PTRE Hero Section', 'ptre-plugin'),
            'description'       => __('A custom block for the Hero Section on the homepage.', 'ptre-plugin'),
            'render_callback'   => 'ptre_plugin_hero_section_block_render_callback',
            'category'          => 'ptre-blocks',
            'icon'              => 'star-filled',
            'keywords'          => array('hero', 'section', 'ptre'),
            'supports'          => array(
                'align' => false,
                'mode' => false,
            ),
        ));
    }
});
?>