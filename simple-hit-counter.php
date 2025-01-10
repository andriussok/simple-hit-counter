<?php
/*
Plugin Name: Simple Hit Counter
Description: A lightweight WordPress plugin to display a page-specific hit counter.
Version: 1.0.1
Requires at least: 6.5
Author: Andrius Sok
Author URI: https://andriuss.lt
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Retrieve options or set defaults
$shc_options = get_option('shc_options', [
  'dedicated_table' => false,
  'keep_data' => false,
]);


// Enqueue necessary scripts and styles
function shc_enqueue_scripts() {
  wp_enqueue_script_module('shc', plugin_dir_url(__FILE__) . 'assets/js/simple-hit-counter.js', [], '1.0.0', true);
  wp_enqueue_style('shc', plugin_dir_url(__FILE__) . 'assets/styles.css');
}
add_action('wp_enqueue_scripts', 'shc_enqueue_scripts');


// Bot detection using CrawlerDetect
use Jaybizzle\CrawlerDetect\CrawlerDetect;

function shc_is_bot() {
  require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

  $CrawlerDetect = new CrawlerDetect();

  return $CrawlerDetect->isCrawler();
}



// Include the appropriate storage handling file
if ($shc_options['dedicated_table']) {
  require_once plugin_dir_path(__FILE__) . 'includes/dedicated-table.php';
} else {
  require_once plugin_dir_path(__FILE__) . 'includes/meta-storage.php';
}

// Include files
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';



// init dedicated table
function shc_create_table() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'shc_hits';
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
      id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      post_id BIGINT(20) UNSIGNED NOT NULL,
      hit_count BIGINT(20) UNSIGNED DEFAULT 0,
      PRIMARY KEY (id),
      UNIQUE KEY post_id (post_id)
  ) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}
register_activation_hook(__FILE__, 'shc_create_table');



function shc_uninstall() {
  include_once(plugin_dir_path(__FILE__) . 'uninstall.php');
}
register_uninstall_hook(__FILE__, 'shc_uninstall');



function shc_add_settings_link($links) {
  
  $settings_url = menu_page_url('shc-settings', false);
  $settings_link = '<a href="' . esc_url($settings_url) . '">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'shc_add_settings_link');