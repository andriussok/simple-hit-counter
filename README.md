# Simple Hit Counter

A lightweight WordPress plugin to display a page-specific hit counter.

<img src="https://github.com/andriussok/simple-hit-counter/blob/main/screenshot.png" width="300" />

## Features

- **Page-specific hit counter**: Increments with each page reload.
- **Counts human visits only**: Bots are ignored using [CrawlerDetect](https://github.com/JayBizzle/Crawler-Detect).
- **Dynamic animation**: Displays smooth count-up effects with [CountUp.js](https://github.com/inorganik/CountUp.js).
- **LED Digit7 style display**: Utilises the [DSEG7 Classic Font](https://github.com/keshikan/DSEG/).
- **Flexible data storage**: Choose between WordPress meta fields or a dedicated database table.


## Development

1. Clone the repository.
2. Navigate into the directory: `cd your-repo`.
3. Install dependencies: `composer install`.
4. Make your code changes.
5. Build a `.zip` file for WordPress: `composer build`.

## WP Installation

1. **Download Plugin**
   - Download the `.zip` file from [releases](https://github.com/andriussok/simple-hit-counter/releases/), or generate one from this repository.

2. **Upload to WordPress**
   - Go to **Plugins > Add New > Upload Plugin** in your WordPress dashboard.
   - Upload the `.zip` file and activate the plugin.

3. **Add Shortcode**
   - Insert `[simple_hit_counter]` anywhere in your page or post content to display the counter.
   - Use `[simple_hit_counter freeze]` to prevent the counter from incrementing (e.g., during edit mode).
