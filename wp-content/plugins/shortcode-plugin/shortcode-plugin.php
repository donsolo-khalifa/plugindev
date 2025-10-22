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

// Short code for DB operation
// add_shortcode("list-posts", "sp_handle_list_posts");


function sp_handle_list_posts()
{

    global $wpdb;


    $table_prefix = $wpdb->prefix;

    $table_name = $table_prefix . "posts";


    $posts = $wpdb->get_results(
        "SELECT post_title from {$table_name} WHERE post_type = 'post' AND post_status = 'publish'"
    );

    if (count($posts) > 0) {
        $outputHtml = "<ul>";
        foreach ($posts as $post) {

            $outputHtml .= "<li>" . $post->post_title . "</li>";
        }

        $outputHtml .= "</ul>";

        return $outputHtml;
    }

    return "NO Post Found";
}


add_shortcode("list-posts", "sp_handle_list_posts_wp_query_class");


// function sp_handle_list_posts_wp_query_class($attributes)
// {

//     $attributes = shortcode_atts(array(
//         "number" => 5,
//     ), $attributes, "list-posts");


//     $number = intval($attributes["number"]);


//     $query = new  WP_Query(array(
//         "posts_per_page" => $number,
//         "post_status" => "publish",
//     ));

//     if ($query->have_posts()) {
//         $outputHtml = "<ul>";
//         while ($query->have_posts()) {
//             $query->the_post();
//             $outputHtml .= "<li> " . get_the_title() . "</li>";
//         }
//         $outputHtml .= "</ul>";


//         return $outputHtml;
//     }

//     return "No Post Found";
// }


 function sp_handle_list_posts_wp_query_class($attributes){

    $attributes = shortcode_atts(array(
        "number" => 5
    ), $attributes, "list-posts");

    $query = new WP_Query(array(
        "posts_per_page" => $attributes['number'],
        "post_status" => "publish"
    ));

    if($query->have_posts()){

        $outputHtml = '<ul>';
        while($query->have_posts()){
            $query->the_post();
            $outputHtml .= '<li class="my_class"><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>'; // Hello World
        }
        $outputHtml .= '</ul>';

        return $outputHtml;
    }

    return "No Post Found";
 }


// add_shortcode( 'list-posts', 'sp_handle_list_posts_wp_query_class' );

// function sp_handle_list_posts_wp_query_class( $attributes ) {
//     // Use the same shortcode tag here ('list-posts')
//     $atts = shortcode_atts( array(
//         'number' => 3,
//     ), $attributes, 'list-posts' );

//     // Ensure it's an integer
//     $number = intval( $atts['number'] );

//     // Correct arg name: posts_per_page (was post_per_page)
//     $query = new WP_Query( array(
//         'posts_per_page' => $number,
//         'post_status'    => 'publish',
//     ) );

//     if ( $query->have_posts() ) {
//         $output = '<ul>';
//         while ( $query->have_posts() ) {
//             $query->the_post();
//             // Escape the title for safety
//             $output .= '<li>' . esc_html( get_the_title() ) . '</li>';
//         }
//         $output .= '</ul>'; // close UL (was "<ul>" by mistake)

//         // Always reset global postdata after a custom query
//         wp_reset_postdata();

//         return $output;
//     }

//     wp_reset_postdata();
//     return '<p>No posts found.</p>';
// }
