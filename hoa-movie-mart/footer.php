</div> <!-- .container -->

    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Column 1: Brand -->
                <div class="footer-col brand-col">
                    <div class="footer-logo">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <?php 
                            $options = get_option( 'hoa_movie_mart_settings' );
                            if ( ! empty( $options['logo_url'] ) ) : ?>
                                <img src="<?php echo esc_url( $options['logo_url'] ); ?>" alt="<?php bloginfo( 'name' ); ?>">
                            <?php else : ?>
                                <?php bloginfo( 'name' ); ?>
                            <?php endif; ?>
                        </a>
                    </div>
                    <p class="footer-desc">
                        <?php 
                        $footer_desc = isset($options['footer_description']) && !empty($options['footer_description']) ? $options['footer_description'] : 'The ultimate destination for premium movie and TV series downloads. Fast, secure, and always high quality.';
                        echo esc_html($footer_desc);
                        ?>
                    </p>
                    <div class="footer-social">
                        <?php if ( ! empty( $options['telegram_link'] ) ) : ?>
                            <a href="<?php echo esc_url( $options['telegram_link'] ); ?>" target="_blank" class="social-link telegram"><i class="fab fa-telegram-plane"></i></a>
                        <?php endif; ?>
                        <?php if ( ! empty( $options['facebook_link'] ) ) : ?>
                            <a href="<?php echo esc_url( $options['facebook_link'] ); ?>" target="_blank" class="social-link facebook"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Column 2: Explore -->
                <div class="footer-col">
                    <h4 class="footer-title"><?php echo isset($options['footer_title_explore']) && !empty($options['footer_title_explore']) ? esc_html($options['footer_title_explore']) : 'Explore'; ?></h4>
                    <?php if ( ! empty( $options['footer_text_explore'] ) ) echo '<div class="footer-extra-text">' . wpautop( $options['footer_text_explore'] ) . '</div>'; ?>
                    <?php
                    if ( has_nav_menu( 'footer_explore' ) ) {
                        wp_nav_menu( array( 'theme_location' => 'footer_explore', 'menu_class' => 'footer-links', 'container' => false ) );
                    } else {
                        echo '<ul class="footer-links">
                            <li><a href="'.home_url('/').'">Home</a></li>
                            <li><a href="'.get_post_type_archive_link('movie').'">New Releases</a></li>
                            <li><a href="'.add_query_arg('orderby', 'popular', home_url('/')).'">Trending Now</a></li>
                            <li><a href="'.add_query_arg('orderby', 'meta_value_num', home_url('/')).'">Top Rated</a></li>
                        </ul>';
                    }
                    ?>
                </div>

                <!-- Column 3: Genres -->
                <div class="footer-col">
                    <h4 class="footer-title"><?php echo isset($options['footer_title_genres']) && !empty($options['footer_title_genres']) ? esc_html($options['footer_title_genres']) : 'Genres'; ?></h4>
                    <?php if ( ! empty( $options['footer_text_genres'] ) ) echo '<div class="footer-extra-text">' . wpautop( $options['footer_text_genres'] ) . '</div>'; ?>
                    
                    <?php
                    if ( has_nav_menu( 'footer_genres' ) ) {
                        wp_nav_menu( array( 
                            'theme_location' => 'footer_genres', 
                            'menu_class'     => 'footer-links two-col', 
                            'container'      => false 
                        ) );
                    } else {
                        echo '<ul class="footer-links two-col">';
                        $genres = get_terms( array( 'taxonomy' => 'movie_genre', 'number' => 8 ) );
                        foreach ( $genres as $genre ) {
                            echo '<li><a href="' . get_term_link( $genre ) . '">' . $genre->name . '</a></li>';
                        }
                        echo '</ul>';
                    }
                    ?>
                </div>

                <!-- Column 4: Legal -->
                <div class="footer-col">
                    <h4 class="footer-title"><?php echo isset($options['footer_title_legal']) && !empty($options['footer_title_legal']) ? esc_html($options['footer_title_legal']) : 'Legal & Help'; ?></h4>
                    <?php if ( ! empty( $options['footer_text_legal'] ) ) echo '<div class="footer-extra-text">' . wpautop( $options['footer_text_legal'] ) . '</div>'; ?>
                    <?php
                    if ( has_nav_menu( 'footer_legal' ) ) {
                        wp_nav_menu( array( 'theme_location' => 'footer_legal', 'menu_class' => 'footer-links', 'container' => false ) );
                    } else {
                        echo '<ul class="footer-links">
                            <li><a href="'.home_url('/request/').'">Request Content</a></li>
                            <li><a href="#">DMCA Policy</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>
                <?php 
                if ( ! empty( $options['footer_text'] ) ) {
                    echo esc_html( $options['footer_text'] );
                } else {
                    echo '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved.';
                }
                ?>
            </p>
            <p class="credits">Designed by <a href="#">HOA Team</a></p>
        </div>
    </footer>

    <!-- Report Dead Link Modal -->
    <div id="report-modal" class="hoa-modal">
        <div class="modal-overlay"></div>
        <div class="modal-content" style="max-width: 500px; aspect-ratio: unset; padding: 30px;">
            <button class="modal-close">&times;</button>
            <h3 class="section-heading">Report Broken Link</h3>
            <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:20px;">Is this link down? Let us know which server or episode is failing.</p>
            
            <form id="dead-link-report-form">
                <input type="hidden" id="report_post_id">
                <input type="hidden" id="report_link_info">
                
                <div class="comment-field" style="margin-bottom:20px;">
                    <label>Description (Optional)</label>
                    <textarea id="report_message" rows="3" placeholder="e.g. Server is showing 404 error..."></textarea>
                </div>
                
                <button type="submit" class="btn-download" style="width:100%;">Submit Report</button>
            </form>
            <div id="report-status" style="margin-top:15px; text-align:center; display:none;"></div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="mobile-bottom-nav">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-item <?php if(is_front_page()) echo 'active'; ?>">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="<?php echo get_post_type_archive_link('movie'); ?>" class="nav-item <?php if(is_post_type_archive('movie')) echo 'active'; ?>">
            <i class="fas fa-film"></i>
            <span>Movies</span>
        </a>
        <a href="<?php echo add_query_arg('orderby', 'popular', home_url('/')); ?>" class="nav-item">
            <i class="fas fa-fire"></i>
            <span>Hot</span>
        </a>
        <a href="<?php echo esc_url( home_url('/request/') ); ?>" class="nav-item">
            <i class="fas fa-paper-plane"></i>
            <span>Request</span>
        </a>
    </div>

    <!-- Back to Top -->
    <button id="back-to-top" class="back-to-top" aria-label="Back to Top">
        <i class="fas fa-chevron-up"></i>
        <svg class="progress-circle" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </button>

    <?php wp_footer(); ?>
</body>
</html>