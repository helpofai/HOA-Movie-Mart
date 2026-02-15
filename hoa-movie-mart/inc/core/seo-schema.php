<?php
/**
 * SEO Schema: JSON-LD for Movies and TV Series
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function hoa_output_movie_schema() {
    if ( ! is_singular( 'movie' ) ) return;

    global $post;
    $post_id = $post->ID;

    $title    = get_the_title();
    $plot     = get_the_excerpt();
    $rating   = get_post_meta( $post_id, '_movie_imdb_rating', true );
    $release  = get_post_meta( $post_id, '_movie_release_date', true );
    $type     = get_post_meta( $post_id, '_movie_type', true );
    $poster   = get_the_post_thumbnail_url( $post_id, 'large' ) ?: get_post_meta( $post_id, '_movie_poster_external', true );
    $genres   = wp_get_post_terms( $post_id, 'movie_genre', array( 'fields' => 'names' ) );

    $schema = array(
        "@context" => "https://schema.org",
        "@type"    => ( $type === 'tv' ) ? "TVSeries" : "Movie",
        "name"     => $title,
        "description" => $plot,
        "image"    => $poster,
        "genre"    => $genres,
    );

    if ( $release ) {
        $schema["datePublished"] = $release;
    }

    if ( $rating ) {
        $schema["aggregateRating"] = array(
            "@type"       => "AggregateRating",
            "ratingValue" => $rating,
            "bestRating"  => "10",
            "worstRating" => "1",
            "ratingCount" => "100" // Fallback count
        );
    }

    echo '<script type="application/ld+json">' . json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>';
}
add_action( 'wp_head', 'hoa_output_movie_schema' );
