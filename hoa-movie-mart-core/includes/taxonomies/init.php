<?php
/**
 * Register Taxonomies
 */
function helpofai_register_movie_taxonomies() {
    // Genres
    register_taxonomy( 'movie_genre', 'movie', array(
        'label'        => __( 'Genres', 'helpofai' ),
        'rewrite'      => array( 'slug' => 'genre' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );

    // Year
    register_taxonomy( 'movie_year', 'movie', array(
        'label'        => __( 'Years', 'helpofai' ),
        'rewrite'      => array( 'slug' => 'year' ),
        'hierarchical' => false,
        'show_in_rest' => true,
    ) );

    // Quality
    register_taxonomy( 'movie_quality', 'movie', array(
        'label'        => __( 'Quality', 'helpofai' ),
        'rewrite'      => array( 'slug' => 'quality' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );

    // Director
    register_taxonomy( 'movie_director', 'movie', array(
        'label'        => __( 'Directors', 'helpofai' ),
        'rewrite'      => array( 'slug' => 'director' ),
        'hierarchical' => false,
        'show_in_rest' => true,
    ) );

    // Cast
    register_taxonomy( 'movie_cast', 'movie', array(
        'label'        => __( 'Cast', 'helpofai' ),
        'rewrite'      => array( 'slug' => 'cast' ),
        'hierarchical' => false,
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'helpofai_register_movie_taxonomies' );
