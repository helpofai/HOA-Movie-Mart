/**
 * Grid Progress Bar Logic
 * Shows watched progress on movie cards in the grid.
 */
jQuery(document).ready(function($) {
    $('.movie-card').each(function() {
        var $card = $(this);
        var postId = $card.find('a').attr('href').split('/').filter(Boolean).pop(); // Heuristic to get slug/id or use a data attr
        
        // Better: Use a data attribute. I will add this to content-movie.php
        var dataId = $card.data('post-id');
        if (!dataId) return;

        var progress = localStorage.getItem('hoa_progress_' + dataId);
        
        if (progress && progress > 0) {
            var progressBar = `
                <div class="grid-progress-container">
                    <div class="grid-progress-fill" style="width: ${progress}%"></div>
                </div>
            `;
            $card.find('a').append(progressBar);
        }
    });
});
