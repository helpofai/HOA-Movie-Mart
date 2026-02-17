jQuery(document).ready(function($) {
    // Initialize Color Picker
    $('.hoa-color-picker').wpColorPicker();

    // Re-organize the DOM into groups
    var currentSectionId = '';
    $('#hoa-sections-container').children().each(function() {
        var $el = $(this);
        if ($el.is('h2')) {
            var title = $el.text();
            if (title.indexOf('Branding') !== -1) currentSectionId = 'branding';
            else if (title.indexOf('Social') !== -1) currentSectionId = 'social';
            else if (title.indexOf('API') !== -1) currentSectionId = 'api-management';
            else if (title.indexOf('Footer') !== -1) currentSectionId = 'footer-settings';
            else if (title.indexOf('3-Step Bridge') !== -1) currentSectionId = 'bridge-ads';
            else if (title.indexOf('Legal') !== -1) currentSectionId = 'legal';
            else if (title.indexOf('Advanced') !== -1) currentSectionId = 'advanced';
            else if (title.indexOf('Documentation') !== -1) currentSectionId = 'documentation';
            else if (title.indexOf('Header Style') !== -1) currentSectionId = 'header_style';
            else if (title.indexOf('Header Typography') !== -1) currentSectionId = 'header_typo';
            else if (title.indexOf('Header Colors') !== -1) currentSectionId = 'header_colors';
            else if (title.indexOf('Header Others') !== -1) currentSectionId = 'header_others';

            if (!$('#section-' + currentSectionId).length) {
                $('#hoa-sections-container').append('<div id="section-' + currentSectionId + '" class="hoa-section-group"></div>');
            }
        }
        
        if (currentSectionId) {
            $('#section-' + currentSectionId).append($el);
        }
    });

    function switchTab(targetId) {
        // Update Sidebar
        $('.hoa-settings-sidebar li').removeClass('active');
        $('.hoa-settings-sidebar a[href="#' + targetId + '"]').parent().addClass('active');

        // Handle Header special case
        if (targetId === 'header-main') {
            $('#header-subtabs-nav').show();
            $('.hoa-section-group').hide();
            
            // Find current active sub-tab or default to style
            var activeSub = $('.hoa-subtab-link.active').attr('href').replace('#', '');
            $('#section-' + activeSub).show();
        } else {
            $('#header-subtabs-nav').hide();
            $('.hoa-section-group').hide();
            $('#section-' + targetId).show();
        }
    }

    $('.hoa-settings-sidebar a').on('click', function(e) {
        e.preventDefault();
        var targetId = $(this).attr('href').replace('#', '');
        switchTab(targetId);
    });

    // Sub-tab logic
    $('.hoa-subtab-link').on('click', function(e) {
        e.preventDefault();
        $('.hoa-subtab-link').removeClass('active').css('color', '#1d2327');
        $(this).addClass('active').css('color', '#2271b1');
        
        var targetSubId = $(this).attr('href').replace('#', '');
        $('.hoa-section-group').hide();
        $('#section-' + targetSubId).show();
    });

    // Show first tab by default
    switchTab('branding');

    // Media Uploader Logic
    $('.hoa-upload-button').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var targetInput = $('#' + button.data('target'));
        var previewImg = $('#preview-' + button.data('target'));

        var frame = wp.media({
            title: 'Select or Upload Image',
            button: { text: 'Use this image' },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            targetInput.val(attachment.url);
            previewImg.attr('src', attachment.url).show();
        });

        frame.open();
    });

    // Update preview if URL is typed manually
    $('.hoa-upload-input').on('change blur', function() {
        var url = $(this).val();
        var previewId = '#preview-' + $(this).attr('id');
        if (url) {
            $(previewId).attr('src', url).show();
        } else {
            $(previewId).hide();
        }
    });
});
