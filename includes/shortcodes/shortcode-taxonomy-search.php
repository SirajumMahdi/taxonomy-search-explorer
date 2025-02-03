<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register shortcode
add_shortcode('tcse_search', 'taxonomy_search_shortcode');

function taxonomy_search_shortcode($atts) {
    // Start output buffering
    ob_start();

    // Initialize plugin class
    $plugin = new Taxonomy_Search();

    // Shortcode attributes
    $atts = shortcode_atts(array(
        'taxonomy'       => 'category',
        'terms_per_page' => 10,
        'show_terms'     => 'always', // Options: 'always', 'search', 'never'
    ), $atts);

    // Get messages for this taxonomy
    $messages = $plugin->get_messages($atts['taxonomy']);

    // Get current search query
    $search_query = '';
    if (isset($_GET['term_search']) && isset($_GET['taxonomy_search_nonce'])) {
        if (wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['taxonomy_search_nonce'])), 'taxonomy_search_nonce')) {
            $search_query = sanitize_text_field(wp_unslash($_GET['term_search']));
        }
    }

    // Display search form
    ?>
    <div class="taxonomy-search-form">
        <form method="get">
            <?php wp_nonce_field('taxonomy_search_nonce', 'taxonomy_search_nonce'); ?>
            <input 
                type="search" 
                id="term_search" 
                name="term_search" 
                placeholder="<?php echo esc_attr($messages['search_placeholder']); ?>" 
                value="<?php echo esc_attr($search_query); ?>"
                data-taxonomy="<?php echo esc_attr($atts['taxonomy']); ?>"
            />
            <button type="submit" class="taxonomy-search-submit">
                <span>Search</span>
            </button>
        </form>
        <div class="autocomplete-results" id="taxonomy-search-autocomplete" style="display: none;"></div>
    </div>
    <?php

    // Determine if we should show terms
    $should_show_terms = false;
    switch($atts['show_terms']) {
        case 'always':
            $should_show_terms = true;
            break;
        case 'search':
            $should_show_terms = !empty($search_query);
            break;
        case 'never':
            $should_show_terms = false;
            break;
    }

    // Display results if needed
    if ($should_show_terms) {
        if (!class_exists('Taxonomy_Search')) {
            echo '<p>' . esc_html($messages['plugin_error']) . '</p>';
        } else {
            $plugin->display_search_results($atts['taxonomy'], $atts['terms_per_page'], $search_query);
        }
    }

    return ob_get_clean();
}
