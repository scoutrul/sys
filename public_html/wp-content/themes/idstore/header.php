<?php
/**
 * The Header for our theme.
 *
 */
?>
<?php global $etheme_responsive; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php if($etheme_responsive): ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
<?php endif; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', ETHEME_DOMAIN ), max( $paged, $page ) );

	?></title>
	<link rel="shortcut icon" href="<?php etheme_option('favicon',true) ?>" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <script type="text/javascript">
        var etheme_wp_url = '<?php echo home_url(); ?>'; 
        var succmsg = '<?php _e('All is well, your e&ndash;mail has been sent!', ETHEME_DOMAIN); ?>';
        var menuTitle = '<?php _e('Menu', ETHEME_DOMAIN); ?>';
        var nav_accordion = false;
        var ajaxFilterEnabled = <?php echo (etheme_get_option('ajax_filter')) ? 1 : 0 ; ?>;
        var isRequired = ' <?php _e('Please, fill in the required fields!', ETHEME_DOMAIN); ?>';
        var someerrmsg = '<?php _e('Something went wrong', ETHEME_DOMAIN); ?>';
		var successfullyAdded = '<?php _e('Successfully added to your shopping cart', ETHEME_DOMAIN); ?>';
    </script>
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    
<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_head();
?>
<?php $header_type = ''; $header_type = apply_filters('custom_header_filter', $header_type); ?>
</head>
<body <?php $fixed = ''; if(etheme_get_option('fixed_nav')) $fixed .= ' fixNav-enabled '; if($header_type == 'variant6' && is_front_page()) $fixed .= ' header-overlapped '; body_class('no-svg '.etheme_get_option('main_layout').' banner-mask-'.etheme_get_option('banner_mask').$fixed); ?>>
	
	<div class="wrapper">
	
    <?php if(etheme_get_option('loader')): ?>
    <div id="loader">
        <div id="loader-status">
            <p class="center-text">
                <em><?php _e('Loading the content...', ETHEME_DOMAIN); ?></em>
                <em><?php _e('Loading depends on your connection speed!', ETHEME_DOMAIN); ?></em>
            </p>
        </div>
    </div>
    <?php endif; ?>
    
	<?php if((etheme_get_option('search_form') || (class_exists('Woocommerce') && !etheme_get_option('just_catalog') && etheme_get_option('cart_widget')) || etheme_get_option('top_links') || etheme_get_option('header_phone') != '')): ?>
		<div class="header-top header-top-<?php echo $header_type; ?> <?php if($header_type == "default") echo 'hidden-desktop'; ?>">
			<div class="container">
				<div class="row header-variant2">
		    		<div class="span4 header-phone"><?php etheme_option('header_phone') ?></div>
		            <div class="span8">
		            	<?php if(etheme_get_option('search_form')): ?>
			                <div class="search_form">
			                    <?php get_search_form(); ?>
			                </div>
			            <?php endif; ?>
			            <?php if(class_exists('Woocommerce') && !etheme_get_option('just_catalog') && etheme_get_option('cart_widget')): ?>
			                <div id="top-cart" class="shopping-cart-wrapper widget_shopping_cart">
			                    <?php $cart_widget = new Etheme_WooCommerce_Widget_Cart(); $cart_widget->widget(); ?>
			                </div>
			            <?php endif ;?> 
			    		<?php if(etheme_get_option('top_links')): ?>
			    			<?php  get_template_part( 'et-links' ); ?>
			            <?php endif; ?>
	                </div>
		    		
				</div>
			</div>
		</div>
	<?php endif; ?>

    
   <?php if(etheme_get_option('fixed_nav')): ?> 
	    <div class="fixed-header-area visible-desktop">
		    <div class="fixed-header container">
			    <div class="row">
		            <div class="span3 logo">
	                    <?php etheme_logo(); ?>
		            </div>
		            <div id="main-nav" class="span9">
		                <?php etheme_header_wp_navigation(); ?>
		            </div>
			        <div class="clear"></div>
			    </div>
		    </div>
	    </div>
    <?php endif; ?>
    
    <div class="header-bg header-type-<?php echo $header_type; ?>">
    <div class="container header-area"> 
	    
        <header class="row header ">
            <div class="span5 logo">    
            	<svg xmlns="http://www.w3.org/2000/svg" id="Слой_1" enable-background="new 0 0 65 65" viewBox="0 0 65 65" x="0px" y="0px" width="65px" height="65px" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve" version="1.1">
                 <g>
                  <title>Ваш носочный магазин SockYourSelf.ru</title>
                  <g id="svg_13">
                   <path id="svg_14" d="m37.363,47.552c0.34,-0.005 0.698,-0.011 1.048,-0.011c-0.009,1.045 0,2.092 -0.009,3.137c0.126,0.002 0.367,0.004 0.484,0.004c0,-1.045 0,-2.088 0,-3.131c0.17,-0.002 0.501,-0.003 0.663,-0.005c0.018,1.046 -0.054,2.094 0.036,3.139c0.108,0.037 0.313,0.104 0.421,0.142c0.045,-1.095 0.018,-2.19 0.018,-3.279c0.17,0 0.501,0.002 0.671,0.002c0,1.087 -0.027,2.182 0.027,3.271c0.09,-0.036 0.304,-0.111 0.412,-0.146c0.09,-1.038 0.018,-2.082 0.036,-3.124c0.152,-0.003 0.448,-0.013 0.591,-0.017c0.018,1.001 -0.045,2.004 0.027,3.003c0.116,0.083 0.367,0.246 0.483,0.328c0.072,-1.105 0.027,-2.213 0.036,-3.324c0.367,0 0.743,0 1.111,0c-0.018,3.072 0,6.141 -0.009,9.214c1.388,0.003 2.758,-0.015 4.137,0.001c1.262,0.016 2.4,0.931 2.812,2.099c0.215,0.895 0.152,1.926 -0.439,2.671c-0.609,0.892 -1.701,1.315 -2.749,1.319c-2.239,0.002 -4.477,0.011 -6.716,-0.004c-1.406,-0.036 -2.901,-1.062 -3.018,-2.541c-0.073,-4.248 0.071,-8.501 -0.073,-12.748l0,0l0,0z" fill="#9B1B2B"/>
                   <path id="svg_15" d="m1.636,6.187c0,-1.229 0.278,-2.173 0.878,-2.833c0.582,-0.659 1.451,-0.989 2.624,-0.989c1.182,0 2.051,0.33 2.642,0.989c0.6,0.66 0.886,1.604 0.886,2.833v0.588h-2.258v-0.738c0,-0.552 -0.107,-0.938 -0.313,-1.164c-0.224,-0.224 -0.51,-0.336 -0.877,-0.336c-0.376,0 -0.672,0.112 -0.895,0.336c-0.198,0.225 -0.314,0.612 -0.314,1.164c0,0.518 0.116,0.979 0.358,1.377c0.224,0.399 0.519,0.776 0.86,1.129c0.349,0.355 0.725,0.71 1.119,1.065c0.403,0.355 0.779,0.741 1.119,1.16c0.349,0.421 0.645,0.899 0.878,1.434c0.233,0.536 0.331,1.167 0.331,1.889c0,1.231 -0.286,2.175 -0.895,2.833c-0.591,0.658 -1.478,0.988 -2.65,0.988c-1.182,0 -2.06,-0.329 -2.66,-0.988s-0.913,-1.602 -0.913,-2.833v-1.042h2.275v1.194c0,0.55 0.099,0.934 0.322,1.15c0.224,0.218 0.537,0.326 0.914,0.326c0.376,0 0.671,-0.108 0.895,-0.326c0.224,-0.216 0.34,-0.6 0.34,-1.15c0,-0.522 -0.116,-0.98 -0.349,-1.379c-0.242,-0.399 -0.528,-0.775 -0.869,-1.13c-0.349,-0.354 -0.717,-0.708 -1.119,-1.064c-0.395,-0.354 -0.78,-0.741 -1.12,-1.162c-0.35,-0.42 -0.645,-0.896 -0.869,-1.431c-0.242,-0.535 -0.34,-1.166 -0.34,-1.89l0,0z" fill="#D2D2D2"/>
                   <path id="svg_16" d="m21.586,14.243c0,0.55 0.107,0.938 0.331,1.162c0.233,0.224 0.519,0.336 0.904,0.336c0.367,0 0.671,-0.112 0.895,-0.336c0.224,-0.225 0.331,-0.612 0.331,-1.162v-8.206c0,-0.552 -0.107,-0.938 -0.331,-1.164c-0.224,-0.224 -0.528,-0.336 -0.895,-0.336c-0.385,0 -0.671,0.112 -0.904,0.336c-0.224,0.226 -0.331,0.612 -0.331,1.164v8.206l0,0zm-2.4,-8.056c0,-1.229 0.313,-2.173 0.94,-2.833c0.627,-0.659 1.522,-0.989 2.695,-0.989c1.164,0 2.059,0.33 2.686,0.989c0.627,0.66 0.931,1.604 0.931,2.833v7.904c0,1.231 -0.304,2.175 -0.931,2.833c-0.627,0.658 -1.522,0.988 -2.686,0.988c-1.173,0 -2.068,-0.329 -2.695,-0.988c-0.627,-0.658 -0.94,-1.602 -0.94,-2.833v-7.904l0,0z" fill="#D2D2D2"/>
                   <path id="svg_17" d="m44.24,12.072v2.019c0,1.231 -0.296,2.175 -0.895,2.833c-0.6,0.658 -1.477,0.988 -2.66,0.988c-1.173,0 -2.059,-0.329 -2.65,-0.988c-0.609,-0.658 -0.913,-1.602 -0.913,-2.833v-7.904c0,-1.229 0.304,-2.173 0.913,-2.833c0.591,-0.659 1.477,-0.989 2.65,-0.989c1.182,0 2.059,0.33 2.66,0.989c0.6,0.66 0.895,1.604 0.895,2.833v1.476h-2.256v-1.626c0,-0.552 -0.099,-0.938 -0.331,-1.164c-0.215,-0.224 -0.528,-0.336 -0.904,-0.336c-0.376,0 -0.672,0.112 -0.904,0.336c-0.215,0.226 -0.331,0.612 -0.331,1.164v8.206c0,0.55 0.116,0.934 0.331,1.15c0.233,0.218 0.528,0.326 0.904,0.326c0.376,0 0.69,-0.108 0.904,-0.326c0.233,-0.216 0.331,-0.6 0.331,-1.15v-2.171h2.256l0,0z" fill="#D2D2D2"/>
                   <polygon id="svg_18" points="58.164,11.681 57.421,13.071 57.421,17.74 55.03,17.74 55.03,2.54 57.421,2.54 57.421,9.162    60.591,2.54 62.937,2.54 59.615,9.315 62.937,17.74 60.484,17.74 58.164,11.681  " fill="#D2D2D2"/>
                   <polygon id="svg_19" points="4.009,35.096 1,24.936 3.498,24.936 5.316,31.861 7.143,24.936 9.426,24.936 6.408,35.096    6.408,40.133 4.009,40.133 4.009,35.096  " fill="#D2D2D2"/>
                   <path id="svg_20" d="m21.899,36.639c0,0.549 0.116,0.934 0.34,1.159c0.224,0.225 0.537,0.337 0.913,0.337c0.376,0 0.672,-0.112 0.895,-0.337c0.224,-0.225 0.34,-0.61 0.34,-1.159v-8.208c0,-0.551 -0.116,-0.938 -0.34,-1.161c-0.224,-0.226 -0.519,-0.338 -0.895,-0.338c-0.376,0 -0.69,0.112 -0.913,0.338c-0.224,0.224 -0.34,0.611 -0.34,1.161v8.208l0,0zm-2.373,-8.056c0,-1.229 0.305,-2.175 0.922,-2.833c0.627,-0.659 1.522,-0.989 2.704,-0.989c1.164,0 2.059,0.33 2.686,0.989c0.627,0.658 0.94,1.604 0.94,2.833v7.903c0,1.229 -0.313,2.176 -0.94,2.834c-0.627,0.658 -1.522,0.988 -2.686,0.988c-1.182,0 -2.077,-0.33 -2.704,-0.988c-0.618,-0.658 -0.922,-1.605 -0.922,-2.834v-7.903l0,0z" fill="#D2D2D2"/>
                   <path id="svg_21" d="m39.924,24.936v11.723c0,0.551 0.125,0.934 0.349,1.151c0.224,0.217 0.51,0.326 0.887,0.326c0.376,0 0.68,-0.108 0.904,-0.326c0.224,-0.216 0.34,-0.6 0.34,-1.151v-11.723h2.257v11.573c0,1.229 -0.305,2.173 -0.896,2.833c-0.6,0.657 -1.495,0.987 -2.659,0.987c-1.182,0 -2.069,-0.33 -2.669,-0.987c-0.6,-0.661 -0.895,-1.604 -0.895,-2.833v-11.573h2.382l0,0z" fill="#D2D2D2"/>
                   <path id="svg_22" d="m58.012,27.107v4.667h0.931c0.448,0 0.797,-0.116 1.057,-0.347c0.251,-0.23 0.376,-0.652 0.376,-1.259v-1.5c0,-0.549 -0.099,-0.946 -0.295,-1.193c-0.188,-0.246 -0.501,-0.368 -0.913,-0.368h-1.156l0,0zm2.615,13.026c-0.036,-0.1 -0.072,-0.194 -0.099,-0.281c-0.027,-0.088 -0.054,-0.196 -0.063,-0.326c-0.036,-0.131 -0.054,-0.297 -0.054,-0.501c-0.009,-0.202 -0.009,-0.455 -0.009,-0.759v-2.388c0,-0.711 -0.125,-1.209 -0.376,-1.499c-0.233,-0.288 -0.645,-0.434 -1.191,-0.434h-0.824v6.188h-2.381v-15.197h3.6c1.245,0 2.14,0.289 2.713,0.867c0.546,0.58 0.824,1.455 0.824,2.629v1.193c0,1.565 -0.51,2.591 -1.567,3.083c0.609,0.246 1.03,0.649 1.254,1.207c0.233,0.558 0.331,1.232 0.331,2.028v2.345c0,0.377 0.018,0.706 0.054,0.988c0.027,0.282 0.089,0.569 0.206,0.858h-2.418l0,-0.001z" fill="#D2D2D2"/>
                   <path id="svg_23" d="m1.967,51.298c0,-1.23 0.295,-2.175 0.877,-2.834c0.591,-0.658 1.46,-0.988 2.641,-0.988c1.173,0 2.042,0.33 2.624,0.988c0.6,0.66 0.895,1.605 0.895,2.834v0.587h-2.265v-0.74c0,-0.55 -0.098,-0.936 -0.313,-1.161c-0.215,-0.225 -0.51,-0.336 -0.886,-0.336c-0.367,0 -0.672,0.111 -0.869,0.336c-0.215,0.225 -0.313,0.61 -0.313,1.161c0,0.521 0.107,0.98 0.349,1.379c0.224,0.398 0.519,0.775 0.869,1.128c0.34,0.355 0.716,0.709 1.101,1.065c0.412,0.356 0.788,0.741 1.119,1.162c0.349,0.42 0.645,0.899 0.877,1.433c0.224,0.537 0.349,1.165 0.349,1.887c0,1.233 -0.304,2.176 -0.913,2.834c-0.6,0.66 -1.478,0.989 -2.66,0.989c-1.173,0 -2.059,-0.329 -2.65,-0.989c-0.6,-0.658 -0.895,-1.601 -0.895,-2.834v-1.039h2.247v1.193c0,0.55 0.108,0.936 0.34,1.152c0.224,0.218 0.528,0.325 0.904,0.325c0.367,0 0.672,-0.107 0.904,-0.325c0.215,-0.216 0.331,-0.602 0.331,-1.152c0,-0.521 -0.116,-0.981 -0.34,-1.378c-0.24,-0.398 -0.535,-0.775 -0.867,-1.131c-0.358,-0.352 -0.734,-0.707 -1.119,-1.063c-0.403,-0.354 -0.779,-0.741 -1.119,-1.161c-0.358,-0.42 -0.645,-0.897 -0.869,-1.433c-0.233,-0.536 -0.349,-1.164 -0.349,-1.889l0,0z" fill="#D2D2D2"/>
                   <polygon id="svg_24" points="22.329,54.055 25.606,54.055 25.606,56.227 22.329,56.227 22.329,60.676 26.448,60.676    26.448,62.849 19.947,62.849 19.947,47.651 26.448,47.651 26.448,49.821 22.329,49.821 22.329,54.055  " fill="#D2D2D2"/>
                   <polygon id="svg_25" points="58.54,54.403 61.612,54.403 61.612,56.574 58.54,56.574 58.54,62.848 56.15,62.848 56.15,47.649    62.462,47.649 62.462,49.821 58.54,49.821 58.54,54.403  " fill="#D2D2D2"/>
                  </g>
                 </g>
                </svg>
            </div>
	           

	        <?php if($header_type == 'default'): ?>
	            <div class="span3 visible-desktop">
	                <?php if(etheme_get_option('header_phone') && etheme_get_option('header_phone') != ''): ?>
	                    <span class="search_text">
	                        <?php etheme_option('header_phone') ?>
	                    </span>
	                <?php endif; ?>
		            <?php if(etheme_get_option('search_form')): ?>
		                <div class="search_form">
		                    <?php get_search_form(); ?>
		                </div>
	                <?php endif; ?>
	            </div>
	            
	            <div class="span3 shopping_cart_wrap visible-desktop">
	
	                <?php if(class_exists('Woocommerce') && !etheme_get_option('just_catalog') && etheme_get_option('cart_widget')): ?>
	                    <div id="top-cart" class="shopping-cart-wrapper widget_shopping_cart">
	                        <?php $cart_widget = new Etheme_WooCommerce_Widget_Cart(); $cart_widget->widget(); ?> 
	                    </div>
	                <?php endif ;?> 
	                <div class="clear"></div>
	                <?php if(etheme_get_option('top_links')): ?>
	                    <?php  get_template_part( 'et-links' ); ?>
	                <?php endif; ?>
	            </div>
	    	<?php endif; ?>
	    	
		    <?php if($header_type == 'variant2' || $header_type == 'variant5' || $header_type == 'variant6'): ?>
	            <div id="main-nav">
	                <?php etheme_header_wp_navigation(); ?>
	            </div>
		    <?php endif; ?>
        </header> 
	    <?php if($header_type == 'default' || $header_type == 'variant3') etheme_header_menu(); ?>
    </div>
    <?php if($header_type == 'variant4') etheme_header_menu(); ?>
    
    <?php 
        get_template_part( 'et-styles' ); 
        if($etheme_responsive){
            get_template_part('large-resolution');
        }
    ?>
</div>

<?php 
if(is_home) {
// echo do_shortcode('[layerslider id="2"]');
echo do_shortcode('[rev_slider homepage]');
}
?>