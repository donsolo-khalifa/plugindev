<?php
/**
 * Plugin Name: CSV Data Uploader
 * Description: This plugin will uploads CSV data to DB Table
 * Author: Donsolo Khalifa
 * Version: 1.0
 * Plugin URI: https://example.com/csv-data-uploader
 * Author URI: https://onlinewebtutorblog.com
 */

define("CDU_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

add_shortcode("csv-data-uploader","cdu_display_frorm_data");

function cdu_display_frorm_data() {
    return "<h2>this is a csv data uploader</h2>";
}