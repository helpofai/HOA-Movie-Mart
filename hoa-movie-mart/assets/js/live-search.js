jQuery(document).ready(function($) {
    var searchTimer;
    var $searchInput = $('.search-field');
    var $searchForm = $('.premium-search-form');

    // Create results container if it doesn't exist
    if ( ! $('.search-results-dropdown').length ) {
        $searchForm.append('<div class="search-results-dropdown"></div>');
    }
    var $dropdown = $('.search-results-dropdown');

    $searchInput.on('input', function() {
        var term = $(this).val();
        
        clearTimeout(searchTimer);

        if ( term.length < 3 ) {
            $dropdown.fadeOut();
            return;
        }

        $dropdown.html('<div class="search-loading"><i class="fas fa-spinner fa-spin"></i> Searching...</div>').show();

        searchTimer = setTimeout(function() {
            $.ajax({
                url: hoa_vars.ajax_url,
                data: {
                    action: 'hoa_live_search',
                    term: term
                },
                success: function(response) {
                    if ( response.success && response.data.length > 0 ) {
                        var html = '<ul>';
                        $.each(response.data, function(index, movie) {
                            html += `
                                <li>
                                    <a href="${movie.permalink}" class="search-result-item">
                                        <img src="${movie.poster}" alt="${movie.title}">
                                        <div class="search-meta">
                                            <span class="search-title">${movie.title}</span>
                                            <div class="search-info">
                                                <span class="search-year">${movie.year}</span>
                                                <span class="search-rating"><i class="fas fa-star"></i> ${movie.rating}</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            `;
                        });
                        html += '</ul>';
                        html += `<a href="/?s=${term}" class="view-all-results">View all results</a>`;
                        $dropdown.html(html);
                    } else {
                        $dropdown.html('<div class="search-no-results">No movies found.</div>');
                    }
                }
            });
        }, 500); // 500ms delay to prevent spamming
    });

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if ( ! $(e.target).closest('.premium-search-form').length ) {
            $dropdown.fadeOut();
        }
    });
});
