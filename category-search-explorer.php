<?php
/**
 * Plugin Name: Category Search Explorer
 * Plugin URI: https://sirajummahdi.com
 * Description: Category Search Explorer brings advanced search capabilities to all your WordPress categories and taxonomies.
 * Version: 1.0.0
 * Author: Sirajum Mahdi
 * Author URI: https://sirajummahdi.com
 * Text Domain: category-search-explorer
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CATEX_VERSION', '1.0.0');
define('CATEX_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CATEX_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include the main plugin class
require_once CATEX_PLUGIN_DIR . 'includes/class-catex-search.php';

// Include the shortcode files
require_once CATEX_PLUGIN_DIR . 'includes/shortcodes/shortcode-catex-search.php';

// Initialize the plugin
new CATEX_Search();
