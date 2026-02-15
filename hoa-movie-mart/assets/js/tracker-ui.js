/**
 * TV Series Progress Tracker (LocalStorage)
 */
jQuery(document).ready(function($) {
    // 1. Initialize Toggles
    $('.episode-watched-toggle').each(function() {
        var id = $(this).data('id'); // format: postID-S1-E1
        if (localStorage.getItem('hoa_watched_' + id)) {
            $(this).prop('checked', true);
            $(this).closest('.episode-item').addClass('watched');
        }
    });

    // 2. Handle Click
    $('.episode-watched-toggle').on('change', function() {
        var id = $(this).data('id');
        var $row = $(this).closest('.episode-item');

        if ($(this).is(':checked')) {
            localStorage.setItem('hoa_watched_' + id, 'true');
            $row.addClass('watched');
        } else {
            localStorage.removeItem('hoa_watched_' + id);
            $row.removeClass('watched');
        }
        
        updateSeriesProgress();
    });

    // 3. Update Visual Progress Bar logic
    function updateSeriesProgress() {
        if (!$('body.single-movie').length) return;
        
        var postId = $('#hoa-download-gate').data('post-id');
        var totalEpisodes = $('.episode-watched-toggle').length;
        var watchedEpisodes = $('.episode-watched-toggle:checked').length;
        
        if (totalEpisodes > 0) {
            var percentage = Math.round((watchedEpisodes / totalEpisodes) * 100);
            localStorage.setItem('hoa_progress_' + postId, percentage);
        }
    }
    
    // Initial calculation on page load
    updateSeriesProgress();
});
