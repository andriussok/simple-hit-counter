=== Simple Hit Counter ===
Version: 1.0.1
Requires at least: 6.5
Author: Andrius Sok
Author URI: https://andriuss.lt
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

=== Description ===

A lightweight WordPress plugin to display a page-specific hit counter.

## Features

* Page-specific hit counter: Increments with each page reload.
* Counts human visits only: Bots are ignored using [CrawlerDetect](https://github.com/JayBizzle/Crawler-Detect).
* Dynamic animation: Displays smooth count-up effects with [CountUp.js](https://github.com/inorganik/CountUp.js).
* LED Digit7 style display: Utilises the [DSEG7 Classic Font](https://github.com/keshikan/DSEG/).
* Flexible data storage: Choose between WordPress meta fields or a dedicated database table.



= Installation =

1. Upload the plugin files to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Use the [simple_hit_counter] shortcode anywhere in your page or post content to display the counter.
4. To prevent the counter from incrementing (e.g., during edit mode), use the attribute `[simple_hit_counter freeze]`.

Happy Counting.