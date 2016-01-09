<?php
/**
 * Single Product Up-Sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

$upsells = $product->get_upsells();
$product_per_row = etheme_get_option('prodcuts_per_row');

$rand = rand(1000,9999);

if ( sizeof( $upsells ) == 0 ) return;

$args = array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'posts_per_page' 		=> 20,
	'no_found_rows' 		=> 1,
	'orderby' 				=> 'rand',
	'post__in' 				=> $upsells
);

$products = new WP_Query( $args );
$upsells_count = 0;
if ( $products->have_posts() ) : ?>

    <div class="product-slider upsells columns<?php echo $product_per_row ?>">
        <h4 class="slider-title"><?php _e('You may also like&hellip;', ETHEME_DOMAIN) ?></h4>
        <div class="clear"></div>
        <div class="carousel slider-<?php echo $rand ?>" <?php if($upsells_count < 5): ?>style="height:auto;"<?php endif; ?>>
            <div class="slider">
			<?php while ( $products->have_posts() ) : $products->the_post(); $upsells_count++; ?>
		      <div class="slide product-slide">
				<?php woocommerce_get_template_part( 'content', 'product' ); ?>
	           </div> 
			<?php endwhile; // end of the loop. ?>
            </div>
        </div>
        <?php if($upsells_count > 1): ?>
        	<?php
        		$arrowClass = '';
        		if($upsells_count < 4) {
	        		$arrowClass = 'hidden-desktop';
        		}
        	?>
        	
            <div class="prev <?php echo $arrowClass; ?> related-arrow arrow<?php echo $rand ?>" style="cursor: pointer; ">&nbsp;</div>
            <div class="next <?php echo $arrowClass; ?> related-arrow arrow<?php echo $rand ?>" style="cursor: pointer; ">&nbsp;</div>
        <?php endif; ?>
			
                           
    </div><!-- product-slider -->     

    <?php if($upsells_count > 1): ?>
	    <script type="text/javascript">
            jQuery(document).ready(function(){
    	        jQuery('.arrow<?php echo $rand ?>.prev').addClass('disabled');
    	        jQuery('.slider-<?php echo $rand ?>').iosSlider({
    	            desktopClickDrag: true,
    	            snapToChildren: true,
    	            infiniteSlider: false,
    	            navNextSelector: '.arrow<?php echo $rand ?>.next',
    	            navPrevSelector: '.arrow<?php echo $rand ?>.prev',
    	            lastSlideOffset: 3,
    	            onFirstSlideComplete: function(){
    	                jQuery('.arrow<?php echo $rand ?>.prev').addClass('disabled');
    	            },
    	            onLastSlideComplete: function(){
    	                jQuery('.arrow<?php echo $rand ?>.next').addClass('disabled');
    	            },
    	            onSlideChange: function(){
    	                jQuery('.arrow<?php echo $rand ?>.prev').removeClass('disabled');
    	                jQuery('.arrow<?php echo $rand ?>.next').removeClass('disabled');
    	            }
    	        });  
	        });               
	    </script>
    <?php endif; ?>
    
<?php endif; 

wp_reset_query();