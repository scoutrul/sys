<?php

/**
 * Gateway API request class - sends given POST data to Gateway server
 **/
class paypal_advanced_request {
	
	var $_tran_mode = false;
	
	/** constructor */
	public function __construct($tran_mode = '') {
		$this->_tran_mode = $tran_mode;
	}
	
	/**
	 * Get url
	 */
	public function get_url(){
		// Which endpoint will we be using?
		if($this->_tran_mode == 'sandbox') //if test mode
			return "https://pilot-payflowpro.paypal.com";
		return "https://payflowpro.paypal.com";
	}

	/**
	 * Create and send the request
	 *
	 * @param array $options array of options to be send in POST request
	 * @return gateway_response response object
	 *
	 */
	public function send($options='') {
		$result = $this->gateway_request($options);
		return new paypal_advanced_response($result);;
	}
	
	/**
     * Run the curl request and send data to PayPal
     */
	private function gateway_request($post_data) {
		global $woocommerce;
		
		$paramList = array();
	    foreach($post_data as $index => $value) {
	        $paramList[] = $index . "[" . strlen($value) . "]=" . $value;
	    }
	    $apiStr = implode("&", $paramList);
		
		// Send back post vars to paypal
        $params = array( 
        	'body' 			=> $apiStr,
        	'sslverify' 	=> false,
        	'timeout' 		=> 30,
        	'user-agent'	=> 'WooCommerce/' . $woocommerce->version
        );
		try {
			// Post back to get a response
			$response = wp_remote_post( $this->get_url(), $params );
			// check to see if the request was valid
	        if ( !is_wp_error($response) && $response['response']['code'] >= 200 
	        	&& $response['response']['code'] < 300) {
	        	parse_str( $response['body'], $parsed_response );
				return $parsed_response;
	        }
			return $response;
		} catch(Exception $ex) {
			return new WP_Error($ex->getCode(), $ex->getMessage());
		}
	}
}
?>