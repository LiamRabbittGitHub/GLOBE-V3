<?php

/**
 * Custom end point API for showing dots and clusters on the globe
 */
add_action('rest_api_init', 'distance_calculation');

function distance_calculation()
{
    register_rest_route('map-api', 'distance-item', array(
        'methods' => 'GET',
        'callback' => 'map_distance_data',
    ));
}

function map_distance_data()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";

    // Get the order_item_id for items with meta_key = 'bool_hidden'
    $result = $wpdb->get_results("SELECT order_item_id FROM $table_name WHERE meta_key = 'bool_hidden'");
    $finalIds = array();
    foreach ($result as $id) {
        array_push($finalIds, $id->order_item_id);
    }

    // Get orders from the database based on the finalIds and prepare the response
    return prepareResponse(getOrders($finalIds));
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

function search_cluster_meta( $request ) {

	$categories = $request->get_param( 'categories' );
	$final_cat  = preg_replace( "/[^a-zA-Z]/", "", $categories );

	if ( $final_cat ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
		$result = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'categories' AND meta_value = '$final_cat'" );
		if ( count( $result ) > 0 ) {
			$finalIds = array();
			foreach ( $result as $id ) {
				array_push( $finalIds, $id->order_item_id );
			}
			$finalIds = implode( ", ", $finalIds );
			return prepareResponse( getOrders( $finalIds ) );
		}
	}
	return array();
}
/**
 * Endpoint for categories search (clusters)
 */
add_action( 'rest_api_init', 'register_search_cluster_route' );

function register_search_cluster_route() {
    register_rest_route( 'search-cluster-api', 'search-item', array(
        'methods'  => 'GET',
        'callback' => 'search_cluster_meta',
    ));
}

function search_cluster_meta( WP_REST_Request $request ) {
    $categories = $request->get_param( 'categories' );
    $final_cat = preg_replace( "/[^a-zA-Z]/", "", $categories );
    if ( empty( $final_cat ) ) {
        return new WP_Error( 'invalid_parameter', 'Invalid parameter: categories', array( 'status' => 400 ) );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
    $result = $wpdb->get_results( $wpdb->prepare(
        "SELECT order_item_id FROM $table_name WHERE meta_key = 'categories' AND meta_value = %s",
        $final_cat
    ));

    if ( count( $result ) > 0 ) {
        $finalIds = array();
        foreach ( $result as $id ) {
            array_push( $finalIds, $id->order_item_id );
        }
        $finalIds = implode( ", ", $finalIds );

        return prepareResponse( getOrders( $finalIds ) );
    } else {
        return new WP_Error( 'no_results', 'No results found', array( 'status' => 404 ) );
    }
}

function getOrders( $finalIds ) {
    global $wpdb;
    $table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
    $result = $wpdb->get_results( $wpdb->prepare(
        "SELECT itemTable.order_id, meta_key, meta_value
        FROM $table_name as metaTable
        LEFT JOIN $table_name as itemTable ON itemTable.order_item_id = metaTable.order_item_id
        WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories', 'bool_hidden')
        AND metaTable.order_item_id IN ($finalIds)"
    ));

    $ordersSet = [];
    foreach ( $result as $meta_item ) {
        $order_id = $meta_item->order_id;
        if ( ! isset( $ordersSet[ $order_id ] ) ) {
            $ordersSet[ $order_id ] = [];
        }
        $ordersSet[ $order_id ][ $meta_item->meta_key ] = $meta_item->meta_value;
    }

    return $ordersSet;
}

/**
 * Custom end point API for search
 */
add_action( 'rest_api_init', 'register_search_route' );

function
/**
 * Endpoint for categories search (clusters)
 */
add_action( 'rest_api_init', 'register_search_cluster_route' );
function register_search_cluster_route() {
	register_rest_route( 'search-cluster-api', 'search-item', array(
		'methods'  => 'GET',
		'callback' => 'search_cluster_meta',
	) );
}

function search_cluster_meta( WP_REST_Request $request ) {
	global $wpdb;

	$categories = $request->get_param( 'categories' );
	$final_cat  = preg_replace( "/[^a-zA-Z]/", "", $categories );

	$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result = $wpdb->get_col( $wpdb->prepare(
		"SELECT order_item_id FROM $table_name WHERE meta_key = %s AND meta_value = %s",
		'categories',
		$final_cat
	) );

	if ( empty( $result ) ) {
		return array();
	}

	$result = $wpdb->get_results( $wpdb->prepare(
		"SELECT meta_key, meta_value FROM $table_name WHERE meta_key IN(%s, %s, %s, %s, %s, %s, %s) AND order_item_id IN (". implode( ",", $result ) .")",
		'Sender',
		'Receiver',
		'Description',
		'lat',
		'long',
		'categories',
		'bool_hidden'
	) );

	$response = array();
	$temp = array();
	foreach ( $result as $i => $results ) {
		$temp[ $results->meta_key ] = $results->meta_value;
		if ( ( $i + 1 ) % 7 === 0 ) {
			if ( $temp['lat'] && $temp['long'] ) {
				$temp['lng'] = $temp['long'];
				unset( $temp['long'] );
				$response[] = $temp;
			}
			$temp = array();
		}
	}

	return prepareResponse( getOrders( implode( ",", $result ) ) );
}

function getOrders( $order_item_ids ) {
	global $wpdb;

	$items_table = $wpdb->prefix . "woocommerce_order_items";
	$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";

	$result = $wpdb->get_results( $wpdb->prepare(
		"SELECT itemTable.order_id, meta_key, meta_value FROM $table_name as metaTable
			LEFT JOIN $items_table as itemTable ON itemTable.order_item_id = metaTable.order_item_id
			WHERE meta_key IN(%s, %s, %s, %s, %s, %s) AND metaTable.order_item_id IN ($order_item_ids)",
		'Sender',
		'Receiver',
		'Description',
		'lat',
		'long',
		'categories'
	) );

	$ordersSet = array();
	foreach ( $result as $meta_item ) {
		if ( $meta_item->meta_key === 'long' ) {
			$ordersSet[ $meta_item->order_id
			/**
			 * Custom end point for Featured image for Globe's card background
			 */
			add_action( 'rest_api_init', 'register_image_route' );

			function register_image_route() {

				register_rest_route( 'image-api', 'image-item/(?P<id>\d+)', array(
						'methods'  => 'GET',
						'callback' => 'my_image_meta',
			            'args'     => array(
			                'id' => array(
			                    'required' => true,
			                    'validate_callback' => function( $param, $request, $key ) {
			                        return is_numeric( $param );
			                    }
			                )
			            )
					)
				);
			}

			function my_image_meta( $request ) {

				$post_id          = $request->get_param( 'id' );
				$post             = get_post( $post_id );

				if ( !$post ) {
					return new WP_Error( 'invalid_post_id', 'Invalid post ID', array( 'status' => 404 ) );
				}

				$featured_img_url = get_the_post_thumbnail_url( $post->ID, 'full' );
				$response         = array( 'url' => $featured_img_url );

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

				$args   = array(
					'post_type'      => 'product',
					'posts_per_page' => 30,
				);
				$loop   = new WP_Query( $args );
				$terms  = array();

				if ( $loop->have_posts() ) {
					while ( $loop->have_posts() ) {
						$loop->the_post();
						global $product;
						$product_cats = wp_get_post_terms( get_the_ID(), 'product_cat' );
						foreach ( $product_cats as $cat ) {
							if ( !in_array( $cat->name, $terms ) ) {
								$terms[] = $cat->name;
							}
						}
					}
					wp_reset_postdata();
				}

				if ( empty( $terms ) ) {
					return new WP_Error( 'no_categories_found', 'No categories found', array( 'status' => 404 ) );
				}

				$response = array( 'categories' => $terms );
				return $response;
			}
			/**
			 * REST API for getting product categories
			 */
			add_action( 'rest_api_init', 'register_categories_route' );

			function register_categories_route() {

				register_rest_route( 'categories-api', 'categories-item', array(
						'methods'  => 'GET',
						'callback' => 'get_product_categories',
					)
				);
			}

			function get_product_categories() {

				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => 30,
				);

				$loop = new WP_Query( $args );

				$categories = array();

				while ( $loop->have_posts() ) : $loop->the_post();
					global $product;
					$product_variations = $product->get_available_variations();

					foreach ( $product_variations as $variation ) {
						$var_data = $variation['attributes'];
						foreach ( $var_data as $category ) {
							if ( !empty( $category ) ) {
								$categories[] = $category;
							}
						}
					}

				endwhile;

				$categories = array_unique( $categories, SORT_REGULAR );

				return $categories;
			}


			/**
			 * REST API for getting Globe data
			 */
			add_action( 'rest_api_init', 'register_globe_route' );

			function register_globe_route() {

				register_rest_route( 'globe-api', 'globe-item', array(
						'methods'  => 'GET',
						'callback' => 'get_globe_data',
					)
				);
			}

			function get_globe_data() {

				global $wpdb;
				$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
				$result     = $wpdb->get_results( "SELECT order_item_id FROM $table_name WHERE meta_key = 'bool_hidden'" );
				$finalIds   = array();
				foreach ( $result as $id ) {
					array_push( $finalIds, $id->order_item_id );
				}
				$finalIds = implode( ", ", $finalIds );
				$result   = $wpdb->get_results( "SELECT meta_key, meta_value FROM $table_name WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories', 'bool_hidden') AND order_item_id IN ($finalIds)" );

				$data = array();
				$temp = array();

				foreach ( $result as $row ) {
					if ( $row->meta_key === 'categories' ) {
						$temp[ $row->meta_key ][] = $row->meta_value;
					} else {
						$temp[ $row->meta_key ] = $row->meta_value;
					}

					if ( count( $temp ) === 7 ) {
						$data[] = $temp;
						$temp   = array();
					}
				}

				return $data;
			}
			function get_globe_data() {
			    global $wpdb;

			    $hidden_item_ids = $wpdb->get_results( "SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key = 'bool_hidden'" );

			    $hidden_item_ids = implode( ", ", wp_list_pluck( $hidden_item_ids, 'order_item_id' ) );

			    $data = $wpdb->get_results( "SELECT meta_key, meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key IN('Sender', 'Receiver', 'Description', 'lat', 'long', 'categories', 'bool_hidden') AND order_item_id IN ($hidden_item_ids)" );

			    $items = array();
			    foreach ( $data as $row ) {
			        $items[ $row->order_item_id ][ $row->meta_key ] = $row->meta_value;
			    }

			    $points = array();
			    foreach ( $items as $item ) {
			        $point = array(
			            'lat' => $item['lat'],
			            'lng' => $item['long'],
			            // Add any other data you want to include here
			        );

			        $points[] = $point;
			    }

			    $result = prepare_response( $points );

			    return $result;
			}

			function prepare_response( $points ) {
			    // Cluster the points however you want
			    $clusters = $points;

			    return array(
			        'points' => $points,
			        'clusters' => $clusters
			    );
			}
