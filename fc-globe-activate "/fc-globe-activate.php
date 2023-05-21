<?php
/**
 * Plugin Name: Template Activation For Globe
 * Description: A plugin for showing purchased templates on Glob.
 * Version: 1.0.0
 * Author: Forum Cube
 * License: GPL2
 */

/**
 * Enqueue files
 *
 * @return void
 */
function fc_style1() {
    wp_enqueue_style( 'fc-style1', plugins_url( '/stylesheet1.css', __FILE__ ) );
    wp_enqueue_script( 'fc-script1', plugins_url( '/script1.js', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'fc_style1' );

/**
 * Custom end point API Dev for Globe
 */
add_action( 'rest_api_init', 'register_order_route');

function register_order_route() {

    register_rest_route( 'order-api', 'order-item', array(
        'methods' => 'GET',
        'callback' => 'my_order_meta',
        )
    );
}

function my_order_meta() {

    global $wpdb;
    $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
    $result = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'bool_hidden' AND meta_value != 'false'" );
    $finalIds = array();
    foreach($result as $id) {
        array_push($finalIds, $id->order_item_id);
    }
    $finalIds = implode(", ",$finalIds);
    $result = $wpdb->get_results( "SELECT meta_key, meta_value FROM $table_name WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories') AND order_item_id IN ($finalIds)" );

    $i = 0;
    $temp = array();
    $response = array();
    foreach($result as $results) {
        $temp[$results->meta_key] = $results->meta_value;
        $i++;
        if($i%6 == 0) {
            array_push($response, $temp);
            $temp = array();
        }
    }

    return $response;
}


add_action( 'page_template', 'act_temp');
function act_temp( $page_template )
{
    if ( is_page( 'activate' ) ) {
        $page_template = dirname( __FILE__ ) . '/act-temp.php';
    }
    return $page_template;
}

