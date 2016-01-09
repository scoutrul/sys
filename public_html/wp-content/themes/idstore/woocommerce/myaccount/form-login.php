<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

<div class="col2-set" id="customer_login">

	<div class="col-1">

<?php endif; ?>

		<h2><?php _e('Login', ETHEME_DOMAIN); ?></h2>
		<form method="post" class="login">
            <div class="login-fields">
            	<p class="form-row form-row-first login-head">
            		<i class="icon-signin"></i>
    				<span class="login-span-big"><?php _e('Already have an account?', ETHEME_DOMAIN); ?></span>
    				<span class="login-span-small"><?php _e('Please, log in to continue.', ETHEME_DOMAIN); ?></span>
    			</p>
    			<p class="form-row form-row-first">
    				<label for="username"><?php _e('Enter your login', ETHEME_DOMAIN); ?> <span class="required">*</span></label>
    				<input type="text" class="input-text" name="username" id="username" />
    			</p>
    			<p class="form-row form-row-last">
    				<label for="password"><?php _e('Enter your password', ETHEME_DOMAIN); ?> <span class="required">*</span></label>
    				<input class="input-text" type="password" name="password" id="password" />
    			</p>
    			<div class="clear"></div>
			</div>
			<p class="form-row">
				<?php wp_nonce_field( 'woocommerce-login' ); ?>
				<a class="lost_password" href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e('Lost Password?', ETHEME_DOMAIN); ?></a>
				<input type="submit" class="button fl-r login-button" name="login" value="<?php _e('Login', ETHEME_DOMAIN); ?>" />
			</p>
		</form>

<?php if (get_option('woocommerce_enable_myaccount_registration')=='yes') : ?>	
		
	</div>
	<div class="account-separator">-&nbsp;<?php _e('OR', ETHEME_DOMAIN); ?>&nbsp;-</div>
	<div class="col-2">
	
		<h2><?php _e('Register', ETHEME_DOMAIN); ?></h2>
		<form method="post" class="register">
		
            <div class="login-fields">
            	<p class="form-row form-row-first register-head">
            		<i class="icon-user"></i>
    				<span class="register-span-big"><?php _e('New Customer?', ETHEME_DOMAIN); ?></span>
    				<span class="register-span-small"><?php _e('Please, register your account to continue.', ETHEME_DOMAIN); ?></span>
    			</p>
				<p class="form-row form-row-first">
					<label for="reg_username"><?php _e('Enter your full name', ETHEME_DOMAIN); ?> <span class="required">*</span></label>
					<input type="text" class="input-text" name="username" id="reg_username" value="<?php if (isset($_POST['username'])) echo esc_attr($_POST['username']); ?>" />
				</p>
				<p class="form-row form-row-last">
					<label for="reg_email"><?php _e('Enter your E-mail address', ETHEME_DOMAIN); ?> <span class="required">*</span></label>
					<input type="email" class="input-text" name="email" id="reg_email" value="<?php if (isset($_POST['email'])) echo esc_attr($_POST['email']); ?>" />
				</p>
				<div class="clear"></div>
				
				<p class="form-row form-row-first">
					<label for="reg_password"><?php _e('Enter your password', ETHEME_DOMAIN); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password" id="reg_password" value="<?php if (isset($_POST['password'])) echo esc_attr($_POST['password']); ?>" />
				</p>
				<p class="form-row form-row-last">
					<label for="reg_password2"><?php _e('Re-enter your password', ETHEME_DOMAIN); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password2" id="reg_password2" value="<?php if (isset($_POST['password2'])) echo esc_attr($_POST['password2']); ?>" />
				</p>
				<div class="clear"></div>
			</div>
				
				<!-- Spam Trap -->
				<div style="left:-999em; position:absolute;"><label for="trap">Anti-spam</label><input type="text" name="email_2" id="trap" /></div>
				
				<?php do_action( 'register_form' ); ?>
				
				<p class="form-row">
					<?php wp_nonce_field( 'woocommerce-register'); ?>
					<input type="submit" class="button" name="register" value="<?php _e('Register', ETHEME_DOMAIN); ?>" />
				</p>
		</form>
		
	</div>
	
</div>
<?php endif; ?>

<?php do_action('woocommerce_after_customer_login_form'); ?>