/**
 * Stats Tracker JS
 */
jQuery(document).ready(function($) {
    
    // 1. Track Post View (Only on Single Movie)
    if ($('body.single-movie').length) {
        var postId = $('#hoa-download-gate').data('post-id');
        if (postId) {
            $.post(hoa_vars.ajax_url, {
                action: 'hoa_track_view',
                post_id: postId
            });
        }
    }

    // 2. Track Downloads
    $(document).on('click', '.table-btn-download, .btn-download', function() {
        var $gate = $('#hoa-download-gate');
        if ($gate.length) {
            var postId = $gate.data('post-id');
            $.post(hoa_vars.ajax_url, {
                action: 'hoa_track_download',
                post_id: postId
            });
        }
    });
});
