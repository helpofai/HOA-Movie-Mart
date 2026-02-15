<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 */

if ( ! function_exists( 'hoa_movie_poster' ) ) :
    /**
     * Display the movie poster.
     */
    function hoa_movie_poster( $size = 'medium', $class = 'movie-poster' ) {
        if ( has_post_thumbnail() ) {
            the_post_thumbnail( $size, array( 'class' => $class ) );
        } else {
            $external_poster = get_post_meta( get_the_ID(), '_movie_poster_external', true );
            if ( $external_poster ) {
                echo '<img src="' . esc_url( $external_poster ) . '" class="' . esc_attr( $class ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            } else {
                echo '<img src="https://via.placeholder.com/300x450?text=No+Poster" class="' . esc_attr( $class ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            }
        }
    }
endif;

if ( ! function_exists( 'hoa_movie_rating' ) ) :
    /**
     * Display the IMDb rating.
     */
    function hoa_movie_rating() {
        $imdb_rating = get_post_meta( get_the_ID(), '_movie_imdb_rating', true );
        if ( $imdb_rating ) {
            echo '<span class="rating"><i class="fas fa-star"></i> ' . esc_html( $imdb_rating ) . '</span>';
        }
    }
endif;

if ( ! function_exists( 'hoa_movie_year' ) ) :
    /**
     * Display the movie release year.
     */
    function hoa_movie_year() {
        $year_terms = get_the_terms( get_the_ID(), 'movie_year' );
        $year = $year_terms ? $year_terms[0]->name : '';
        if ( $year ) {
            echo '<span class="movie-year">' . esc_html( $year ) . '</span>';
        }
    }
endif;

if ( ! function_exists( 'hoa_movie_runtime' ) ) :
    /**
     * Display the runtime.
     */
    function hoa_movie_runtime() {
        $runtime = get_post_meta( get_the_ID(), '_movie_runtime', true );
        if ( $runtime ) {
            echo '<span class="movie-runtime"><i class="far fa-clock"></i> ' . esc_html( $runtime ) . '</span>';
        }
    }
endif;

if ( ! function_exists( 'hoa_movie_trailer' ) ) :
    /**
     * Display a button that triggers a cinematic trailer modal.
     */
    function hoa_movie_trailer() {
        $trailer_url = get_post_meta( get_the_ID(), '_movie_trailer_url', true );
        if ( ! $trailer_url ) return;

        $video_id = '';
        if ( strpos( $trailer_url, 'youtube.com' ) !== false || strpos( $trailer_url, 'youtu.be' ) !== false ) {
            preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $trailer_url, $match );
            $video_id = isset( $match[1] ) ? $match[1] : '';
        }

        if ( $video_id ) : ?>
            <div class="trailer-action-box" style="margin-bottom: 50px;">
                <div class="section-header" style="margin-bottom: 25px;">
                    <h3 class="section-title">Official Trailer</h3>
                </div>
                <div class="trailer-preview-card" style="background-image: url('https://img.youtube.com/vi/<?php echo $video_id; ?>/maxresdefault.jpg');">
                    <div class="play-trigger" data-video-id="<?php echo esc_attr($video_id); ?>">
                        <div class="play-btn-circle">
                            <i class="fas fa-play"></i>
                        </div>
                        <span>Watch Trailer</span>
                    </div>
                </div>
            </div>

            <!-- Modal Structure (Hidden by default) -->
            <div id="trailer-modal" class="hoa-modal">
                <div class="modal-overlay"></div>
                <div class="modal-content">
                    <button class="modal-close">&times;</button>
                    <div class="video-wrapper">
                        <div id="player-container"></div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <p><a href="<?php echo esc_url( $trailer_url ); ?>" target="_blank" class="btn-download"><i class="fab fa-youtube"></i> Watch Trailer on External Site</a></p>
        <?php endif;
    }
endif;

if ( ! function_exists( 'hoa_movie_download_buttons' ) ) :
    /**
     * Display a verification box only if links exist.
     */
    function hoa_movie_download_buttons() {
        $options = get_option( 'hoa_movie_mart_settings' );
        $site_key = isset( $options['turnstile_site_key'] ) ? $options['turnstile_site_key'] : '';
        $post_id = get_the_ID();

        // 1. Comprehensive Link Check
        $links = get_post_meta( $post_id, '_movie_download_links_json', true );
        $legacy_720 = get_post_meta( $post_id, '_movie_download_link_720p', true );
        $legacy_1080 = get_post_meta( $post_id, '_movie_download_link_1080p', true );

        $has_links = ! empty($links) || ! empty($legacy_720) || ! empty($legacy_1080);

        echo '<div id="hoa-download-gate" class="download-container" data-post-id="' . esc_attr($post_id) . '">';
        echo '<div class="section-header"><h3 class="section-title">Download Links</h3></div>';
        
        if ( $has_links ) {
            // Links are available -> Show Verification
            echo '<div class="verification-box">';
            echo '<div class="verify-content">';
            echo '<h4><i class="fas fa-user-shield"></i> Human Verification Required</h4>';
            echo '<p>Please complete the security check below to reveal the download links. This helps us prevent automated scrapers.</p>';
            
            if ( $site_key ) {
                echo '<div id="turnstile-container" class="cf-turnstile" data-sitekey="' . esc_attr($site_key) . '" data-callback="hoaOnVerify"></div>';
            } else {
                echo '<div class="notice notice-warning"><p>Please configure Turnstile Site Key in settings.</p></div>';
            }
            
            echo '<div id="download-loading" style="display:none; margin-top:20px;">';
            echo '<i class="fas fa-spinner fa-spin"></i> Loading secure links...';
            echo '</div>';
            echo '</div></div>';
            echo '<div id="secure-download-target"></div>';
        } else {
            // No links yet -> Show informative message (No verification needed)
            echo '<div class="no-links-placeholder">';
            echo '<i class="fas fa-clock"></i>';
            echo '<h4>Links Coming Soon</h4>';
            echo '<p>We are currently processing the high-quality files for this title. Please check back later!</p>';
            echo '</div>';
        }
        
        echo '</div>'; // #hoa-download-gate
    }
endif;

if ( ! function_exists( 'hoa_movie_taxonomy_bar' ) ) :
    /**
     * Display a horizontal bar for any taxonomy.
     */
    function hoa_movie_taxonomy_bar( $taxonomy = 'movie_genre', $label = 'Genres', $icon_prefix = 'fa-film' ) {
        $terms = get_terms( array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => true,
            'number'     => 15
        ) );

        if ( is_wp_error( $terms ) || empty( $terms ) ) return;

        // Extended icon map for genres
        $icon_map = array(
            'action'    => 'fa-fire', 'adventure' => 'fa-compass', 'animation' => 'fa-ghost',
            'comedy'    => 'fa-face-grin-tears', 'crime' => 'fa-mask', 'drama' => 'fa-theater-masks',
            'horror'    => 'fa-skull', 'romance' => 'fa-heart', 'sci-fi' => 'fa-rocket',
            'thriller'  => 'fa-user-secret', 'documentary' => 'fa-camera-retro', 'fantasy' => 'fa-wand-sparkles',
            'family'    => 'fa-people-group', 'mystery' => 'fa-magnifying-glass', 'war' => 'fa-person-rifle'
        );

        echo '<div class="taxonomy-bar-container" style="margin-bottom: 30px;">';
        echo '<div class="section-header" style="border-left:none; padding-left:0; margin-bottom:15px;"><h3 class="section-title" style="font-size: 1rem; opacity: 0.7;">Browse by ' . esc_html( $label ) . '</h3></div>';
        echo '<div class="genre-scroll scroll-container">';
        
        foreach ( $terms as $term ) {
            $slug = strtolower( $term->slug );
            $icon = 'fa-film'; // Default
            
            if ( $taxonomy === 'movie_genre' ) {
                $icon = isset( $icon_map[$slug] ) ? $icon_map[$slug] : 'fa-clapperboard';
            } elseif ( $taxonomy === 'movie_year' ) {
                $icon = 'fa-calendar-days';
            } elseif ( $taxonomy === 'movie_quality' ) {
                $icon = 'fa-bolt';
            }
            
            echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="genre-card">';
            echo '<i class="fas ' . esc_attr( $icon ) . '"></i>';
            echo '<span>' . esc_html( $term->name ) . '</span>';
            echo '</a>';
        }
        
        echo '</div></div>';
    }
endif;

// Keep old function for compatibility but point to new one
// Related Movies Logic
if ( ! function_exists( 'hoa_related_movies' ) ) :
    function hoa_related_movies() {
        $post_id = get_the_ID();
        $genres = wp_get_post_terms( $post_id, 'movie_genre', array( 'fields' => 'ids' ) );

        if ( empty( $genres ) ) return;

        $args = array(
            'post_type'      => 'movie',
            'posts_per_page' => 5,
            'post__not_in'   => array( $post_id ),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'movie_genre',
                    'field'    => 'id',
                    'terms'    => $genres,
                ),
            ),
        );

        $related_query = new WP_Query( $args );

        if ( $related_query->have_posts() ) :
            echo '<div class="related-movies-section" style="margin-top: 60px; padding-top: 40px; border-top: 1px solid rgba(255,255,255,0.05);">';
            echo '<div class="section-header"><h3 class="section-title">You Might Also Like</h3></div>';
            echo '<div class="movie-grid">';
            while ( $related_query->have_posts() ) : $related_query->the_post();
                get_template_part( 'template-parts/content', 'movie' );
            endwhile;
            echo '</div></div>';
            wp_reset_postdata();
        endif;
    }
endif;

/**
 * Display User Star Rating in Comments
 */
function hoa_display_comment_rating( $comment_text, $comment ) {
    $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
    if ( $rating ) {
        $stars = '<div class="user-stars" style="color:#ffd700; margin-bottom:10px;">';
        for ( $i = 1; $i <= 5; $i++ ) {
            $stars .= ( $i <= $rating ) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
        }
        $stars .= '</div>';
        $comment_text = $stars . $comment_text;
    }
    return $comment_text;
}
add_filter( 'get_comment_text', 'hoa_display_comment_rating', 10, 2 );
