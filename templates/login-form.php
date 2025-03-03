<?php
/**
 * Login form template
 *
 * @package CoolKidsNetwork
 */

$redirect_to = isset( $_GET['redirect_to'] ) ? esc_url_raw( $_GET['redirect_to'] ) : home_url();
?>

<div class="ckn-form ckn-login-form">
    <h2><?php esc_html_e( 'Login to Cool Kids Network', 'cool-kids-network' ); ?></h2>
    
    <?php if ( isset( $_GET['login'] ) && 'failed' === $_GET['login'] ) : ?>
        <div class="ckn-error">
            <p><?php esc_html_e( 'Login failed. Please check your email address.', 'cool-kids-network' ); ?></p>
        </div>
    <?php endif; ?>
    
    <form method="post" action="">
        <?php wp_nonce_field( 'ckn_login', 'ckn_login_nonce' ); ?>
        <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>">
        
        <div class="ckn-field">
            <label for="email"><?php esc_html_e( 'Email Address', 'cool-kids-network' ); ?></label>
            <input type="email" name="email" id="email" required />
        </div>

        <button type="submit" class="ckn-submit">
            <?php esc_html_e( 'Login', 'cool-kids-network' ); ?>
        </button>
    </form>
    
    <div class="ckn-signup-link">
        <p><?php esc_html_e( 'Don\'t have an account?', 'cool-kids-network' ); ?> <a href="<?php echo esc_url( home_url( '/signup' ) ); ?>"><?php esc_html_e( 'Sign up', 'cool-kids-network' ); ?></a></p>
    </div>
</div>