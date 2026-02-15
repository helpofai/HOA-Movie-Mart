<?php
/**
 * Enqueue scripts and styles
 */
function helpofai_scripts() {
    // Cache busting for development
    $version = time(); 

    // 1. Theme stylesheet (Metadata)
    wp_enqueue_style( 'helpofai-style', get_stylesheet_uri(), array(), $version );

    // 2. CSS Variables (Design System)
    wp_enqueue_style( 'helpofai-vars', get_template_directory_uri() . '/assets/css/variables.css', array(), $version );

    // 3. Background Effects
    wp_enqueue_style( 'helpofai-effects', get_template_directory_uri() . '/assets/css/effects.css', array('helpofai-vars'), $version );

    // 4. Components (Buttons, Cards, Tables)
    wp_enqueue_style( 'helpofai-components', get_template_directory_uri() . '/assets/css/components.css', array('helpofai-vars'), $version );

    // 5. Hero Slider (Always available for the homepage)
    wp_enqueue_style( 'swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css', array(), '11.0.0' );
    wp_enqueue_style( 'helpofai-hero-slider', get_template_directory_uri() . '/assets/css/hero-slider.css', array('swiper-css', 'helpofai-vars'), $version );

    // 6. Main Layout
    wp_enqueue_style( 'helpofai-main', get_template_directory_uri() . '/assets/css/main.css', array('helpofai-components'), $version );

    // 7. Footer Styles
    wp_enqueue_style( 'helpofai-footer', get_template_directory_uri() . '/assets/css/footer.css', array('helpofai-vars'), $version );

    // 8. Blog & Static Pages
    wp_enqueue_style( 'helpofai-blog-pages', get_template_directory_uri() . '/assets/css/blog-pages.css', array('helpofai-vars'), $version );

    // External Libraries
    wp_enqueue_style( 'helpofai-fonts', 'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;600;700&display=swap', array(), null );
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0' );

    // --- JavaScript ---
    wp_enqueue_script( 'swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), '11.0.0', true );
    wp_enqueue_script( 'helpofai-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array('jquery'), $version, true );
    wp_enqueue_script( 'helpofai-drag-scroll', get_template_directory_uri() . '/assets/js/drag-scroll.js', array('jquery'), $version, true );
    wp_enqueue_script( 'helpofai-slider', get_template_directory_uri() . '/assets/js/slider.js', array('swiper-js', 'jquery'), $version, true );
    wp_enqueue_script( 'helpofai-live-search', get_template_directory_uri() . '/assets/js/live-search.js', array('jquery'), $version, true );
    wp_enqueue_script( 'helpofai-grid-progress', get_template_directory_uri() . '/assets/js/grid-progress.js', array('jquery'), $version, true );
    wp_enqueue_script( 'helpofai-report', get_template_directory_uri() . '/assets/js/report.js', array('jquery'), $version, true );
    wp_enqueue_script( 'helpofai-trailer', get_template_directory_uri() . '/assets/js/trailer.js', array('jquery'), $version, true );

    // Verification & Tracking (Only on Single Movie)
    if ( is_singular( 'movie' ) ) {
        wp_enqueue_script( 'helpofai-verification', get_template_directory_uri() . '/assets/js/verification.js', array('jquery'), $version, true );
        wp_enqueue_script( 'helpofai-tracker', get_template_directory_uri() . '/assets/js/tracker.js', array('jquery'), $version, true );
        wp_enqueue_script( 'helpofai-tracker-ui', get_template_directory_uri() . '/assets/js/tracker-ui.js', array('jquery'), $version, true );
    }

    // Common Variables for JS
    wp_localize_script( 'helpofai-navigation', 'hoa_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'hoa_download_nonce' )
    ));
}
add_action( 'wp_enqueue_scripts', 'helpofai_scripts' );

/**
 * Output Header Scripts from Settings
 */
function helpofai_output_header_scripts() {
    $options = get_option( 'hoa_movie_mart_settings' );
    if ( ! empty( $options['header_scripts'] ) ) {
        echo $options['header_scripts'];
    }
}
add_action( 'wp_head', 'helpofai_output_header_scripts', 100 );
