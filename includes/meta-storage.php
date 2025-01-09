<?php
// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

function shc_increment_hit_count_meta($post_id) {
    $count = get_post_meta($post_id, '_shc_page_views', true);
    $count = $count ? $count + 1 : 1;
    update_post_meta($post_id, '_shc_page_views', $count);
}

function shc_get_hit_count_from_meta($post_id) {
    $count = get_post_meta($post_id, '_shc_page_views', true);
    return $count ? (int) $count : 0;
}