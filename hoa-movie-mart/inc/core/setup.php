<?php
/**
 * Theme Setup
 */
if ( ! function_exists( 'helpofai_setup' ) ) :
    function helpofai_setup() {
        // Add theme support
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );

        // Register Menus
        register_nav_menus( array(
            'primary' => esc_html__( 'Primary Menu', 'helpofai' ),
            'mobile'  => esc_html__( 'Mobile Menu', 'helpofai' ),
            'footer_explore' => esc_html__( 'Footer Explore', 'helpofai' ),
            'footer_genres'  => esc_html__( 'Footer Genres', 'helpofai' ),
            'footer_legal'   => esc_html__( 'Footer Legal & Help', 'helpofai' ),
        ) );

        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );
    }
endif;
add_action( 'after_setup_theme', 'helpofai_setup' );

/**
 * Register widget area.
 */
function helpofai_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'helpofai' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'helpofai' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'helpofai_widgets_init' );
