<?php

/**
 * Show product labels
 */
 
function etheme_product_labels( $product_id = '' ) { 
    echo etheme_get_product_labels($product_id);
}
function etheme_get_product_labels( $product_id = '' ) {
	global $post, $wpdb;
    $count_labels = 0; 
    $output = '';
    if ( etheme_get_option('sale_icon') ) : 
        if(etheme_product_on_special($product_id)) : $count_labels++; 
                $output .= '<span class="label-icon sale-label">'.__( 'Sale!', ETHEME_DOMAIN ).'</span>';
            
        endif; 
    endif; 
    
    if ( etheme_get_option('new_icon') ) : $count_labels++; 
        if(etheme_product_is_new($product_id)) :
            if($count_labels > 1) $second_label = 'second_label';
            $output .= '<span class="label-icon new-label '.$second_label.'">'.__( 'New!', ETHEME_DOMAIN ).'</span>';
        endif;
    endif; 
    return $output;
}

/**
 * Is product New
 */
function etheme_product_is_new( $product_id = '' ) {
	global $post, $wpdb;
    $key = '_etheme_new_label';
	if(!$product_id) $product_id = $post->ID;
	if(!$product_id) return false;
    $_etheme_new_label = get_post_meta($product_id, $key);
        return $_etheme_new_label;
    if($_etheme_new_label[0] == 1) {
        return true;
    }
    return false;	
}
global $etheme_productspage_id, $etheme_shoppingcart_id, $etheme_transactionresults_id, $etheme_userlog_id;
$etheme_productspage_id = etheme_shortcode2id('[productspage]');
$etheme_shoppingcart_id = etheme_shortcode2id('[shoppingcart]');
$etheme_transactionresults_id = etheme_shortcode2id('[transactionresults]');
$etheme_userlog_id = etheme_shortcode2id('[userlog]');

/**
 * Create product slider by query
 */

function etheme_create_slider($args,$title = false,$image_width = 215,$image_height = 215,$crop = false,$enable_slider_from=4,$last_offset = 3){
	global $wpdb;
    $product_per_row = etheme_get_option('prodcuts_per_row');
    $box_id = rand(1000,10000);
	$multislides = new WP_Query( $args );
	if ( $multislides->have_posts() ) :
		if ($title) {
			$title_output = '<h4 class="slider-title">'.$title.'</h4>';
		}	
          echo '<div class="product-slider columns' . $product_per_row . '">';
            echo $title_output;
            echo '<div class="clear"></div>';
            echo '<div class="carousel slider-'.$box_id.'">';
                echo '<div class="slider">';
               	
                $_i=0;
                
        		while ($multislides->have_posts()) : $multislides->the_post();
                    $_i++;
                    
                    if(class_exists('Woocommerce')) {
                        global $product;
                        if (!$product->is_visible()) continue; 
                        echo '<div class="slide product-slide">';
                            woocommerce_get_template_part( 'content', 'product' );
                        echo '</div><!-- slide -->';
                    }

        		endwhile; 
                echo '</div><!-- slider -->'; 
            echo '</div><!-- carousel -->'; 
                
            if($_i > $enable_slider_from):
                echo '<div class="prev arrow'.$box_id.'" style="cursor: pointer; ">&nbsp;</div>';
                echo '<div class="next arrow'.$box_id.'" style="cursor: pointer; ">&nbsp;</div>';
            endif; 
          echo '</div><!-- product-slider -->'; 
	endif;
	wp_reset_query();
	if ($_i>$enable_slider_from) {   
	   
        if(etheme_get_option('touch_carusels')){
            $desktopClickDrag = 'true';
        }else{
            $desktopClickDrag = 'false';
        }
        echo '
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery(".arrow'.$box_id.'.prev").addClass("disabled");
                    jQuery(".slider-'.$box_id.'").iosSlider({
                        desktopClickDrag: '.$desktopClickDrag.',
                        snapToChildren: true,
                        infiniteSlider: false,
                        navNextSelector: ".arrow'.$box_id.'.next",
                        navPrevSelector: ".arrow'.$box_id.'.prev",
                        lastSlideOffset: '.$last_offset.',
                        onFirstSlideComplete: function(){
                            jQuery(".arrow'.$box_id.'.prev").addClass("disabled");
                        },
                        onLastSlideComplete: function(){
                            jQuery(".arrow'.$box_id.'.next").addClass("disabled");
                        },
                        onSlideChange: function(){
                            jQuery(".arrow'.$box_id.'.next").removeClass("disabled");
                            jQuery(".arrow'.$box_id.'.prev").removeClass("disabled");
                        }
                    });
                });
            </script>
        ';
        
	}
}
