<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php 
    $options = get_option( 'hoa_movie_mart_settings' );
    if ( ! empty( $options['favicon_url'] ) ) : ?>
        <link rel="icon" href="<?php echo esc_url( $options['favicon_url'] ); ?>" type="image/x-icon">
    <?php endif; ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    
    <!-- Ambient Background -->
    <div class="hoa-background">
        <div class="hoa-blob hoa-blob-1"></div>
        <div class="hoa-blob hoa-blob-2"></div>
        <div class="hoa-blob hoa-blob-3"></div>
    </div>

    <header class="site-header">
        <div class="header-container">
            <div class="header-left">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
                <div class="logo">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <?php 
                        $options = get_option( 'hoa_movie_mart_settings' );
                        if ( ! empty( $options['logo_url'] ) ) : ?>
                            <img src="<?php echo esc_url( $options['logo_url'] ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="main-logo">
                        <?php else : ?>
                            <span class="logo-text"><?php bloginfo( 'name' ); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <nav id="site-navigation" class="main-navigation">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => 'div',
                    'container_class' => 'menu-wrapper',
                    'fallback_cb'    => false,
                ) );
                ?>
            </nav>

            <div class="header-right">
                <button class="search-trigger" aria-label="Open Search">
                    <i class="fas fa-search"></i>
                </button>
                <div class="header-actions-desktop">
                    <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( get_edit_user_link() ); ?>" class="header-btn profile-btn"><i class="fas fa-user-circle"></i></a>
                    <?php else : ?>
                        <a href="<?php echo esc_url( wp_login_url() ); ?>" class="header-btn login-btn">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Advanced Full-Screen Search Overlay -->
        <div class="hoa-search-overlay">
            <div class="search-overlay-close">&times;</div>
            <div class="search-overlay-content">
                <form role="search" method="get" class="premium-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="search" class="search-field" placeholder="Search for movies, TV shows..." value="<?php echo get_search_query(); ?>" name="s" autocomplete="off">
                        <button type="submit" class="search-submit-btn">Search</button>
                    </div>
                </form>
                <div class="search-results-dropdown"></div>
                <div class="search-trending">
                    <h4>Trending Searches</h4>
                    <div class="trending-tags">
                        <?php
                        $trending_genres = get_terms( array('taxonomy' => 'movie_genre', 'number' => 5, 'hide_empty' => true) );
                        if ( ! is_wp_error( $trending_genres ) ) :
                            foreach ( $trending_genres as $genre ) : ?>
                                <a href="<?php echo esc_url( get_term_link( $genre ) ); ?>"><?php echo esc_html( $genre->name ); ?></a>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Drawer Overlay -->
    <div class="mobile-menu-drawer">
        <div class="drawer-header">
            <div class="drawer-logo">
                <?php if ( ! empty( $options['logo_url'] ) ) : ?>
                    <img src="<?php echo esc_url( $options['logo_url'] ); ?>" alt="<?php bloginfo( 'name' ); ?>">
                <?php else : ?>
                    <span><?php bloginfo( 'name' ); ?></span>
                <?php endif; ?>
            </div>
            <div class="drawer-close">&times;</div>
        </div>
        <div class="drawer-search">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" placeholder="Quick Search..." name="s">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="drawer-menu-container">
            <?php
            if ( has_nav_menu( 'mobile' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu-list',
                    'container'      => false,
                    'menu_class'     => 'mobile-nav-list',
                    'fallback_cb'    => false,
                ) );
            } else {
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-menu-fallback',
                    'container'      => false,
                    'menu_class'     => 'mobile-nav-list',
                    'fallback_cb'    => false,
                ) );
            }
            ?>
        </div>
        <div class="drawer-footer">
            <div class="drawer-socials">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-telegram"></i></a>
            </div>
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
        </div>
    </div>
    <div class="drawer-overlay"></div>

    <div class="container">
