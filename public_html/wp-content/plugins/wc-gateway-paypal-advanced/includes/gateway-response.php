<?php
/**
 * Paypal API response class
 * 
**/
class paypal_advanced_response {
		
	private $response;

	/** constructor */
	public function __construct( $response ) {
		$this->response = $response;		
	}

	/**
	* Return whether or not the request was successful
	**/
	public function success() {
		if(is_wp_error($this->response )) {
			return false;
		}
		if (isset($this->response['RESULT']) && $this->response['RESULT'] == 0) 
			return true;
		
		return false;
	}
	
	/**
	 * Get declined message
	 **/
	public function get_error() {
		if(is_wp_error($this->response )) {
			return $this->response->get_error_message();
		}
		// Long error
		return 'ERROR-'.$this->response['RESULT']. ': '. $this->response['RESPMSG'];
	}
	
	
	/**
	 * Get token id
	 */
	public function get_token_id() {
		if (isset($this->response['SECURETOKENID'])) 
			return $this->response['SECURETOKENID'];
	}
	
	/**
	 * Get token
	 */
	public function get_token(){
		if (isset($this->response['SECURETOKEN'])) 
			return $this->response['SECURETOKEN'];
	}
	
	/**
	 * Get all response, good for get express
	 */
	public function get_response(){
		return $this->response;
	}
}
?>