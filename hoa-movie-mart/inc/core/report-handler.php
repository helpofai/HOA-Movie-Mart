<?php
/**
 * Report Broken Link System - AJAX Handlers
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// 2. AJAX Submission Handler
function hoa_submit_broken_link_report() {
    check_ajax_referer( 'hoa_download_nonce', 'security' );

    $post_id   = absint( $_POST['post_id'] );
    $link_info = sanitize_text_field( $_POST['link_info'] ); // e.g., "720p - Server 1"
    $message   = sanitize_textarea_field( $_POST['message'] );

    $movie_title = get_the_title( $post_id );

    $report_id = wp_insert_post( array(
        'post_title'   => 'Dead Link: ' . $movie_title . ' (' . $link_info . ')',
        'post_content' => "User Message: $message

Movie ID: $post_id
Link Context: $link_info",
        'post_type'    => 'hoa_report',
        'post_status'  => 'publish'
    ));

    if ( $report_id ) {
        wp_send_json_success( 'Thank you! Our team will fix this link ASAP.' );
    } else {
        wp_send_json_error( 'Failed to send report. Try again later.' );
    }
}
add_action( 'wp_ajax_hoa_submit_report', 'hoa_submit_broken_link_report' );
add_action( 'wp_ajax_nopriv_hoa_submit_report', 'hoa_submit_broken_link_report' );
