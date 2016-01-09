<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) 
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) 
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

?>
<div class="category-block">

	<?php do_action( 'woocommerce_before_subcategory', $category ); ?>
		
	<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">
				
		<?php
			/** 
			 * woocommerce_before_subcategory_title hook
			 *
			 * @hooked woocommerce_subcategory_thumbnail - 10
			 */	  
			do_action( 'woocommerce_before_subcategory_title', $category ); 
		?>
		
		<h3>
			<?php echo $category->name; ?> 
			<?php if ( $category->count > 0 ) : ?>
				<mark class="count">(<?php echo $category->count; ?>)</mark>
			<?php endif; ?>
		</h3>

		<?php
			/** 
			 * woocommerce_after_subcategory_title hook
			 */	  
			do_action( 'woocommerce_after_subcategory_title', $category ); 
		?>
	
	</a>
	
	<?php do_action( 'woocommerce_after_subcategory', $category ); ?>
			
</div>