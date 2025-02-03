=== Category Search Explorer ===
Contributors: sirajummahdi
Tags: category search, categories, tags, woocommerce, custom taxonomy
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A powerful and user-friendly category search tool for WordPress. Perfect for sites with extensive categories, tags, or custom taxonomies.

== Description ==

Category Search Explorer brings advanced search capabilities to all your WordPress categories and taxonomies. Whether you're managing a blog with numerous categories, an eCommerce store with complex product categorization, or a custom site with specialized taxonomies, this plugin makes content organization and discovery seamless.

= Works With All WordPress Categories =

* **Blog Categories**: Easily search through your blog categories
* **Post Tags**: Quick access to all your post tags
* **WooCommerce Product Categories**: Navigate product categories efficiently
* **WooCommerce Product Tags**: Find product tags instantly
* **Custom Categories**: Works with any custom taxonomy in your theme or plugins

= Key Features =

* **Live Search**: Real-time category suggestions as you type
* **Smart Autocomplete**: Intelligent search with immediate results
* **Customizable Display**: Control how many results to show
* **Responsive Design**: Works perfectly on all devices
* **Developer Friendly**: Extensible architecture with filters and actions
* **User-Friendly Labels**: Automatically uses proper labels for each category type
* **Pagination Support**: Handle large numbers of categories efficiently
* **Accessibility Ready**: Built with WCAG guidelines in mind

= Perfect For =

* 📚 Blog sites with extensive categorization
* 🛍️ WooCommerce stores with multiple product categories
* 🏷️ Sites using tags for content organization
* 🎯 Custom post types with specialized categories
* 🔍 Any WordPress site needing improved category navigation

= Use Cases =

1. **Content-Rich Blogs**
   * Help readers find relevant categories quickly
   * Make tag exploration intuitive
   * Improve content discovery

2. **eCommerce Stores**
   * Quick product category navigation
   * Easy access to product tags
   * Better shopping experience

3. **Directory Websites**
   * Efficient browsing of business categories
   * Location-based category search
   * Custom classification systems

4. **Educational Platforms**
   * Course category exploration
   * Subject matter organization
   * Learning path discovery

= Implementation Examples =

**Basic Category Search**
`[tcse_search taxonomy="category"]`

**WooCommerce Product Categories**
`[tcse_search taxonomy="product_cat"]`

**Custom Category Search**
`[tcse_search taxonomy="your_custom_taxonomy"]`

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/taxonomy-search-explorer`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place the shortcode `[tcse_search]` in your posts, pages, or widgets

== Frequently Asked Questions ==

= Does this work with WooCommerce? =

Yes! The plugin seamlessly integrates with WooCommerce categories including product categories (product_cat) and product tags (product_tag).

= Can I use this with custom categories? =

Absolutely! The plugin works with any properly registered WordPress taxonomy. Just specify the taxonomy slug in the shortcode.

= How do I customize the appearance? =

The plugin includes customizable CSS classes and follows WordPress styling conventions. You can override styles in your theme's stylesheet.

= Is it translation-ready? =

Yes, the plugin is fully translatable and includes English as the default language.

== Screenshots ==

1. Default category search interface
2. WooCommerce product category search
3. Custom category search example
4. Mobile responsive view
5. Admin settings page

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release

== Usage Examples ==

= Basic Implementation =

Add category search to any page or post:
`[tcse_search]`

= WooCommerce Product Categories =

Add product category search:
`[tcse_search taxonomy="product_cat"]`

= Custom Number of Results =

Show 20 results per page:
`[tcse_search terms_per_page="20"]`

= Advanced Configuration =

Full configuration example:
`[tcse_search taxonomy="custom_taxonomy" terms_per_page="15" show_terms="always"]`

== Development ==

= GitHub Repository =
Contribute to the development at: https://github.com/SirajumMahdi/taxonomy-search-explorer

= Support =
For support questions, please use the WordPress.org plugin support forums.