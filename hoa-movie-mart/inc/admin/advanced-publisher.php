<?php
/**
 * Advanced Movie Publisher
 * A custom interface for adding movies with API support.
 */

// Register the Menu Page
function helpofai_register_publisher_page() {
    add_submenu_page(
        'edit.php?post_type=movie',
        __( 'Advanced Publisher', 'helpofai' ),
        __( 'Add New (Advanced)', 'helpofai' ),
        'edit_posts',
        'hoa_movie_publisher',
        'helpofai_render_publisher_page'
    );
}
add_action( 'admin_menu', 'helpofai_register_publisher_page' );

// Enqueue Assets for this page
function helpofai_publisher_assets( $hook ) {
    if ( 'movie_page_hoa_movie_publisher' !== $hook ) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_style( 'hoa-publisher-css', get_template_directory_uri() . '/assets/css/publisher.css', array(), '1.0' );
    wp_enqueue_script( 'hoa-publisher-js', get_template_directory_uri() . '/assets/js/publisher.js', array( 'jquery' ), '1.0', true );
    
    // Pass API Keys to JS
    $options = get_option( 'hoa_movie_mart_settings' );
    wp_localize_script( 'hoa-publisher-js', 'hoaData', array(
        'omdbKey' => isset( $options['omdb_api_key'] ) ? $options['omdb_api_key'] : '',
        'tmdbKey' => isset( $options['tmdb_api_key'] ) ? $options['tmdb_api_key'] : '',
    ));
}
add_action( 'admin_enqueue_scripts', 'helpofai_publisher_assets' );

// Handle Form Submission
function helpofai_handle_publisher_save() {
    if ( ! isset( $_POST['hoa_publisher_nonce'] ) || ! wp_verify_nonce( $_POST['hoa_publisher_nonce'], 'hoa_save_movie' ) ) {
        return;
    }

    $title   = sanitize_text_field( $_POST['movie_title'] );
    $content = wp_kses_post( $_POST['movie_content'] );
    $excerpt = sanitize_textarea_field( $_POST['movie_short_desc'] );

    // Create Post
    $post_id = wp_insert_post( array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_excerpt' => $excerpt,
        'post_status'  => 'publish',
        'post_type'    => 'movie',
    ));

    if ( $post_id ) {
        // 1. Taxonomies
        if ( ! empty( $_POST['movie_genres'] ) ) {
            wp_set_object_terms( $post_id, explode( ',', $_POST['movie_genres'] ), 'movie_genre' );
        }
        if ( ! empty( $_POST['movie_year'] ) ) {
            wp_set_object_terms( $post_id, $_POST['movie_year'], 'movie_year' );
        }
        if ( ! empty( $_POST['movie_quality'] ) ) {
            wp_set_object_terms( $post_id, $_POST['movie_quality'], 'movie_quality' );
        }
        if ( ! empty( $_POST['movie_director'] ) ) {
            wp_set_object_terms( $post_id, explode( ',', $_POST['movie_director'] ), 'movie_director' );
        }
        if ( ! empty( $_POST['movie_cast'] ) ) {
            wp_set_object_terms( $post_id, explode( ',', $_POST['movie_cast'] ), 'movie_cast' );
        }

        // 2. Meta Data
        update_post_meta( $post_id, '_movie_type', sanitize_text_field( $_POST['content_type'] ) );
        update_post_meta( $post_id, '_movie_imdb_id', sanitize_text_field( $_POST['imdb_id'] ) );
        update_post_meta( $post_id, '_movie_total_seasons', sanitize_text_field( $_POST['total_seasons'] ) );
        update_post_meta( $post_id, '_movie_status', sanitize_text_field( $_POST['series_status'] ) );
        update_post_meta( $post_id, '_movie_imdb_rating', sanitize_text_field( $_POST['imdb_rating'] ) );
        update_post_meta( $post_id, '_movie_runtime', sanitize_text_field( $_POST['runtime'] ) );
        update_post_meta( $post_id, '_movie_trailer_url', esc_url_raw( $_POST['trailer_url'] ) );
        update_post_meta( $post_id, '_movie_language', sanitize_text_field( $_POST['language'] ) );
        update_post_meta( $post_id, '_movie_release_date', sanitize_text_field( $_POST['release_date'] ) );
        update_post_meta( $post_id, '_movie_rated', sanitize_text_field( $_POST['parental_rating'] ) );
        update_post_meta( $post_id, '_movie_country', sanitize_text_field( $_POST['country'] ) );
        update_post_meta( $post_id, '_movie_studio', sanitize_text_field( $_POST['studio'] ) );
        update_post_meta( $post_id, '_movie_seo_keywords', sanitize_text_field( $_POST['movie_keywords'] ) );
        
        // 3. Featured Image
        if ( ! empty( $_POST['poster_image_id'] ) ) {
            set_post_thumbnail( $post_id, absint( $_POST['poster_image_id'] ) );
            delete_post_meta( $post_id, '_movie_poster_external' );
        } elseif ( ! empty( $_POST['poster_url_external'] ) ) {
            update_post_meta( $post_id, '_movie_poster_external', esc_url_raw( $_POST['poster_url_external'] ) );
        }

        // 4. Screenshots Gallery
        if ( ! empty( $_POST['movie_gallery_ids'] ) ) {
            update_post_meta( $post_id, '_movie_gallery_ids', sanitize_text_field( $_POST['movie_gallery_ids'] ) );
        }

        // 5. Download Links
        if ( ! empty( $_POST['download_links'] ) ) {
            $links = array();
            foreach ( $_POST['download_links'] as $link ) {
                if ( ! empty( $link['url'] ) ) {
                    $links[] = array(
                        'label' => sanitize_text_field( $link['label'] ),
                        'url'   => esc_url_raw( $link['url'] ),
                        'quality' => sanitize_text_field( $link['quality'] )
                    );
                }
            }
            update_post_meta( $post_id, '_movie_download_links_json', $links );
            
            // Backwards Compatibility (Map first 2 to old fields)
            if ( isset( $links[0] ) ) update_post_meta( $post_id, '_movie_download_link_720p', $links[0]['url'] );
            if ( isset( $links[1] ) ) update_post_meta( $post_id, '_movie_download_link_1080p', $links[1]['url'] );
        }

        // 6. Save Seasons & Episodes
        if ( ! empty( $_POST['seasons_data'] ) ) {
            // Decode the JSON string sent from JS (because nested arrays in POST can be tricky with dynamic keys)
            $seasons_json = stripslashes( $_POST['seasons_data'] );
            update_post_meta( $post_id, '_movie_seasons_json', $seasons_json );
        }

        // Redirect
        wp_redirect( admin_url( 'edit.php?post_type=movie&page=hoa_movie_publisher&saved=1' ) );
        exit;
    }
}
add_action( 'admin_post_hoa_save_movie', 'helpofai_handle_publisher_save' );

// Render Page
function helpofai_render_publisher_page() {
    ?>
    <div class="wrap hoa-publisher-wrap">
        <h1 class="wp-heading-inline">Advanced Movie Publisher</h1>
        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p>Movie published successfully!</p></div>
        <?php endif; ?>

        <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" id="movie-publisher-form">
            <input type="hidden" name="action" value="hoa_save_movie">
            <input type="hidden" name="seasons_data" id="seasons_data">
            <?php wp_nonce_field( 'hoa_save_movie', 'hoa_publisher_nonce' ); ?>

            <div class="publisher-grid">
                <!-- Left Column: Main Content -->
                <div class="col-main">
                    <!-- Fetcher -->
                    <div class="card fetch-card">
                        <h2><span class="dashicons dashicons-cloud-upload"></span> Multi-API Quick Fetch</h2>
                        
                        <div class="fetch-row" style="margin-bottom: 15px;">
                            <label style="font-weight:bold; display:block; margin-bottom:5px;">TMDB (High Quality Posters)</label>
                            <div class="input-group">
                                <input type="text" id="fetch_title" placeholder="Enter Movie Name for TMDB..." class="large-text">
                                <button type="button" id="btn-fetch" class="button button-primary">Fetch TMDB</button>
                            </div>
                        </div>

                        <div class="fetch-row">
                            <label style="font-weight:bold; display:block; margin-bottom:5px;">OMDB (Accurate IMDb Ratings)</label>
                            <div class="input-group">
                                <input type="text" id="fetch_title_omdb" placeholder="Enter Movie Name for OMDB..." class="large-text">
                                <button type="button" id="btn-fetch-omdb" class="button button-secondary">Fetch OMDB</button>
                            </div>
                        </div>

                        <div class="fetch-row">
                            <label style="font-weight:bold; display:block; margin-bottom:5px;">TVmaze (Best for TV Series)</label>
                            <div class="input-group">
                                <input type="text" id="fetch_title_tv" placeholder="Enter Series Name..." class="large-text">
                                <button type="button" id="btn-fetch-tv" class="button button-secondary" style="background:#3c434a; border-color:#2c3338; color:#fff;">Fetch TVmaze</button>
                            </div>
                        </div>
                        
                        <p class="description">API Keys must be configured for TMDB/OMDB. TVmaze requires no key.</p>
                    </div>

                    <!-- Basic Info -->
                    <div class="card basic-card">
                        <div class="form-group">
                            <label>Content Type</label>
                            <select name="content_type" id="content_type" class="widefat" style="max-width: 200px;">
                                <option value="movie">Movie</option>
                                <option value="tv">TV Series</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Movie Title</label>
                            <input type="text" name="movie_title" id="movie_title" required class="widefat title-input">
                        </div>
                        
                        <div class="form-group">
                            <label>Short Description (Excerpt)</label>
                            <textarea name="movie_short_desc" id="movie_short_desc" rows="3" class="widefat"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Full Description</label>
                            <?php wp_editor( '', 'movie_content', array( 'media_buttons' => false, 'textarea_rows' => 8 ) ); ?>
                        </div>

                        <div class="form-group">
                            <label>Automated SEO Keywords</label>
                            <input type="text" name="movie_keywords" id="movie_keywords" class="widefat" placeholder="Keywords for search engines...">
                        </div>
                    </div>

                    <!-- Download Section -->
                    <div class="card download-card">
                        <h2>Download Links (Movies)</h2>
                        <div id="download-repeater">
                            <!-- JS will inject rows here -->
                        </div>
                        <button type="button" id="add-download-row" class="button button-secondary">+ Add Button</button>
                    </div>

                    <!-- TV Seasons Manager -->
                    <div class="card seasons-card tv-only" style="display:none;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                            <h2 style="margin:0;">Seasons & Episodes</h2>
                            <button type="button" id="btn-fetch-episodes" class="button button-primary">Auto-Fetch Episodes (TMDB)</button>
                        </div>
                        
                        <div id="seasons-wrapper">
                            <!-- Seasons will be injected here -->
                        </div>
                        <button type="button" id="add-season" class="button button-secondary" style="margin-top:15px;">+ Add Season</button>
                    </div>
                </div>

                <!-- Right Column: Meta Data -->
                <div class="col-sidebar">
                    <div class="card meta-card">
                        <h3>Publishing Quality</h3>
                        <div class="content-score-wrapper" style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Content Score</span>
                                <span id="score-value">0%</span>
                            </div>
                            <div class="score-bar" style="height: 10px; background: #f0f0f1; border-radius: 5px; overflow: hidden;">
                                <div id="score-fill" style="height: 100%; width: 0%; background: #dc3232; transition: width 0.5s;"></div>
                            </div>
                            <p class="description" style="font-size: 11px; margin-top: 5px;">Score increases as you add more movie details.</p>
                        </div>

                        <h3>Movie Details</h3>
                        
                        <div class="poster-wrapper">
                            <input type="hidden" name="poster_image_id" id="poster_image_id">
                            <input type="hidden" name="poster_url_external" id="poster_url_external">
                            <div id="poster-preview" class="poster-preview">
                                <span class="placeholder-text">Poster Preview</span>
                            </div>
                            <button type="button" class="button" id="upload-poster">Upload / Select Poster</button>
                        </div>

                        <hr>
                        
                        <h3>Screenshots Gallery</h3>
                        <div class="gallery-wrapper">
                            <input type="hidden" name="movie_gallery_ids" id="movie_gallery_ids">
                            <div id="gallery-preview" class="gallery-preview" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px; margin-bottom: 10px;">
                                <!-- Gallery items here -->
                            </div>
                            <button type="button" class="button" id="upload-gallery">Add Screenshots</button>
                        </div>

                        <hr>

                        <div class="meta-grid">
                            <div class="form-group">
                                <label>IMDb ID</label>
                                <input type="text" name="imdb_id" id="imdb_id" class="widefat" placeholder="e.g. tt1234567">
                            </div>
                            <div class="form-group">
                                <label>IMDb Rating</label>
                                <input type="text" name="imdb_rating" id="imdb_rating" class="widefat">
                            </div>
                            <div class="form-group">
                                <label>Runtime</label>
                                <input type="text" name="runtime" id="runtime" class="widefat">
                            </div>
                            <div class="form-group tv-only" style="display:none;">
                                <label>Total Seasons</label>
                                <input type="text" name="total_seasons" id="total_seasons" class="widefat">
                            </div>
                            <div class="form-group tv-only" style="display:none;">
                                <label>Status</label>
                                <input type="text" name="series_status" id="series_status" class="widefat" placeholder="Running / Ended">
                            </div>
                            <div class="form-group">
                                <label>Year</label>
                                <input type="text" name="movie_year" id="movie_year" class="widefat">
                            </div>
                            <div class="form-group">
                                <label>Release Date</label>
                                <input type="date" name="release_date" id="release_date" class="widefat">
                            </div>
                            <div class="form-group">
                                <label>Rated</label>
                                <input type="text" name="parental_rating" id="parental_rating" class="widefat" placeholder="e.g. PG-13">
                            </div>
                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" name="country" id="country" class="widefat">
                            </div>
                            <div class="form-group">
                                <label>Studio / Network</label>
                                <input type="text" name="studio" id="studio" class="widefat">
                            </div>
                             <div class="form-group">
                                <label>Language</label>
                                <input type="text" name="language" id="language" class="widefat">
                            </div>
                            <div class="form-group">
                                <label>Quality</label>
                                <input type="text" name="movie_quality" id="movie_quality" placeholder="1080p, 4K" class="widefat">
                            </div>
                            <div class="form-group">
                                <label>Director(s) (Comma separated)</label>
                                <input type="text" name="movie_director" id="movie_director" class="widefat">
                            </div>
                            <div class="form-group">
                                <label>Cast / Actors (Comma separated)</label>
                                <input type="text" name="movie_cast" id="movie_cast" class="widefat">
                            </div>
                            <div class="form-group">
                                <label>Genres (Comma separated)</label>
                                <input type="text" name="movie_genres" id="movie_genres" class="widefat">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Trailer URL</label>
                            <input type="url" name="trailer_url" id="trailer_url" class="widefat">
                        </div>

                        <div class="publish-actions">
                            <button type="submit" class="button button-primary button-hero">Publish Movie</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}
