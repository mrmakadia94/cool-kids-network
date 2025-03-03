<div class="ckn-form ckn-signup-form">
    <form method="post" action="">
        <?php wp_nonce_field( 'ckn_signup', 'ckn_signup_nonce' ); ?>
        
        <div class="ckn-field">
            <label for="email"><?php esc_html_e( 'Email Address', 'cool-kids-network' ); ?></label>
            <input type="email" name="email" id="email" required />
        </div>

        <button type="submit" class="ckn-submit">
            <?php esc_html_e( 'Confirm', 'cool-kids-network' ); ?>
        </button>
    </form>
</div>