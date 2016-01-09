<?php
/** 
 * Load option tree plugin
 */
    
add_filter( 'ot_show_pages', '__return_false' );
add_filter( 'ot_show_new_layout', '__return_false' );
add_filter( 'ot_theme_mode', '__return_true' );
load_template( trailingslashit( get_template_directory() ) . 'option-tree/ot-loader.php' );


global $etheme_responsive;

add_theme_support('woocommerce');

register_nav_menu('top', 'Top Navigation');

$just_catalog = etheme_get_option('just_catalog');
$etheme_responsive   = etheme_get_option('responsive');

$etheme_color_version = etheme_get_option('main_color_scheme');

if(isset($_COOKIE['responsive'])) {
	$etheme_responsive = false;
}

function remove_loop_button(){
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
	remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
	remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
	remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
}

if($just_catalog) {
    add_action('init','remove_loop_button');
}

if (!isset( $content_width )) $content_width = 920;

function etheme_enqueue_styles() {
	global $etheme_responsive, $etheme_color_version;

    $custom_css = etheme_get_option('custom_css');
    if ( !is_admin() ) {
        wp_enqueue_style("bootstrap",get_template_directory_uri().'/css/bootstrap.css');
        wp_enqueue_style("style",get_stylesheet_directory_uri().'/style.css');
        if($etheme_responsive){
            wp_enqueue_style("bootstrap-responsive",get_template_directory_uri().'/css/bootstrap-responsive.css');
            wp_enqueue_style("responsive",get_template_directory_uri().'/css/responsive.css');
        }
        wp_enqueue_style("slider",get_template_directory_uri().'/css/slider.css');
        wp_enqueue_style("font-awesome",get_template_directory_uri().'/css/font-awesome.min.css');
        wp_enqueue_style("cbpQTRotator",get_template_directory_uri().'/code/testimonials/assets/css/component.css');
        if($custom_css) {
            wp_enqueue_style("custom",get_template_directory_uri().'/custom.css');  
        }
        if($etheme_color_version=='dark') {
            wp_enqueue_style("dark",get_template_directory_uri().'/css/dark.css');  
        }
        
        wp_enqueue_style("open-sans","http://fonts.googleapis.com/css?family=Open+Sans");
        wp_enqueue_style("lato","http://fonts.googleapis.com/css?family=Lato:100,400");
    
        $script_depends = array();

        if(class_exists('WooCommerce')) {
            $script_depends = array('wc-add-to-cart-variation');
        }
            
        wp_enqueue_script("jquery");
        wp_enqueue_script('jquery.easing', get_template_directory_uri().'/js/jquery.easing.1.3.min.js',array(),false,true);
        wp_enqueue_script('cookie', get_template_directory_uri().'/js/cookie.js',array());
        wp_enqueue_script('jquery.nicescroll', get_template_directory_uri().'/js/jquery.nicescroll.min.js',array(),false,true);
        wp_enqueue_script('hoverIntent', get_template_directory_uri().'/js/hoverIntent.js',array(),false,true);
        wp_enqueue_script('jquery.slider', get_template_directory_uri().'/js/jquery.slider.js',array(),false,true);
        wp_enqueue_script('modernizr.custom', get_template_directory_uri().'/js/modernizr.custom.js');
        wp_enqueue_script('cbpQTRotator', get_template_directory_uri().'/js/jquery.cbpQTRotator.min.js',array(),false,true);
        wp_enqueue_script('jquery.inview', get_template_directory_uri().'/js/jquery.inview.js',array(),false,true);
        wp_enqueue_script('modals', get_template_directory_uri().'/js/modals.js',array(),false,true);
        wp_enqueue_script('tooltip', get_template_directory_uri().'/js/tooltip.js');
        wp_enqueue_script('prettyPhoto', get_template_directory_uri().'/js/jquery.prettyPhoto.js');
        wp_enqueue_script('et_masonry', get_template_directory_uri().'/js/jquery.masonry.min.js',array(),false,true);
        wp_enqueue_script('flexslider', get_template_directory_uri().'/js/jquery.flexslider-min.js',array(),false,true);
        wp_enqueue_script('etheme', get_template_directory_uri().'/js/script.js',$script_depends);
    }
    
	wp_dequeue_style('woocommerce_prettyPhoto_css');
	wp_enqueue_style( 'woocommerce_prettyPhoto_css', get_template_directory_uri().'/css/prettyPhoto.css');
 
}
/** Remove white space around shrtcodes */

//remove_filter( 'the_content', 'wpautop' );
//add_filter( 'the_content', 'wpautop' , 12);

add_action( 'wp_enqueue_scripts', 'etheme_enqueue_styles' );
function jsString($str='') { 
    return trim(preg_replace("/('|\"|\r?\n)/", '', $str)); 
} 

function etheme_get_the_category_list($separator = '', $parents='', $post_id = false){
	global $wp_rewrite;
	$categories = get_the_category( $post_id );
	if ( !is_object_in_taxonomy( get_post_type( $post_id ), 'category' ) )
		return apply_filters( 'the_category', '', $separator, $parents );

	if ( empty( $categories ) )
		return apply_filters( 'the_category', __( 'Uncategorized' ), $separator, $parents );

	$rel = "";

	$thelist = '';
	if ( '' == $separator ) {
		$thelist .= '<ul class="post-categories">';
		foreach ( $categories as $category ) {
			$thelist .= "\n\t<li>";
			switch ( strtolower( $parents ) ) {
				case 'multiple':
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, true, $separator );
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a></li>';
					break;
				case 'single':
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel . '>';
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, false, $separator );
					$thelist .= $category->name.'</a></li>';
					break;
				case '':
				default:
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a></li>';
			}
		}
		$thelist .= '</ul>';
	} else {
		$i = 0;
		foreach ( $categories as $category ) {
			if ( 0 < $i )
				$thelist .= $separator;
			switch ( strtolower( $parents ) ) {
				case 'multiple':
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, true, $separator );
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a>';
					break;
				case 'single':
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel . '>';
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, false, $separator );
					$thelist .= "$category->name</a>";
					break;
				case '':
				default:
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a>';
			}
			++$i;
		}
	}
	return apply_filters( 'the_category', $thelist, $separator, $parents );
}


function etheme_get_contents( $url ) {
	if ( function_exists('file_get_contents') ) {
		$output = file_get_contents( $url );
	}
	else {
		return false;
	}
	return $output;
}

function etheme_demo_alerts(){
    do_action('etheme_demo_alerts');
}

function get_header_type() {
	return etheme_get_option('header_type');
}

add_filter('custom_header_filter', 'get_header_type',10);

/* Header Template Parts */

function etheme_header_menu(){
	    	
	$menuClass = 'menu '.etheme_get_option('menu_type').'-menu';
	if(!etheme_get_option('menu_type')){
		$menuClass = 'menu default-menu';
	}
	?>
    <div class="row">
        <div id="main-nav" class="span12">
            <?php wp_nav_menu(array('theme_location' => 'top', 'name' => 'top', 'container' => 'div', 'container_class' => $menuClass)); ?>
        </div>
    </div> 
	<?php
}

function etheme_header_wp_navigation(){
	wp_nav_menu(array('theme_location' => 'top', 'name' => 'top', 'container' => 'div', 'container_class' => 'menu default-menu'));
}

function etheme_logo() {
	$logoimg = etheme_get_option('logo'); ?>
	<?php if($logoimg): ?>
		<a href="<?php echo home_url(); ?>"><img src="<?php echo $logoimg ?>" alt="<?php bloginfo( 'description' ); ?>" /></a>
	<?php else: ?>
		<a href="<?php echo home_url(); ?>"><span class="logo-text-red">ID</span>Store</a>
	<?php endif ;
}



add_action( 'after_setup_theme', 'et_promo_remove', 11 );
if(!function_exists('et_promo_remove')) {
	function et_promo_remove() {
		//update_option('et_close_promo_etag', 'ETag: "bca6c0-b9-500bba1239ca80"');
	}
}


if(!function_exists('et_show_promo_text')) {
	function et_show_promo_text() {
		$versionsUrl = 'http://8theme.com/import/';
		$ver = 'promo';
		$folder = $versionsUrl.''.$ver;
		
		$txtFile = $folder.'/idstore.txt';
		$file_headers = @get_headers($txtFile);
		
		$etag = $file_headers[4];
		
		$cached = false;
		$promo_text = false;
		
		$storedEtag = get_option('et_last_promo_etag');
		$closedEtag = get_option('et_close_promo_etag');
		
		if($etag == $storedEtag && $closedEtag != $etag) {
			$storedEtag = get_option('et_last_promo_etag');
			$promo_text = get_option('et_promo_text');
		} else if($closedEtag == $etag) {
			return;
		} else {
			$fileContent = file_get_contents($txtFile);
			update_option('et_last_promo_etag', $etag);
			update_option('et_promo_text', $fileContent);
		}
		
		if($file_headers[0] == 'HTTP/1.1 200 OK') {
			echo '<div class="promo-text-wrapper">';
				if(!$promo_text && isset($fileContent)) {
					echo $fileContent;
				} else {
					echo $promo_text;
				}	
				echo '<div class="close-btn" title="Hide promo text">x</div>';	
			echo '</div>';	
		}
	}
}

add_action("wp_ajax_et_close_promo", "et_close_promo");
add_action("wp_ajax_nopriv_et_close_promo", "et_close_promo");
if(!function_exists('et_close_promo')) {
	function et_close_promo() {
		$versionsUrl = 'http://8theme.com/import/';
		$ver = 'promo';
		$folder = $versionsUrl.''.$ver;
		
		$txtFile = $folder.'/idstore.txt';
		$file_headers = @get_headers($txtFile);
		
		$etag = $file_headers[4];
		$res = update_option('et_close_promo_etag', $etag);
		die();
	}
}

/**
* Function for disabling Responsive layout
*
*/

function etheme_set_responsive() {
	if(isset($_GET['responsive']) && $_GET['responsive'] == 'off') {
		if (!isset($_COOKIE['responsive'])) {
			setcookie('responsive', 1, time()+1209600, COOKIEPATH, COOKIE_DOMAIN, false);
		}
	    $redirect_to = $_SERVER['HTTP_REFERER'];
		wp_redirect($redirect_to); exit();
	}elseif(isset($_GET['responsive']) && $_GET['responsive'] == 'on') {
		if (isset($_COOKIE['responsive'])) {
			setcookie('responsive', 1, time()-1209600, COOKIEPATH, COOKIE_DOMAIN, false);
		}
	    $redirect_to = $_SERVER['HTTP_REFERER'];
		wp_redirect($redirect_to); exit();
	}
}
if(etheme_get_option('responsive'))
	add_action( 'init', 'etheme_set_responsive');




function etheme_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'etheme_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @return int
 */
function etheme_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'etheme_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @return string "Continue Reading" link
 */
function etheme_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', ETHEME_DOMAIN ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and etheme_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @return string An ellipsis
 */
function etheme_auto_excerpt_more( $more ) {
	return ' &hellip;' . etheme_continue_reading_link();
}
add_filter( 'excerpt_more', 'etheme_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function etheme_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= etheme_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'etheme_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Deprecated way to remove inline styles printed when the gallery shortcode is used.
 *
 * This function is no longer needed or used. Use the use_default_gallery_style
 * filter instead, as seen above.
 *
 *
 * @return string The gallery style filter, with the styles themselves removed.
 */
function etheme_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
// Backwards compatibility with WordPress 3.0.
if ( version_compare( $GLOBALS['wp_version'], '3.1', '<' ) )
	add_filter( 'gallery_style', 'etheme_remove_gallery_css' );

if ( ! function_exists( 'etheme_comment' ) ) :
function etheme_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
            <?php echo get_avatar( $comment, 55 ); ?>
            <div class="comment-meta">
                <h5 class="author"><?php echo get_comment_author_link() ?> / <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></h5>	
                <?php if ( $comment->comment_approved == '0' ) : ?>
        			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', ETHEME_DOMAIN ); ?></em>	
        		<?php endif; ?>
                <p class="date">
        			<?php
        				/* translators: 1: date, 2: time */
        				printf( __( '%1$s at %2$s', ETHEME_DOMAIN ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', ETHEME_DOMAIN ), ' ' );
        			?>
                </p>
            </div>
    		<div class="comment-body"><?php comment_text(); ?></div>
            <div class="clear"></div>
<!-- .reply -->
    	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', ETHEME_DOMAIN ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', ETHEME_DOMAIN ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * This function uses a filter (show_recent_comments_widget_style) new in WordPress 3.1
 * to remove the default style. Using Twenty Ten 1.2 in WordPress 3.0 will show the styles,
 * but they won't have any effect on the widget in default Twenty Ten styling.
 *
 */
function etheme_remove_recent_comments_style() {
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}
add_action( 'widgets_init', 'etheme_remove_recent_comments_style' );

if ( ! function_exists( 'etheme_posted_on' ) ) :
function etheme_posted_on() {
	printf( __( '<span class="%1$s"></span> %2$s', ETHEME_DOMAIN ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		)
	);
}
endif;
if ( ! function_exists( 'etheme_posted_by' ) ) :
function etheme_posted_by() {
	printf( __( '<span class="%1$s">Posted by</span> %2$s', ETHEME_DOMAIN ),
		'meta-author',
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			esc_attr( sprintf( __( 'View all posts by %s', ETHEME_DOMAIN ), get_the_author() ) ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'etheme_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
function etheme_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', ETHEME_DOMAIN );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', ETHEME_DOMAIN );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', ETHEME_DOMAIN );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		etheme_get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

function etheme_excerpt_more($more) {
    global $post;
    return '<br><a class="button fl-r" style="margin-bottom:10px;" href="'. get_permalink($post->ID) . '"><span>'.__('Read More',ETHEME_DOMAIN).'</span></a><div class="clear"></div>';
}
add_filter('excerpt_more', 'etheme_excerpt_more');

function etheme_get_image( $attachment_id = 0, $width = null, $height = null, $crop = true, $post_id = null ) {
	global $post;
	if (!$attachment_id) {
		if (!$post_id) {
			$post_id = $post->ID;
		}
		if ( has_post_thumbnail( $post_id ) ) {
			$attachment_id = get_post_thumbnail_id( $post_id );
		} 
		else {
			$attached_images = (array)get_posts( array(
				'post_type'   => 'attachment',
				'numberposts' => 1,
				'post_status' => null,
				'post_parent' => $post_id,
				'orderby'     => 'menu_order',
				'order'       => 'ASC'
			) );
			if ( !empty( $attached_images ) )
				$attachment_id = $attached_images[0]->ID;
		}
	}
	
	if (!$attachment_id)
		return;
		
	$image_url = etheme_get_resized_url($attachment_id,$width, $height, $crop);
	
	return apply_filters( 'blanco_product_image', $image_url );
}


// **********************************************************************// 
// ! Registration 
// **********************************************************************// 
add_action( 'wp_ajax_et_register_action', 'et_register_action' );
add_action( 'wp_ajax_nopriv_et_register_action', 'et_register_action' );
if(!function_exists('et_register_action')) {
	function et_register_action() {
		global $wpdb, $user_ID;
		$captcha_instance = new ReallySimpleCaptcha();
		if(!$captcha_instance->check( $_REQUEST['captcha-prefix'], $_REQUEST['captcha-word'] )) {
			$return['status'] = 'error';
			$return['msg'] = __('The security code you entered did not match. Please try again.', ETHEME_DOMAIN);
			echo json_encode($return);
			die();
		}
	    if(!empty($_REQUEST)){
	        //We shall SQL escape all inputs
	        $username = esc_sql($_REQUEST['username']);
	        if(empty($username)) {
				$return['status'] = 'error';
				$return['msg'] = __( "User name should not be empty.", ETHEME_DOMAIN );
				echo json_encode($return);
	            die();
	        }
	        $email = esc_sql($_REQUEST['email']);
	        if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) {
				$return['status'] = 'error';
				$return['msg'] = __( "Please enter a valid email.", ETHEME_DOMAIN );
				echo json_encode($return);
	            die();
	        }
	        $pass = esc_sql($_REQUEST['pass']);
	        $pass2 = esc_sql($_REQUEST['pass2']);
	        if(empty($pass) || strlen($pass) < 5) {
				$return['status'] = 'error';
				$return['msg'] = __( "Password should have more than 5 symbols", ETHEME_DOMAIN );
				echo json_encode($return);
	            die();
	        }
	        if($pass != $pass2) {
				$return['status'] = 'error';
				$return['msg'] = __( "The passwords do not match", ETHEME_DOMAIN );
				echo json_encode($return);
	            die();
	        }
	        
	        $status = wp_create_user( $username, $pass, $email );
	        if ( is_wp_error($status) ) {
				$return['status'] = 'error';
				$return['msg'] = __( "Username already exists. Please try another one.", ETHEME_DOMAIN );
				echo json_encode($return);
	        }
	        else {
	            $from = get_bloginfo('name');
	            $from_email = get_bloginfo('admin_email');
	            $headers = 'From: '.$from . " <". $from_email .">\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	            $subject = __("Registration successful", ETHEME_DOMAIN);
	            $message = et_registration_email($username);
	            wp_mail( $email, $subject, $message, $headers );
				$return['status'] = 'success';
				$return['msg'] = __( "Please check your email for login details.", ETHEME_DOMAIN );
				echo json_encode($return);
	        }
	        die();
	    } 
	}
}

if(!function_exists('et_registration_email')) {
	function et_registration_email($username = '') {
        global $woocommerce;
        $logoimg = etheme_get_option('logo');
        $logoimg = apply_filters('etheme_logo_src',$logoimg);
		ob_start(); ?>
			<div style="background-color: #f5f5f5;width: 100%;-webkit-text-size-adjust: none;margin: 0;padding: 70px 0 70px 0;">
				<div style="-webkit-box-shadow: 0 0 0 3px rgba(0,0,0,0.025) ;box-shadow: 0 0 0 3px rgba(0,0,0,0.025);-webkit-border-radius: 6px;border-radius: 6px ;background-color: #fdfdfd;border: 1px solid #dcdcdc; padding:20px; margin:0 auto; width:500px; max-width:100%; color: #737373; font-family:Arial; font-size:14px; line-height:150%; text-align:left;">
			        <?php if($logoimg): ?>
			            <a href="<?php echo home_url(); ?>" style="display:block; text-align:center;"><img style="max-width:100%;" src="<?php echo $logoimg ?>" alt="<?php bloginfo( 'description' ); ?>" /></a>
			        <?php else: ?>
			            <a href="<?php echo home_url(); ?>" style="display:block; text-align:center;"><img style="max-width:100%;" src="<?php echo PARENT_URL.'/images/logo.png'; ?>" alt="<?php bloginfo('name'); ?>"></a>
			        <?php endif ; ?>
					<p><?php printf(__('Thanks for creating an account on %s. Your username is %s.', ETHEME_DOMAIN), get_bloginfo( 'name' ), $username);?></p>
					<?php if (class_exists('Woocommerce')): ?>
					
						<p><?php printf(__('You can access your account area to view your orders and change your password here: <a href="%s">%s</a>.', ETHEME_DOMAIN), get_permalink( get_option('woocommerce_myaccount_page_id') ), get_permalink( get_option('woocommerce_myaccount_page_id') ));?></p>
					
					<?php endif; ?>
					
				</div>
			</div>
		<?php 
	    $output = ob_get_contents();
	    ob_end_clean();
	    return $output;
	}
}


function etheme_get_images($width = null, $height = null, $crop = true, $post_id = null ) {
	global $post;
	
	if (!$post_id) {
		$post_id = $post->ID;
	}	
	
	if ( has_post_thumbnail( $post_id ) ) {
		$attachment_id = get_post_thumbnail_id( $post_id );
	} 
	
	$args = array(
	    'post_type' => 'attachment',
	    'post_status' => null,
	    'post_parent' => $post_id,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'exclude' => get_post_thumbnail_id( $post_id )
	);
	
	$attachments = get_posts($args);
	
	if (empty( $attachments) && empty($attachment_id))
		return;
		
	$image_urls = array();

	if(!empty($attachment_id))
		$image_urls[] = etheme_get_resized_url($attachment_id,$width, $height, $crop);
		
	foreach($attachments as $one) {
		$image_urls[] = etheme_get_resized_url($one->ID,$width, $height, $crop);
	}

	return apply_filters( 'blanco_attachment_image', $image_urls );
}

function etheme_get_resized_url($id,$width, $height, $crop) {
	if ( function_exists("gd_info") && (($width >= 10) && ($height >= 10)) && (($width <= 1024) && ($height <= 1024)) ) {
		$vt_image = vt_resize( $id, '', $width, $height, $crop );
		if ($vt_image) 
			$image_url = $vt_image['url'];
		else
			$image_url = false;
	}
	else {
		$full_image = wp_get_attachment_image_src( $id, 'full');
		if (!empty($full_image[0]))
			$image_url = $full_image[0];
		else
			$image_url = false;
	}
	
    if( is_ssl() && !strstr(  $image_url, 'https' ) ) str_replace('http', 'https', $image_url);
    
    return $image_url;
}

if ( !function_exists('vt_resize') ) {
	function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
	
		// this is an attachment, so we have the ID
		if ( $attach_id ) {
		
			$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
			$file_path = get_attached_file( $attach_id );
		
		// this is not an attachment, let's use the image url
		} else if ( $img_url ) {
			
			$file_path = parse_url( $img_url );
			$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
			
			//$file_path = ltrim( $file_path['path'], '/' );
			//$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];
			
			$orig_size = getimagesize( $file_path );
			
			$image_src[0] = $img_url;
			$image_src[1] = $orig_size[0];
			$image_src[2] = $orig_size[1];
		}
		
		$file_info = pathinfo( $file_path );
	
		// check if file exists
		$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
		if ( !file_exists($base_file) )
			return;
		 
		$extension = '.'. $file_info['extension'];
	
		// the image path without the extension
		$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
		
		// checking if the file size is larger than the target size
		// if it is smaller or the same size, stop right here and return
		if ( $image_src[1] > $width || $image_src[2] > $height ) {
	
			if ( $crop == true ) {
			
				$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
				
				// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
				if ( file_exists( $cropped_img_path ) ) {
		
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					
					$vt_image = array (
						'url' => $cropped_img_url,
						'width' => $width,
						'height' => $height
					);
					
					return $vt_image;
				}
			}
			elseif ( $crop == false ) {
			
				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
	
				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {
				
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
	
					$vt_image = array (
						'url' => $resized_img_url,
						'width' => $proportional_size[0],
						'height' => $proportional_size[1]
					);
					
					return $vt_image;
				}
			}
	
			// check if image width is smaller than set width
			$img_size = getimagesize( $file_path );
			if ( $img_size[0] <= $width ) $width = $img_size[0];		
	
			// no cache files - let's finally resize it
			$new_img_path = image_resize( $file_path, $width, $height, $crop );
			$new_img_size = getimagesize( $new_img_path );
			$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
	
			// resized output
			$vt_image = array (
				'url' => $new_img,
				'width' => $new_img_size[0],
				'height' => $new_img_size[1]
			);
			
			return $vt_image;
		}
	
		// default output - without resizing
		$vt_image = array (
			'url' => $image_src[0],
			'width' => $image_src[1],
			'height' => $image_src[2]
		);
		
		return $vt_image;
	}
}

if ( !function_exists('vt_resize2') ) {
	function vt_resize2( $img_name, $dir_url, $dir_path, $width, $height, $crop = false ) {
		
		$file_path = trailingslashit($dir_path).$img_name;
		
		$orig_size = getimagesize( $file_path );
		
		$image_src[0] = trailingslashit($dir_url).$img_name;
		$image_src[1] = $orig_size[0];
		$image_src[2] = $orig_size[1];
		
		$file_info = pathinfo( $file_path );
	
		// check if file exists
		$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
		if ( !file_exists($base_file) )
			return;
		 
		$extension = '.'. $file_info['extension'];
	
		// the image path without the extension
		$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
		
		// checking if the file size is larger than the target size
		// if it is smaller or the same size, stop right here and return
		if ( $image_src[1] > $width || $image_src[2] > $height ) {
	
			if ( $crop == true ) {
			
				$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
				
				// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
				if ( file_exists( $cropped_img_path ) ) {
		
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					
					$vt_image = array (
						'url' => $cropped_img_url,
						'width' => $width,
						'height' => $height
					);
					
					return $vt_image;
				}
			}
			elseif ( $crop == false ) {
			
				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
	
				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {
				
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
	
					$vt_image = array (
						'url' => $resized_img_url,
						'width' => $proportional_size[0],
						'height' => $proportional_size[1]
					);
					
					return $vt_image;
				}
			}
	
			// check if image width is smaller than set width
			$img_size = getimagesize( $file_path );
			if ( $img_size[0] <= $width ) $width = $img_size[0];		
	
			// no cache files - let's finally resize it
			$new_img_path = image_resize( $file_path, $width, $height, $crop );
			$new_img_size = getimagesize( $new_img_path );
			$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
	
			// resized output
			$vt_image = array (
				'url' => $new_img,
				'width' => $new_img_size[0],
				'height' => $new_img_size[1]
			);
			
			return $vt_image;
		}
	
		// default output - without resizing
		$vt_image = array (
			'url' => $image_src[0],
			'width' => $image_src[1],
			'height' => $image_src[2]
		);
		
		return $vt_image;
	}
}

function etheme_product_page_banner(){
    global $post;
    $etheme_productspage_id = etheme_shortcode2id('[productspage]');
    if($post->ID == $etheme_productspage_id && etheme_get_option('product_bage_banner') && etheme_get_option('product_bage_banner') != ''):
    ?>
        <div class="wpsc_category_details">
            <img src="<?php etheme_option('product_bage_banner') ?>"/>              
        </div>
    <?php endif;
}

function blog_breadcrumbs() {

  $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $delimiter = '<span class="delimeter">/</span>'; // delimiter between crumbs
  $home = __('Home', ETHEME_DOMAIN); // text for the 'Home' link
  $blogPage = 'Blog';
  $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
  
  global $post;
  $homeLink = home_url();
  
  if (is_front_page()) {
  
    if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
  
  } else {
  
    echo '<div class="span12 breadcrumbs">';
    echo '<div id="breadcrumb">';
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
  
    if ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
      echo $before . __('Archive by category', ETHEME_DOMAIN) .' "' . single_cat_title('', false) . '"' . $after;
  
    } elseif ( is_search() ) {
      echo $before . __('Search results for', ETHEME_DOMAIN) .' "' . get_search_query() . '"' . $after;
  
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
  
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
  
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
  
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() == 'etheme_portfolio' ) {
        $portfolioId = etheme_tpl2id('portfolio.php'); 
        $portfolioLink = get_permalink($portfolioId);
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $portfolioLink . '/">' . $post_type->labels->name . '</a>';
        if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      } elseif ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
        if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        echo $cats;
        if ($showCurrent == 1) echo $before . get_the_title() . $after;
      }
  
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
  
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
  
    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $before . get_the_title() . $after;
  
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
  
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
  
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;
  
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }else{
        
        echo $blogPage;
    }
  
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo ' ('.__('Page') . ' ' . get_query_var('paged').')';
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
  
    echo '</div>';
    echo '<a class="back-to" href="javascript: history.go(-1)"><span>‹</span>'.__('Return to Previous Page',ETHEME_DOMAIN).'</a></div>';
  
  }
}

// Add GOOGLE fonts
function etheme_recognized_google_font_families( $array, $field_id = false ) {
	$array = array(
		'Open+Sans'           => '"Open Sans", sans-serif',
		'Droid+Sans'          => '"Droid Sans", sans-serif',
		'Lato'                => '"Lato"',
		'Cardo'               => '"Cardo"',
		'Fauna+One'           => '"Fauna One"',
		'Oswald'              => '"Oswald"',
		'Yanone+Kaffeesatz'   => '"Yanone Kaffeesatz"',
		'Muli'                => '"Muli"'
	);
	
	return $array;
	
}

function etheme_get_chosen_google_font() {
	$chosenFonts = array();
	$fontOptions = array();
    $fontOptions[] = etheme_get_option('h1'); 
    $fontOptions[] = etheme_get_option('h2'); 
    $fontOptions[] = etheme_get_option('h3');  
    $fontOptions[] = etheme_get_option('h4');  
    $fontOptions[] = etheme_get_option('h5'); 
    $fontOptions[] = etheme_get_option('h6');  
    $fontOptions[] = etheme_get_option('sfont'); 
    
    foreach($fontOptions as $value){
    	if($value['google-font'] != '')
		    $chosenFonts[] = $value['google-font'];
    }
    
    return $chosenFonts;
    
}

// Footer Demo Blocks
function etheme_footer_demo($block){
	switch ($block) {
		case 1:
			?>
		        <span class="footer_title">Our Contacts</span>
		        <p class="footer-home">
		        	<i class="icon-home"></i>
		            United Kingdom, London
		            <br>
		            Simple Street 15A
		        </p>
		        <p class="footer-phone">
		        	<i class="icon-phone"></i>
		            (123) 123.456.7890
		            <br>
		            (123) 123.456.7890
		        </p>
		        <p class="footer-mail">
		        	<i class="icon-envelope-alt"></i>
		            megashop@info.com
		            <br>
		            megashop@holding.com
		        </p>
			<?php 
		break;
		case 2:
			?>	                        
				<span class="footer_title">About Our Shop</span>
	            <p>
	            Lorem Ipsum is simply dummy text of the printing and typesetting 
	            industry. Lorem Ipsum has been the industry's standard dummy text 
	            ever since the 1500s, when an unknown printer took a galley of type 
	            and scrambled it to make a type specimen book. It has survived not 
	            only five centuries, but also the leap into electronic typesetting, 
	            remaining.
	            </p>
			<?php 
		break;
		case 3:
			?>
				<span class="footer_title">Flickr</span>
				<div class="footer_thumbs">
					<ul class="img-list">
						<li class="flickr-photo ">
							<a href="http://www.flickr.com/photos/we-are-envato/8954733698" target="_blank">
								<img src="http://farm4.static.flickr.com/3820/8954733698_a2646a7642_s.jpg" alt="Author Guilherme Salum (DD Studios) at work in his studio" width="60" height="60">
							</a>
						</li>
						<li class="flickr-photo ">
							<a href="http://www.flickr.com/photos/we-are-envato/8953389435" target="_blank">
								<img src="http://farm4.static.flickr.com/3685/8953389435_e5caf8d988_s.jpg" alt="Checking out the outdoor space" width="60" height="60">
							</a>
						</li>
						<li class="flickr-photo footer_thumbs_last-child">
							<a href="http://www.flickr.com/photos/we-are-envato/8954585074" target="_blank">
								<img src="http://farm4.static.flickr.com/3795/8954585074_a38ff86602_s.jpg" alt="The team listening to what the next few months holds for the company" width="60" height="60">
							</a>
						</li>
						<li class="flickr-photo ">
							<a href="http://www.flickr.com/photos/we-are-envato/8954585316" target="_blank">
								<img src="http://farm3.static.flickr.com/2879/8954585316_60966c9a23_s.jpg" alt="Selina and Collis" width="60" height="60">
							</a>
						</li>
						<li class="flickr-photo ">
							<a href="http://www.flickr.com/photos/we-are-envato/8954584978" target="_blank">
								<img src="http://farm8.static.flickr.com/7346/8954584978_00d1041821_s.jpg" alt="Collis speaking to the team" width="60" height="60">
							</a>
						</li>
						<li class="flickr-photo footer_thumbs_last-child">
							<a href="http://www.flickr.com/photos/we-are-envato/8953388295" target="_blank">
								<img src="http://farm8.static.flickr.com/7301/8953388295_b5ef30267f_s.jpg" alt="Cyan finds Collis" presentation="" pretty="" width="60" height="60">
							</a>
						</li>
					</ul>
				</div>
			<?php 
		break;
		case 4:
			?>
		        <span class="footer_title">STORES</span>
		        <ul class="footer_menu">
		        <li><a href="#">New York</a></li>
		        <li><a href="#">Paris</a></li>
		        <li><a href="#">London</a></li>
		        <li><a href="#">Madrid</a></li>
		        <li><a href="#">Tokio</a></li>
		        <li><a href="#">Milan</a></li>
		        <li><a href="#">Hong Kong</a></li>
		        </ul>
			<?php 
		break;
		case 5:
			?>
	            <span class="footer_title">Our Offers</span>
	            <ul class="footer_menu">
	            <li><a href="#">New products</a></li>
	            <li><a href="#">Top sellers</a></li>
	            <li><a href="#">Specials</a></li>
	            <li><a href="#">Manufacturers</a></li>
	            <li><a href="#">Suppliers</a></li>
	            <li><a href="#">Specials</a></li>
	            <li><a href="#">Customer Service</a></li>
	            </ul>
			<?php 
		break;
		case 6:
			?>
		        <span class="footer_title">Our Services</span>
		        <ul class="footer_menu">
		        <li><a href="#">Order tracking</a></li>
		        <li><a href="#">Privacy Policy</a></li>
		        <li><a href="#">Gift Cards</a></li>
		        <li><a href="#">Shipping Information</a></li>
		        <li><a href="#">Returns & refunds</a></li>
		        <li><a href="#">Personalised Cards</a></li>
		        <li><a href="#">Delivery information</a></li>
		        </ul>
			<?php 
		break;
		case 7:
			?>
	            <span class="footer_title">Our Offers</span>
	            <img src="<?php echo get_template_directory_uri();?>/images/label_2-1.png" class="footer-logo"alt=""/>
	            <br>
	            <img src="<?php echo get_template_directory_uri();?>/images/label_3-1.png"class="footer-logo2" alt=""/> 
	            <img src="<?php echo get_template_directory_uri();?>/images/label_1-1.png"class="footer-logo3" alt=""/>        
			<?php 
		break;
		case 8:
			?>
	            <ul class="footer_copyright_menu">
	                <li><a href="#">Site Map</a> / </li>
	                <li><a href="#">Advanced Search</a> / </li>
	                <li><a href="#">Orders and Returns</a> / </li>
	                <li><a href="#">Contact Us</a></li>
	            </ul>    
			<?php 
		break;
		case 9:
			?>
		        <ul class="footer_copyright_payments hidden-phone">
		            <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/images/1363982755_paypal.png" alt=""/></a></li>
		            <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/images/1363982759_mastercard.png" alt=""/></a></li>
		            <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/images/1363984018_visa.png" alt=""/></a></li>
		            <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/images/1363982767_discover.png" alt=""/></a></li>
		            <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/images/1363982770_maestro.png" alt=""/></a></li>
		            <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/images/1363982772_google_checkout.png" alt=""/></a></li>
		            <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/images/1363982777_cirrus.png" alt=""/></a></li>
		        </ul>     
			<?php 
		break;
		case 7:
			?>
	            <span class="footer_title">Our Offers</span>
	            <img src="<?php echo get_template_directory_uri();?>/images/mcafee_antivirus_logo_images-1.png" class="footer-logo"alt=""/>
	            <br>
	            <img src="<?php echo get_template_directory_uri();?>/images/ab-seal-horizontal-large.png"class="footer-logo2" alt=""/>         
			<?php 
		break;
		case 7:
			?>
	            <span class="footer_title">Our Offers</span>
	            <img src="<?php echo get_template_directory_uri();?>/images/mcafee_antivirus_logo_images-1.png" class="footer-logo"alt=""/>
	            <br>
	            <img src="<?php echo get_template_directory_uri();?>/images/ab-seal-horizontal-large.png"class="footer-logo2" alt=""/>         
			<?php 
		break;
	}
}

function prar($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}