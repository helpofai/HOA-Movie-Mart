<?php
/**
 * AJAX Live Search Handler
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function hoa_handle_live_search() {
    $term = isset( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : '';

    if ( strlen( $term ) < 3 ) {
        wp_send_json_error( 'Term too short' );
    }

    $args = array(
        'post_type'      => 'movie',
        'post_status'    => 'publish',
        's'              => $term,
        'posts_per_page' => 5
    );

    $query = new WP_Query( $args );
    $results = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            
            $rating = get_post_meta( get_the_ID(), '_movie_imdb_rating', true );
            $year   = strip_tags( get_the_term_list( get_the_ID(), 'movie_year', '', ', ', '' ) );
            $poster = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
            
            // Fallback for poster
            if ( ! $poster ) {
                $poster = get_post_meta( get_the_ID(), '_movie_poster_external', true );
            }
            if ( ! $poster ) {
                $poster = 'https://via.placeholder.com/90x135?text=No+Img';
            }

            $results[] = array(
                'title'     => get_the_title(),
                'permalink' => get_permalink(),
                'poster'    => $poster,
                'year'      => $year,
                'rating'    => $rating ? $rating : 'N/A'
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success( $results );
}
add_action( 'wp_ajax_hoa_live_search', 'hoa_handle_live_search' );
add_action( 'wp_ajax_nopriv_hoa_live_search', 'hoa_handle_live_search' );
