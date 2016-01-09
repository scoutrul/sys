

<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


global $woocommerce; $woocommerce_checkout = $woocommerce->checkout(); 
$isAccordion = etheme_get_option('checkout_accordion');
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() );

wc_print_notices(); 

woocommerce_checkout_coupon_form(); ?>

<div class="<?php if($isAccordion): ?>tabs accordion checkout-accordion<?php else: ?>checkout-default<?php endif; ?>">
    <?php if(!is_user_logged_in()): ?>
        <!-- ----------------------------------------------- -->
        <!-- ------------------- LOGIN --------------------- -->
        <!-- ----------------------------------------------- -->
        <?php if($isAccordion): ?><a class="tab-title checkout-accordion-title" id="tab_1"><span><?php _e('Checkout Method', ETHEME_DOMAIN) ?></span></a><?php endif; ?>
        <div class="tab-content tab-login" id="content_tab_1">  
            <div class="col2-set">
                <div class="col-1 checkout-login">
                    <h3><?php _e('New Customers', ETHEME_DOMAIN) ?></h3>
                    <div class="checkout-methods">
						<?php if ($checkout->enable_guest_checkout): ?>
	                        <div class="method-radio">
	                            <input type="radio" id="method1" name="method" value="1" />
	                            <label for="method1"><?php _e('Checkout as Guest', ETHEME_DOMAIN); ?></label>
	                            <div class="clear"></div>
	                        </div>
						<?php endif ?>
						<?php if (get_option('woocommerce_enable_signup_and_login_from_checkout') != 'no'): ?>
	                        <div class="method-radio">
	                            <input type="radio" id="method2" name="method" value="2" <?php if (!$checkout->enable_guest_checkout): ?> checked <?php endif; ?> />
	                            <label for="method2"><?php _e('Create an Account', ETHEME_DOMAIN); ?></label>
	                            <div class="clear"></div>
	                        </div>
                        <?php endif; ?>
                        <div class="clear"></div>
                    </div>
                    <?php if($isAccordion): ?><a class="button checkout-cont checkout-cont1"><span><?php _e('Continue', ETHEME_DOMAIN) ?></span></a><?php endif; ?>
                </div>
                <div class="col-2 checkout-customers">
                    <h3><?php _e('Returning Customers', ETHEME_DOMAIN) ?></h3>
                    <?php do_action( 'woocommerce_before_checkout_form', $checkout );
                        // If checkout registration is disabled and not logged in, the user cannot checkout
                        if (get_option('woocommerce_enable_signup_and_login_from_checkout')=="no" && get_option('woocommerce_enable_guest_checkout')=="no" && !is_user_logged_in()) :
                        	echo apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', ETHEME_DOMAIN));
                        endif;
                    ?>
                    
                </div>
                <div class="clear"></div>
            </div>
        </div>
    <?php endif; ?>
    
        
<form name="checkout" method="post" class="checkout checkout-form" action="<?php echo esc_url( $get_checkout_url ); ?>">
    
    <?php if(!is_user_logged_in()): ?>
        <?php if (get_option('woocommerce_enable_signup_and_login_from_checkout')=="yes") : ?>
            <!-- ----------------------------------------------- -->
            <!-- -------------- -- REGISTER -- ----------------- -->
            <!-- ----------------------------------------------- -->
            <?php if($isAccordion): ?><a class="tab-title checkout-accordion-title" id="tab-register"><span><?php _e('Create an Account', ETHEME_DOMAIN) ?></span></a><?php endif; ?>
            <div class="tab-content register-tab-content" id="content_tab-register">   	
                
                	<?php if (get_option('woocommerce_enable_guest_checkout')=='yes') : ?>
                		
                		<p class="form-row">
                			<input class="input-checkbox" id="createaccount" <?php checked($woocommerce_checkout->get_value('createaccount'), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e('Create an account?', ETHEME_DOMAIN); ?></label>
                		</p>
                		
                	<?php endif; ?>
                	
                	<?php do_action( 'woocommerce_before_checkout_registration_form', $woocommerce_checkout ); ?>
                	
                	<div class="create-account-form">
                	
                		<!--<p><?php _e('Create an account by entering the information below. If you are a returning customer please login with your username at the top of the page.', ETHEME_DOMAIN); ?></p>-->
                	
                		<?php foreach ($woocommerce_checkout->checkout_fields['account'] as $key => $field) : ?>
                		
                			<?php woocommerce_form_field( $key, $field, $woocommerce_checkout->get_value( $key ) ); ?>
                		
                		<?php endforeach; ?>
                	
                	</div>
                	
                	<?php do_action( 'woocommerce_after_checkout_registration_form', $woocommerce_checkout ); ?>
                    
                    <?php if($isAccordion): ?><a class="button checkout-cont checkout-cont2"><span><?php _e('Continue', ETHEME_DOMAIN) ?></span></a><?php endif; ?>
                				
            </div>	
        <?php endif; ?>
        
    <?php endif; ?>
    
    
    <?php
    // filter hook for include new pages inside the payment method
    $get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url() ); ?>
    
    	<?php if (sizeof($woocommerce_checkout->checkout_fields)>0) : ?>
            	
            <!-- ----------------------------------------------- -->
            <!-- ----------------- BILLING --------------------- -->
            <!-- ----------------------------------------------- -->
            <?php if($isAccordion): ?><a class="tab-title checkout-accordion-title" id="tab_3"><span><?php _e('Billing Address', ETHEME_DOMAIN) ?></span></a><?php endif; ?>
            <div class="tab-content tab-billing" id="content_tab_3">
                <?php do_action('woocommerce_checkout_billing'); ?>
                <?php if($isAccordion): ?><a class="button checkout-cont"><span><?php _e('Continue', ETHEME_DOMAIN) ?></span></a><?php endif; ?>
            </div>
            
            
            <!-- ----------------------------------------------- -->
            <!-- ----------------- SHIPPING -------------------- -->
            <!-- ----------------------------------------------- -->
            <?php if($isAccordion): ?><a href="javascript:void(0)" class="tab-title checkout-accordion-title" id="tab_4"><span><?php _e('Shipping Address', ETHEME_DOMAIN) ?></span></a><?php endif; ?>
            <div class="tab-content tab-shipping" id="content_tab_4">   
        	   <?php do_action('woocommerce_checkout_shipping'); ?>
                <?php if($isAccordion): ?><a class="button checkout-cont"><span><?php _e('Continue', ETHEME_DOMAIN) ?></span></a><?php endif; ?>
            </div>
            <!-- ----------------------------------------------- -->
            <!-- ------------------ ORDER ---------------------- -->
            <!-- ----------------------------------------------- -->
    	<?php endif; ?>
        
        <?php if($isAccordion): ?><a class="tab-title checkout-accordion-title" id="tab_5"><span><?php _e('Your order', ETHEME_DOMAIN) ?></span></a><?php endif; ?>
        <div class="tab-content tap-order" id="content_tab_5">   
            <h3 id="order_review_heading"><?php _e('Your order', ETHEME_DOMAIN); ?></h3>
            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>

            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
        </div>
</form>
</div>

<?php do_action('woocommerce_after_checkout_form'); ?>