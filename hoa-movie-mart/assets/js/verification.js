/**
 * Human Verification Logic
 */

// Cloudflare callback function
function hoaOnVerify(token) {
    var $ = jQuery;
    var $container = $('#hoa-download-gate');
    var postId = $container.data('post-id');
    var $loading = $('#download-loading');
    var $verifyBox = $('.verify-content');
    var $target = $('#secure-download-target');

    // 1. Show loading state
    $loading.show();
    $('#turnstile-container').css('opacity', '0.3').css('pointer-events', 'none');

    // 2. AJAX Handshake
    $.ajax({
        url: hoa_vars.ajax_url,
        type: 'POST',
        data: {
            action: 'hoa_get_downloads',
            post_id: postId,
            token: token,
            security: hoa_vars.nonce
        },
        success: function(response) {
            if (response.success) {
                // SUCCESS: Proof of Human.
                $('.verification-box').fadeOut(300, function() {
                    $target.hide().html(response.data).fadeIn(500);
                });
            } else {
                // FAIL: Bot or error
                alert('Verification Error: ' + response.data);
                
                // RESET UI
                $loading.hide();
                $('#turnstile-container').css('opacity', '1').css('pointer-events', 'auto');
                
                // Reset Turnstile so user can try again
                if (typeof turnstile !== 'undefined') {
                    turnstile.reset('#turnstile-container');
                }
            }
        },
        error: function() {
            alert('Security handshake failed. Please check your internet connection or refresh the page.');
            $loading.hide();
            $('#turnstile-container').css('opacity', '1').css('pointer-events', 'auto');
        }
    });
}

jQuery(document).ready(function($) {
    // Only run if the gate exists
    if (!$('#hoa-download-gate').length) return;

    // Development Bypass: Auto-verify if on localhost
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        console.log('HOA Movie Mart: Localhost detected. Bypassing Turnstile for development...');
        hoaOnVerify('dev_bypass_token');
        return;
    }

    // Load Turnstile script dynamically if not present
    if ($('.cf-turnstile').length && typeof turnstile === 'undefined') {
        $.getScript('https://challenges.cloudflare.com/turnstile/v0/api.js');
    }
});
