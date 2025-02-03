<?php
/**
 * Main plugin class
 */
class Taxonomy_Search {

    public function __construct() {
        // Enqueue assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));

        // Include the AJAX handling file
        require_once plugin_dir_path(__FILE__) . './ajax-handling.php';
    }

    /**
     * Get human-readable taxonomy label
     */
    private function get_taxonomy_label($taxonomy) {
        $taxonomy_obj = get_taxonomy($taxonomy);
        if ($taxonomy_obj) {
            return $taxonomy_obj->labels->name ?: $taxonomy_obj->labels->singular_name;
        }
        return __('Items', 'category-search-explorer');
    }

    /**
     * Get taxonomy-specific messages
     */
    public function get_messages($taxonomy) {
        $tax_label = $this->get_taxonomy_label($taxonomy);
        
        return array(
            'no_results' => sprintf(
                /* translators: %s: Taxonomy name (e.g., Categories, Tags, Publishers) */
                __('No %s found.', 'category-search-explorer'),
                strtolower($tax_label)
            ),
            'invalid_taxonomy' => sprintf(
                /* translators: %s: Taxonomy name */
                __('Invalid %s type.', 'category-search-explorer'),
                strtolower($tax_label)
            ),
            'plugin_error' => sprintf(
                /* translators: %s: Taxonomy name */
                __('%s search functionality unavailable.', 'category-search-explorer'),
                $tax_label
            ),
            'search_placeholder' => sprintf(
                /* translators: %s: Taxonomy name */
                __('Search %s...', 'category-search-explorer'),
                strtolower($tax_label)
            ),
            'loading' => sprintf(
                /* translators: %s: Taxonomy name */
                __('Searching %s...', 'category-search-explorer'),
                strtolower($tax_label)
            )
        );
    }

    /**
     * Display search results
     */
    public function display_search_results($taxonomy, $terms_per_page, $search_query) {
        // Get messages for this taxonomy
        $messages = $this->get_messages($taxonomy);
        
        // Validate and sanitize inputs
        $taxonomy = sanitize_key($taxonomy);
        $terms_per_page = absint($terms_per_page) ?: 10; // Default to 10 if invalid
        $search_query = sanitize_text_field($search_query);

        // Verify taxonomy exists
        if (!taxonomy_exists($taxonomy)) {
            echo '<p>' . esc_html($messages['invalid_taxonomy']) . '</p>';
            return;
        }

        // Current page number
        $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;

        // Calculate the offset
        $offset = ($paged - 1) * $terms_per_page;

        // Query arguments
        $args = array(
            'taxonomy'    => $taxonomy,
            'hide_empty'  => false,
            'number'      => $terms_per_page,
            'offset'      => $offset,
            'orderby'     => 'name',
            'order'       => 'ASC',
        );

        // Add search parameter only if there's a search query
        if (!empty($search_query)) {
            $args['search'] = $search_query;
        }

        // Get terms
        $terms = get_terms($args);
        
        // Get total terms count with the same search criteria
        $total_terms_args = array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'search'     => $search_query,
            'fields'     => 'count', // Retrieve only the count
        );
        $total_terms = get_terms($total_terms_args);


        // Display results
        if (!is_wp_error($terms) && !empty($terms)) {
            echo '<div class="tax-lists">';
            foreach ($terms as $term) {
                $term_name = $term->name;
                $term_url = get_term_link($term);
                
                if (!is_wp_error($term_url)) {
                    echo '<div class="tax-item">';
                    echo '<div class="term-name">';
                    echo '<h4><a href="' . esc_url($term_url) . '">' . esc_html($term_name) . '</a></h4>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            echo '</div>';

            // Pagination
            if ($total_terms > $terms_per_page) {
                echo '<div class="pagination">';
                $pagination_links = paginate_links(array(
                    'base'      => add_query_arg('paged', '%#%'),
                    'format'    => '?paged=%#%',
                    'current'   => $paged,
                    'total'     => ceil($total_terms / $terms_per_page),
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'add_args'  => array(
                        'term_search' => urlencode($search_query)
                    ),
                ));
                echo wp_kses_post($pagination_links);
                echo '</div>';
            }
        } else {
            echo '<p>' . esc_html($messages['no_results']) . '</p>';
        }
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Enqueue CSS
        wp_enqueue_style(
            'taxonomy-search-style',
            plugin_dir_url(__FILE__) . '../assets/css/style.css',
            array(),
            '1.0.0'
        );

        // Enqueue JS
        wp_enqueue_script(
            'taxonomy-search-script',
            plugin_dir_url(__FILE__) . '../assets/js/script.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Localize script with messages
        $default_messages = $this->get_messages('category'); // Default messages
        wp_localize_script(
            'taxonomy-search-script',
            'taxonomySearch',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('taxonomy_search_nonce'),
                'messages' => $default_messages
            )
        );
    }
}