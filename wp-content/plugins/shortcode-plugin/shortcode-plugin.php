<?php


/**
 * Plugin Name: ShortCode Plugin
 * Description: This is the second plugin which gives some information about shortcodes
 * Author: Donsolo Khalifa
 * Version: 1.0
 * Author URI: https://onlinewebtutorblog.com
 * Plugin URI: https://example.com/hello-world
 */


add_shortcode("message", "sp_show_static_message"); //[message]

function sp_show_static_message()
{


    return "This is a simple shortcode message";
}



// parameterised shortcode

add_shortcode("student", "sp_handle_student_data");


function sp_handle_student_data($attributes)
{
    $attributes = shortcode_atts(array(
        "name" => "Default Student",
        "email" => "Default Email",
    ), $attributes, "student");

    return "<h3 style='color:blue;'>Student Data: Name - " . $attributes['name'] . ", Email - " . $attributes['email'] . "</h3>";
}
