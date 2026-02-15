jQuery(document).ready(function($) {
    
    // Wrapper container
    var container = $('#hoa-download-repeater');

    // Add Row
    $('#hoa-add-download-row').on('click', function(e) {
        e.preventDefault();
        var index = container.find('.hoa-download-row').length;
        
        var row = `
            <div class="hoa-download-row">
                <input type="text" name="download_links[${index}][label]" placeholder="Label (e.g. Server 1)" style="flex:1">
                <input type="text" name="download_links[${index}][quality]" placeholder="Quality (e.g. 720p)" style="width: 100px;">
                <input type="url" name="download_links[${index}][url]" placeholder="https://..." style="flex:2">
                <button type="button" class="button hoa-row-remove"><span class="dashicons dashicons-trash" style="margin-top: 4px;"></span></button>
            </div>
        `;
        
        container.append(row);
    });

    // Remove Row
    container.on('click', '.hoa-row-remove', function(e) {
        e.preventDefault();
        $(this).closest('.hoa-download-row').remove();
    });

});
