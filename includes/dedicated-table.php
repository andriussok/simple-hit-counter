<?php
if (!defined('ABSPATH')) {
  exit;
}

function shc_increment_hit_count_table($post_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'shc_hits';

    $wpdb->query(
        $wpdb->prepare(
            "INSERT INTO $table_name (post_id, hit_count)
            VALUES (%d, 1)
            ON DUPLICATE KEY UPDATE hit_count = hit_count + 1",
            $post_id
        )
    );
}

function shc_get_hit_count_from_table($post_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'shc_hits';
    return (int) $wpdb->get_var($wpdb->prepare("SELECT hit_count FROM $table_name WHERE post_id = %d", $post_id));
}