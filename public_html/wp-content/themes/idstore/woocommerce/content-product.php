<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $post, $woocommerce_loop,$woocommerce;

$product_page_productname = etheme_get_option('product_page_productname');
$product_page_price = etheme_get_option('product_page_price');
$product_page_addtocart = etheme_get_option('product_page_addtocart');
if(isset($_GET['btn'])) {
	$product_page_addtocart = true;
}

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) 
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) 
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibilty
if ( ! $product->is_visible() ) 
	return; 

// Increase loop count
$woocommerce_loop['loop']++;
 
$columns = etheme_get_option('prodcuts_per_row');
$product_img_hover = etheme_get_option('product_img_hover');

$class = '';
if($woocommerce_loop['loop']%$columns == 0){
    $class = 'last';
}
$product_per_row = etheme_get_option('prodcuts_per_row'); 
$product_sidebar = etheme_get_option('product_page_sidebar');
if($product_per_row == 4 && !$product_sidebar) {
    $class .= ' span3';
}elseif($product_per_row == 4){
    $class .= ' span2';
}else{
    $class .= ' span3';
}
if($product_page_productname == 0 && $product_page_price == 0 && $product_page_addocart == 0) {
    $class .= ' no-attributes';
}
?>

	<div class="product-grid <?php echo $class; ?>">
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
        
		<?php 
			$width = etheme_get_option('product_page_image_width');
			$height = etheme_get_option('product_page_image_height');
			$crop = etheme_get_option('product_page_image_cropping');
			
			$hoverUrl = '';
			
            $url = etheme_get_image(false, $width, $height, $crop);
            
    		if ( has_post_thumbnail() ){
    			?>
                    <a id="<?php echo etheme_get_image( false, 460, 628, false ) ?>" href="<?php echo the_permalink(); ?>" class="product-image <?php if($product_img_hover == 'tooltip'): ?>imageTooltip<?php endif; ?>">
		
						<?php woocommerce_get_template( 'loop/sale-flash.php' );  ?>
                        <?php if(etheme_get_custom_field('_etheme_hover') && $product_img_hover == 'swap'): ?><div class="img-wrapper"><img class="product_image img-hided" src="<?php echo etheme_get_custom_field('_etheme_hover'); ?>" alt="<?php the_title(); ?>"/></div><?php endif; ?>
                        <div class="img-wrapper<?php if(etheme_get_custom_field('_etheme_hover') && $product_img_hover == 'swap') echo ' hideableHover' ?>"><img class="product_image" src="<?php echo $url; ?>" alt="<?php the_title(); ?>"/></div>
                    </a>
                <?php
    		}
            else {
                echo '<img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" width="' . $placeholder_width . '" height="' . $placeholder_height . '" />';
            }
        
        ?>	
        <div class="product-information">
	        <?php etheme_print_stars() ?>
            <?php if($product_page_productname): ?>
                <div class="product-name-price">
                    <div class="product-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                    <div class="clear"></div>
                </div>
            <?php endif; ?>
            <div class="product-descr"><?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?></div>
            <div class="addtocont">
	            <?php if($product_page_price): ?>
	                <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
	            <?php endif; ?>
	            <?php if($product_page_addtocart): ?>
	                <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	            <?php endif; ?>
            </div>
            
            <div class="clear"></div>
        </div>
    <div class="clear"></div>
	</div>
<?php

