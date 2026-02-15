<?php
/**
 * Register Dead Link Report Custom Post Type
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function hoa_register_report_cpt() {
    $labels = array(
        'name'                  => _x( 'Reports', 'Post Type General Name', 'helpofai' ),
        'singular_name'         => _x( 'Report', 'Post Type Singular Name', 'helpofai' ),
        'menu_name'             => __( 'Dead Link Reports', 'helpofai' ),
        'name_admin_bar'        => __( 'Report', 'helpofai' ),
        'archives'              => __( 'Report Archives', 'helpofai' ),
        'attributes'            => __( 'Report Attributes', 'helpofai' ),
        'parent_item_colon'     => __( 'Parent Report:', 'helpofai' ),
        'all_items'             => __( 'All Reports', 'helpofai' ),
        'add_new_item'          => __( 'Add New Report', 'helpofai' ),
        'add_new'               => __( 'Add New', 'helpofai' ),
        'new_item'              => __( 'New Report', 'helpofai' ),
        'edit_item'             => __( 'Edit Report', 'helpofai' ),
        'update_item'           => __( 'Update Report', 'helpofai' ),
        'view_item'             => __( 'View Report', 'helpofai' ),
        'view_items'            => __( 'View Reports', 'helpofai' ),
        'search_items'          => __( 'Search Report', 'helpofai' ),
        'not_found'             => __( 'Not found', 'helpofai' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'helpofai' ),
        'featured_image'        => __( 'Featured Image', 'helpofai' ),
        'set_featured_image'    => __( 'Set featured image', 'helpofai' ),
        'remove_featured_image' => __( 'Remove featured image', 'helpofai' ),
        'use_featured_image'    => __( 'Use as featured image', 'helpofai' ),
        'insert_into_item'      => __( 'Insert into report', 'helpofai' ),
        'uploaded_to_this_item' => __( 'Uploaded to this report', 'helpofai' ),
        'items_list'            => __( 'Reports list', 'helpofai' ),
        'items_list_navigation' => __( 'Reports list navigation', 'helpofai' ),
        'filter_items_list'     => __( 'Filter reports list', 'helpofai' ),
    );
    register_post_type( 'hoa_report', array(
        'label'               => __( 'Report', 'helpofai' ),
        'description'         => __( 'Dead Link Reports', 'helpofai' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor' ),
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 7,
        'menu_icon'           => 'dashicons-warning',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => false,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest'        => false,
    ));
}
add_action( 'init', 'hoa_register_report_cpt', 0 );
