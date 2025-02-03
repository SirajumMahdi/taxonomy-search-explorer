<?php
/**
 * Plugin Name: Category Search Explorer
 * Plugin URI: https://sirajummahdi.com
 * Description: Category Search Explorer brings advanced search capabilities to all your WordPress categories and taxonomies. Whether you're managing a blog with numerous categories, an eCommerce store with complex product categorization, or a custom site with specialized taxonomies, this plugin makes content organization and discovery seamless.
 * Version: 1.0.0
 * Author: Sirajum Mahdi
 * Author URI: https://sirajummahdi.com
 * Text Domain: taxonomy-search-explorer
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * This plugin enables advanced search functionality for all WordPress categories including:
 * - Blog Categories and Tags
 * - WooCommerce Product Categories and Tags
 * - Custom Categories (Custom Taxonomies)
 * 
 * Features:
 * - AJAX-powered live search
 * - Intelligent autocomplete
 * - Responsive design
 * - Accessibility ready
 * - Developer friendly
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include the main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/class-taxonomy-search.php';

// Include the shortcode files
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/shortcode-taxonomy-search.php';

// Initialize the plugin
new Taxonomy_Search();