# Simple Hit Counter

A lightweight WordPress plugin that displays a page hit counter with dynamic animations and LED Digit7 style display.
<img src="https://github.com/andriussok/simple-hit-counter/blob/main/screenshot.png" width="300" />

## Features
- Page-specific hit counter: The counter increments each time the page is reloaded.
- Counts human visits only, ignoring bots with [CrawlerDetect](https://github.com/JayBizzle/Crawler-Detect).
- Dynamic animation using [CountUp.js](https://github.com/inorganik/CountUp.js).
- LED Digit7 style display using [DSEG7 Classic Font](https://github.com/keshikan/DSEG/).
- Store data in metafields table or in dedicated table


## Development

1. **Clone repo**
2. `cd` in to your repo
3. `composer install`
4. Do your changes
5. Build zip for WP, run `composer build`


## WP Installation

1. **Download Plugin**
   - Download the plugin as a `.zip` file.

2. **Upload to WordPress**
   - Navigate to **Plugins > Add New > Upload Plugin** in your WordPress dashboard.
   - Upload the `.zip` file and activate the plugin.

3. **Add Shortcode**
   - Use the `[simple_hit_counter]` shortcode anywhere on your page or post content where you want the counter to appear.