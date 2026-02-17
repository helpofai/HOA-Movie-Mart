<?php
/**
 * Bridge & Dynamic Tunnel Handler
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Generate a secure bridge URL for a given movie link
 */
function hoa_get_bridge_url( $original_url, $post_id = 0 ) {
    // Generate a unique key
    $key = bin2hex( random_bytes( 16 ) );
    
    // If post_id not provided, try to get it
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    // Store the real URL and Post ID in a transient
    $data = array(
        'url'     => $original_url,
        'post_id' => $post_id
    );
    
    set_transient( 'hoa_bridge_' . $key, $data, 30 * MINUTE_IN_SECONDS );
    
    // Find the bridge page URL
    $bridge_page = get_pages( array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-bridge.php'
    ) );

    if ( ! empty( $bridge_page ) ) {
        $base_url = get_permalink( $bridge_page[0]->ID );
        return add_query_arg( 'key', $key, $base_url );
    }

    // Fallback if page not created
    return $original_url;
}

/**
 * Handle the final redirection from the bridge
 */
function hoa_handle_bridge_final_redirect() {
    if ( ! isset( $_POST['bridge_key'] ) ) {
        wp_die( 'Invalid link.' );
    }

    $key = sanitize_text_field( $_POST['bridge_key'] );
    $data = get_transient( 'hoa_bridge_' . $key );

    if ( ! $data || ! isset($data['url']) ) {
        wp_die( 'This link has expired. Please go back and try again.' );
    }

    $real_url = $data['url'];

    // Delete the transient so the link can only be used once (One-Time Token)
    delete_transient( 'hoa_bridge_' . $key );

    // Perform a header-level redirect
    // We use a "Referrer-Policy" to hide where the traffic is coming from
    header( "Referrer-Policy: no-referrer" );
    header( "Location: " . $real_url );
    exit;
}
add_action( 'admin_post_hoa_final_redirect', 'hoa_handle_bridge_final_redirect' );
add_action( 'admin_post_nopriv_hoa_final_redirect', 'hoa_handle_bridge_final_redirect' );
