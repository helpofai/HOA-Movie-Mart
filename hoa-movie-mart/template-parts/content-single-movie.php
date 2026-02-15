<?php
/**
 * Template part for displaying single movie details (Premium Layout)
 */

$post_id = get_the_ID();
$backdrop = get_the_post_thumbnail_url($post_id, 'full');
if (!$backdrop) {
    $backdrop = get_post_meta($post_id, '_movie_poster_external', true);
}

// Meta Data
$rating   = get_post_meta($post_id, '_movie_imdb_rating', true);
$runtime  = get_post_meta($post_id, '_movie_runtime', true);
$language = get_post_meta($post_id, '_movie_language', true);
$release  = get_post_meta($post_id, '_movie_release_date', true);
$rated    = get_post_meta($post_id, '_movie_rated', true);
$country  = get_post_meta($post_id, '_movie_country', true);
$studio   = get_post_meta($post_id, '_movie_studio', true);
$keywords = get_post_meta($post_id, '_movie_seo_keywords', true);

// TV Specifics
$type     = get_post_meta($post_id, '_movie_type', true);
$seasons  = get_post_meta($post_id, '_movie_total_seasons', true);
$status   = get_post_meta($post_id, '_movie_status', true);

// Quality Taxonomy
$qualities = get_the_term_list($post_id, 'movie_quality', '', ', ', '');
?>

<div class="movie-premium-wrapper">
    <!-- 1. Cinematic Backdrop -->
    <div class="movie-backdrop" style="background-image: url('<?php echo esc_url($backdrop); ?>');">
        <div class="backdrop-overlay"></div>
    </div>

    <div class="movie-main-container">
        <div class="movie-flex-layout">
            
            <!-- 2. Sidebar (Poster) -->
            <aside class="movie-sidebar">
                <div class="sticky-poster">
                    <?php hoa_movie_poster('large', 'single-main-poster'); ?>
                    
                    <?php if ($rating) : ?>
                        <div class="poster-rating">
                            <i class="fas fa-star"></i>
                            <span><?php echo esc_html($rating); ?></span>
                            <small>/ 10</small>
                        </div>
                    <?php endif; ?>

                    <div class="sidebar-info-box">
                        <div class="side-info-item">
                            <strong>Status:</strong> 
                            <span><?php echo $type === 'tv' ? esc_html($status) : 'Released'; ?></span>
                        </div>
                        <?php if ($type === 'tv' && $seasons) : ?>
                        <div class="side-info-item">
                            <strong>Seasons:</strong> 
                            <span><?php echo esc_html($seasons); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="side-info-item">
                            <strong>Quality:</strong> 
                            <span class="badge-quality"><?php echo $qualities ? strip_tags($qualities) : 'HD'; ?></span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- 3. Content Area -->
            <main class="movie-content-area">
                <h1 class="movie-main-title"><?php the_title(); ?></h1>
                
                <!-- Quick Stats Bar -->
                <div class="movie-stats-bar">
                    <div class="stat-item"><i class="far fa-calendar"></i> <?php echo esc_html(substr($release, 0, 4)); ?></div>
                    <div class="stat-item"><i class="far fa-clock"></i> <?php echo esc_html($runtime); ?></div>
                    <div class="stat-item"><i class="fas fa-globe"></i> <?php echo esc_html($language); ?></div>
                    <div class="stat-item"><i class="fas fa-eye"></i> <?php echo hoa_get_views($post_id); ?></div>
                    <?php if ($rated) : ?><div class="stat-item badge-rated"><?php echo esc_html($rated); ?></div><?php endif; ?>
                </div>

                <!-- Detailed Info Grid -->
                <div class="movie-info-grid">
                    <div class="info-row">
                        <span class="info-label">Genres:</span>
                        <span class="info-value"><?php echo get_the_term_list($post_id, 'movie_genre', '', ', ', ''); ?></span>
                    </div>
                    <?php 
                    $directors = get_the_term_list($post_id, 'movie_director', '', ', ', '');
                    if ($directors) : ?>
                    <div class="info-row">
                        <span class="info-label">Director:</span>
                        <span class="info-value"><?php echo $directors; ?></span>
                    </div>
                    <?php endif; ?>
                    <?php 
                    $cast = get_the_term_list($post_id, 'movie_cast', '', ', ', '');
                    if ($cast) : ?>
                    <div class="info-row">
                        <span class="info-label">Cast:</span>
                        <span class="info-value"><?php echo $cast; ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="info-row">
                        <span class="info-label">Studio:</span>
                        <span class="info-value"><?php echo $studio ? esc_html($studio) : 'N/A'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Country:</span>
                        <span class="info-value"><?php echo $country ? esc_html($country) : 'N/A'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Release:</span>
                        <span class="info-value"><?php echo $release ? esc_html($release) : 'N/A'; ?></span>
                    </div>
                </div>

                <!-- Storyline -->
                <div class="movie-section">
                    <h3 class="section-heading">Storyline</h3>
                    <div class="movie-description">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Media (Trailer & Gallery) -->
                <div class="movie-media-section">
                    <?php hoa_movie_trailer(); ?>

                    <?php 
                    $gallery_ids = get_post_meta($post_id, '_movie_gallery_ids', true);
                    if ($gallery_ids) : 
                        $ids = explode(',', $gallery_ids);
                    ?>
                        <div class="movie-gallery-wrapper">
                            <h3 class="section-heading">Screenshots</h3>
                            <div class="screenshot-grid">
                                <?php foreach ($ids as $img_id) : 
                                    $img_url = wp_get_attachment_image_url($img_id, 'large');
                                    if ($img_url) : ?>
                                    <a href="<?php echo esc_url($img_url); ?>" class="screenshot-item" target="_blank">
                                        <img src="<?php echo esc_url($img_url); ?>" alt="Screenshot">
                                    </a>
                                <?php endif; endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Download Section -->
                <div class="movie-download-wrapper">
                    <?php 
                    if ( $type === 'tv' ) {
                        $seasons_json = get_post_meta( $post_id, '_movie_seasons_json', true );
                        $seasons = json_decode( $seasons_json, true );

                        if ( ! empty( $seasons ) || ! empty( get_post_meta($post_id, '_movie_download_links_json', true) ) ) {
                            // Check for ANY links (Packs or Episodes)
                            $pack_links = get_post_meta($post_id, '_movie_download_links_json', true);
                            $has_links = ! empty($pack_links);
                            
                            if (!$has_links && !empty($seasons)) {
                                foreach ($seasons as $s) {
                                    if (!empty($s['zip'])) { $has_links = true; break; }
                                    if (!empty($s['episodes'])) {
                                        foreach ($s['episodes'] as $e) {
                                            if (!empty($e['link'])) { $has_links = true; break 2; }
                                        }
                                    }
                                }
                            }

                            $options = get_option( 'hoa_movie_mart_settings' );
                            $site_key = isset( $options['turnstile_site_key'] ) ? $options['turnstile_site_key'] : '';

                            echo '<div id="hoa-download-gate" class="download-container" data-post-id="' . esc_attr($post_id) . '">';
                            // Header is now handled inside the AJAX response for better layout control
                            
                            if ( $has_links ) {
                                // Verification Gate
                                echo '<div class="verification-box">';
                                echo '<div class="verify-content">';
                                echo '<h4><i class="fas fa-user-shield"></i> Human Verification Required</h4>';
                                echo '<p>Please complete the security check to view the episode links.</p>';
                                if ( $site_key ) {
                                    echo '<div id="turnstile-container" class="cf-turnstile" data-sitekey="' . esc_attr($site_key) . '" data-callback="hoaOnVerify"></div>';
                                } else {
                                    echo '<div class="notice notice-warning"><p>Please configure Turnstile Site Key.</p></div>';
                                }
                                echo '<div id="download-loading" style="display:none; margin-top:20px;"><i class="fas fa-spinner fa-spin"></i> Loading secure links...</div>';
                                echo '</div></div>';
                                echo '<div id="secure-download-target"></div>';
                            } else {
                                // Coming Soon
                                echo '<div class="no-links-placeholder"><i class="fas fa-clock"></i><h4>Episodes Coming Soon</h4><p>We are processing the files. Check back later!</p></div>';
                            }
                            echo '</div>';
                        }
                    } else {
                        hoa_movie_download_buttons();
                    }
                    ?>
                </div>

                <!-- 4. Related Movies -->
                <?php hoa_related_movies(); ?>

                <!-- 5. Comments & Reviews -->
                <div class="movie-reviews-section" style="margin-top: 60px;">
                    <?php comments_template(); ?>
                </div>

                <!-- Tags & Keywords Section -->
                <?php if ($keywords) : ?>
                <div class="movie-keywords-section" style="margin-top: 50px; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.05);">
                    <h4 style="font-size: 0.9rem; text-transform: uppercase; color: var(--text-dim); margin-bottom: 15px;">Keywords & Tags</h4>
                    <div class="keyword-cloud">
                        <?php 
                        $tag_array = explode(',', $keywords);
                        foreach ($tag_array as $tag) : 
                            if (trim($tag)) :
                        ?>
                            <span class="keyword-tag"><?php echo esc_html(trim($tag)); ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 6. Legal / Copyright Note -->
                <?php 
                $options = get_option( 'hoa_movie_mart_settings' );
                $copyright_note = isset( $options['copyright_note'] ) ? $options['copyright_note'] : '';
                if ( ! empty( $copyright_note ) ) :
                ?>
                    <div class="movie-legal-note">
                        <div class="legal-header">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Copyright & DMCA Notice</span>
                        </div>
                        <div class="legal-content">
                            <?php echo wpautop( esc_html( $copyright_note ) ); ?>
                        </div>
                    </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
</div>