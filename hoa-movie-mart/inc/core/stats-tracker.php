<?php
/**
 * Stats Tracker: Post Views & Download Counter
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Update Post Views via AJAX
 */
function hoa_track_post_view() {
    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    if ($post_id) {
        $views = get_post_meta($post_id, '_hoa_views_count', true);
        $views = $views ? (int)$views + 1 : 1;
        update_post_meta($post_id, '_hoa_views_count', $views);
        wp_send_json_success($views);
    }
    wp_die();
}
add_action('wp_ajax_hoa_track_view', 'hoa_track_post_view');
add_action('wp_ajax_nopriv_hoa_track_view', 'hoa_track_post_view');

/**
 * Update Download Count via AJAX
 */
function hoa_track_download() {
    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    if ($post_id) {
        $downloads = get_post_meta($post_id, '_hoa_download_count', true);
        $downloads = $downloads ? (int)$downloads + 1 : 1;
        update_post_meta($post_id, '_hoa_download_count', $downloads);
        wp_send_json_success($downloads);
    }
    wp_die();
}
add_action('wp_ajax_hoa_track_download', 'hoa_track_download');
add_action('wp_ajax_nopriv_hoa_track_download', 'hoa_track_download');

/**
 * Helper to get counts
 */
function hoa_get_views($post_id) {
    $count = get_post_meta($post_id, '_hoa_views_count', true);
    return $count ? number_format_i18n($count) : '0';
}

function hoa_get_downloads($post_id) {
    $count = get_post_meta($post_id, '_hoa_download_count', true);
    return $count ? number_format_i18n($count) : '0';
}
