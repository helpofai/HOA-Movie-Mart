<?php
/**
 * Register Movie Custom Post Type
 */
function helpofai_register_movie_cpt() {
    $labels = array(
        'name'                  => _x( 'Movies', 'Post type general name', 'helpofai' ),
        'singular_name'         => _x( 'Movie', 'Post type singular name', 'helpofai' ),
        'menu_name'             => _x( 'Movies', 'Admin Menu text', 'helpofai' ),
        'name_admin_bar'        => _x( 'Movie', 'Add New on Toolbar', 'helpofai' ),
        'add_new'               => __( 'Add New', 'helpofai' ),
        'add_new_item'          => __( 'Add New Movie', 'helpofai' ),
        'new_item'              => __( 'New Movie', 'helpofai' ),
        'edit_item'             => __( 'Edit Movie', 'helpofai' ),
        'view_item'             => __( 'View Movie', 'helpofai' ),
        'all_items'             => __( 'All Movies', 'helpofai' ),
        'search_items'          => __( 'Search Movies', 'helpofai' ),
        'parent_item_colon'     => __( 'Parent Movies:', 'helpofai' ),
        'not_found'             => __( 'No movies found.', 'helpofai' ),
        'not_found_in_trash'    => __( 'No movies found in Trash.', 'helpofai' ),
        'featured_image'        => _x( 'Movie Poster', 'Overrides the “Featured Image” phrase', 'helpofai' ),
        'set_featured_image'    => _x( 'Set movie poster', 'Overrides the “Set featured image” phrase', 'helpofai' ),
        'remove_featured_image' => _x( 'Remove movie poster', 'Overrides the “Remove featured image” phrase', 'helpofai' ),
        'use_featured_image'    => _x( 'Use as movie poster', 'Overrides the “Use as featured image” phrase', 'helpofai' ),
        'archives'              => _x( 'Movie archives', 'The post type archive label used in nav menus', 'helpofai' ),
        'insert_into_item'      => _x( 'Insert into movie', 'Overrides the “Insert into post”/”Insert into page” phrase', 'helpofai' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this movie', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase', 'helpofai' ),
        'filter_items_list'     => _x( 'Filter movies list', 'Screen reader text for the filter links', 'helpofai' ),
        'items_list_navigation' => _x( 'Movies list navigation', 'Screen reader text for the pagination', 'helpofai' ),
        'items_list'            => _x( 'Movies list', 'Screen reader text for the items list', 'helpofai' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'movie' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'           => 'dashicons-video-alt2',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'movie', $args );
}
add_action( 'init', 'helpofai_register_movie_cpt' );
