<?php
/**
 * Main plugin class
 * @package CoolKidsNetwork
 */

/**
 * Main plugin class
 */
class Cool_Kids_Network {
	/**
	 * Plugin version
	 * @var string
	 */
	private $version;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->version = CKN_VERSION;
	}

	/**
	 * Initialize plugin
	 */
	public function init() {
		// Load translations.
		add_action( 'init', array( $this, 'load_textdomain' ) );

		// Register scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add shortcodes.
		add_shortcode( 'cool_kids_signup', array( $this, 'signup_form_shortcode' ) );
		add_shortcode( 'cool_kids_login', array( $this, 'login_form_shortcode' ) );
		add_shortcode( 'character_profile', array( $this, 'character_profile_shortcode' ) );
		add_shortcode( 'user_listing', array( $this, 'user_listing_shortcode' ) );

		// Handle form submissions.
		add_action( 'init', array( $this, 'handle_signup_form' ) );
		add_action( 'init', array( $this, 'handle_login_form' ) );

		// Add signup button to homepage.
		add_filter( 'the_content', array( $this, 'add_signup_button' ) );

		// Customize login URL
		add_filter( 'login_url', array( $this, 'custom_login_url' ), 10, 3 );
	}

	/**
	 * Load plugin translations
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'cool-kids-network',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue_scripts() {
		wp_enqueue_style(
			'cool-kids-network',
			CKN_PLUGIN_URL . 'assets/css/style.css',
			array(),
			$this->version
		);

		wp_enqueue_script(
			'cool-kids-network',
			CKN_PLUGIN_URL . 'assets/js/scripts.js',
			array( 'jquery' ),
			$this->version,
			true
		);

		wp_localize_script(
			'cool-kids-network',
			'cknAjax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ckn-nonce' ),
			)
		);
	}

	/**
	 * Add signup button to homepage
	 *
	 * @param string $content Post content.
	 *
	 * @return string
	 */
	public function add_signup_button( $content ) {
		if ( is_front_page() && ! is_user_logged_in() ) {
			$button = '<div class="ckn-signup-button">';
			$button .= '<a href="' . esc_url( home_url( '/signup' ) ) . '" class="button">';
			$button .= esc_html__( 'Join the Cool Kids Network', 'cool-kids-network' );
			$button .= '</a>';
			$button .= '</div>';

			return $content . $button;
		}

		return $content;
	}

	/**
	 * Signup form shortcode
	 * @return string
	 */
	public function signup_form_shortcode() {
		if ( is_user_logged_in() ) {
			return '<p>' . esc_html__( 'You are already registered.', 'cool-kids-network' ) . '</p>';
		}

		ob_start();
		include CKN_PLUGIN_DIR . 'templates/signup-form.php';

		return ob_get_clean();
	}

	/**
	 * Login form shortcode
	 * @return string
	 */
	public function login_form_shortcode() {
		if ( is_user_logged_in() ) {
			return '<p>' . esc_html__( 'You are already logged in.', 'cool-kids-network' ) . '</p>';
		}

		ob_start();
		include CKN_PLUGIN_DIR . 'templates/login-form.php';

		return ob_get_clean();
	}

	/**
	 * Character profile shortcode
	 * @return string
	 */
	public function character_profile_shortcode() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		ob_start();
		include CKN_PLUGIN_DIR . 'templates/character-profile.php';

		return ob_get_clean();
	}

	/**
	 * User listing shortcode
	 * @return string
	 */
	public function user_listing_shortcode() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		ob_start();
		include CKN_PLUGIN_DIR . 'templates/user-listing.php';

		return ob_get_clean();
	}

	/**
	 * Handle signup form submission
	 */
	public function handle_signup_form() {
		if ( ! isset( $_POST['ckn_signup_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['ckn_signup_nonce'], 'ckn_signup' ) ) {
			return;
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

		if ( ! is_email( $email ) ) {
			wp_die( esc_html__( 'Please enter a valid email address.', 'cool-kids-network' ) );
		}

		if ( email_exists( $email ) ) {
			wp_die( esc_html__( 'This email is already registered.', 'cool-kids-network' ) );
		}

		// Generate random username from email.
		$username = sanitize_user( current( explode( '@', $email ) ), true );

		// Ensure username is unique.
		$username = $this->ensure_unique_username( $username );

		// Create user.
		$password = wp_generate_password();
		$user_id  = wp_create_user( $username, $password, $email );

		if ( is_wp_error( $user_id ) ) {
			wp_die( $user_id->get_error_message() );
		}

		// Set default role.
		$user = new WP_User( $user_id );
		$user->set_role( 'cool_kid' );

		// Generate character data.
		$character      = new Character_Generator();
		$character_data = $character->generate( $email );

		// Store character data as user meta.
		update_user_meta( $user_id, 'first_name', $character_data['first_name'] );
		update_user_meta( $user_id, 'last_name', $character_data['last_name'] );
		update_user_meta( $user_id, 'ckn_country', $character_data['country'] );
		update_user_meta( $user_id, 'ckn_created_at', current_time( 'mysql' ) );

		// Send welcome email.
		$this->send_welcome_email( $email );

		// Redirect to login page.
		wp_safe_redirect( home_url( '/login' ) );
		exit;
	}

	/**
	 * Ensure username is unique
	 *
	 * @param string $username Username.
	 *
	 * @return string
	 */
	private function ensure_unique_username( $username ) {
		$original_username = $username;
		$counter           = 1;

		while ( username_exists( $username ) ) {
			$username = $original_username . $counter;
			$counter ++;
		}

		return $username;
	}

	/**
	 * Send welcome email
	 *
	 * @param string $email    Email address.
	 */
	private function send_welcome_email( $email ) {
		$subject = __( 'Welcome to Cool Kids Network', 'cool-kids-network' );

		$message = sprintf(
		/* translators: 1: email */
			__(
				'Welcome to Cool Kids Network!

Your account has been created with the following credentials:

Email: %1$s

Please log in at: %2$s

Best regards,
Cool Kids Network Team',
				'cool-kids-network'
			),
			$email,
			home_url( '/login' )
		);

		wp_mail( $email, $subject, $message );
	}

	/**
	 * Handle login form submission
	 */
	public function handle_login_form() {
		if ( ! isset( $_POST['ckn_login_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['ckn_login_nonce'], 'ckn_login' ) ) {
			return;
		}

		$email       = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$redirect_to = isset( $_POST['redirect_to'] ) ? esc_url_raw( $_POST['redirect_to'] ) : home_url();

		if ( ! is_email( $email ) ) {
			wp_die( esc_html__( 'Please enter a valid email address.', 'cool-kids-network' ) );
		}

		$user = get_user_by( 'email', $email );

		if ( ! $user ) {
			wp_die( esc_html__( 'No account found with that email address.', 'cool-kids-network' ) );
		}

		// Log the user in without password
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID );
		do_action( 'wp_login', $user->user_login, $user );

		// Redirect after login
		wp_safe_redirect( $redirect_to );
		exit;
	}

	/**
	 * Custom login URL
	 *
	 * @param string $login_url    The login URL.
	 * @param string $redirect     The redirect URL.
	 * @param bool   $force_reauth Whether to force reauthorization.
	 *
	 * @return string
	 */
	public function custom_login_url( $login_url, $redirect, $force_reauth ) {
		$login_page = home_url( '/login' );

		if ( ! empty( $redirect ) ) {
			$login_page = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_page );
		}

		if ( $force_reauth ) {
			$login_page = add_query_arg( 'reauth', '1', $login_page );
		}

		return $login_page;
	}
}