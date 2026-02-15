<?php
/**
 * Bot Protection & Human Verification (Ghost Protocol)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * AJAX Handler to Fetch Download Links after verification
 */
function hoa_get_secured_downloads() {
    // 1. Basic Security Check
    check_ajax_referer( 'hoa_download_nonce', 'security' );

    $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
    $token   = isset( $_POST['token'] ) ? sanitize_text_field( $_POST['token'] ) : '';

    if ( ! $post_id || ! $token ) {
        wp_send_json_error( 'Invalid request.' );
    }

    // 2. Cloudflare Turnstile Verification
    $options = get_option( 'hoa_movie_mart_settings' );
    $secret  = isset( $options['turnstile_secret_key'] ) ? $options['turnstile_secret_key'] : '';

    // Development Bypass: If on localhost, skip Cloudflare check
    $is_localhost = in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')) || stripos($_SERVER['SERVER_NAME'], 'localhost') !== false;

    if ( $secret && !$is_localhost ) {
        $response = wp_remote_post( 'https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
            'body' => array(
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        ) );

        if ( is_wp_error( $response ) ) {
            wp_send_json_error( 'Server Connection Error: ' . $response->get_error_message() );
        }

        $outcome = json_decode( wp_remote_retrieve_body( $response ) );

        if ( ! $outcome->success ) {
            $error_codes = isset($outcome->{'error-codes'}) ? implode(', ', $outcome->{'error-codes'}) : 'Unknown';
            wp_send_json_error( 'Verification failed. Reason: ' . $error_codes );
        }
    }

    // 3. Human Proven! Fetch and Render
    ob_start();
    
    $type = get_post_meta( $post_id, '_movie_type', true );
    
    if ( $type === 'tv' ) {
        // 1. Show "All-in-One / Full Pack" Table first if links exist
        $pack_links = get_post_meta( $post_id, '_movie_download_links_json', true );
        if ( ! empty( $pack_links ) ) {
            echo '<div class="full-pack-section" style="margin-bottom: 40px;">';
            echo '<div class="section-header" style="border-left-color: var(--secondary-gradient);"><h3 class="section-title">Full Series Download (All-in-One)</h3></div>';
            hoa_render_download_table_content( $post_id );
            echo '</div>';
        }

        // 2. Show Season/Episode List
        hoa_render_tv_seasons_content( $post_id );
    } else {
        // Standard Movie Table
        echo '<div class="section-header"><h3 class="section-title">Download Options</h3></div>';
        hoa_render_download_table_content( $post_id );
    }
    
    $html = ob_get_clean();

    wp_send_json_success( $html );
}
add_action( 'wp_ajax_hoa_get_downloads', 'hoa_get_secured_downloads' );
add_action( 'wp_ajax_nopriv_hoa_get_downloads', 'hoa_get_secured_downloads' );

/**
 * Render TV Seasons
 */
function hoa_render_tv_seasons_content( $post_id ) {
    $seasons_json = get_post_meta( $post_id, '_movie_seasons_json', true );
    $seasons = json_decode( $seasons_json, true );

    if ( empty( $seasons ) ) return;

    echo '<div class="tv-seasons-container animated-fade-in">';
    
    // Tabs
    echo '<div class="season-tabs scroll-container">';
    foreach ( $seasons as $index => $season ) {
        $active = $index === 0 ? 'active' : '';
        echo '<button class="season-tab ' . $active . '" data-target="season-' . $index . '">Season ' . esc_html( $season['number'] ) . '</button>';
    }
    echo '</div>';

    // Content
    echo '<div class="season-contents">';
    foreach ( $seasons as $index => $season ) {
        $active = $index === 0 ? 'active' : '';
        echo '<div class="season-pane ' . $active . '" id="season-' . $index . '">';
        
        // Zip
        if ( ! empty( $season['zip'] ) ) {
            echo '<a href="' . esc_url( $season['zip'] ) . '" class="season-zip-btn" target="_blank"><i class="fas fa-file-archive"></i> Download Season Pack (Zip)</a>';
        }

        // Episodes
        echo '<div class="episode-list">';
        foreach ( $season['episodes'] as $episode ) {
            $ep_id = $post_id . '-s' . $season['number'] . '-e' . $episode['number'];
            $img = !empty($episode['img']) ? $episode['img'] : 'https://via.placeholder.com/150x85?text=No+Img';
            
            echo '<div class="episode-item">';
            echo '<div class="ep-left">';
            echo '<div class="ep-check"><input type="checkbox" class="episode-watched-toggle" id="watched-' . $ep_id . '" data-id="' . $ep_id . '"><label for="watched-' . $ep_id . '"></label></div>';
            echo '<img src="' . esc_url($img) . '" class="ep-thumb">';
            echo '<div class="ep-info-text"><span class="ep-num">E' . esc_html($episode['number']) . '</span><span class="ep-title">' . esc_html($episode['title']) . '</span><span class="ep-date">' . esc_html($episode['date']) . '</span></div>';
            echo '</div>';
            
            if ( ! empty( $episode['link'] ) ) {
                echo '<div class="ep-right-actions" style="display:flex; gap:10px; align-items:center;">';
                echo '<a href="' . esc_url( $episode['link'] ) . '" class="ep-download-btn" target="_blank"><i class="fas fa-download"></i> <span class="hide-mobile">Download</span></a>';
                echo '<button class="btn-report-dead small" data-post-id="' . $post_id . '" data-link="S' . $season['number'] . 'E' . $episode['number'] . '" title="Report Broken"><i class="fas fa-flag"></i></button>';
                echo '</div>';
            } else {
                echo '<span class="ep-soon">Coming Soon</span>';
            }
            echo '</div>'; // .episode-item
        }
        echo '</div>'; // .episode-list
        echo '</div>'; // .season-pane
    }
    echo '</div></div>';
    
    // Re-initialize Tabs and Tracker JS (Since content is dynamic)
    echo '<script>
        jQuery(".season-tab").on("click", function() {
            var target = jQuery(this).data("target");
            jQuery(".season-tab").removeClass("active");
            jQuery(this).addClass("active");
            jQuery(".season-pane").removeClass("active");
            jQuery("#" + target).addClass("active");
        });
        
        // Re-init watched status
        jQuery(".episode-watched-toggle").each(function() {
            var id = jQuery(this).data("id");
            if (localStorage.getItem("hoa_watched_" + id)) {
                jQuery(this).prop("checked", true);
                jQuery(this).closest(".episode-item").addClass("watched");
            }
        });
        
        jQuery(".episode-watched-toggle").on("change", function() {
            var id = jQuery(this).data("id");
            var $row = jQuery(this).closest(".episode-item");
            if (jQuery(this).is(":checked")) {
                localStorage.setItem("hoa_watched_" + id, "true");
                $row.addClass("watched");
            } else {
                localStorage.removeItem("hoa_watched_" + id);
                $row.removeClass("watched");
            }
        });
    </script>';
}

/**
 * Pure Render Logic (No verification inside this helper)
 */
function hoa_render_download_table_content( $post_id ) {
    $download_links = get_post_meta( $post_id, '_movie_download_links_json', true );
    
    if ( empty( $download_links ) ) return;

    echo '<div class="download-table-wrapper animated-fade-in">';
    echo '<table class="download-table">';
    echo '<thead><tr><th>Quality</th><th>Server</th><th>Size</th><th>Action</th></tr></thead>';
    echo '<tbody>';

    foreach ( $download_links as $link ) {
        $quality = ! empty( $link['quality'] ) ? $link['quality'] : 'HD';
        $q_class = 'q-' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $quality));

                    echo '<tr>';

                    echo '<td><span class="quality-badge ' . esc_attr($q_class) . '">' . esc_html($quality) . '</span></td>';

                    echo '<td><i class="fas fa-server"></i> ' . esc_html($link['label']) . '</td>';

                    echo '<td>' . esc_html($link['size']) . '</td>';

                    echo '<td class="table-actions">';

                    echo '<a href="' . esc_url($link['url']) . '" class="table-btn-download">Download <i class="fas fa-arrow-down"></i></a>';

                    echo '<button class="btn-report-dead" data-post-id="' . $post_id . '" data-link="' . esc_attr($link['label'] . ' ' . $quality) . '" title="Report Broken Link"><i class="fas fa-flag"></i></button>';

                    echo '</td>';

                    echo '</tr>';

        
    }

    echo '</tbody></table></div>';
}
