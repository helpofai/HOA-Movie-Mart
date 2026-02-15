/**
 * Dead Link Reporting System
 */
jQuery(document).ready(function($) {
    const $modal = $('#report-modal');
    const $form = $('#dead-link-report-form');
    const $status = $('#report-status');

    // 1. Open Modal
    $(document).on('click', '.btn-report-dead', function() {
        const postId = $(this).data('post-id');
        const linkInfo = $(this).data('link');

        $('#report_post_id').val(postId);
        $('#report_link_info').val(linkInfo);
        
        $status.hide().removeClass('success error');
        $form.show();
        $modal.addClass('active');
        $('body').css('overflow', 'hidden');
    });

    // 2. Close Modal
    $('.modal-close, .modal-overlay').on('click', function() {
        $modal.removeClass('active');
        $('body').css('overflow', '');
    });

    // 3. Handle Submission
    $form.on('submit', function(e) {
        e.preventDefault();

        const $btn = $(this).find('button');
        const originalText = $btn.html();

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

        $.ajax({
            url: hoa_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'hoa_submit_report',
                security: hoa_vars.nonce,
                post_id: $('#report_post_id').val(),
                link_info: $('#report_link_info').val(),
                message: $('#report_message').val()
            },
            success: function(response) {
                if (response.success) {
                    $form.hide();
                    $status.html('<i class="fas fa-check-circle" style="color:#46b450; font-size:2rem; display:block; margin-bottom:10px;"></i>' + response.data)
                           .addClass('success').fadeIn();
                    
                    // Auto close after 3 seconds
                    setTimeout(() => {
                        $modal.removeClass('active');
                        $('body').css('overflow', '');
                        $('#report_message').val('');
                    }, 3000);
                } else {
                    $status.html(response.data).addClass('error').fadeIn();
                }
            },
            error: function() {
                $status.html('Network error. Please try again.').addClass('error').fadeIn();
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
