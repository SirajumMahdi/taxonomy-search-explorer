jQuery(document).ready(function($) {
  // Cache DOM elements
  const $searchInput = $('#term_search');
  const $resultsContainer = $('#taxonomy-search-autocomplete');
  let searchTimeout;
  let currentRequest;
  
  // Get messages for current taxonomy
  const getCurrentMessages = () => {
      const taxonomy = $searchInput.data('taxonomy');
      // Make AJAX call to get messages for this taxonomy
      $.post(taxonomySearch.ajaxurl, {
          action: 'get_taxonomy_messages',
          nonce: taxonomySearch.nonce,
          taxonomy: taxonomy
      }, function(response) {
          if (response.success) {
              taxonomySearch.messages = response.data;
          }
      });
  };

  // Initialize messages for current taxonomy
  getCurrentMessages();

  // Handle input changes
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
              url: taxonomySearch.ajaxurl,
              type: 'POST',
              data: {
                  action: 'taxonomy_search_autocomplete',
                  nonce: taxonomySearch.nonce,
                  search_query: query,
                  taxonomy: taxonomy
              },
              beforeSend: function() {
                  $resultsContainer.html(
                      '<div class="loading">' + 
                      taxonomySearch.messages.loading + 
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
                          taxonomySearch.messages.no_results + 
                          '</div>'
                      ).show();
                  }
              },
              error: function() {
                  $resultsContainer.html(
                      '<div class="error">' + 
                      taxonomySearch.messages.plugin_error + 
                      '</div>'
                  ).show();
              }
          });
      }, 300);
  });

  // Close results when clicking outside
  $(document).on('click', function(e) {
      if (!$(e.target).closest('.taxonomy-search-form').length) {
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
          // Down arrow
          case 40:
              e.preventDefault();
              if (!$highlighted.length) {
                  $suggestions.first().addClass('highlighted');
              } else {
                  $highlighted.removeClass('highlighted')
                             .next('.taxonomy-suggestion')
                             .addClass('highlighted');
              }
              break;

          // Up arrow
          case 38:
              e.preventDefault();
              if (!$highlighted.length) {
                  $suggestions.last().addClass('highlighted');
              } else {
                  $highlighted.removeClass('highlighted')
                             .prev('.taxonomy-suggestion')
                             .addClass('highlighted');
              }
              break;

          // Enter
          case 13:
              if ($highlighted.length) {
                  e.preventDefault();
                  window.location.href = $highlighted.find('a').attr('href');
              }
              break;
      }
  });
});