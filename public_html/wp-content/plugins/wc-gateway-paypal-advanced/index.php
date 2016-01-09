<?php
/*
Plugin Name: WC Paypal Advanced
Plugin URI: http://www.browsepress.com
Description: Extends WooCommerce. Provides a Paypal Advanced gateway for WooCommerce.
Version: 1.0.0
Author: Buif.Dw
Author URI: http://www.browsepress.com
*/

add_action('plugins_loaded', 'init_paypal_advanced_gateway', 0);

function init_paypal_advanced_gateway() {
	if ( ! class_exists( 'woocommerce_payment_gateway' ) ) { return; }
	

	$plugin_dir = plugin_dir_path(__FILE__);
	
	/**
 	 * Localication
	 */
	//load_textdomain( 'woocommerce', $plugin_dir.'lang/paypal-advanced-'.get_locale().'.mo' );
	
	require_once($plugin_dir . 'includes/gateway-request.php');
	require_once($plugin_dir . 'includes/gateway-response.php');
	require_once $plugin_dir . 'paypal-advanced.php';
	
	/**
 	* Add the Gateway to WooCommerce
 	**/
	function add_paypal_advanced_gateway($methods) {
		$methods[] = 'WC_Paypal_Advanced';
		return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'add_paypal_advanced_gateway' );
	
}



?>
