<?php

/**
 *
 * Template Name: Download Template
 */
if( ! empty( $_POST['serial'] ) ) {
    $serial = $_POST['serial'];

    /**
     * Validating data against Serial Number
     */
    $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE meta_value ='$serial'" );
    if(count($result)== 0)
    {
        echo "<script type=\"text/javascript\">window.alert('No Record Found!!');
       window.location.href = 'download/';</script>";
#        // echo '<div class="fc-error">';
#        // echo 'No record found!!';
#        // echo '</div>';
    }
    else
    {
        $snum = reset($result)->order_item_id;
        $result = $wpdb->get_results( "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE order_item_id ='$snum'" );
        foreach($result as $data)
        {
            $order_id = $data->order_item_id;
            if($data->meta_key == 'Sender')
            $sender = $data->meta_value;
            if($data->meta_key == 'Receiver')
            $receiver = $data->meta_value;
            if($data->meta_key == 'Description')
            $description = $data->meta_value;
            if($data->meta_key == 'Serial Number')
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

        $order_details = wc_get_order_id_by_order_item_id( $order_id );
        $order_final_id = wc_get_order( $order_details );
        $status = $order_final_id->get_status();

        if($status == 'completed')
        {
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
                    $order_id = $data->order_item_id;
                    if($data->meta_key == 'Sender')
                    $sender = $data->meta_value;
                    if($data->meta_key == 'Receiver')
                    $receiver = $data->meta_value;
                    if($data->meta_key == 'Description')
                    $description = $data->meta_value;
                    if($data->meta_key == 'Serial Number')
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
        else
        {
            echo "<script type=\"text/javascript\">window.alert('Your order is under process for now!!');
            window.location.href = 'download/';</script>";
        }
    }
}

get_header(); ?>
<?php if(function_exists('pf_show_link')){echo pf_show_link();} ?>
<div class="fc-activate-wrap">
    <div class="main-template">
        <img class="main-img" src="https://theoneinamillionglobe.com/wp-content/uploads/2020/07/master-july-3-no-1.png" >
        <div class="content-template">
            <span class="main-name fc-receive fc-receive-left"><?php  if(isset($receiver)){echo $receiver;} else{echo 'David Bowie';} ?></span>
            <div>

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
            <h2>Please enter serial number to download: </h2>
            <input type="text" id="serial" name="serial" placeholder="Enter your serial number" value= "<?php  if(isset($serial)){echo $serial;} ?>" required/>
            <?php if(isset($serial)) { ?>
            <input type='button' class='button-default button-large' id='but_screenshot' value='Download' onclick='screenshot();'>
            <?php echo '<div class="fc-screenshot"> </div>'?>
            <?php }?>
            <input class="button-default button-large" <?php if(!empty($serial)){echo 'style=display:none';} ?> id="submit-button" type="submit" value="Submit"/>
        </form>
    </div>
</div>
<?php get_footer(); ?>