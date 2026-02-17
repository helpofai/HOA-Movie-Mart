<?php
/**
 * Template Name: Download Bridge
 * Description: High-security 3-step link protection bridge with ad integration.
 */

if ( ! isset( $_GET['key'] ) ) {
    wp_redirect( home_url() );
    exit;
}

$key = sanitize_text_field( $_GET['key'] );
$data = get_transient( 'hoa_bridge_' . $key );

if ( ! $data ) {
    wp_die( 'Link expired or invalid. Please go back to the movie page.' );
}

$post_id = $data['post_id'];
$options = get_option( 'hoa_movie_mart_settings' );

// Get Timer Settings
$timer1 = isset($options['bridge_timer_1']) ? absint($options['bridge_timer_1']) : 15;
$timer2 = isset($options['bridge_timer_2']) ? absint($options['bridge_timer_2']) : 10;

// Fetch Metadata
$title = get_the_title( $post_id );
$imdb_rating = get_post_meta( $post_id, '_movie_imdb_rating', true );
$runtime = get_post_meta( $post_id, '_movie_runtime', true );
$language = get_post_meta( $post_id, '_movie_language', true );
$release_date = get_post_meta( $post_id, '_movie_release_date', true );
$quality = strip_tags( get_the_term_list( $post_id, 'movie_quality', '', ', ', '' ) );

get_header(); ?>

<div class="bridge-master-wrapper">
    <!-- Top Ad Slot -->
    <?php if ( ! empty( $options['bridge_ad_top'] ) ) : ?>
        <div class="bridge-ad-container ad-top">
            <?php echo $options['bridge_ad_top']; ?>
        </div>
    <?php endif; ?>

    <div class="bridge-main-layout">
        <!-- Movie Info Sidebar -->
        <aside class="bridge-movie-sidebar">
            <div class="bridge-poster-box">
                <?php 
                if ( has_post_thumbnail($post_id) ) {
                    echo get_the_post_thumbnail($post_id, 'medium');
                } else {
                    $ext = get_post_meta($post_id, '_movie_poster_external', true);
                    if ($ext) echo '<img src="'.esc_url($ext).'">';
                }
                ?>
                <?php if ($imdb_rating) : ?>
                    <div class="bridge-rating"><i class="fas fa-star"></i> <?php echo esc_html($imdb_rating); ?></div>
                <?php endif; ?>
            </div>
            <div class="bridge-meta-list">
                <h3><?php echo esc_html($title); ?></h3>
                <div class="bridge-meta-item">
                    <span class="label">Released:</span>
                    <span class="value"><?php echo esc_html($release_date); ?></span>
                </div>
                <div class="bridge-meta-item">
                    <span class="label">Runtime:</span>
                    <span class="value"><?php echo esc_html($runtime); ?></span>
                </div>
                <div class="bridge-meta-item">
                    <span class="label">Language:</span>
                    <span class="value"><?php echo esc_html($language); ?></span>
                </div>
                <div class="bridge-meta-item">
                    <span class="label">Quality:</span>
                    <span class="value badge-q"><?php echo esc_html($quality ?: 'HD'); ?></span>
                </div>
            </div>
        </aside>

        <div class="bridge-content-area">
            
            <!-- Step 1: Security Verification -->
            <div id="bridge-step-1" class="bridge-card active">
                <div class="bridge-header">
                    <div class="bridge-icon-anim">
                        <i class="fas fa-shield-alt fa-pulse"></i>
                    </div>
                    <h2>Link Security Verification</h2>
                    <p>Please wait while we verify the security of the dynamic tunnel...</p>
                </div>

                <!-- Middle Ad Slot -->
                <?php if ( ! empty( $options['bridge_ad_mid'] ) ) : ?>
                    <div class="bridge-ad-container ad-mid">
                        <?php echo $options['bridge_ad_mid']; ?>
                    </div>
                <?php endif; ?>

                <div class="timer-box">
                    <span class="timer-label">Checking security in...</span>
                    <div id="countdown-1" class="timer-count"><?php echo $timer1; ?></div>
                </div>

                <div class="bridge-actions">
                    <button id="btn-continue-1" class="btn-bridge disabled" disabled>
                        Please Wait...
                    </button>
                </div>
            </div>

            <!-- Step 2: Dynamic Link Generation -->
            <div id="bridge-step-2" class="bridge-card">
                <div class="bridge-header">
                    <div class="bridge-icon-anim">
                        <i class="fas fa-project-diagram fa-spin"></i>
                    </div>
                    <h2>Generating Dynamic Tunnel</h2>
                    <p>We are creating a secure, masked proxy for your download.</p>
                </div>

                <div class="timer-box">
                    <span class="timer-label">Finalizing link in...</span>
                    <div id="countdown-2" class="timer-count"><?php echo $timer2; ?></div>
                </div>

                <div class="bridge-actions">
                    <button id="btn-continue-2" class="btn-bridge disabled" disabled>
                        Generating...
                    </button>
                </div>
            </div>

            <!-- Step 3: Final Download -->
            <div id="bridge-step-3" class="bridge-card">
                <div class="bridge-header">
                    <div class="bridge-icon-anim success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2>Link Ready!</h2>
                    <p>Your secure download link has been generated successfully.</p>
                </div>

                <div class="final-info-box">
                    <span><i class="fas fa-lock"></i> Encrypted Tunnel Active</span>
                    <span><i class="fas fa-user-shield"></i> Safe for Download</span>
                </div>

                <div class="bridge-actions">
                    <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
                        <input type="hidden" name="action" value="hoa_final_redirect">
                        <input type="hidden" name="bridge_key" value="<?php echo esc_attr($key); ?>">
                        <button type="submit" class="btn-bridge success">
                            Download Now <i class="fas fa-download"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Sidebar Ad Slot -->
        <?php if ( ! empty( $options['bridge_ad_sidebar'] ) ) : ?>
            <aside class="bridge-sidebar-ad">
                <div class="sticky-ad">
                    <span class="ad-label">Advertisement</span>
                    <?php echo $options['bridge_ad_sidebar']; ?>
                </div>
            </aside>
        <?php endif; ?>
    </div>

    <!-- Bottom Ad Slot -->
    <?php if ( ! empty( $options['bridge_ad_bottom'] ) ) : ?>
        <div class="bridge-ad-container ad-bottom">
            <?php echo $options['bridge_ad_bottom']; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Bridge Layout */
.bridge-master-wrapper { max-width: 1300px; margin: 40px auto; padding: 0 20px; }
.bridge-main-layout { display: flex; gap: 40px; margin: 30px 0; align-items: flex-start; }

/* Movie Sidebar */
.bridge-movie-sidebar { width: 280px; background: var(--bg-surface); border: var(--glass-border); border-radius: var(--radius-md); overflow: hidden; position: sticky; top: 100px; }
.bridge-poster-box { position: relative; }
.bridge-poster-box img { width: 100%; height: auto; display: block; }
.bridge-rating { position: absolute; bottom: 15px; right: 15px; background: rgba(0,0,0,0.8); color: #ffd700; padding: 5px 12px; border-radius: 6px; font-weight: 800; backdrop-filter: blur(5px); border: 1px solid rgba(255,215,0,0.3); }

.bridge-meta-list { padding: 25px; }
.bridge-meta-list h3 { font-size: 1.4rem; margin-bottom: 20px; line-height: 1.2; color: #fff; }
.bridge-meta-item { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 8px; }
.bridge-meta-item .label { color: var(--text-dim); font-weight: 600; }
.bridge-meta-item .value { color: var(--text-main); font-weight: 700; }
.bridge-meta-item .badge-q { background: var(--primary-color); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; text-transform: uppercase; }

.bridge-content-area { flex: 1; min-width: 0; }
.bridge-sidebar-ad { width: 300px; }
.sticky-ad { position: sticky; top: 100px; text-align: center; }
.ad-label { display: block; font-size: 10px; color: var(--text-dim); text-transform: uppercase; margin-bottom: 5px; }

.bridge-ad-container { text-align: center; margin: 20px 0; background: rgba(255,255,255,0.02); border-radius: 8px; min-height: 90px; display: flex; align-items: center; justify-content: center; overflow: hidden; }

/* Bridge Card */
.bridge-card { 
    background: var(--bg-surface); 
    border: var(--glass-border); 
    border-radius: var(--radius-lg); 
    padding: 50px 40px; 
    text-align: center; 
    backdrop-filter: blur(20px); 
    display: none; 
    box-shadow: var(--glass-shadow);
}
.bridge-card.active { display: block; animation: fadeIn 0.5s ease; }

.bridge-header h2 { font-size: 2.2rem; margin-bottom: 10px; }
.bridge-header p { color: var(--text-muted); font-size: 1.1rem; }

.bridge-icon-anim { font-size: 4rem; color: var(--primary-color); margin-bottom: 30px; }
.bridge-icon-anim.success { color: #46b450; }

/* Timer Box */
.timer-box { margin: 40px 0; }
.timer-label { display: block; color: var(--text-dim); font-size: 0.9rem; text-transform: uppercase; margin-bottom: 10px; }
.timer-count { font-size: 4rem; font-weight: 800; font-family: var(--font-heading); color: #fff; line-height: 1; }

/* Buttons */
.btn-bridge { 
    width: 100%; max-width: 400px; padding: 20px; border-radius: 12px; border: none; 
    font-size: 1.2rem; font-weight: 800; text-transform: uppercase; cursor: pointer;
    transition: all 0.3s; background: var(--primary-gradient); color: #fff;
}
.btn-bridge.disabled { background: #333; cursor: not-allowed; opacity: 0.5; }
.btn-bridge.success { background: linear-gradient(135deg, #46b450 0%, #2d8a35 100%); box-shadow: 0 10px 20px rgba(70, 180, 80, 0.2); }
.btn-bridge:not(.disabled):hover { transform: translateY(-3px); box-shadow: var(--glow-primary); }

.final-info-box { display: flex; justify-content: center; gap: 20px; margin-bottom: 30px; }
.final-info-box span { font-size: 0.9rem; color: #46b450; background: rgba(70, 180, 80, 0.1); padding: 8px 15px; border-radius: 30px; }

@media (max-width: 991px) {
    .bridge-main-layout { flex-direction: column; align-items: center; gap: 20px; }
    .bridge-movie-sidebar { width: 100%; max-width: 100%; position: relative; top: 0; }
    .bridge-sidebar-ad { width: 100%; order: 3; }
    .bridge-content-area { width: 100%; order: 2; }
    .bridge-header h2 { font-size: 1.6rem; }
    .timer-count { font-size: 3rem; }
    .bridge-card { padding: 30px 20px; }
    .bridge-ad-container { min-height: 60px; padding: 10px; }
    .bridge-ad-container img { max-width: 100%; height: auto; }
}

@media (max-width: 480px) {
    .bridge-poster-box { max-width: 200px; margin: 0 auto; border-radius: 8px; overflow: hidden; }
    .bridge-meta-list h3 { font-size: 1.2rem; text-align: center; }
}
</style>

<script>
jQuery(document).ready(function($) {
    var timer1 = <?php echo $timer1; ?>;
    var timer2 = <?php echo $timer2; ?>;

    // Step 1 Logic
    var countdown1 = setInterval(function() {
        timer1--;
        $('#countdown-1').text(timer1);
        if (timer1 <= 0) {
            clearInterval(countdown1);
            $('#btn-continue-1').removeClass('disabled').removeAttr('disabled').text('Verify Link & Continue');
        }
    }, 1000);

    $('#btn-continue-1').on('click', function() {
        $('#bridge-step-1').removeClass('active');
        $('#bridge-step-2').addClass('active');
        startStep2();
    });

    // Step 2 Logic
    function startStep2() {
        var countdown2 = setInterval(function() {
            timer2--;
            $('#countdown-2').text(timer2);
            if (timer2 <= 0) {
                clearInterval(countdown2);
                $('#btn-continue-2').removeClass('disabled').removeAttr('disabled').text('Generate Dynamic Link');
            }
        }, 1000);
    }

    $('#btn-continue-2').on('click', function() {
        $('#bridge-step-2').removeClass('active');
        $('#bridge-step-3').addClass('active');
    });
});
</script>

<?php get_footer(); ?>