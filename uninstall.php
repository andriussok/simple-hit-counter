<?php

// If uninstall is not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Uninstall function to clean up plugin data
if (defined('WP_UNINSTALL_PLUGIN')) {
  global $wpdb;

  $shc_options = get_option('shc_options', [
      'keep_data' => false,
  ]);

  if (!$shc_options['keep_data']) {
      
      // Delete custom table
      $table_name = $wpdb->prefix . 'shc_hits';
      if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name) {
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
      }    

      // Delete all post meta data related to the plugin
      $wpdb->query(
          $wpdb->prepare(
              "DELETE FROM {$wpdb->postmeta} WHERE meta_key = %s",
              SHC_META_KEY
          )
      );
  }

  // Delete options table entry
  delete_option('shc_options');
}

