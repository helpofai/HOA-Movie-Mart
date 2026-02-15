<?php
/**
 * HOA Movie Mart Theme Settings
 */

function helpofai_add_admin_menu() {
    $page = add_theme_page(
        __( 'Movie Mart Settings', 'helpofai' ),
        __( 'Movie Mart Settings', 'helpofai' ),
        'manage_options',
        'hoa_movie_mart',
        'helpofai_options_page'
    );
    add_action( 'admin_print_scripts-' . $page, 'helpofai_admin_scripts' );
}
add_action( 'admin_menu', 'helpofai_add_admin_menu' );

function helpofai_admin_scripts() {
    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );
    // Enqueue our custom admin JS which handles tabs and color pickers
    wp_enqueue_script( 'hoa-admin-js', get_template_directory_uri() . '/assets/js/admin-settings.js', array( 'wp-color-picker' ), '1.0', true );
}

function helpofai_settings_init() {
    register_setting( 'hoa_movie_mart_group', 'hoa_movie_mart_settings' );

    // Branding Section
    add_settings_section(
        'hoa_branding_section',
        __( 'Branding & Identity', 'helpofai' ),
        'helpofai_section_callback',
        'hoa_movie_mart'
    );

    add_settings_field(
        'logo_url',
        __( 'Logo URL', 'helpofai' ),
        'helpofai_render_upload_field',
        'hoa_movie_mart',
        'hoa_branding_section',
        array( 'label_for' => 'logo_url', 'class' => 'regular-text' )
    );

    add_settings_field(
        'favicon_url',
        __( 'Favicon URL', 'helpofai' ),
        'helpofai_render_upload_field',
        'hoa_movie_mart',
        'hoa_branding_section',
        array( 'label_for' => 'favicon_url', 'class' => 'regular-text' )
    );

    // Footer Section
    add_settings_section(
        'hoa_footer_section',
        __( 'Footer Settings', 'helpofai' ),
        'helpofai_section_callback',
        'hoa_movie_mart'
    );

    add_settings_field(
        'footer_description',
        __( 'Footer Description', 'helpofai' ),
        'helpofai_render_textarea_field',
        'hoa_movie_mart',
        'hoa_footer_section',
        array( 'label_for' => 'footer_description', 'description' => 'A short description about your site shown in the footer.' )
    );

    add_settings_field(
        'footer_title_explore',
        __( 'Explore Column Title', 'helpofai' ),
        'helpofai_render_text_field',
        'hoa_movie_mart',
        'hoa_footer_section',
        array( 'label_for' => 'footer_title_explore', 'class' => 'regular-text' )
    );

    add_settings_field(
        'footer_text_explore',
        __( 'Explore Column Extra Text', 'helpofai' ),
        'helpofai_render_textarea_field',
        'hoa_movie_mart',
        'hoa_footer_section',
        array( 'label_for' => 'footer_text_explore', 'description' => 'Text or HTML to show under the Explore title.' )
    );

    add_settings_field(
        'footer_title_genres',
        __( 'Genres Column Title', 'helpofai' ),
        'helpofai_render_text_field',
        'hoa_movie_mart',
        'hoa_footer_section',
        array( 'label_for' => 'footer_title_genres', 'class' => 'regular-text' )
    );

    add_settings_field(
        'footer_text_genres',
        __( 'Genres Column Extra Text', 'helpofai' ),
        'helpofai_render_textarea_field',
        'hoa_movie_mart',
        'hoa_footer_section',
        array( 'label_for' => 'footer_text_genres' )
    );

    add_settings_field(
        'footer_title_legal',
        __( 'Legal Column Title', 'helpofai' ),
        'helpofai_render_text_field',
        'hoa_movie_mart',
        'hoa_footer_section',
        array( 'label_for' => 'footer_title_legal', 'class' => 'regular-text' )
    );

    add_settings_field(
        'footer_text_legal',
        __( 'Legal Column Extra Text', 'helpofai' ),
        'helpofai_render_textarea_field',
        'hoa_movie_mart',
        'hoa_footer_section',
        array( 'label_for' => 'footer_text_legal' )
    );

    add_settings_field(
        'footer_text',
        __( 'Footer Copyright Text', 'helpofai' ),
        'helpofai_render_text_field',
        'hoa_movie_mart',
        'hoa_footer_section',
        array( 'label_for' => 'footer_text', 'class' => 'regular-text' )
    );

    // Header Sections
    add_settings_section( 'hoa_header_style_section', __( 'Header Style', 'helpofai' ), 'helpofai_section_callback', 'hoa_movie_mart' );
    add_settings_field( 'header_bg_color', __( 'Background Color', 'helpofai' ), 'helpofai_render_text_field', 'hoa_movie_mart', 'hoa_header_style_section', array( 'label_for' => 'header_bg_color', 'class' => 'regular-text' ) );
    add_settings_field( 'header_padding', __( 'Padding (px)', 'helpofai' ), 'helpofai_render_text_field', 'hoa_movie_mart', 'hoa_header_style_section', array( 'label_for' => 'header_padding', 'class' => 'regular-text' ) );

    add_settings_section( 'hoa_header_typo_section', __( 'Header Typography', 'helpofai' ), 'helpofai_section_callback', 'hoa_movie_mart' );
    add_settings_field( 'header_font_size', __( 'Font Size (px)', 'helpofai' ), 'helpofai_render_text_field', 'hoa_movie_mart', 'hoa_header_typo_section', array( 'label_for' => 'header_font_size', 'class' => 'regular-text' ) );

    add_settings_section( 'hoa_header_colors_section', __( 'Header Colors', 'helpofai' ), 'helpofai_section_callback', 'hoa_movie_mart' );
    add_settings_field( 'header_link_color', __( 'Link Color', 'helpofai' ), 'helpofai_render_text_field', 'hoa_movie_mart', 'hoa_header_colors_section', array( 'label_for' => 'header_link_color', 'class' => 'regular-text' ) );
    add_settings_field( 'header_link_hover_color', __( 'Link Hover Color', 'helpofai' ), 'helpofai_render_text_field', 'hoa_movie_mart', 'hoa_header_colors_section', array( 'label_for' => 'header_link_hover_color', 'class' => 'regular-text' ) );

    add_settings_section( 'hoa_header_others_section', __( 'Header Others', 'helpofai' ), 'helpofai_section_callback', 'hoa_movie_mart' );
    add_settings_field( 'header_sticky', __( 'Enable Sticky Header', 'helpofai' ), 'helpofai_render_text_field', 'hoa_movie_mart', 'hoa_header_others_section', array( 'label_for' => 'header_sticky', 'class' => 'regular-text' ) );

    // Social Section
    add_settings_section(
        'hoa_social_section',
        __( 'Social Media Links', 'helpofai' ),
        'helpofai_section_callback',
        'hoa_movie_mart'
    );

    add_settings_field(
        'telegram_link',
        __( 'Telegram Channel URL', 'helpofai' ),
        'helpofai_render_text_field',
        'hoa_movie_mart',
        'hoa_social_section',
        array( 'label_for' => 'telegram_link', 'class' => 'regular-text' )
    );

    add_settings_field(
        'facebook_link',
        __( 'Facebook Page URL', 'helpofai' ),
        'helpofai_render_text_field',
        'hoa_movie_mart',
        'hoa_social_section',
        array( 'label_for' => 'facebook_link', 'class' => 'regular-text' )
    );

    // Advanced Section
    add_settings_section(
        'hoa_advanced_section',
        __( 'Advanced Settings', 'helpofai' ),
        'helpofai_section_callback',
        'hoa_movie_mart'
    );

    add_settings_field(
        'header_scripts',
        __( 'Header Scripts (Analytics/Ads)', 'helpofai' ),
        'helpofai_render_textarea_field',
        'hoa_movie_mart',
        'hoa_advanced_section',
        array( 'label_for' => 'header_scripts' )
    );

    // API Management Section
    add_settings_section(
        'hoa_api_section',
        __( 'API Configuration', 'helpofai' ),
        'helpofai_section_callback',
        'hoa_movie_mart'
    );

    add_settings_field(
        'omdb_api_key',
        __( 'OMDB API Key', 'helpofai' ),
        'helpofai_render_text_field',
        'hoa_movie_mart',
        'hoa_api_section',
        array( 'label_for' => 'omdb_api_key', 'class' => 'regular-text', 'description' => 'Used for fetching movie details and ratings.' )
    );

    add_settings_field(
        'tmdb_api_key',
        __( 'TMDB API Key (v3 auth)', 'helpofai' ),
        'helpofai_render_text_field',
        'hoa_movie_mart',
        'hoa_api_section',
        array( 'label_for' => 'tmdb_api_key', 'class' => 'regular-text', 'description' => 'Used for fetching high-quality posters and backdrops.' )
    );

    // Legal Section
    add_settings_section(
        'hoa_legal_section',
        __( 'Legal & Copyright Settings', 'helpofai' ),
        'helpofai_section_callback',
        'hoa_movie_mart'
    );

    add_settings_field(
        'copyright_note',
        __( 'Copyright Removal / DMCA Note', 'helpofai' ),
        'helpofai_render_textarea_field',
        'hoa_movie_mart',
        'hoa_legal_section',
        array( 'label_for' => 'copyright_note', 'description' => 'This message will appear at the bottom of every single movie page.' )
    );

    // Turnstile Section
    add_settings_section(
        'hoa_turnstile_section',
        __( 'Bot Protection (Cloudflare Turnstile)', 'helpofai' ),
        'helpofai_section_callback',
        'hoa_movie_mart'
    );

    add_settings_field(
        'turnstile_site_key',
        __( 'Turnstile Site Key', 'helpofai' ),
        'helpofai_render_text_field',
        'hoa_movie_mart',
        'hoa_turnstile_section',
        array( 'label_for' => 'turnstile_site_key', 'class' => 'regular-text' )
    );

    add_settings_field(
        'turnstile_secret_key',
        __( 'Turnstile Secret Key', 'helpofai' ),
        'helpofai_render_text_field',
        'hoa_movie_mart',
        'hoa_turnstile_section',
        array( 'label_for' => 'turnstile_secret_key', 'class' => 'regular-text' )
    );

    // Documentation Section
    add_settings_section(
        'hoa_docs_section',
        __( 'Documentation', 'helpofai' ),
        'helpofai_render_docs_inside_settings',
        'hoa_movie_mart'
    );
}
add_action( 'admin_init', 'helpofai_settings_init' );

function helpofai_render_docs_inside_settings() {
    ?>
    <div class="docs-inside-wrapper">
        <p>Welcome to the <strong>HOA Movie Mart</strong> user guide. Follow the steps below to set up your premium movie portal.</p>
        <hr>
        <h3>1. API Configuration</h3>
        <p>This theme uses external APIs to automate data entry. Configure these in the <strong>API Management</strong> tab.</p>
        
        <strong>A. TMDB API (The Movie Database):</strong>
        <ol>
            <li>Go to <a href="https://www.themoviedb.org/signup" target="_blank">TMDB Signup</a> and create a free account.</li>
            <li>Go to your <strong>Settings -> API</strong> section in your profile.</li>
            <li>Create a new API Key (request a "Developer" key).</li>
            <li>Copy the <strong>API Key (v3 auth)</strong>.</li>
            <li><em>Used for: High-quality posters, TV series metadata, and automatic episode lists.</em></li>
        </ol>

        <strong>B. OMDB API (Open Movie Database):</strong>
        <ol>
            <li>Visit <a href="http://www.omdbapi.com/apikey.aspx" target="_blank">OMDB API Key Page</a>.</li>
            <li>Select the <strong>FREE</strong> tier (1,000 requests per day).</li>
            <li>Enter your email. You will receive a key via email.</li>
            <li><strong>IMPORTANT:</strong> You must click the activation link in the email they send you!</li>
            <li><em>Used for: Precise IMDb ratings and movie runtimes.</em></li>
        </ol>

        <strong>C. TVmaze API:</strong>
        <ul>
            <li>No configuration needed! This API is completely free and used as a specialized source for TV Series schedules and network info.</li>
        </ul>
        <hr>
        <h3>2. Advanced Publisher</h3>
        <p>Found under the <strong>Movies</strong> menu. Enter a title and click <strong>Fetch TMDB</strong>. The system will auto-populate all data, including posters, ratings, and even TV season/episode lists.</p>

        <h3>3. Download Verification (Ghost Protocol)</h3>
        <p>Your download links are protected by a "Ghost Protocol" system. This means they are physically non-existent in the page source until a human verification is completed.</p>
        <strong>Step-by-Step Setup:</strong>
        <ol>
            <li>Go to the <a href="https://dash.cloudflare.com/" target="_blank">Cloudflare Dashboard</a> and navigate to the <strong>Turnstile</strong> section.</li>
            <li>Click <strong>Add Site</strong>.</li>
            <li>Enter a name (e.g., "My Movie Site").</li>
            <li><strong>Domain:</strong> Add your live domain (e.g., <code>yoursite.com</code>). For local testing on this PC, add <code>localhost</code>.</li>
            <li>Select "Managed" (Recommended) or "Non-interactive" widget type.</li>
            <li>Copy your <strong>Site Key</strong> and <strong>Secret Key</strong>.</li>
            <li>Paste them into <strong>Movie Mart Settings -> API Management</strong>.</li>
        </ol>
        <p><em>Tip: The system includes an automatic bypass for <code>localhost</code> development, so you can see your links immediately while building. On your live site, the protection will be fully active.</em></p>

        <h3>4. Movie Requests</h3>
        <p>Users can request content via the <code>/request/</code> page. You can manage these requests in the <strong>Requests</strong> dashboard menu.</p>
        
        <h3>5. Manual Page Setup</h3>
        <p>To ensure all features work correctly, you should manually create the following pages in <strong>Pages -> Add New</strong>:</p>
        <ul>
            <li><strong>Homepage:</strong> Create a page (e.g., "Home"). The theme will automatically use the <code>front-page.php</code> template for the site root.</li>
            <li><strong>Blog Page:</strong> Create a page (e.g., "Blog"). Go to <strong>Settings -> Reading</strong> and set "Posts page" to this page.</li>
            <li><strong>Request Page:</strong> Create a page named "Request". In the <strong>Page Attributes</strong> sidebar, select the <strong>Request Page</strong> template. This will automatically show the request form.</li>
        </ul>

        <h3>6. Shortcodes</h3>
        <p>Use <code>[movie_request_form]</code> to display the request form on any custom page or post.</p>
    </div>
    <?php
}

function helpofai_section_callback( $args ) {
    echo '<p>' . __( 'Configure your theme settings below.', 'helpofai' ) . '</p>';
}

function helpofai_render_upload_field( $args ) {
    $options = get_option( 'hoa_movie_mart_settings' );
    $value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : '';
    $id = $args['label_for'];
    ?>
    <div class="hoa-upload-wrapper">
        <input type="text" 
               id="<?php echo esc_attr( $id ); ?>" 
               name="hoa_movie_mart_settings[<?php echo esc_attr( $id ); ?>]" 
               value="<?php echo esc_attr( $value ); ?>" 
               class="regular-text hoa-upload-input">
        <button type="button" class="button hoa-upload-button" data-target="<?php echo esc_attr( $id ); ?>">
            <i class="dashicons dashicons-upload" style="margin-top: 4px;"></i> <?php _e( 'Select Image', 'helpofai' ); ?>
        </button>
        <div class="hoa-preview-wrapper" style="margin-top: 10px;">
            <img id="preview-<?php echo esc_attr( $id ); ?>" 
                 src="<?php echo esc_url( $value ); ?>" 
                 style="max-width: 150px; max-height: 150px; display: <?php echo $value ? 'block' : 'none'; ?>; border: 1px solid #ccd0d4; padding: 5px; background: #f0f0f1;">
        </div>
    </div>
    <?php
}

function helpofai_render_text_field( $args ) {
    $options = get_option( 'hoa_movie_mart_settings' );
    $value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : '';
    ?>
    <input type="text" 
           id="<?php echo esc_attr( $args['label_for'] ); ?>" 
           name="hoa_movie_mart_settings[<?php echo esc_attr( $args['label_for'] ); ?>]" 
           value="<?php echo esc_attr( $value ); ?>" 
           class="<?php echo esc_attr( $args['class'] ); ?>">
    <?php
}

function helpofai_render_textarea_field( $args ) {
    $options = get_option( 'hoa_movie_mart_settings' );
    $value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : '';
    ?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" 
              name="hoa_movie_mart_settings[<?php echo esc_attr( $args['label_for'] ); ?>]" 
              rows="5" cols="50" class="large-text code"><?php echo esc_textarea( $value ); ?></textarea>
    <?php
}

function helpofai_render_color_field( $args ) {
    $options = get_option( 'hoa_movie_mart_settings' );
    $value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : '';
    ?>
    <input type="text" 
           id="<?php echo esc_attr( $args['label_for'] ); ?>" 
           name="hoa_movie_mart_settings[<?php echo esc_attr( $args['label_for'] ); ?>]" 
           value="<?php echo esc_attr( $value ); ?>" 
           class="hoa-color-picker" 
           data-default-color="#ffffff">
    <?php
}

function helpofai_options_page() {
    ?>
    <div class="wrap hoa-settings-container">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        
        <div class="hoa-settings-wrapper">
            <!-- Sidebar Tabs -->
            <div class="hoa-settings-sidebar">
                <ul>
                    <li class="active"><a href="#branding"><i class="dashicons dashicons-admin-appearance"></i> Branding</a></li>
                    <li><a href="#header-main"><i class="dashicons dashicons-editor-table"></i> Header</a></li>
                    <li><a href="#social"><i class="dashicons dashicons-share"></i> Social Media</a></li>
                    <li><a href="#api-management"><i class="dashicons dashicons-rest-api"></i> API Management</a></li>
                    <li><a href="#footer-settings"><i class="dashicons dashicons-editor-insertmore"></i> Footer</a></li>
                    <li><a href="#legal"><i class="dashicons dashicons-shield"></i> Legal</a></li>
                    <li><a href="#advanced"><i class="dashicons dashicons-admin-generic"></i> Advanced</a></li>
                    <li><a href="#documentation"><i class="dashicons dashicons-book"></i> Documentation</a></li>
                </ul>
            </div>

            <!-- Content Area -->
            <div class="hoa-settings-content">
                <form action="options.php" method="post">
                    <?php settings_fields( 'hoa_movie_mart_group' ); ?>

                    <!-- Header Sub-tabs Navigation -->
                    <div id="header-subtabs-nav" class="hoa-subtabs-nav" style="display:none; margin-bottom: 20px; border-bottom: 1px solid #ccd0d4; padding-bottom: 10px;">
                        <ul style="margin:0; padding:0; list-style:none; display:flex; gap: 15px;">
                            <li><a href="#header_style" class="hoa-subtab-link active" style="text-decoration:none; font-weight:bold; color:#2271b1;">Style</a></li>
                            <li><a href="#header_typo" class="hoa-subtab-link" style="text-decoration:none; color:#1d2327;">Typography</a></li>
                            <li><a href="#header_colors" class="hoa-subtab-link" style="text-decoration:none; color:#1d2327;">Colors</a></li>
                            <li><a href="#header_others" class="hoa-subtab-link" style="text-decoration:none; color:#1d2327;">Others</a></li>
                        </ul>
                    </div>

                    <div id="hoa-sections-container">
                        <?php do_settings_sections( 'hoa_movie_mart' ); ?>
                    </div>

                    <div class="hoa-settings-footer">
                        <?php submit_button( 'Save All Changes', 'primary', 'submit', false ); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .hoa-settings-container { margin-top: 20px; }
        .hoa-settings-wrapper { display: flex; background: #fff; border: 1px solid #ccd0d4; border-radius: 8px; overflow: hidden; min-height: 500px; margin-top: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .hoa-settings-sidebar { width: 220px; background: #f0f0f1; border-right: 1px solid #ccd0d4; }
        .hoa-settings-sidebar ul { margin: 0; padding: 0; list-style: none; }
        .hoa-settings-sidebar li { border-bottom: 1px solid #ccd0d4; }
        .hoa-settings-sidebar li a { display: block; padding: 15px 20px; text-decoration: none; color: #1d2327; font-weight: 500; transition: all 0.2s; display: flex; align-items: center; gap: 10px; }
        .hoa-settings-sidebar li.active a { background: #fff; color: #2271b1; border-left: 4px solid #2271b1; padding-left: 16px; }
        .hoa-settings-sidebar li a:hover { background: #e0e0e0; }
        .hoa-settings-content { flex: 1; padding: 30px 40px; }
        .hoa-tab-content h2 { font-size: 1.5rem; margin-top: 0; padding-bottom: 10px; border-bottom: 2px solid #f0f0f1; margin-bottom: 20px; }
        .hoa-settings-footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #f0f0f1; }
        
        .hoa-section-group { display: none; }
        .hoa-section-group.active { display: block; }
        
        .form-table th { width: 200px; }
    </style>
    <?php
}
