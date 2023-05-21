<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
    return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
        'woocommerce-product-gallery--columns-' . absint( $columns ),
        'images',
    )
);
        $image_url = wp_get_attachment_url( $product->get_image_id());

?>

<div class="main-template">
        <img class="main-img" src="<?php echo $image_url ?>" >
    <div class="content-template">
        <span class="main-name fc-receive fc-receive-left">David Bowie</span>
        <div>
        <img src="https://shop.theoneinamillionglobe.com/wp-content/uploads/2020/07/logo-mid.png">
        </div>
        <p class="desc"> Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley.
        </p>
        <div class="sender-info">
            <span class="fc_sender-title">Presented by</span><span class="main-name fc-send fc-send-left">Ziggy stardust</span>
            <p class="serial-num">Lorem Ipsum is simply dummy text typesetting industry. </p>
        </div>
    </div>
</div>
<div class="close-overlay close-template main-template-hide" ></div>

<div class="main-template-hide">
    <div class="main-template">
        <div class="close-template"><i class="far fa-times-circle"></i></div>
         <img class="main-img" src="<?php echo $image_url ?>" >
        <div class="content-template">
            <span class="main-name fc-receive fc-receive-left">David Bowie</span>
            <div>
            <img src="https://shop.theoneinamillionglobe.com/wp-content/uploads/2020/07/logo-mid.png">
            </div>
            <p class="desc"> Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley.
            </p>
            <div class="sender-info">
                <span class="fc_sender-title">Presented by</span><span class="main-name fc-send fc-send-left">Ziggy stardust</span>
                <p class="serial-num">Lorem Ipsum is simply dummy text typesetting industry. </p>
            </div>
        </div>
    </div>
</div>

