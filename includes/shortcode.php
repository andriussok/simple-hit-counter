<?php

if (!defined('ABSPATH')) {
  exit;
}

// Shortcode to display the counter
function shc_display_counter($atts) {
  if (!is_singular() || shc_is_bot()) {
    return;
  }

  // Check for 'freeze' in both positional and key-value formats
  $freeze = in_array('freeze', $atts, true) || array_key_exists('freeze', $atts);

  $post_id = get_the_ID();
  
  // Increment only if not already incremented in this request
  static $already_incremented = false;
  if (!$already_incremented && !$freeze) {
      $shc_options = get_option('shc_options', [
          'dedicated_table' => false,
      ]);
      if ($shc_options['dedicated_table']) {
          shc_increment_hit_count_table($post_id);
      } else {
          shc_increment_hit_count_meta($post_id);
      }
      $already_incremented = true;
  }

  $count = shc_get_hit_count($post_id);
  // $faded_count_str = str_repeat('8', strlen($count));
  // $faded_count = number_format($faded_count_str); // add thousands
  /* add data-fadedcount="<?php echo esc_attr($faded_count); ?>" */
  
  ob_start();
  ?>
  <div id="shc-counter" data-count="<?php echo esc_attr($count); ?>" >
    <span>Loading...</span>
  </div>
  <?php
  return ob_get_clean();
}
add_shortcode('simple_hit_counter', 'shc_display_counter');


function shc_get_hit_count($post_id) {
  $shc_options = get_option('shc_options', [
      'dedicated_table' => false,
  ]);

  if ($shc_options['dedicated_table']) {
      return shc_get_hit_count_from_table($post_id);
  } else {
      return shc_get_hit_count_from_meta($post_id);
  }
}