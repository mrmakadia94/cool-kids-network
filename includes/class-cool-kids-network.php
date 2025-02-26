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
	}
}