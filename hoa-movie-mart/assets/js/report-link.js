/**
 * Dead Link Reporting Logic
 */
jQuery(document).ready(function($) {
    var $modal = $('#report-modal');
    var $form = $('#dead-link-report-form');
    var $status = $('#report-status');

    // 1. Open Modal
    $(document).on('click', '.btn-report-dead', function(e) {
        e.preventDefault();
        var postId = $(this).data('post-id');
        var linkInfo = $(this).data('link');

        $('#report_post_id').val(postId);
        $('#report_link_info').val(linkInfo);
        
        $status.hide();
        $form.show();
        $modal.fadeIn(300).css('display', 'flex');
    });

    // 2. Close Modal
    $('.modal-close, .modal-overlay').on('click', function() {
        $modal.fadeOut(300);
    });

    // 3. AJAX Submit
    $form.on('submit', function(e) {
        e.preventDefault();
        
        var data = {
            action: 'hoa_submit_report',
            post_id: $('#report_post_id').val(),
            link_info: $('#report_link_info').val(),
            message: $('#report_message').val(),
            security: hoa_vars.nonce
        };

        $form.css('opacity', '0.5').find('button').prop('disabled', true);

        $.post(hoa_vars.ajax_url, data, function(response) {
            $form.hide().css('opacity', '1').find('button').prop('disabled', false);
            $status.html('<div style="color:#46b450; font-weight:700;">' + response.data + '</div>').fadeIn();
            
            setTimeout(function() {
                $modal.fadeOut(300);
                $('#report_message').val(''); // Reset
            }, 2500);
        });
    });
});
