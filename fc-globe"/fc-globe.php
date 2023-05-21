<?php
/**
 * Plugin Name: Globe Integration
 * Description: A plugin for showing custom fields on product pages.
 * Version: 1.0
 * Author: Forum Cube
 * License: GPL2
 */

function fc_style() {
    wp_enqueue_style( 'fc-style', plugins_url( '/stylesheet.css', __FILE__ ) );
    wp_enqueue_script( 'fc-script', plugins_url( '/script.js', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'fc_style' );

add_action( 'init', 'cyb_enqueue_styles' );
function cyb_enqueue_styles() {
    $user = wp_get_current_user();
    if( ! empty( $user ) && count( array_intersect( [ "editor"], (array) $user->roles ) ) ) {
        wp_enqueue_style( 'fc-script-editor', plugins_url( '/editor.css', __FILE__ ) );
    }
}


/**
 * Display custom text field
 * @since 1.0.0
 */
function fc_create_custom_field() {
    $args = array(
        'id'            => 'custom_text_field_title',
        'label'         => __( 'Sender', 'cfwc' ),
        'class'         => 'cfwc-custom-field',
        'desc_tip'      => true,
        'description'   => __( 'Enter the title of your custom text field.', 'ctwc' ),
    );
    woocommerce_wp_text_input( $args );

    $args = array(
        'id'            => 'custom_text_field_title3',
        'label'         => __( 'Description', 'cfwc3' ),
        'class'         => 'cfwc-custom-field3',
        'desc_tip'      => true,
        'description'   => __( 'Enter the title of your custom text field.', 'ctwc3' ),
    );
    woocommerce_wp_text_input( $args );

    $args = array(
        'id'            => 'custom_text_field_title2',
        'label'         => __( 'Receiver', 'cfwc2' ),
        'class'         => 'cfwc-custom-field2',
        'desc_tip'      => true,
        'description'   => __( 'Enter the title of your custom text field.', 'ctwc2' ),
    );
    woocommerce_wp_text_input( $args );

    $args = array(
        'value' => '',
        'class' => 'cfwc-custom-field4',
        'id' => 'custom_text_field_title4'
    );

    woocommerce_wp_hidden_input( $args );

    woocommerce_wp_hidden_input( $args );

    $args = array(
        'value' => '',
        'class' => 'cfwc-custom-field5',
        'id' => 'custom_text_field_title5'
    );

    woocommerce_wp_hidden_input( $args );

    $args = array(
        'value' => '',
        'class' => 'cfwc-custom-field6',
        'id' => 'custom_text_field_title6'
    );

    woocommerce_wp_hidden_input( $args );

    $args = array(
        'value' => '',
        'class' => 'cfwc-custom-field7',
        'id' => 'custom_text_field_title7'
    );

    woocommerce_wp_hidden_input( $args );
}
add_action( 'woocommerce_product_options_general_product_data', 'fc_create_custom_field' );




/**
 * Save custom field
 * @since 1.0.0
 */
function fc_save_custom_field( $post_id ) {
    $product = wc_get_product( $post_id );
    $title = isset( $_POST['custom_text_field_title'] ) ? $_POST['custom_text_field_title'] : '';
    $title2 = isset( $_POST['custom_text_field_title2'] ) ? $_POST['custom_text_field_title2'] : '';
    $title3 = isset( $_POST['custom_text_field_title3'] ) ? $_POST['custom_text_field_title3'] : '';
    $title4 = isset( $_POST['custom_text_field_title4'] ) ? $_POST['custom_text_field_title4'] : '';
    $title5 = isset( $_POST['custom_text_field_title5'] ) ? $_POST['custom_text_field_title5'] : '';
    $title6 = isset( $_POST['custom_text_field_title6'] ) ? $_POST['custom_text_field_title6'] : '';
    $title7 = isset( $_POST['custom_text_field_title7'] ) ? $_POST['custom_text_field_title7'] : '';
    $product->update_meta_data( 'custom_text_field_title', sanitize_text_field( $title ) );
    $product->update_meta_data( 'custom_text_field_title2', sanitize_text_field( $title2 ) );
    $product->update_meta_data( 'custom_text_field_title3', sanitize_text_field( $title3 ) );
    $product->update_meta_data( 'custom_text_field_title4', sanitize_text_field( $title4 ) );
    $product->update_meta_data( 'custom_text_field_title5', sanitize_text_field( $title5 ) );
    $product->update_meta_data( 'custom_text_field_title6', sanitize_text_field( $title6 ) );
    $product->update_meta_data( 'custom_text_field_title7', sanitize_text_field( $title7 ) );
    $product->save();
}
add_action( 'woocommerce_process_product_meta', 'fc_save_custom_field' );




/**
 * Display custom field on the front end
 * @since 1.0.0
 */
function fc_display_custom_field() {
    global $post;
    // Check for the custom field value
    $product = wc_get_product( $post->ID );
    $title = $product->get_meta( 'custom_text_field_title' );
    $title2 = $product->get_meta( 'custom_text_field_title2' );
    $title3 = $product->get_meta( 'custom_text_field_title3' );
    $title4 = $product->get_meta( 'custom_text_field_title4' );
    $title5 = $product->get_meta( 'custom_text_field_title5' );
    $title6 = $product->get_meta( 'custom_text_field_title6' );
    $title7 = $product->get_meta( 'custom_text_field_title7' );
    if( $title || $title2 || $title3 || $title4 || $title5 || $title6 || $title7 ) {
        // Only display our field if we've got a value for the field title
        printf(
            '<div class="cfwc-custom-field cfwc-custom-field-wrapper2"><label for="cfwc-title-field2">%s</label><input type="text" id="cfwc-title-field2" class="fc-receive" name="cfwc-title-field2" value=""></div>',
            esc_html( $title2 )
        );

        printf(
            '<div class="cfwc-custom-field cfwc-custom-field-wrapper3"><label for="cfwc-title-field3">%s</label><textarea id="cfwc-title-field3" rows="4" cols="50" class="fc-desc" name="cfwc-title-field3" value="" maxlength="400"></textarea></div></div>',
            esc_html( $title3 )
        );

         printf(
            '<div class="cfwc-custom-field cfwc-custom-field-wrapper"><label for="cfwc-title-field">%s</label><input type="text" id="cfwc-title-field" class="fc-send" name="cfwc-title-field" value=""></div>',
            esc_html( $title )
        );

        printf(
            '<div class="cfwc-custom-field cfwc-custom-field-wrapper4"><label for="cfwc-title-field4">%s</label><input type="hidden" id="playerscards" name="cfwc-title-field4"></div>',
            esc_html( $title4 )
        );

        printf(
            '<div class="cfwc-custom-field cfwc-custom-field-wrapper5"><label for="cfwc-title-field5">%s</label><input type="hidden" id="cfwc-title-field5" name="cfwc-title-field5"></div>',
            esc_html( $title5 )
        );

        printf(
            '<div class="cfwc-custom-field cfwc-custom-field-wrapper6"><label for="cfwc-title-field6">%s</label><input type="hidden" id="cfwc-title-field6" name="cfwc-title-field6"></div>',
            esc_html( $title6 )
        );

        printf(
            '<div class="cfwc-custom-field cfwc-custom-field-wrapper7"><label for="cfwc-title-field7">%s</label><input type="hidden" id="cfwc-title-field7" name="cfwc-title-field7"></div>',
            esc_html( $title7 )
        );
    }
}
add_action( 'woocommerce_before_add_to_cart_button', 'fc_display_custom_field' );





/**
 * Validate the text field
 * @since 1.0.0
 * @param Array         $passed                    Validation status.
 * @param Integer   $product_id     Product ID.
 * @param Boolean      $quantity           Quantity
 */
function fc_validate_custom_field( $passed, $product_id, $quantity ) {
    if( empty( $_POST['cfwc-title-field']) || empty( $_POST['cfwc-title-field2']) || empty( $_POST['cfwc-title-field3'])) {
        // Fails validation
        $passed = false;
        wc_add_notice( __( 'Please enter a value into the text field', 'cfwc' ), 'error' );
    }
    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'fc_validate_custom_field', 10, 3 );




/**
 * Add the text field as item data to the cart object
 * @since 1.0.0
 * @param Array         $cart_item_data Cart item meta data.
 * @param Integer   $product_id     Product ID.
 * @param Integer   $variation_id   Variation ID.
 * @param Boolean      $quantity           Quantity
 */
function fc_add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {

    if( ! empty( $_POST['cfwc-title-field'] ) && ! empty( $_POST['cfwc-title-field2'] ) &&  ! empty( $_POST['cfwc-title-field3'] ) ) {

        $cart_item_data['title_field'] = $_POST['cfwc-title-field'];
        $cart_item_data['title_field2'] = $_POST['cfwc-title-field2'];
        $cart_item_data['title_field3'] = $_POST['cfwc-title-field3'];
        $cart_item_data['title_field4'] = $_POST['cfwc-title-field4'];
        $cart_item_data['title_field5'] = $_POST['cfwc-title-field5'];
        $cart_item_data['title_field6'] = $_POST['cfwc-title-field6'];
        $cart_item_data['title_field7'] = "true";
        $product = wc_get_product( $product_id );
    }
    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'fc_add_custom_field_item_data', 10, 4 );


/**
 * Display the custom field value in the cart
 * @since 1.0.0
 */
function fc_cart_item_name( $name, $cart_item, $cart_item_key ) {
    if( isset( $cart_item['title_field'] ) && isset( $cart_item['title_field2'] ) && isset( $cart_item['title_field3'] ) ) {
        $name .= sprintf(
            '<br>Sender:<p>%s</p>',
            esc_html( $cart_item['title_field'] )
        );
        $name .= sprintf(
            '<br>Receiver:<p>%s</p>',
            esc_html( $cart_item['title_field2'] )
        );
        $name .= sprintf(
            '<br>Description:<p>%s</p>',
            esc_html( $cart_item['title_field3'] )
        );

        $name .= sprintf(
            '<br>Serial:<p>Serial Number: %s</p>',
            esc_html( $cart_item['title_field4'] )
        );
    }

    return $name;
}
add_filter( 'woocommerce_cart_item_name', 'fc_cart_item_name', 10, 3 );





/**
 * Add custom field to order object
 */
function fc_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
    foreach( $item as $cart_item_key=>$values ) {
        if( isset( $values['title_field'] ) ) {
            $item->add_meta_data( __( 'Sender', 'cfwc' ), $values['title_field'], true );
            $item->add_meta_data( __( 'Receiver', 'cfwc2' ), $values['title_field2'], true );
            $item->add_meta_data( __( 'Description', 'cfwc3' ), $values['title_field3'], true );
            $item->add_meta_data( __( 'SerialNum', 'cfwc3' ), $values['title_field4'], true );
            $item->add_meta_data( __( 'lat', 'cfwc4' ), $values['title_field5'], true );
            $item->add_meta_data( __( 'long', 'cfwc4' ), $values['title_field6'], true );
            $item->add_meta_data( __( 'bool_hidden', 'cfwc7' ), $values['title_field7'], true );
        }
    }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'fc_add_custom_data_to_order', 10, 4 );



function fc_button_pre() {
    global $product;
      echo '<div class="preview-btn"><button type="button" onclick="dealcard()" class="preview-template button">Preview</button></div>';
}
add_action( 'woocommerce_after_add_to_cart_button', 'fc_button_pre', 30 );



/**
 * Register a custom menu page.
 */
function fc_custom_menu_page(){
    add_menu_page(
        __( 'Download CSV', 'textdomain' ),
        'Download CSV',
        'manage_options',
        'custompage',
        'fc_menu_page'
    );
}
add_action( 'admin_menu', 'fc_custom_menu_page' );

/**
 * Display a custom menu page
 */
function fc_menu_page(){
    global $wpdb;
    if(isset($_GET['orders'])) {
        $date= $_GET['orders'];
    }
    else
    {
        $date = date("Y-m-d");
    }

    $selected_date = '';
    $table_name = $wpdb->prefix . "posts";
    $result = $wpdb->get_results( "SELECT * FROM $table_name WHERE post_type='shop_order' and DATE(post_date)='$date'" );
    foreach($result as $d)
    {
       $selected_date = $d->post_date;
    }

    $orders = '<table class="fc-csv-table" style="border: 1px solid #000;" class="table table-bordered">
        <tr>
            <th style="border: 1px solid #000;">Sender</th>
            <th style="border: 1px solid #000;">Receiver</th>
            <th style="border: 1px solid #000;"">Description</th>
            <th style="border: 1px solid #000;">Serial Number</th>
        </tr>

    ';
    foreach($result as $order)
    {
        $o = wc_get_order( $order->ID );
        $details = $o->get_items();
        $item = reset($details);

        if( $item->get_meta( 'Sender', true )) {
            $orders .= '<tr>';
        }

        $orders .= '
            <td style="border: 1px solid #000;">'.$item->get_meta( 'Sender', true ).'</td>
            <td style="border: 1px solid #000;">'.$item->get_meta( 'Receiver', true ).'</td>
            <td style="border: 1px solid #000;">'.$item->get_meta( 'Description', true ).'</td>
            <td style="border: 1px solid #000;">'.$item->get_meta( 'SerialNum', true ).'</td>
        ';
    }

    if(!isset($item)) {
        echo 'No RECORD Found!';
        echo '<br><br>';
        echo '<form action="'.admin_url().'admin.php?page=custompage&download=1">
                <input type="hidden" name="page" value="custompage"/>
                <label for="date">Select Date:</label>
                <input type="date" id="fc-orders" name="orders">
                <input type="submit">
                </form>
            ';
        exit();
    }

    if( $item->get_meta( 'Sender', true )) {
        $orders .= '</tr>';
    }

    $orders .= '</table>';
    echo $orders;

    if(isset($_GET['download'])) {
        fc_csv_menu_page();
    }
    $current_date = date("Y-m-d", strtotime($selected_date));
    echo '<a class="fc-csv-export" href="'.admin_url().'admin.php?page=custompage&download=1&date='.$current_date.'">Export CSV</a><br><br>';
    echo '<form action="'.admin_url().'admin.php?page=custompage">
            <input type="hidden" name="page" value="custompage"/>
            <label for="date">Select Date:</label>
            <input type="date" id="fc-orders" name="orders" value="'. $current_date .'"></<input>
            <input type="submit">
        </form>
    ';
}


function fc_csv_menu_page(){
    global $wpdb;
    $header = ['Sender','Receiver','Description','Serial Number'];
    ob_end_clean();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=orders.csv');

    $file = fopen('php://output', 'w');
    fputcsv($file, $header);
    if(isset($_GET['date'])) {
        $date= $_GET['date'];
    }
    else
    {
        $date = date("Y-m-d");
    }
    $table_name = $wpdb->prefix . "posts";
    $result = $wpdb->get_results( "SELECT * FROM $table_name WHERE post_type='shop_order' and DATE(post_date)='$date'" );

    foreach($result as $order){
        $o = wc_get_order( $order->ID );
        $details = $o->get_items();
        $item = reset($details);

        $data = [$item->get_meta( 'Sender', true ),$item->get_meta( 'Receiver', true ),$item->get_meta( 'Description', true ),$item->get_meta( 'SerialNum', true )];
        fputcsv($file , $data);
    }
    exit;
}

/**
 * shortcode to show order details on page
 *
 * @param [type] $atts
 * @return void
 */
function fc_front_order( $atts ){

    global $wpdb;
    if(isset($_REQUEST['fc-csc'])) {
        $date= $_POST['fc-orders'];
    }
    else
    {
        $date = date("Y-m-d");
    }

    $selected_date = '';
    $table_name = $wpdb->prefix . "posts";
    $result = $wpdb->get_results( "SELECT * FROM $table_name WHERE post_type='shop_order' and DATE(post_date)='$date'" );
    foreach($result as $d)
    {
       $selected_date = $d->post_date;
    }
    $orders = '<table class="fc-csv-form" class="table table-bordered">
        <tr>
            <th style="border: 1px solid #000;">Sender</th>
            <th style="border: 1px solid #000;">Receiver</th>
            <th style="border: 1px solid #000;">Description</th>
            <th style="border: 1px solid #000;">Serial Number</th>
        </tr>

    ';
    foreach($result as $order)
    {
        $o = wc_get_order( $order->ID );
        $details = $o->get_items();
        $item = reset($details);

        if( $item->get_meta( 'Sender', true )) {
            $orders .= '<tr>';
        }

        $orders .= '
            <td style="border: 1px solid #000;">'.$item->get_meta( 'Sender', true ).'</td>
            <td style="border: 1px solid #000;">'.$item->get_meta( 'Receiver', true ).'</td>
            <td style="border: 1px solid #000;">'.$item->get_meta( 'Description', true ).'</td>
            <td style="border: 1px solid #000;">'.$item->get_meta( 'SerialNum', true ).'</td>
        ';
    }

    if(!isset($item)) {
        echo 'No RECORD Found!';
        echo '<br><br>';
        echo '<form method="POST">
                <label for="date">Select Date:</label>
                <input type="date" id="fc-orders" name="fc-orders">
                <input type="submit" name="fc-csc">
                </form>
            ';
        exit();
    }

    if( $item->get_meta( 'Sender', true )) {
        $orders .= '</tr>';
    }

    $orders .= '</table>';
    echo $orders;

    if(isset($_GET['download'])) {
        fc_csv_menu_page();
    }

    $current_date = date("Y-m-d", strtotime($selected_date));
    echo '<a class="fc-csv-export" href="'.admin_url().'admin.php?page=custompage&download=1&date='.$current_date.'">Export CSV</a><br><br>';
    echo '<form action="'.admin_url().'admin.php?page=custompage">
            <input type="hidden" name="page" value="custompage"/>
            <label for="date">Select Date:</label>
            <input type="date" id="fc-orders" name="orders" value="'. $current_date .'"></<input>
            <input type="submit">
        </form>
    ';
}
add_shortcode( 'fc_csv_orders_front', 'fc_front_order' );



/**
 * lat and long management
 */
add_action( 'woocommerce_thankyou', 'wc_send_order_to_mypage' );
function wc_send_order_to_mypage( $order_id ) {

    /**
     * getting order's address
     */

    $order = wc_get_order( $order_id );
    $order_data = $order->get_data();
    $order_billing_address_1 = $order_data['billing']['address_1'];
    $order_billing_address_2 = $order_data['billing']['address_2'];
    $order_billing_city = $order_data['billing']['city'];
    $order_billing_state = $order_data['billing']['state'];
    $order_billing_postcode = $order_data['billing']['postcode'];
    $order_billing_country = $order_data['billing']['country'];


    /**
     * managing lat and long
     */
    $street_address = str_replace(" ", "+", $order_billing_address_1);
    $city = str_replace(" ", "+", $order_billing_city);
    $state = str_replace(" ", "+", $order_billing_state);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$street_address,+$city,+$state&key=AIzaSyBHBiCw6nr4NvmgV86pza-iVWLio1qi_-k";
    $google_api_response = wp_remote_get( $url );

    $results = json_decode( $google_api_response['body'] );
    $results = (array) $results;
    $status = $results["status"];
    $location_all_fields = (array) $results["results"][0];
    $location_geometry = (array) $location_all_fields["geometry"];
    $location_lat_long = (array) $location_geometry["location"];

    echo "<!-- GEOCODE RESPONSE " ;
    var_dump( $location_lat_long );
    echo " -->";

    if( $status == 'OK'){
        $latitude = $location_lat_long["lat"];
        $longitude = $location_lat_long["lng"];
    }else{
        $latitude = '';
        $longitude = '';
    }

   $return = array(
        'latitude'  => $latitude,
        'longitude' => $longitude
    );

    
    foreach ($order->get_items() as $item_id => $item )
    {
        $item_data = $item->get_data();
        $order_item_id = $item_data['id'];
    }
    

    $data = [
        'meta_value' => $latitude
    ];

    $where = [
        'order_item_id' => $order_item_id,
        'meta_key' => 'lat',
    ];

    global $wpdb;
    $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
    $wpdb->update($table_name, $data, $where, $format = null, $where_format = null);

    $data = [
        'meta_value' => $longitude
    ];

    $where = [
        'order_item_id' => $order_item_id,
        'meta_key' => 'long',
    ];
    $wpdb->update($table_name, $data, $where, $format = null, $where_format = null);
}

/**
 * overriding WC order template
 */
add_filter( 'woocommerce_locate_template', 'woocommerce_locate_order_template', 10, 3 );
function getting_plugin_path() {
    // getting the absolute path to this plugin directory
    return untrailingslashit( plugin_dir_path( __FILE__ ) );
}

function woocommerce_locate_order_template( $template, $template_name, $template_path ) {
    global $woocommerce;

    $_template = $template;
    if ( ! $template_path ) {
        $template_path = $woocommerce->template_url;
    }

    $plugin_path = getting_plugin_path() . '/woocommerce/';
    $template    = locate_template(

        array(
            $template_path . $template_name,
            $template_name
        )
    );

    // Modification: Get the template from this plugin, if it exists
    if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
        $template = $plugin_path . $template_name;
    }

    // Use default template
    if ( ! $template ) {
        $template = $_template;
    }

    return $template;
}

/**
 * adding download template
 */
add_action( 'page_template', 'download_temp');
function download_temp( $page_template )
{
    if ( is_page( 'download' ) ) {
        $page_template = dirname( __FILE__ ) . '/download-temp.php';
    }
    return $page_template;
}


/**
 * API Endpoints
 */
include( plugin_dir_path( __FILE__ ) . 'api-endpoints.php');