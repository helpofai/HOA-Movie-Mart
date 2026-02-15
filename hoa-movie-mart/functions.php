<?php
/**
 * HOA Movie Mart Functions and Definitions
 */

// Define Constants
define( 'HOA_THEME_DIR', get_template_directory() );
define( 'HOA_THEME_URI', get_template_directory_uri() );

/**
 * Core Setup
 */
require HOA_THEME_DIR . '/inc/core/setup.php';

/**
 * Enqueue Scripts
 */
require HOA_THEME_DIR . '/inc/core/enqueue.php';

/**
 * Template Tags
 */
require HOA_THEME_DIR . '/inc/core/template-tags.php';

/**
 * Bot Protection
 */
require HOA_THEME_DIR . '/inc/core/bot-protection.php';

/**
 * Stats Tracker
 */
require HOA_THEME_DIR . '/inc/core/stats-tracker.php';

/**
 * Request Handler
 */
require HOA_THEME_DIR . '/inc/core/request-handler.php';

/**
 * Dead Link Report Handler
 */
require HOA_THEME_DIR . '/inc/core/report-handler.php';

/**
 * SEO Schema
 */
require HOA_THEME_DIR . '/inc/core/seo-schema.php';

/**
 * Documentation Page
 */
require HOA_THEME_DIR . '/inc/admin/documentation.php';

/**
 * Dynamic CSS (Customizer/Settings)
 */
require HOA_THEME_DIR . '/inc/core/dynamic-css.php';

/**
 * Admin Settings
 */
require HOA_THEME_DIR . '/inc/admin/theme-settings.php';

/**
 * Advanced Publisher
 */
require HOA_THEME_DIR . '/inc/admin/advanced-publisher.php';

/**
 * Modify Main Query (Keep this here or move to a separate file if it grows)
 */
function helpofai_pre_get_posts( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( is_home() || is_archive() || is_search() ) {
        $query->set( 'post_type', array( 'movie', 'post' ) );
        
        // Handle Quick Sorting
        if ( isset( $_GET['orderby'] ) ) {
            $orderby = sanitize_text_field( $_GET['orderby'] );
            if ( $orderby === 'meta_value_num' ) {
                $query->set( 'meta_key', '_movie_imdb_rating' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
            } elseif ( $orderby === 'comment_count' ) {
                $query->set( 'orderby', 'comment_count' );
                $query->set( 'order', 'DESC' );
            } elseif ( $orderby === 'popular' ) {
                $query->set( 'meta_key', '_hoa_views_count' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
            }
        }
    }
}
add_action( 'pre_get_posts', 'helpofai_pre_get_posts' );

/**
 * Save Comment Rating
 */
function hoa_save_comment_rating( $comment_id ) {
    if ( isset( $_POST['rating'] ) && ! empty( $_POST['rating'] ) ) {
        $rating = absint( $_POST['rating'] );
        add_comment_meta( $comment_id, 'rating', $rating );
    }
}
add_action( 'comment_post', 'hoa_save_comment_rating' );