<?php
if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu for settings
function shc_add_admin_menu() {
    add_options_page(
        'Simple Hit Counter',          // Page title
        'SHC Settings',                // Menu title
        'manage_options',              // Capability
        'shc-settings',                // Menu slug
        'shc_settings_page'            // Callback to render the page
    );
}
add_action('admin_menu', 'shc_add_admin_menu');

// Register settings and fields
function shc_register_settings() {
    // Register the settings group and option
    register_setting('shc_settings_group', 'shc_options', [
        'sanitize_callback' => 'shc_sanitize_options',
    ]);

    // Add a section to the settings page
    add_settings_section(
        'shc_main_section', // Section ID
        'SHC Settings',     // Section title
        null,               // Section callback
        'shc-settings'      // Page slug
    );

    // Add "Use Dedicated Table" field
    add_settings_field(
        'shc_dedicated_table',
        'Use Dedicated Table',
        'shc_render_checkbox',
        'shc-settings',
        'shc_main_section',
        [
            'id' => 'dedicated_table',
            'label' => 'Check to use a dedicated database table instead of post metadata.',
        ]
    );

    // Add "Keep Data on Plugin Deletion" field
    add_settings_field(
        'shc_keep_data',
        'Keep Data on Plugin Deletion',
        'shc_render_checkbox',
        'shc-settings',
        'shc_main_section',
        [
            'id' => 'keep_data',
            'label' => 'Check to retain plugin data after the plugin is deleted.',
        ]
    );
}
add_action('admin_init', 'shc_register_settings');

// Render the settings page
function shc_settings_page() {
    ?>
    <div class="wrap">
        <h1>Simple Hit Counter</h1>
        <hr>
        <form method="post" action="options.php">
            <?php
            settings_fields('shc_settings_group'); // Security and hidden fields
            do_settings_sections('shc-settings'); // Output all settings sections
            submit_button('Save Settings'); // Default "Save" button
            ?>
        </form>
    </div>
    <?php
}

// Render a checkbox field
function shc_render_checkbox($args) {
    $options = get_option('shc_options', ['dedicated_table' => false, 'keep_data' => false]);
    $checked = !empty($options[$args['id']]);
    ?>
    <input type="checkbox" name="shc_options[<?php echo esc_attr($args['id']); ?>]" value="1" <?php checked($checked, true); ?> />
    <label for="<?php echo esc_attr($args['id']); ?>"><?php echo esc_html($args['label']); ?></label>
    <?php
}

// Sanitize options
function shc_sanitize_options($options) {
    return [
        'dedicated_table' => isset($options['dedicated_table']) ? (bool)$options['dedicated_table'] : false,
        'keep_data' => isset($options['keep_data']) ? (bool)$options['keep_data'] : false,
    ];
}

// Handle migration logic on option update
function shc_handle_migration($old_value, $new_value) {
    if ($new_value['dedicated_table'] && !$old_value['dedicated_table']) {
        shc_migrate_meta_to_table();
    } elseif (!$new_value['dedicated_table'] && $old_value['dedicated_table']) {
        shc_migrate_table_to_meta();
    }
}
add_action('update_option_shc_options', 'shc_handle_migration', 10, 2);

// Migration from metadata to dedicated table
function shc_migrate_meta_to_table() {
    global $wpdb;

    shc_ensure_table_exists();

    $table_name = $wpdb->prefix . 'shc_hits';
    $meta_key = '_shc_page_views';

    // Fetch all posts with the metadata
    $posts = $wpdb->get_results($wpdb->prepare(
        "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value IS NOT NULL",
        $meta_key
    ));

    // Insert into the dedicated table
    foreach ($posts as $post) {
        $post_id = (int)$post->post_id;
        $hit_count = (int)$post->meta_value;

        $wpdb->query($wpdb->prepare(
            "INSERT INTO $table_name (post_id, hit_count)
             VALUES (%d, %d)
             ON DUPLICATE KEY UPDATE hit_count = %d",
            $post_id, $hit_count, $hit_count
        ));
    }

    // Delete the metadata field after successful migration
    // delete_post_meta($post_id, $meta_key);
}

// Migration from dedicated table to metadata
function shc_migrate_table_to_meta() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'shc_hits';

    // Fetch all records from the dedicated table
    $rows = $wpdb->get_results("SELECT post_id, hit_count FROM $table_name");

    // Update post metadata
    foreach ($rows as $row) {
        $post_id = (int)$row->post_id;
        $hit_count = (int)$row->hit_count;

        update_post_meta($post_id, '_shc_page_views', $hit_count);
    }
}

// Ensure the table exists
function shc_ensure_table_exists() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'shc_hits';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
        shc_create_table(); // Create the table if it doesn't exist
    }
}