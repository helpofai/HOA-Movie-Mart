<?php
/**
 * Request System Handler
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. Register Request CPT
function hoa_register_request_cpt() {
    register_post_type( 'request', array(
        'labels' => array(
            'name' => 'Requests',
            'singular_name' => 'Request'
        ),
        'public' => false,
        'show_ui' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-format-status',
        'supports' => array( 'title', 'editor', 'custom-fields' )
    ));
}
add_action( 'init', 'hoa_register_request_cpt' );

// 2. Handle Form Submission
function hoa_handle_request_submission() {
    if ( ! isset( $_POST['hoa_request_nonce'] ) || ! wp_verify_nonce( $_POST['hoa_request_nonce'], 'hoa_submit_request' ) ) {
        return;
    }

    $title = sanitize_text_field( $_POST['req_title'] );
    $type  = sanitize_text_field( $_POST['req_type'] );
    $year  = sanitize_text_field( $_POST['req_year'] );
    $quality = sanitize_text_field( $_POST['req_quality'] );

    if ( empty( $title ) ) {
        wp_die( 'Please enter a title.' );
    }

    $post_id = wp_insert_post( array(
        'post_title'  => $title,
        'post_type'   => 'request',
        'post_status' => 'pending', // Save as pending so admin sees it
        'meta_input'  => array(
            '_req_type'    => $type,
            '_req_year'    => $year,
            '_req_quality' => $quality,
            '_req_ip'      => $_SERVER['REMOTE_ADDR']
        )
    ));

    if ( $post_id ) {
        wp_redirect( add_query_arg( 'request_sent', '1', get_permalink( $_POST['page_id'] ) ) );
        exit;
    }
}
add_action( 'admin_post_hoa_submit_request', 'hoa_handle_request_submission' );
add_action( 'admin_post_nopriv_hoa_submit_request', 'hoa_handle_request_submission' );

// 3. Add Columns to Admin List
function hoa_request_columns($columns) {
    $columns['req_type'] = 'Type';
    $columns['req_quality'] = 'Quality';
    $columns['req_status'] = 'Status';
    return $columns;
}
add_filter('manage_request_posts_columns', 'hoa_request_columns');

function hoa_request_custom_column($column, $post_id) {
    if ($column === 'req_type') echo get_post_meta($post_id, '_req_type', true);
    if ($column === 'req_quality') echo get_post_meta($post_id, '_req_quality', true);
    if ($column === 'req_status') echo get_post_status($post_id);
}
add_action('manage_request_posts_custom_column', 'hoa_request_custom_column', 10, 2);

/**
 * Shortcode: [movie_request_form]
 */
function hoa_request_form_shortcode() {
    ob_start();
    if ( isset( $_GET['request_sent'] ) ) : ?>
        <div class="request-success-message">
            <i class="fas fa-check-circle" style="color:#46b450; font-size:4rem; margin-bottom:20px;"></i>
            <h3>Request Received!</h3>
            <p>We have added your request to our queue. Check back soon!</p>
            <a href="<?php echo esc_url( remove_query_arg('request_sent') ); ?>" class="btn-download">Submit Another</a>
        </div>
    <?php else : ?>
        <form action="<?php echo admin_url('admin-post.php'); ?>" method="post" class="request-form">
            <input type="hidden" name="action" value="hoa_submit_request">
            <input type="hidden" name="page_id" value="<?php echo get_the_ID(); ?>">
            <?php wp_nonce_field( 'hoa_submit_request', 'hoa_request_nonce' ); ?>

            <div class="req-grid">
                <div class="req-group">
                    <label>Title *</label>
                    <input type="text" name="req_title" placeholder="e.g. Avengers: Endgame" required>
                </div>
                <div class="req-group">
                    <label>Type</label>
                    <select name="req_type">
                        <option value="Movie">Movie</option>
                        <option value="TV Series">TV Series</option>
                        <option value="Anime">Anime</option>
                    </select>
                </div>
                <div class="req-group">
                    <label>Year (Optional)</label>
                    <input type="text" name="req_year" placeholder="e.g. 2019">
                </div>
                <div class="req-group">
                    <label>Preferred Quality</label>
                    <select name="req_quality">
                        <option value="Any">Any Quality</option>
                        <option value="1080p">1080p Full HD</option>
                        <option value="4K">4K Ultra HD</option>
                        <option value="720p">720p HD</option>
                    </select>
                </div>
            </div>
            <div class="req-actions">
                <button type="submit" class="btn-download submit-req-btn">Submit Request <i class="fas fa-paper-plane"></i></button>
            </div>
        </form>
    <?php endif;
    return ob_get_clean();
}
add_shortcode( 'movie_request_form', 'hoa_request_form_shortcode' );
