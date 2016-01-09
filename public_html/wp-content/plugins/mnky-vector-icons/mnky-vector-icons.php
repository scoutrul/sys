<?php
	/*
	  Plugin Name: MNKY Vector Icons Plugin
	  Plugin URI: http://www.mnkystudio.com/plugins/
	  Version: 1.2
	  Author: MNKY Studio
	  Author URI: http://www.mnkystudio.com/
	  Description: Vector icon plugin based on iconic font collections
	*/

	
	/*	
	*	---------------------------------------------------------------------
	*	Compatibility mode
	*	Set to TRUE to enable compatibility mode - [v_icon]
	*	--------------------------------------------------------------------- 
	*/

	define( 'VI_SAFE_MODE', apply_filters( 'vi_safe_mode', FALSE ) );
	
	
	/* Setup perfix */
	function mnky_compatibility_mode() {
		$prefix = ( VI_SAFE_MODE == true ) ? 'v_' : '';
		return $prefix;
	}

	

	/*	
	*	---------------------------------------------------------------------
	*	Setup plugin
	*	--------------------------------------------------------------------- 
	*/
		
	function mnky_plugin_init() {
	
		add_filter( 'widget_text', 'do_shortcode' );
		@ini_set( 'pcre.backtrack_limit', 500000 );

		wp_register_style( 'jquery-ui-theme', 'http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css', false, '', 'all' );
		wp_register_style( 'icon-font-style', mnky_plugin_url() . '/css/icon-font-style.css', false, '', 'all' );
		wp_register_style( 'mnky-icon-generator', mnky_plugin_url() . '/css/generator.css', false, '', 'all' );
		wp_register_script( 'mnky-icon-generator', mnky_plugin_url() . '/js/generator.js', array( 'jquery' ), '', false );

		if ( !is_admin() ) {
		
			wp_enqueue_style( 'icon-font-style' );
			
		} elseif ( is_admin() ) {
		
			wp_enqueue_style( 'icon-font-style' );
		
			global $pagenow;
			$mnky_generator_includes_pages = array( 'post.php', 'edit.php', 'post-new.php', 'index.php', 'edit-tags.php');
			
			if ( in_array( $pagenow, $mnky_generator_includes_pages ) ) {
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_style( 'farbtastic' );
				wp_enqueue_style( 'jquery-ui-theme' );
				wp_enqueue_style( 'mnky-icon-generator' );	
				

				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-slider' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_script( 'farbtastic' );		
				wp_enqueue_script( 'mnky-icon-generator' );		
				
			}
		}
	}
	
	add_action( 'init', 'mnky_plugin_init' );
	
	
	
	/*	
	*	---------------------------------------------------------------------
	*	IE7 compatibility
	*	--------------------------------------------------------------------- 
	*/

	function mnky_ie7() { ?>
		<!--[if lte IE 7]>
			<link href="<?php echo mnky_plugin_url() ?>/css/ie7.min.css" media="screen" rel="stylesheet" type="text/css">
		<![endif]-->
	<?php }
	
	add_action('wp_head', 'mnky_ie7');


	
	/*	
	*	---------------------------------------------------------------------
	*	Plugin URL
	*	--------------------------------------------------------------------- 
	*/
	
	function mnky_plugin_url() {
		return plugins_url( basename( __FILE__, '.php' ), dirname( __FILE__ ) );
	}


	
	/*	
	*	---------------------------------------------------------------------
	*	Icon generator button
	*	--------------------------------------------------------------------- 
	*/
	
	function mnky_generator_button( $page = null, $target = null ) {
		echo '<a href="#" class="button" title="Add Icon" id="mnky-generator-button"><span class="mnky-button-icon"></span>Add Icon</a>';
	}

	add_action( 'media_buttons', 'mnky_generator_button', 100 );
		
	

	/*	
	*	---------------------------------------------------------------------
	*	Icon generator box
	*	--------------------------------------------------------------------- 
	*/

	function mnky_generator() {
		
		include_once 'inc/list.php'; ?>
		<div id="mnky-generator-overlay" class="mnky-overlay-bg" style="display:none"></div>
		<div id="mnky-generator-wrap" style="display:none">
			<div id="mnky-generator">
				<a href="#" id="mnky-generator-close"><span class="mnky-close-icon"></span></a>
				<div id="mnky-generator-shell">
					
					<table border="0">
						<tr>
							<td class="generator-title">
								<span>Icon pack:</span>
							</td>							
							<td>
								<select name="icon-pack" id="mnky-generator-select-pack">
								   <option value="fontawesome-icons-list">Font Awesome icons</option>
								   <option value="moon-icons-list">Moon icons</option>
								   <option value="zocial-icons-list">Zocial icons</option>
								   <option value="loop-icons-list">Loop icons</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="generator-title">
								<span>Icon color:</span>
							</td>					
							<td>
								<span class="mnky-generator-select-color"><span class="mnky-generator-select-color-wheel"></span><input type="text" name="color" value="#444444" id="mnky-generator-attr-icon" class="mnky-generator-attr mnky-generator-select-color-value" /></span>
							</td>
							<td class="size-info">
								<!-- dummy space -->
							</td>	
							<td class="generator-title">
								<span>Icon style:</span>
							</td>							
							<td>
								<select name="style" class="mnky-generator-attr mnky-generator-select-style">
								   <option value="">None</option>
								   <option value="icon-background">With background</option>
								   <option value="metro-background">With background (METRO)</option>
								   <option value="icon-spin">Spin icon</option>
								   <option value="pull-right">Align right</option>
								   <option value="pull-left">Align left</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="generator-title">
								<span>Icon size:</span>
							</td>
							<td>
								<div id="mnky-generator-size-slider"></div>
							</td>
							<td class="size-info">
								<input type="text" id="mnky-generator-icon-size" class="mnky-generator-attr" name="size" />
							</td>	
							<td class="generator-title">
								<span>On mouseover:</span>
							</td>					
							<td>
								<select name="hover" class="mnky-generator-attr mnky-generator-select-hover">
								   <option value="">No effect</option>
								   <option value="fade">Fade</option>
								   <option value="show-color">Show color</option>
								</select>
							</td>
						</tr>
					</table>
					
					<div class="mnky-generator-icon-select">
						<ul class="fontawesome-icon-list">
						<?php 
						foreach ( $mnky_icon_list['fontawesome'] as $font_awesome_icon ) {
							$selected_icon = ( 'awesome-adjust' == $font_awesome_icon ) ? ' checked' : '';
							echo '<li><input name="name" type="radio" value="' . $font_awesome_icon . '" id="' . $font_awesome_icon . '" '. $selected_icon .' ><label for="' . $font_awesome_icon . '"><i class="' . $font_awesome_icon . '"></i></label></li>';
						} 
						?>
						</ul>
						<ul class="zocial-icon-list" style="display:none">
						<?php 
						foreach ( $mnky_icon_list['zocial'] as $zocial_icon ) {
							echo '<li><input name="name" type="radio" value="' . $zocial_icon . '" id="' . $zocial_icon . '"><label for="' . $zocial_icon . '"><i class="' . $zocial_icon . '"></i></label></li>';
						} 
						?>
						</ul>
						<ul class="loop-icon-list" style="display:none">
						<?php 
						foreach ( $mnky_icon_list['loop'] as $loop_icon ) {
							echo '<li><input name="name" type="radio" value="' . $loop_icon . '" id="' . $loop_icon . '"><label for="' . $loop_icon . '"><i class="' . $loop_icon . '"></i></label></li>';
						} 
						?>
						</ul>
						<ul class="moon-icon-list" style="display:none">
						<?php 
						foreach ( $mnky_icon_list['moon'] as $moon_icon ) {
							echo '<li><input name="name" type="radio" value="' . $moon_icon . '" id="' . $moon_icon . '"><label for="' . $moon_icon . '"><i class="' . $moon_icon . '"></i></label></li>';
						} 
						?>
						</ul>
					</div>
					
					<div id="mnky-shortcode-html" style="display:none">
						<span class="generator-title">HTML code:</span>
						<a href="#" id="mnky-generator-close-html"><span class="mnky-close-icon"></span></a>
						<textarea></textarea>
						<span class="mnky-generator-note">Use this html code to place icon where shortcodes are not supported. For example, into slider or into another shortcode. Or to add some customization.</span>
					</div>
					
					<input name="mnky-generator-insert" type="submit" class="button button-primary button-large" id="mnky-generator-insert" value="Insert Icon">
					<input name="mnky-generator-show-html" type="submit" class="button" id="mnky-generator-show-html" value="Get HTML code">
					<div class="mnky-clear"></div>
					
					<input type="hidden" name="mnky-generator-url" id="mnky-generator-url" value="<?php echo mnky_plugin_url(); ?>" />
					<input type="hidden" name="mnky-generator-result" id="mnky-generator-result" value="" />
					<input type="hidden" name="mnky-compatibility-mode" id="mnky-compatibility-mode" value="<?php echo mnky_compatibility_mode(); ?>" />
				</div>
			</div>
		</div>
		
	<?php
	}

	add_action( 'admin_footer', 'mnky_generator' );
		
	
	
	/*	
	*	---------------------------------------------------------------------
	*	Execute shortcode
	*	--------------------------------------------------------------------- 
	*/

	function mnky_icon_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'color' => '#444444',
				'size' => '25px',
				'style' => '',
				'hover' => '',
				'name' => 'icon-ok'
				), $atts ) );
				
		if ($style == 'icon-background' || $style == 'metro-background') { $element_style = 'color:#fff; background-color:' . $color; } else { $element_style = 'color:' . $color; }
		if ($style) { $style = ' ' . $style; } else { $style = ''; }
		
		if	($hover == 'show-color') { 
			if ($style == ' icon-background') {
				$return = '<span style="color:' . $color . '"><i style="font-size:' . $size . '; background-color:#fff;" class="' . $name . ' icon-background hover-show-color-bg"></i></span>';
			} elseif ($style == ' metro-background') {
				$return = '<span style="background-color:' . $color . '"><i style="font-size:' . $size . ';" class="' . $name . ' metro-background hover-show-color-metro-bg"></i></span>';
			} else {
				$return = '<span style="color:' . $color . '"><i style="' . $element_style . '; font-size:' . $size . '" class="' . $name . $style . ' hover-show-color"></i></span>';
			}
		} else {
			if ($hover) { $hover = ' hover-' . $hover; } else { $hover = ''; }
			$return = '<i style="' . $element_style . '; font-size:' . $size . '" class="' . $name . $style . $hover . '"></i>';
		}
		
		return $return;
	}
	
	add_shortcode( mnky_compatibility_mode() . 'icon', 'mnky_icon_shortcode' );
?>