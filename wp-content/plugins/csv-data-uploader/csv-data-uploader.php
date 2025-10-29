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

add_shortcode("csv-data-uploader", "cdu_display_form_data");

function cdu_display_form_data()
{

    // Start PHP buffer

    ob_start();

    include_once CDU_PLUGIN_DIR_PATH . "/template/cdu_form.php";

    // Read buffer
    $template = ob_get_contents();

    // Clean buffer
    ob_end_clean();

    return $template;
}


// DB Table create on plugin activation
register_activation_hook(__FILE__, "cdu_create_table");

function cdu_create_table()
{

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $table_name = $table_prefix . "students_data";

    $table_collate = $wpdb->get_charset_collate();

    $sql_command = "
        CREATE TABLE `" . $table_name . "` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) DEFAULT NULL,
        `email` varchar(50) DEFAULT NULL,
        `age` int(5) DEFAULT NULL,
        `phone` varchar(30) DEFAULT NULL,
        `photo` varchar(120) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) " . $table_collate . "
    ";

    // required to perform database queries the function is dbDelta()
    require_once(ABSPATH . "/wp-admin/includes/upgrade.php");

    dbDelta($sql_command);
}

// to add script.js file
add_action("wp_enqueue_scripts", "cdu_add_script_file");


function cdu_add_script_file()
{

    // unique identifiyer of script first , source path second, dependencies third
    wp_enqueue_script("cdu-script-js", plugin_dir_url(__FILE__) . "assets/script.js", array("jquery"));

    // creates a url to hit the ajax url to submit the form data
    wp_localize_script("cdu-script-js", "cdu_object", array(
        "ajax_url" => admin_url("admin-ajax.php")
    ));
}

// Capture Ajax request
// add_action("wp_ajax_{action_name}");
add_action("wp_ajax_cdu_submit_form_data", "cdu_ajax_handler");

// to allow cdu_ajax_handler() function to work despite login status
// add_action("wp_ajax_nopriv_{action_name}");
add_action("wp_ajax_nopriv_cdu_submit_form_data", "cdu_ajax_handler");



// will only work if user is logged in
function cdu_ajax_handler()
{

    if ($_FILES['csv_data_file']) {
        $csvFile = $_FILES['csv_data_file']['tmp_name'];
        $handle = fopen($csvFile, "r");

        global $wpdb;
        $table_name = $wpdb->prefix."students_data";
        if ($handle) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !==FALSE) {
                if($row == 0){
                    $row++;
                    continue;
                }

                $wpdb->insert($table_name, array(
                    "name"=>$data[1],
                    "email"=>$data[2],
                    "age"=>$data[3],
                    "phone"=>$data[4],
                    "photo"=>$data[5],
                ));
            }

            fclose($handle);
            echo json_encode([
                "status"=>1,
                "message"=>"Data Uploaded successfully",
            ]);
        }
    } else {
        echo json_encode(array(
            "status" => 0,
            "message" => "No File Found"
        ));
    }



    exit;
}
