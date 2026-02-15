/**
 * Trailer Modal Handler
 */
jQuery(document).ready(function($) {
    const $modal = $('#trailer-modal');
    const $playerContainer = $('#player-container');

    // 1. Open Trailer
    $('.play-trigger').on('click', function() {
        const videoId = $(this).data('video-id');
        if (!videoId) return;

        // Create Iframe (Privacy Enhanced Mode)
        const iframeHtml = `<iframe 
            src="https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0" 
            title="YouTube video player" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            allowfullscreen></iframe>`;

        $playerContainer.html(iframeHtml);
        $modal.addClass('active');
        $('body').css('overflow', 'hidden');
    });

    // 2. Close Modal
    $('.modal-close, #trailer-modal .modal-overlay').on('click', function() {
        $modal.removeClass('active');
        $playerContainer.html(''); // Stop video playback
        $('body').css('overflow', '');
    });

    // Escape Key to close
    $(document).on('keydown', function(e) {
        if (e.key === "Escape" && $modal.hasClass('active')) {
            $modal.removeClass('active');
            $playerContainer.html('');
            $('body').css('overflow', '');
        }
    });
});
