<?php
/*
 * Paypal Advanced Payment Gateway for WooCoomerce
 * 
 * @package    Woocoomerce Payment Gateway
 * @subpackage WC Paypal Advanced
 * 
 * @since: 1.0.0
 * 
 */

class WC_paypal_advanced extends WC_Payment_Gateway {
	
	private $_payflow_url = 'https://payflowlink.paypal.com/';
	
	public function __construct() { 
		global $woocommerce;
		
        $this->id			= 'paypal_advanced';
        $this->icon 		= plugins_url('images/paypal-advanced.png', __FILE__);
        $this->has_fields 	= false;
		$this->method_title = "Paypal Advanced";
		
		// Load the form fields
		$this->init_form_fields();
		
		// Load the settings.
		$this->init_settings();

		// Get setting values
		$this->enabled 		= $this->settings['enabled'];
		$this->title 		= $this->settings['title'];
		$this->description	= $this->settings['description'];
		$this->partner		= $this->settings['partner'];
		$this->vendor		= $this->settings['vendor'];
		$this->user			= $this->settings['user'];
		$this->password		= $this->settings['password'];
		$this->template		= $this->settings['template'];
		$this->trxtype		= $this->settings['trxtype'];
		$this->trxserver	= $this->settings['trxserver'];
		$this->debug		= $this->settings['debug'];
		
		// Logs
		if ($this->debug=='yes') $this->log = $woocommerce->logger();
		
		// Hooks
		if($this->enabled == 'yes') {
			add_action( 'init', array(&$this, 'response_handler') );
			add_action('woocommerce_receipt_paypal_advanced', array(&$this, 'receipt_page'));
		}
		add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
    } 


	/**
     * Initialize Gateway Settings Form Fields
     */
    function init_form_fields() {
    
    	$this->form_fields = array(
			'enabled' => array(
							'title' => __( 'Enable/Disable', 'woocommerce' ), 
							'label' => __( 'Enable Paypal Advanced Payment Gateway', 'woocommerce' ), 
							'type' => 'checkbox', 
							'description' => '', 
							'default' => 'no'
						), 
			'title' => array(
							'title' => __( 'Title' ), 
							'type' => 'text', 
							'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ), 
							'default' => __( 'Paypal Advanced', 'woocommerce' ),
							'css' => "width: 300px;"
						), 
			'description' => array(
							'title' => __( 'Description', 'woocommerce' ), 
							'type' => 'textarea', 
							'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ), 
							'default' => 'Pay via Paypal Advanced.'
						),
			'user' => array(
							'title' => __( 'User', 'woocommerce' ),
							'label' => 'aaaa',
							'type' => 'text', 
							'description' => __( 'The ID of the user authorized to process transactions. If, however, you have not set up additional users on the account, USER has the same value as VENDOR.', 'woocommerce' ), 
							'default' => ''
						),
			'vendor' => array(
							'title' => __( 'Vendor', 'woocommerce' ), 
							'type' => 'text', 
							'description' => __( 'Your merchant login ID that you created when you registered for the account.', 'woocommerce' ), 
							'default' => ''
						),
			'partner' => array(
							'title' => __( 'Partner', 'woocommerce' ), 
							'type' => 'text', 
							'description' => __( 'The ID provided to you by the authorized PayPal Reseller who registered you for the Gateway gateway. If you purchased your account directly from PayPal, use PayPal.', 'woocommerce' ), 
							'default' => ''
						), 
			'password' => array(
							'title' => __( 'Password', 'woocommerce' ), 
							'type' => 'text', 
							'description' => __('The password that you defined while registering for the account.', 'woocommerce'),
							'default' => '', 
						),
			'template' => array(
							'title' => __( 'Layout templates', 'woocommerce' ), 
							'type' => 'select', 
							'description' => __( 'Determines whether to use one of the two redirect templates (Layout A or B) or the embedded template (Layout C).', 'woocommerce' ), 
							'options' => array(
								'TEMPLATEA'	=>'Layout A',
								'TEMPLATEB'	=>'Layout B',
								'MINLAYOUT'	=>'General Online Checkout Pages',
								'MOBILE'	=>'Mobile Optimized Checkout Pages',
							),
							'default' => 'MINLAYOUT',
						),
			'trxtype' => array(
							'title' => __( 'The type of the transaction', 'woocommerce' ), 
							'type' => 'select', 
							'description' => __( 'The processing method to use for each transaction.', 'woocommerce' ), 
							'options' => array(
								'S'=>'Sale',
								'A'=>'Authorization'
							),
							'default' => 'S',
						),
			'trxserver' => array(
							'title' => __( 'Transaction Server', 'woocommerce' ), 
							'type' => 'select', 
							'description' => __( 'Use the live or testing (sandbox) gateway server to process transactions?', 'woocommerce' ), 
							'options' => array(
								'live'=>'Live',
								'sandbox'=>'Sandbox'
							),
							'default' => 'live'
						),
			'debug' => array(
						'title' => __( 'Debug', 'woocommerce' ), 
						'type' => 'checkbox', 
						'label' => __( 'Enable logging (<code>woocommerce/logs/paypal_advanced.txt</code>)', 'woocommerce' ), 
						'default' => 'no'
					)
			);
    }
	
	
	/**
	 * Admin Panel Options 
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 **/
	public function admin_options() {
?>
		<h3><?php _e('Paypal Advanced', 'woocommerce'); ?></h3>
    	<p><?php _e('Paypal Advanced works by sending the user to Paypal to enter their payment information.', 'woocommerce'); ?></p>
    	
    	<table class="form-table">
    		<?php $this->generate_settings_html(); ?>
		</table><!--/.form-table-->    	
<?php
    }
	
    /**
	 * There are no payment fields for paypal_advanced, but we want to show the description if set.
	 **/
	function payment_fields() {
?>
		<?php if ($this->trxserver == 'sandbox') : ?><p><?php _e('TEST MODE/SANDBOX ENABLED', 'woocommerce'); ?></p><?php endif; ?>
		<?php if ($this->description) : ?><p><?php echo wpautop(wptexturize($this->description)); ?></p><?php endif; ?>
<?php
	}
	
	/**
	 * Get args for passing
	 **/
	function get_params( $order) {
		// Create request
		$params = array (
			"AMT" 				=> $order->get_order_total(),
			"INVNUM" 			=> $order->id,
			"PONUM"				=> $order->order_key,
			"CURRENCY"			=> get_woocommerce_currency(),
            "RETURNURL"     	=> add_query_arg('paypalAdvListener', 'return', trailingslashit(get_permalink(woocommerce_get_page_id('cart')))),
            "CANCELURL"			=> $order->get_cancel_order_url(),
            "ERRORURL"			=> add_query_arg('paypalAdvListener', 'return', trailingslashit(get_permalink(woocommerce_get_page_id('cart')))),
            "URLMETHOD"			=> 'POST',            "TEMPLATE"			=> $this->template,
            			"TRXTYPE" 			=> $this->trxtype,
			"VERBOSITY"			=> "HIGH",
			"TENDER"			=> "C", //credit card
			"CREATESECURETOKEN"	=> "Y",
			"SECURETOKENID"		=> uniqid('ppatokenid-'),
            "CUSTIP" 			=> $_SERVER['REMOTE_ADDR'],
            
			"BILLTOFIRSTNAME" 		=> $order->billing_first_name,
			"BILLTOLASTNAME" 		=> $order->billing_last_name,
			"BILLTOSTREET" 			=> $order->billing_address_1,
			"BILLTOSTREET2"			=> $order->billing_address_2,
			"BILLTOCITY" 			=> $order->billing_city,
			"BILLTOSTATE" 			=> $order->billing_state,
			"BILLTOZIP" 			=> $order->billing_postcode,
			"BILLTOCOUNTRY" 		=> $order->billing_country,
			"BILLTOPHONENUM" 		=> $order->billing_phone,
			"BILLTOEMAIL"			=> $order->billing_email,
			
			"SHIPTOFIRSTNAME" 		=> $order->shipping_first_name,
			"SHIPTOLASTNAME" 		=> $order->shipping_last_name,
			"SHIPTOSTREET" 			=> $order->shipping_address_1,
			"SHIPTOSTREET2" 		=> $order->shipping_address_2,
			"SHIPTOCITY" 			=> $order->shipping_city,
			"SHIPTOSTATE" 			=> $order->shipping_state,
			"SHIPTOZIP" 			=> $order->shipping_postcode,
			"SHIPTOCOUNTRY" 		=> $order->shipping_country,
		);
		
		// merchant info
		$params['USER']					= $this->user;
		$params['VENDOR']				= $this->vendor;
		$params['PARTNER']				= $this->partner;
		$params['PWD']					= $this->password;
		
		return $params;
	}
	/**
	 * Process the payment and return the result
	 **/
	function process_payment( $order_id ) {
		global $woocommerce;

		$order = new WC_Order( $order_id );
				
		// Return thank you redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))))
		);
	
	}
	
	/**
	 * Validate payment form fields
	**/
	public function validate_fields() {
		return true;
	}
	
	/**
	 * receipt_page
	 **/
	function receipt_page( $order_id ) {
		echo '<p>'.__('Thank you for your order.', 'woocommerce').'</p>';
		
		$order = new WC_Order( $order_id );
		$params = $this->get_params( $order );
		
		if ($this->debug=='yes') 
			$this->log->add( 'paypal_advanced', "Sending requrest:" . print_r($params,true));
		
		$request = new paypal_advanced_request($this->trxserver);
		
		$response = $request->send($params);
		
		if($response->success()):
			$src = add_query_arg("SECURETOKEN", $response->get_token(), $this->_payflow_url);
			$src = add_query_arg("SECURETOKENID", $response->get_token_id(), $src);
			$mode = '';
			if($this->trxserver == 'sandbox') {
				$mode	= "TEST";
			} else {
				$mode	= "LIVE";
			}
			$src = add_query_arg("MODE", $mode, $src);?>
<iframe id="paypal-advanced-iframe" src="<?php echo $src ?>" width="100%" height="600px" border="0" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
<?php  else: ?>
	<ul class="woocommerce_error">
			<li><?php echo $response->get_error() ?></li>
	</ul>
<?php
		endif;
	}
	
	/**
	 * Check response data
	 */
	public function response_handler() {
		global $woocommerce;
		
		if (isset($_GET['paypalAdvListener'])) {
			$hdl= $_GET['paypalAdvListener']; // handle value
			if($hdl == 'return') {
				@ob_clean();
				
				$message = '';
				if ($this->debug=='yes') 
					$this->log->add( 'paypal_advanced', "Result response:" . print_r($_POST,true));
				
				$success = ($_POST['RESULT'] == 0);
				if ($success) {
					$order_id = 0; $order_key = '';
					if ( is_numeric( $_POST['INVOICE'] ) ) {
				    	$order_id = $_POST['INVOICE'];
				    	$order_key = $_POST['PONUM'];
			    	} else {
			    		exit;
			    	}
					
					$message = '';
					
					$order = new WC_Order( $order_id );
					if($order->order_key != $order_key) {
						if ($this->debug=='yes') 
							$this->log->add( 'paypal_advanced', 'Error: Order Key does not match invoice.' );
						
						$message = __('Error: Order Key does not match invoice.', 'woocommerce');
						
					} elseif ($order->status == 'completed') { // Check order not already completed
	            		 if ($this->debug=='yes') 
	            		 	$this->log->add( 'paypal_advanced', 'Aborting, Order #' . $order_id . ' is already complete.' );
						 
						 $message = sprintf(__('Aborting, Order #%s is already complete.', 'woocommerce'), $order_id);
						 
	            	} elseif ( $order->get_total() != $_POST['AMT'] ) { // Validate Amount
				    	if ( $this->debug == 'yes' ) 
				    		$this->log->add( 'paypal_advanced', 'Payment error: Amounts do not match (gross ' . $_POST['AMT'] . ')' );
				    
				    	// Put this order on-hold for manual checking
				    	$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayPal amounts do not match (gross %s).', 'woocommerce' ), $_POST['AMT'] ) );
						
						$message = sprintf( __( 'Validation error: PayPal amounts do not match (gross %s).', 'woocommerce' ), $_POST['AMT'] );
				    } else {
				    	// Store PP Details
		                if ( ! empty( $_POST['PNREF'] ) )
		                	update_post_meta( $order_id, 'Transaction ID', $_POST['PNREF'] );
		                if ( ! empty( $_POST['NAME'] ) )
		                	update_post_meta( $order_id, 'Payer Fullname', $_POST['NAME'] );
						if ( ! empty( $_POST['EMAIL'] ) )
		                	update_post_meta( $order_id, 'Payer Email', $_POST['EMAIL'] );
						if ( ! empty( $_POST['METHOD'] ) )
		                	update_post_meta( $order_id, 'Method', $_POST['METHOD'] );
	
		            	// Payment completed
		                $order->add_order_note( __('Payment completed', 'woocommerce') );
		                $order->payment_complete();
	
		                if ($this->debug=='yes') $this->log->add( 'paypal_advanced', 'Payment complete.' );
				    }
					
					$url = '';
					if(!empty($message)) {
						$url = add_query_arg('paypalAdvListener', 'msg', get_permalink(woocommerce_get_page_id('cart')));
						$url = add_query_arg("msg", urlencode($message), $url);
					} else { //if success
						$url = add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(woocommerce_get_page_id('thanks'))));
					}

				} else {
					$message = isset($_POST['RESULT']) ? 'Handler ERROR-'.$_POST['RESULT']. ': '. $_POST['RESPMSG'] : __('Unknow error', 'woocommerce');
					
					if ($this->debug=='yes') 
						$this->log->add( 'paypal_advanced', $message );
					
					$url = add_query_arg('paypalAdvListener', 'msg', get_permalink(woocommerce_get_page_id('cart')));
					$url = add_query_arg("msg", urlencode($message), $url);
				}

				$this->client_redirect($url);
				exit;
			} else { // if error
				$message = isset($_GET['msg']) ? $_GET['msg'] : '';
				if(empty($message)) {
					$message = isset($_POST['RESPMSG']) ? $_POST['RESPMSG'] : __('Unknow error', 'woocommerce');
				}
				$woocommerce->add_error($message);
			}
		}
	}
	
	/**
	 * Client redirect
	 * 
	 * @param $url: Redirect url
	 * 
	 */
	function client_redirect($url=''){
		$url = empty($url) ? 'window.top.location.href' : $url;
?>
		<script type="text/javascript">
		if (window!=top) {top.location.replace(document.location);}
		window.top.location.href = '<?php echo $url; ?>';
		</script>
<?php		
	}
	
}