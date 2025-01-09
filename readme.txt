=== Simple Hit Counter ===
Version: 1.0.0
Requires at least: 6.5
Author: Andrius Sok
Author URI: https://andriuss.lt
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

=== Description ===

A lightweight WordPress plugin that displays a page hit counter with dynamic animations and LED digit7 style display.

## Features

* Page-specific hit counter: The counter increments each time the page is reloaded.
* Counts human visits only, ignoring bots with [CrawlerDetect](https://github.com/JayBizzle/Crawler-Detect).
* Dynamic animation using [CountUp.js](https://github.com/inorganik/CountUp.js).
* LED Digit7 style display using [DSEG7 Classic Font](https://github.com/keshikan/DSEG/).
* Store data in metafields table or in dedicated table.

= Installation =

1. Upload the plugin files to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Use the `[simple_hit_counter]` shortcode anywhere on your page or post to content where you want the counter to appear.