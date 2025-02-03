<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// AJAX callbacks for search
add_action('wp_ajax_taxonomy_search_autocomplete', 'taxonomy_search_autocomplete_callback');
add_action('wp_ajax_nopriv_taxonomy_search_autocomplete', 'taxonomy_search_autocomplete_callback');

function taxonomy_search_autocomplete_callback() {
    // Check nonce
    if (!check_ajax_referer('taxonomy_search_nonce', 'nonce', false)) {
        wp_send_json_error('Invalid nonce');
    }

    // Get and sanitize input
    $search_query = isset($_POST['search_query']) ? sanitize_text_field(wp_unslash($_POST['search_query'])) : '';
    $taxonomy = isset($_POST['taxonomy']) ? sanitize_key(wp_unslash($_POST['taxonomy'])) : '';

    // Verify taxonomy exists
    if (!taxonomy_exists($taxonomy)) {
        wp_send_json_error('Invalid taxonomy');
    }

    // Query terms
    $args = array(
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'number'     => 10,
        'search'     => $search_query,
        'orderby'    => 'name',
        'order'      => 'ASC',
    );

    $terms = get_terms($args);

    // Handle errors in fetching terms
    if (is_wp_error($terms)) {
        wp_send_json_error($terms->get_error_message());
    }

    // Prepare results
    $results = array();
    if (!empty($terms)) {
        foreach ($terms as $term) {
            $results[] = array(
                'name'  => esc_html($term->name),
                'url'   => esc_url(get_term_link($term)),
                'count' => (int) $term->count
            );
        }
    }

    wp_send_json_success($results);
}
