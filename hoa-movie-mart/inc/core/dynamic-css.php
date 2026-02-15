<?php
/**
 * Generate Dynamic CSS based on Theme Settings
 */

function helpofai_dynamic_css() {
    $options = get_option( 'hoa_movie_mart_settings' );
    
    // Defaults
    $header_bg = isset( $options['header_bg_color'] ) && ! empty( $options['header_bg_color'] ) ? $options['header_bg_color'] : '';
    $header_link = isset( $options['header_link_color'] ) && ! empty( $options['header_link_color'] ) ? $options['header_link_color'] : '';
    $header_link_hover = isset( $options['header_link_hover_color'] ) && ! empty( $options['header_link_hover_color'] ) ? $options['header_link_hover_color'] : '';
    $header_padding = isset( $options['header_padding'] ) && ! empty( $options['header_padding'] ) ? $options['header_padding'] : '';
    $header_font_size = isset( $options['header_font_size'] ) && ! empty( $options['header_font_size'] ) ? $options['header_font_size'] : '';

    $css = ":root {";
    
    if ( $header_bg ) {
        // If a specific background color is set, we use it for the scrolled state
        $css .= ".site-header.scrolled { background: " . esc_attr( $header_bg ) . " !important; backdrop-filter: none; }";
    }
    
    // We don't have a variable for link color in variables.css for header specifically, 
    // so we will target the selector directly below.
    
    $css .= "}";

    // Header Specific Overrides
    if ( $header_link ) {
        $css .= "nav ul li a { color: " . esc_attr( $header_link ) . " !important; }";
    }
    if ( $header_link_hover ) {
        $css .= "nav ul li a:hover, nav ul li.current-menu-item a { color: " . esc_attr( $header_link_hover ) . " !important; }";
    }
    if ( $header_padding ) {
        $css .= "header { padding: " . esc_attr( $header_padding ) . " !important; }";
    }
    if ( $header_font_size ) {
        $css .= "nav ul li a { font-size: " . esc_attr( $header_font_size ) . " !important; }";
    }

    // Sticky Header Toggle
    if ( empty( $options['header_sticky'] ) ) {
        // If 'Enable Sticky Header' is NOT checked (assuming checkbox, but we used text 'Enable Sticky Header' placeholder)
        // Wait, the field was a text field in previous step. 
        // Let's assume if it contains 'no' or 'false' or is empty, we disable it. 
        // Ideally this should be a checkbox. For now let's apply if it says 'disable'.
        if ( isset( $options['header_sticky'] ) && stripos( $options['header_sticky'], 'disable' ) !== false ) {
             $css .= "header { position: relative !important; }";
        }
    }

    if ( ! empty( $css ) ) {
        echo '<style id="hoa-dynamic-css">' . $css . '</style>';
    }
}
add_action( 'wp_head', 'helpofai_dynamic_css', 100 );
