<?php

/**
 *
 * Template Name: Activate Template
 */
global $wpdb;
$checkpub = '';
if( ! empty( $_POST['private'] ) && ! empty( $_POST['serial'] )) {

    $serial = $_POST['serial'];
    $private = $_POST['private'];
    $address = $_POST['map'];



    /**
     * managing lat and long
     */
    if(!empty($address)) {

    $city = str_replace(" ", "+", $address);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$city&key=AIzaSyBHBiCw6nr4NvmgV86pza-iVWLio1qi_-k";
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
    }
    $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE meta_value ='$serial'" );

    if(count($result)== 0)
    {
        echo "<script type=\"text/javascript\">window.alert('No Record Found!!');
        window.location.href = 'activate/';</script>";
    }
    else
    {
        $snum = reset($result)->order_item_id;
        $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE order_item_id ='$snum'" );

        foreach($result as $data)
        {
            if($data->meta_key == 'Sender')
            $sender = $data->meta_value;
            if($data->meta_key == 'Receiver')
            $receiver = $data->meta_value;
            if($data->meta_key == 'Description')
            $description = $data->meta_value;
            if($data->meta_key == 'Serial Num')
            $serial_activate = $data->meta_value;
            if(! empty($address)){
                if($data->meta_key == 'lat')
                $lat = $latitude;
                if($data->meta_key == 'long')
                $long = $longitude;
            }
            if($data->meta_key == 'bool_hidden')
            $bool_hidden = 'false';
            if($data->meta_key == 'bool_hidden')
            $checkpub = $data->meta_value;
        }

        if( ! empty( $_POST['serial'] ) && ! empty( $_POST['private'] ) ) {

            $serial = $_POST['serial'];
            $private = $_POST['private'];

            /**
             * updating the boolean value in database
             */
            $data = [
                'meta_value' => $bool_hidden
            ];

            $where = [
                'order_item_id' => $snum,
                'meta_key' => 'bool_hidden',
            ];

            global $wpdb;
            $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
            $wpdb->update($table_name, $data, $where, $format = null, $where_format = null);
            if(! empty($address)){

            /**
             * updating lat
             */
            $data = [
                'meta_value' => $lat
            ];

            $where = [
                'order_item_id' => $snum,
                'meta_key' => 'lat',
            ];

            $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
            $wpdb->update($table_name, $data, $where, $format = null, $where_format = null);


            /**
             * updating long
             */
            $data = [
                'meta_value' => $long
            ];

            $where = [
                'order_item_id' => $snum,
                'meta_key' => 'long',
            ];

            $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
            $wpdb->update($table_name, $data, $where, $format = null, $where_format = null);
            }

            $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE order_item_id ='$snum'" );
            foreach($result as $data)
            {
                if($data->meta_key == 'Sender')
                $sender = $data->meta_value;
                if($data->meta_key == 'Receiver')
                $receiver = $data->meta_value;
                if($data->meta_key == 'Description')
                $description = $data->meta_value;
                if($data->meta_key == 'Serial Num')
                $serial_activate = $data->meta_value;
                if($data->meta_key == 'lat')
                $lat = $data->meta_value;
                if($data->meta_key == 'long')
                $long = $data->meta_value;
                if($data->meta_key == 'bool_hidden')
                $bool_hidden = 'false';
                if($data->meta_key == 'bool_hidden')
                $checkpub = $data->meta_value;
            }
        }

    }
}


else if( ! empty( $_POST['public'] ) && ! empty( $_POST['serial'] ) && ! empty( $_POST['map'] )) {
    $serial = $_POST['serial'];
    $public = $_POST['public'];
    $address = $_POST['map'];

    /**
     * managing lat and long
     */
    $city = str_replace(" ", "+", $address);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$city&key=AIzaSyBHBiCw6nr4NvmgV86pza-iVWLio1qi_-k";
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

    $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE meta_value ='$serial'" );
    if(count($result)== 0)
    {
        echo "<script type=\"text/javascript\">window.alert('No Record Found!!');
        window.location.href = 'activate/';</script>";
    }
    else
    {
        $snum = reset($result)->order_item_id;
        $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE order_item_id ='$snum'" );
        foreach($result as $data)
        {
            if($data->meta_key == 'Sender')
            $sender = $data->meta_value;
            if($data->meta_key == 'Receiver')
            $receiver = $data->meta_value;
            if($data->meta_key == 'Description')
            $description = $data->meta_value;
            if($data->meta_key == 'Serial Num')
            $serial_activate = $data->meta_value;
            if($data->meta_key == 'lat')
            $lat = $latitude;
            if($data->meta_key == 'long')
            $long = $longitude;
            if($data->meta_key == 'bool_hidden')
            $bool_hidden = 'true';
            if($data->meta_key == 'bool_hidden')
            $checkpub = $data->meta_value;
        }

        if( ! empty( $_POST['serial'] ) && ! empty( $_POST['public'] ) && ! empty( $_POST['map'] )) {

            $serial = $_POST['serial'];
            $public = $_POST['public'];

            /**
             * updating the boolean value in database
             */
            $data = [
                'meta_value' => $bool_hidden
            ];

            $where = [
                'order_item_id' => $snum,
                'meta_key' => 'bool_hidden',
            ];

            global $wpdb;
            $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
            $wpdb->update($table_name, $data, $where, $format = null, $where_format = null);

            /**
             * updating lat
             */
            $data = [
                'meta_value' => $lat
            ];

            $where = [
                'order_item_id' => $snum,
                'meta_key' => 'lat',
            ];

            $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
            $wpdb->update($table_name, $data, $where, $format = null, $where_format = null);


            /**
             * updating long
             */
            $data = [
                'meta_value' => $long
            ];

            $where = [
                'order_item_id' => $snum,
                'meta_key' => 'long',
            ];

            $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
            $wpdb->update($table_name, $data, $where, $format = null, $where_format = null);

            $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE order_item_id ='$snum'" );
            foreach($result as $data)
            {
                if($data->meta_key == 'Sender')
                $sender = $data->meta_value;
                if($data->meta_key == 'Receiver')
                $receiver = $data->meta_value;
                if($data->meta_key == 'Description')
                $description = $data->meta_value;
                if($data->meta_key == 'Serial Num')
                $serial_activate = $data->meta_value;
                if($data->meta_key == 'lat')
                $lat = $data->meta_value;
                if($data->meta_key == 'long')
                $long = $data->meta_value;
                if($data->meta_key == 'bool_hidden')
                $bool_hidden = 'false';
                if($data->meta_key == 'bool_hidden')
                $checkpub = $data->meta_value;
            }
        }

    }
}

else if( ! empty( $_POST['serial'] ) ) {
    $serial = $_POST['serial'];

    /**
     * Validating data against Serial Number
     */
    $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE meta_value ='$serial'" );
    if(count($result)== 0)
    {
        echo "<script type=\"text/javascript\">window.alert('No Record Found!!');
       window.location.href = 'activate/';</script>";
    }
    else
    {
        $snum = reset($result)->order_item_id;
        $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE order_item_id ='$snum'" );
        foreach($result as $data)
        {
            if($data->meta_key == 'Sender')
            $sender = $data->meta_value;
            if($data->meta_key == 'Receiver')
            $receiver = $data->meta_value;
            if($data->meta_key == 'Description')
            $description = $data->meta_value;
            if($data->meta_key == 'Serial Num')
            $serial_activate = $data->meta_value;
            if($data->meta_key == 'lat')
            $lat = $data->meta_value;
            if($data->meta_key == 'long')
            $long = $data->meta_value;
            if($data->meta_key == 'bool_hidden')
            $bool_hidden = 'true';
            if($data->meta_key == 'bool_hidden')
            $checkpub = $data->meta_value;
        }

        if( ! empty( $_POST['serial'] ) && ! empty( $_POST['public'] ) ) {

            $serial = $_POST['serial'];
            $public = $_POST['public'];

            /**
             * updating the boolean value in database
             */
            $data = [
                'meta_value' => $bool_hidden
            ];

            $where = [
                'order_item_id' => $snum,
                'meta_key' => 'bool_hidden',
            ];

            global $wpdb;
            $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
            $wpdb->update($table_name, $data, $where, $format = null, $where_format = null);

            $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE order_item_id ='$snum'" );
            foreach($result as $data)
            {
                if($data->meta_key == 'Sender')
                $sender = $data->meta_value;
                if($data->meta_key == 'Receiver')
                $receiver = $data->meta_value;
                if($data->meta_key == 'Description')
                $description = $data->meta_value;
                if($data->meta_key == 'Serial Num')
                $serial_activate = $data->meta_value;
                if($data->meta_key == 'lat')
                $lat = $data->meta_value;
                if($data->meta_key == 'long')
                $long = $data->meta_value;
                if($data->meta_key == 'bool_hidden')
                $bool_hidden = 'true';
                if($data->meta_key == 'bool_hidden')
                $checkpub = $data->meta_value;
            }

        }
    }
}

get_header(); ?>
<div class="fc-activate-wrap">
    <div class="main-template">
        <img class="main-img" src="https://shop.theoneinamillionglobe.com/wp-content/uploads/2020/07/Master-pdf-with-no-logo-July-8th-2020.png" >
        <div class="content-template">
            <span class="main-name fc-receive fc-receive-left"><?php  if(isset($receiver)){echo $receiver;} else{echo 'David Bowie';} ?></span>
            <div>
            <img src="https://shop.theoneinamillionglobe.com/wp-content/uploads/2020/07/logo-mid.png">
            </div>
            <p class="desc"><?php if(isset($description)){echo $description;} else{echo 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley.';} ?>
            </p>
            <div class="sender-info">
                <span class="fc_sender-title">Presented by</span><span class="main-name fc-send fc-send-left"><?php if(isset($sender)){echo $sender;} else{echo 'Ziggy stardust';} ?></span>
            </div>
        </div>
    </div>
    <div class="fc-form-wrap">
        <form method="post" class="fc-custom-checkbox">
            <h2>Please enter serial number: </h2>
            <input type="text" id="serial" name="serial" placeholder="Enter your serial number" value= "<?php  if(isset($serial)){echo $serial;} ?>" required/>
            <input class="button-default button-large" onclick="removeRequired()" <?php if(!empty($serial)){echo 'style=display:none';} ?> id="submit-button" type="submit" value="Submit"/>
            <?php if( !empty( $_POST['serial'] ) && strlen(trim($_POST['serial'])) > 0)
            { ?>
            <div class="fc-checbox-act"><input id="private" class="check" name="private" type="checkbox" <?php if( $checkpub == 'false'){echo 'checked';}?> /><label for="private">Private</label></div>
            <div class="fc-checbox-act"><input id="public" class="check" name="public" type="checkbox" <?php  if( $checkpub == 'true'){echo 'checked';}?> /><label for="public">Public</label></div>
            <div class="fc-checbox-act"><input id="map"  class="map" name ="map" type="text" placeholder="Change your location here"/></div>
            <div class="fc-checbox-act"><input class="button-default button-large" id="save-alert" type="button" value="Save"/></div>
            <div class="fc-saveConfirm">
                <div class="fc-saveForm-wrap">
                    <div class="popup-logo">
                        <img src="https://shop.theoneinamillionglobe.com/wp-content/uploads/2020/03/logo1.png">
                    </div>
                    <p>Your option has been saved </p>
                    <input type="submit"  class="button-default button-large" onclick="removeRequiredSave()" value="Close"/></div>
            </div>
            <?php }?>
        </form>

        <?php if( !empty( $_POST['serial'] ) && strlen(trim($_POST['serial'])) > 0)
        { ?>
        <div class="fc-social-share">
        <p>Share Now</p>
            <div class="fusion-social-networks-wrapper">
            <?php echo do_shortcode('[Sassy_Social_Share]'); ?>
                </div>
        </div>
        <?php }?>
    </div>
</div>
<?php get_footer(); ?>
