<?php

/**
 * custom end point API for showing dots and clusters on globe
 */
add_action( 'rest_api_init', 'distance_calculation' );

function distance_calculation() {

	register_rest_route( 'map-api', 'distance-item', array(
			'methods'  => 'GET',
			'callback' => 'map_distance_data',
		)
	);
}

function map_distance_data() {

	global $wpdb;
	$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result     = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'bool_hidden'" );
	$finalIds   = array();
	foreach ( $result as $id ) {
		array_push( $finalIds, $id->order_item_id );
	}

//	$items_table = $wpdb->prefix . "woocommerce_order_items";

	$finalIds = implode( ", ", $finalIds );

//	$result = $wpdb->get_results( "SELECT itemTable.order_id, meta_key, meta_value FROM $table_name as metaTable
//				LEFT JOIN $items_table as itemTable ON itemTable.order_item_id = metaTable.order_item_id
//				WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories', 'bool_hidden')
//				AND metaTable.order_item_id IN ($finalIds)
//");

//	foreach ( $ordersSet as $order ) {
//		$temp[ $results->meta_key ] = $results->meta_value;
//		$i ++;
//		if ( $i % 7 == 0 ) {
//			if ( $temp['lat'] && $temp['long'] ) {
//				$temp['lng'] = $temp['long'];
//				array_push( $response, $temp );
//			}
//
//			$temp = array();
//		}
//	}


	// $distances = array();
	// $latSet = wp_remote_get('https://my.api.mockaroo.com/test.json?key=d09fe310');
	// $clusters = json_decode($latSet['body'], true);
//	$clusters = $response;
//	$ignorePoints = [];
//	foreach($clusters as $dataKey => $data)
//	{
//		if(in_array($dataKey, $ignorePoints)) continue;
//
//		//For getting point 1 on clusters
//		$lat1 = $data['lat'];
//		$long1 = $data['long'];
//		$points = [];
//		foreach($clusters as $innerKey => $innerPoint) {
//			if($innerKey == $dataKey) continue;
//
//			$lat2 = $innerPoint['lat'];
//			$long2 = $innerPoint['long'];
//
//			$radius = 1;
//			$theta = $long1 - $long2;
//			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
//			$dist = acos($dist);
//			$dist = rad2deg($dist);
//			$miles = $dist * 60 * 1.1515;
//			$unit = strtoupper($miles);
//
//			$distance = $unit;
//			if($distance <= $radius && !$clusters[$innerKey]['points'])
//			{
//				// array_push($distances, $temp);
////                $temp = array();
//				$ignorePoints[] = $innerKey;
//				$points[] = $clusters[$innerKey];
//				unset($clusters[$innerKey]);
//			}
//		}
//
//		if($points) {
//			$points[] = $clusters[$dataKey];
//			$clusters[$dataKey]['points'] = $points;
//		}
//	}
//	$final = ['points' => $response, 'clusters' => $clusters];

	return prepareResponse( getOrders($finalIds) );
}

/**
 * Endpoint for categories search (clusters)
 */
add_action( 'rest_api_init', 'register_search_cluster_route' );
function register_search_cluster_route() {

	register_rest_route( 'search-cluster-api', 'search-item', array(
			'methods'  => 'GET',
			'callback' => 'search_cluster_meta',
		)
	);
}

function search_cluster_meta() {

	$categories = $_REQUEST['categories'];
	$final_cat  = preg_replace( "/[^a-zA-Z]/", "", $categories );
	if ( isset( $_REQUEST['categories'] ) ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
		$result = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'categories' AND meta_value = '$final_cat'" );
		if(count($result) > 0)
        {
			$finalIds = array();
			foreach ( $result as $id ) {
				array_push( $finalIds, $id->order_item_id );
			}
			$finalIds = implode( ", ", $finalIds );
			//		$result   = $wpdb->get_results( "SELECT meta_key, meta_value FROM `wp_woocommerce_order_itemmeta` WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories', 'bool_hidden') AND order_item_id IN ($finalIds)" );

					//Test

			//		$i        = 0;
			//		$temp     = array();
			//		$response = array();
			//		foreach ( $result as $results ) {
			//			$temp[ $results->meta_key ] = $results->meta_value;
			//			$i ++;
			//			if ( $i % 7 == 0 ) {
			//				if ( $temp['lat'] && $temp['long'] ) {
			//					$temp['lng'] = $temp['long'];
			//					unset( $temp['long'] );
			//					array_push( $response, $temp );
			//				}
			//
			//				$temp = array();
			//			}
			//		}

					// $distances = array();
					// $latSet = wp_remote_get('https://my.api.mockaroo.com/test.json?key=d09fe310');
					// $clusters = json_decode($latSet['body'], true);
			//		$clusters = $response;
			//		$ignorePoints = [];
			//		foreach($clusters as $dataKey => $data)
			//		{
			//			if(in_array($dataKey, $ignorePoints)) continue;
			//
			//			//For getting point 1 on clusters
			//			$lat1 = $data['lat'];
			//			$long1 = $data['long'];
			//			$points = [];
			//			foreach($clusters as $innerKey => $innerPoint) {
			//				if($innerKey == $dataKey) continue;
			//
			//				$lat2 = $innerPoint['lat'];
			//				$long2 = $innerPoint['long'];
			//
			//				$radius = 1;
			//				$theta = $long1 - $long2;
			//				$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			//				$dist = acos($dist);
			//				$dist = rad2deg($dist);
			//				$miles = $dist * 60 * 1.1515;
			//				$unit = strtoupper($miles);
			//
			//				$distance = $unit;
			//				if($distance <= $radius && !$clusters[$innerKey]['points'])
			//				{
			//					// array_push($distances, $temp);
			////                $temp = array();
			//					$ignorePoints[] = $innerKey;
			//					$points[] = $clusters[$innerKey];
			//					unset($clusters[$innerKey]);
			//				}
			//			}
			//
			//			if($points) {
			//				$points[] = $clusters[$dataKey];
			//				$clusters[$dataKey]['points'] = $points;
			//			}
			//		}
			//		$final = ['points' => $response, 'clusters' => $clusters];

			return prepareResponse( getOrders($finalIds) );
		}
		else
        {
            return array();
        }
	}
}

function getOrders($finalIds) {
	global $wpdb;
	$items_table = $wpdb->prefix . "woocommerce_order_items";
	$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result = $wpdb->get_results( "SELECT itemTable.order_id, meta_key, meta_value FROM $table_name as metaTable
				LEFT JOIN $items_table as itemTable ON itemTable.order_item_id = metaTable.order_item_id
				WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories', 'bool_hidden')
				AND metaTable.order_item_id IN ($finalIds)
		");

	$ordersSet = [];
	foreach ( $result as $meta_item ) {
		if($meta_item->meta_key == 'long') {
			$ordersSet[$meta_item->order_id][ 'lng' ] = $meta_item->meta_value;
		}
		$ordersSet[$meta_item->order_id][ $meta_item->meta_key ] = $meta_item->meta_value;
	}

	return $ordersSet;
}

/**
 * Custom end point API for search
 */
add_action( 'rest_api_init', 'register_search_route' );
function register_search_route() {

	register_rest_route( 'search-api', 'search-item', array(
			'methods'  => 'GET',
			'callback' => 'my_search_meta',
		)
	);
}

function my_search_meta() {

	$categories = $_REQUEST['categories'];
	$lat        = $_REQUEST['lat'];
	$long       = $_REQUEST['long'];
	$final_cat  = preg_replace( "/[^a-zA-Z]/", "", $categories );
	if ( isset( $_REQUEST['categories'] ) && isset( $_REQUEST['lat'] ) && isset( $_REQUEST['lat'] ) ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
		$result     = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'categories' AND meta_value = '$final_cat'" );
		$result     = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'lat' AND meta_value = '$lat'" );
		$result     = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'long' AND meta_value = '$long'" );

		$finalIds = array();
		foreach ( $result as $id ) {
			array_push( $finalIds, $id->order_item_id );
		}
		$finalIds = implode( ", ", $finalIds );
		$result   = $wpdb->get_results( "SELECT meta_key, meta_value FROM $table_name WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories') AND order_item_id IN ($finalIds)" );

		$i        = 0;
		$temp     = array();
		$response = array();
		foreach ( $result as $results ) {
			$temp[ $results->meta_key ] = $results->meta_value;
			$i ++;
			if ( $i % 6 == 0 ) {
				array_push( $response, $temp );
				$temp = array();
			}
		}

		return $response;
	} elseif ( isset( $_REQUEST['categories'] ) ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
		$result     = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'categories' AND meta_value = '$final_cat'" );
		if(count($result) > 0)
        {
			$finalIds = array();
			foreach ( $result as $id ) {
				array_push( $finalIds, $id->order_item_id );
			}
			$finalIds = implode( ", ", $finalIds );
			$result   = $wpdb->get_results( "SELECT meta_key, meta_value FROM $table_name WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories', 'bool_hidden') AND order_item_id IN ($finalIds)" );

			$i        = 0;
			$temp     = array();
			$response = array();
			foreach ( $result as $results ) {
				$temp[ $results->meta_key ] = $results->meta_value;
				$i ++;
				if ( $i % 7 == 0 ) {
					array_push( $response, $temp );
					$temp = array();
				}
			}

			return $response;
		}
		
	} elseif ( isset( $_REQUEST['lat'] ) && isset( $_REQUEST['long'] ) ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
		$result     = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'lat' AND meta_value = '$lat'" );
		$result     = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'long' AND meta_value = '$long'" );

		$finalIds = array();
		foreach ( $result as $id ) {
			array_push( $finalIds, $id->order_item_id );
		}
		$finalIds = implode( ", ", $finalIds );

		$result = $wpdb->get_results( "SELECT meta_key, meta_value FROM $table_name WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories') AND order_item_id IN ($finalIds)" );

		$i        = 0;
		$temp     = array();
		$response = array();
		foreach ( $result as $results ) {
			$temp[ $results->meta_key ] = $results->meta_value;
			$i ++;
			if ( $i % 6 == 0 ) {
				array_push( $response, $temp );
				$temp = array();
			}
		}

		return $response;
	}
}


/**
 * Custom end point for Featured image for Globe's card background
 */
add_action( 'rest_api_init', 'register_image_route' );

function register_image_route() {

	register_rest_route( 'image-api', 'image-item', array(
			'methods'  => 'GET',
			'callback' => 'my_image_meta',
		)
	);
}

function my_image_meta() {

	$post             = get_post( 892 );
	$featured_img_url = get_the_post_thumbnail_url( $post->ID, 'full' );
	$response['url']  = $featured_img_url;

	return $response;
}

/**
 * REST API for categories
 */
add_action( 'rest_api_init', 'register_categories_route' );

function register_categories_route() {

	register_rest_route( 'categories-api', 'categories-item', array(
			'methods'  => 'GET',
			'callback' => 'my_categories_meta',
		)
	);
}

function my_categories_meta() {

	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => 30,
	);
	$loop = new WP_Query( $args );

	while ( $loop->have_posts() ) : $loop->the_post();
		global $product;
		$product_variations = $product->get_available_variations();

		$temp = array();

		$response['categories'] = array();
		foreach ( $product_variations as $variation ) {
			$var_data = $variation['attributes'];
			foreach ( $var_data as $categories ) {
				$temp = $categories;
				if ( $temp != '' ) {
					array_push( $response['categories'], $temp );
				}
			}
		}

	endwhile;

	$response['categories'] = isset($response['categories']) ? array_unique($response['categories'],SORT_REGULAR) : $response['categories'];
	return $response;

}


add_action( 'rest_api_init', 'register_globe_route' );

function register_globe_route() {

	register_rest_route( 'globe-api', 'globe-item', array(
			'methods'  => 'GET',
			'callback' => 'my_globe_meta',
		)
	);
}

function my_globe_meta() {

	global $wpdb;
	$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result     = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'bool_hidden'" );
	$finalIds   = array();
	foreach ( $result as $id ) {
		array_push( $finalIds, $id->order_item_id );
	}
	$finalIds = implode( ", ", $finalIds );
	$result   = $wpdb->get_results( "SELECT meta_key, meta_value FROM $table_name WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories', 'bool_hidden') AND order_item_id IN ($finalIds)" );

	$i        = 0;
	$temp     = array();
	$response = array();
	foreach ( $result as $results ) {
		$temp[ $results->meta_key ] = $results->meta_value;
		$i ++;
		if ( $i % 7 == 0 ) {
			array_push( $response, $temp );
			$temp = array();
		}
	}

	return $response;
}

/**
 * Function for generalizing Cluster & Points Response
 */
function prepareResponse( $openPoints ) {
//	$openPoints   = getSamplePoints();
	$clusters     = $openPoints;
	$ignorePoints = [];
	foreach ( $clusters as $dataKey => $data ) {
		if ( in_array( $dataKey, $ignorePoints ) ) {
			continue;
		}

		$ignorePoints[] = $dataKey;

		//For getting point 1 on clusters
		$points = [];
		foreach ( $clusters as $innerKey => $innerPoint ) {
			if ( $innerKey == $dataKey ) {
				continue;
			}

			$radius   = 20;
			$distance = latLongDistance( floatval($data['lat']), floatval($data['lng']), floatval($innerPoint['lat']),floatval($innerPoint['lng']) );

			if ( $distance <= $radius ) {
				$ignorePoints[]         = $innerKey;
				$innerPoint['distance'] = $distance;
				$points[]               = $innerPoint;
				unset( $clusters[ $innerKey ] );
			}
		}

		if ( $points && count( $points ) > 0 ) {
			$points[]                       = $data;
			$clusters[ $dataKey ]['points'] = $points;
		}
	}

	return [ 'points' => $openPoints, 'clusters' => $clusters ];
}

function getSamplePoints( $number = 5000 ) {
	$args = array(
		'timeout'   => 50,
		'sslverify' => false
	);
//	$dataSet = wp_remote_get("https://api.mockaroo.com/api/970411b0?count=$number&key=83189fc0", $args);
	$dataSet = wp_remote_get( plugin_dir_url( __FILE__ ) . 'sample-points.json', $args );

	return json_decode( $dataSet['body'], true );
}

function latLongDistance( $lat1, $lon1, $lat2, $lon2, $unit = 'K' ) {
	if ( ( $lat1 == $lat2 ) && ( $lon1 == $lon2 ) ) {
		return 0;
	} else {
		$theta = $lon1 - $lon2;
		$dist  = sin( deg2rad( $lat1 ) ) * sin( deg2rad( $lat2 ) ) + cos( deg2rad( $lat1 ) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad( $theta ) );
		$dist  = acos( $dist );
		$dist  = rad2deg( $dist );
		$miles = $dist * 60 * 1.1515;
		$unit  = strtoupper( $unit );

		if ( $unit == "K" ) {
			return ( $miles * 1.609344 );
		} else if ( $unit == "N" ) {
			return ( $miles * 0.8684 );
		} else {
			return $miles;
		}
	}
}