<?php
/**
 * HOA Movie Mart Documentation
 */

function helpofai_register_docs_page() {
    add_submenu_page(
        'hoa_movie_mart',
        __( 'Theme Documentation', 'helpofai' ),
        __( 'Documentation', 'helpofai' ),
        'manage_options',
        'hoa_theme_docs',
        'helpofai_render_docs_page'
    );
}
add_action( 'admin_menu', 'helpofai_register_docs_page' );

function helpofai_render_docs_page() {
    ?>
    <div class="wrap hoa-docs-wrap">
        <h1>HOA Movie Mart - Documentation</h1>
        
        <div class="docs-container">
            <!-- Sidebar Navigation -->
            <ul class="docs-nav">
                <li class="active" data-tab="intro">Getting Started</li>
                <li data-tab="publisher">Advanced Publisher</li>
                <li data-tab="security">Ghost Protocol (Security)</li>
                <li data-tab="requests">Request System</li>
                <li data-tab="shortcodes">Shortcodes & Tips</li>
            </ul>

            <!-- Content Area -->
            <div class="docs-content">
                
                <!-- 1. Intro -->
                <div id="intro" class="doc-section active">
                    <h2>Getting Started</h2>
                    <p>Welcome to HOA Movie Mart, a professional-grade movie portal theme.</p>
                    <h3>First Steps:</h3>
                    <ol>
                        <li>Go to <strong>Movie Mart Settings -> API Management</strong>.</li>
                        <li>Enter your <strong>TMDB API Key</strong> (v3) for high-quality posters.</li>
                        <li>Enter your <strong>OMDB API Key</strong> for accurate IMDb ratings.</li>
                        <li>Save Changes.</li>
                    </ol>
                </div>

                <!-- 2. Publisher -->
                <div id="publisher" class="doc-section">
                    <h2>Advanced Publisher</h2>
                    <p>The core of this theme is the automated content fetcher.</p>
                    <h3>How to Add a Movie:</h3>
                    <ol>
                        <li>Go to <strong>Movies -> Advanced Publisher</strong>.</li>
                        <li>Type the movie name (e.g., "Inception") in the <strong>Fetch TMDB</strong> bar.</li>
                        <li>Click "Fetch". The form will auto-fill Title, Plot, Rating, Poster, etc.</li>
                        <li>Add Download Links in the repeater section.</li>
                        <li>Click <strong>Publish Movie</strong>.</li>
                    </ol>
                    <h3>How to Add a TV Series:</h3>
                    <ol>
                        <li>Search for the series (e.g., "Breaking Bad").</li>
                        <li>The system will auto-detect "TV Series" and show the <strong>Seasons & Episodes</strong> card.</li>
                        <li>Click <strong>Auto-Fetch Episodes (TMDB)</strong> to generate the full episode list instantly.</li>
                        <li>Use the "Bulk Links" button to paste download URLs for an entire season at once.</li>
                    </ol>
                </div>

                <!-- 3. Security -->
                <div id="security" class="doc-section">
                    <h2>Ghost Protocol (Anti-Bot Security)</h2>
                    <p>Your download links are protected by an advanced verification system that is invisible to bots.</p>
                    <h3>Setup:</h3>
                    <ol>
                        <li>Go to <a href="https://dash.cloudflare.com/" target="_blank">Cloudflare Turnstile</a> and get your Site/Secret keys.</li>
                        <li>Enter them in <strong>Movie Mart Settings -> API Management</strong>.</li>
                    </ol>
                    <p><strong>Note:</strong> On `localhost`, the system automatically bypasses verification for you (the admin) so you can test easily. On a live site, users will see a "Verify you are human" box.</p>
                </div>

                <!-- 4. Requests -->
                <div id="requests" class="doc-section">
                    <h2>Request System</h2>
                    <p>Users can request movies/shows they want to see.</p>
                    <ul>
                        <li><strong>Public Page:</strong> Users visit <code>/request/</code> to fill out a form.</li>
                        <li><strong>Admin Management:</strong> Go to the new <strong>Requests</strong> menu in your dashboard to see pending requests.</li>
                        <li><strong>Status:</strong> You can mark requests as "Completed" once you upload the content.</li>
                    </ul>
                </div>

                <!-- 5. Shortcodes -->
                <div id="shortcodes" class="doc-section">
                    <h2>Shortcodes & Helpers</h2>
                    <p>Use these shortcodes in your posts or pages:</p>
                    <table class="widefat striped">
                        <thead><tr><th>Shortcode</th><th>Description</th></tr></thead>
                        <tbody>
                            <tr>
                                <td><code>[movie_request_form]</code></td>
                                <td>Displays the Request Form. Use this on your "Request" page.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <style>
        .hoa-docs-wrap { margin-top: 20px; }
        .docs-container { display: flex; background: #fff; border: 1px solid #ccd0d4; border-radius: 4px; min-height: 500px; }
        .docs-nav { width: 250px; background: #f0f0f1; border-right: 1px solid #ccd0d4; margin: 0; padding: 0; list-style: none; }
        .docs-nav li { padding: 15px 20px; border-bottom: 1px solid #ddd; cursor: pointer; font-weight: 500; transition: background 0.2s; }
        .docs-nav li:hover { background: #e0e0e0; }
        .docs-nav li.active { background: #fff; border-left: 4px solid #2271b1; color: #2271b1; font-weight: 700; margin-left: -1px; }
        .docs-content { flex: 1; padding: 40px; }
        .doc-section { display: none; animation: fadeIn 0.3s; }
        .doc-section.active { display: block; }
        .doc-section h2 { border-bottom: 2px solid #f0f0f1; padding-bottom: 10px; margin-top: 0; }
        .doc-section li { margin-bottom: 10px; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        jQuery(document).ready(function($) {
            $('.docs-nav li').on('click', function() {
                var tab = $(this).data('tab');
                $('.docs-nav li').removeClass('active');
                $(this).addClass('active');
                $('.doc-section').removeClass('active');
                $('#' + tab).addClass('active');
            });
        });
    </script>
    <?php
}
