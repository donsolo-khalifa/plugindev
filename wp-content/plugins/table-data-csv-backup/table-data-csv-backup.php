<?php

/**
 * Plugin Name: CSV Data Backup
 * Description: This plugin will export data from a Database table to a CSV file
 * Author: Donsolo Khalifa
 * Version: 1.0
 * Plugin URI: https://example.com/csv-data-exporter
 * Author URI: https://onlinewebtutorblog.com
 */


// Add menu
//note to self spacing inside the "" can cause an error
define("TDCB_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

add_action("admin_menu", "tdcb_create_admin_menu");

function tdcb_create_admin_menu()
{
    add_menu_page("CSV Data Backup Plugin", "CSV Data Backup", "manage_options", "csv-data-backup", "tdcb_export_form", "dashicons-database-export", 8);
}

function tdcb_export_form()
{
    ob_start();


    include_once TDCB_PLUGIN_DIR_PATH . "/template/table_data_backup.php";

    // Read buffer
    $template = ob_get_contents();

    // Clean buffer
    ob_end_clean();

    echo $template;
}


add_action("admin_init", "tdcb_handle_form_export");

function tdcb_handle_form_export()
{
    if (isset($_POST['tdcb_export_button'])) {
        global $wpdb;
        $table_prefix = $wpdb->prefix;
        $table_name = $table_prefix . "students_data";

        $students = $wpdb->get_results("SELECT * FROM {$table_name}", ARRAY_A);
        // echo "<pre>";
        // var_dump($students);
        // die;

        if (empty($students)) {
            # code...
        }

        $filename = "students_data_" . time() . ".csv";
        header("Content-Type: text/csv; charset=utf-8;");
        header("Content-Disposition: attachment; filename=" . $filename . ";");


        $output = fopen("php://output", "w");
        fputcsv($output, array_keys($students[0]));
        
        foreach ($students as $student) {

            fputcsv($output, $student);
        }

        fclose($output);
        exit;
    }
}
