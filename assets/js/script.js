jQuery(document).ready(function($) {
    const $searchInput = $('#term_search');
    const $resultsContainer = $('#catex-search-autocomplete');
    let searchTimeout;
    let currentRequest;
    
    function getCurrentMessages() {
        const taxonomy = $searchInput.data('taxonomy');
        $.post(catexSearch.ajaxurl, {
            action: 'get_taxonomy_messages',
            nonce: catexSearch.nonce,
            taxonomy: taxonomy
        }, function(response) {
            if (response.success) {
                catexSearch.messages = response.data;
            }
        });
    }

    getCurrentMessages();

    $searchInput.on('input', function() {
        const query = $(this).val().trim();
        const taxonomy = $(this).data('taxonomy');

        clearTimeout(searchTimeout);

        if (query.length === 0) {
            $resultsContainer.empty().hide();
            return;
        }

        searchTimeout = setTimeout(function() {
            if (currentRequest) {
                currentRequest.abort();
            }

            currentRequest = $.ajax({
                url: catexSearch.ajaxurl,
                type: 'POST',
                data: {
                    action: 'catex_search_autocomplete',
                    nonce: catexSearch.nonce,
                    search_query: query,
                    taxonomy: taxonomy
                },
                beforeSend: function() {
                    $resultsContainer.html(
                        '<div class="loading">' + 
                        catexSearch.messages.loading + 
                        '</div>'
                    ).show();
                },
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        const results = response.data.map(function(item) {
                            return `<div class="taxonomy-suggestion">
                                    <a href="${item.url}">
                                        ${item.name}
                                        <span class="term-count">(${item.count})</span>
                                    </a>
                                </div>`;
                            }).join('');

                            $resultsContainer.html(results).show();
                        } else {
                            $resultsContainer.html(
                                '<div class="no-results">' + 
                                catexSearch.messages.no_results + 
                                '</div>'
                            ).show();
                        }
                    },
                    error: function() {
                        $resultsContainer.html(
                            '<div class="error">' + 
                            catexSearch.messages.plugin_error + 
                            '</div>'
                        ).show();
                    }
                });
            }, 300);
    });

    // Close results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.catex-search-form').length) {
            $resultsContainer.hide();
        }
    });

    // Handle suggestion clicks
    $(document).on('click', '.taxonomy-suggestion a', function(e) {
        e.preventDefault();
        window.location.href = $(this).attr('href');
    });

    // Handle keyboard navigation
    $searchInput.on('keydown', function(e) {
        const $suggestions = $('.taxonomy-suggestion');
        const $highlighted = $('.taxonomy-suggestion.highlighted');
        
        switch(e.keyCode) {
            case 40: // Down arrow
                e.preventDefault();
                if (!$highlighted.length) {
                    $suggestions.first().addClass('highlighted');
                } else {
                    $highlighted.removeClass('highlighted')
                               .next('.taxonomy-suggestion')
                               .addClass('highlighted');
                }
                break;

            case 38: // Up arrow
                e.preventDefault();
                if (!$highlighted.length) {
                    $suggestions.last().addClass('highlighted');
                } else {
                    $highlighted.removeClass('highlighted')
                               .prev('.taxonomy-suggestion')
                               .addClass('highlighted');
                }
                break;

            case 13: // Enter
                if ($highlighted.length) {
                    e.preventDefault();
                    window.location.href = $highlighted.find('a').attr('href');
                }
                break;
        }
    });
});