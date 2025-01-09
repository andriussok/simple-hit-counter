<?php
if (!defined('ABSPATH')) {
  exit;
}

// Add admin menu for settings
function shc_add_admin_menu() {
  add_options_page(
      'Simple Hit Counter Settings',
      'SHC Settings',
      'manage_options',
      'shc-settings',
      'shc_settings_page'
  );
}
add_action('admin_menu', 'shc_add_admin_menu');


function shc_settings_page() {
  $shc_options = get_option('shc_options', [
      'dedicated_table' => false,
      'keep_data' => false,
  ]);

  // Store the previous state of the dedicated_table option
  $was_dedicated_table = $shc_options['dedicated_table'];


  if (isset($_POST['shc_save_settings'])) {
    check_admin_referer('shc_settings_save');

    $was_dedicated_table = $shc_options['dedicated_table'];
    $shc_options['dedicated_table'] = isset($_POST['shc_dedicated_table']);
    $shc_options['keep_data'] = isset($_POST['shc_keep_data']);
    update_option('shc_options', $shc_options);

    // Check if the storage method has changed
    if ($shc_options['dedicated_table'] && !$was_dedicated_table) {
        // Migrate from metadata to the dedicated table
        shc_migrate_meta_to_table();
    } elseif (!$shc_options['dedicated_table'] && $was_dedicated_table) {
        // Migrate from the dedicated table to metadata
        shc_migrate_table_to_meta();
    }

    echo '<div class="updated"><p>Settings saved.</p></div>';
  }

  ?>
  <div class="wrap">
      <h1>Simple Hit Counter Settings</h1>
      <form method="post" action="">
          <?php wp_nonce_field('shc_settings_save'); ?>
          <input type="hidden" name="shc_save_settings" value="1">
          <table class="form-table">
              <tr valign="top">
                  <th scope="row">Use Dedicated Table</th>
                  <td>
                      <input type="checkbox" name="shc_dedicated_table" value="1" <?php checked($shc_options['dedicated_table'], true); ?> />
                      <label for="shc_dedicated_table">Check to use a dedicated database table instead of post metadata.</label>
                  </td>
              </tr>
              <tr valign="top">
                  <th scope="row">Keep Data on Plugin Deletion</th>
                  <td>
                      <input type="checkbox" name="shc_keep_data" value="1" <?php checked($shc_options['keep_data'], true); ?> />
                      <label for="shc_keep_data">Check to retain plugin data after the plugin is deleted.</label>
                  </td>
              </tr>
          </table>
          <?php submit_button('Save Settings'); ?>
      </form>
  </div>
  <?php
}




function shc_migrate_meta_to_table() {
  global $wpdb;

  shc_ensure_table_exists();

  $table_name = $wpdb->prefix . 'shc_hits';

  // Fetch all posts with the `_shc_page_views` metadata
  $meta_key = '_shc_page_views';
  $posts = $wpdb->get_results($wpdb->prepare(
      "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value IS NOT NULL",
      $meta_key
  ));

  // Insert into the dedicated table
  foreach ($posts as $post) {
      $post_id = (int) $post->post_id;
      $hit_count = (int) $post->meta_value;

      $wpdb->query($wpdb->prepare(
          "INSERT INTO $table_name (post_id, hit_count)
          VALUES (%d, %d)
          ON DUPLICATE KEY UPDATE hit_count = %d",
          $post_id, $hit_count, $hit_count
      ));
  }
}


function shc_migrate_table_to_meta() {
  global $wpdb;

  $table_name = $wpdb->prefix . 'shc_hits';

  // Fetch all records from the dedicated table
  $rows = $wpdb->get_results("SELECT post_id, hit_count FROM $table_name");

  // Update post metadata
  foreach ($rows as $row) {
      $post_id = (int) $row->post_id;
      $hit_count = (int) $row->hit_count;

      update_post_meta($post_id, '_shc_page_views', $hit_count);
  }
}


function shc_ensure_table_exists() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'shc_hits';

  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
      shc_create_table(); // Create the table if it doesn't exist
  }
}
