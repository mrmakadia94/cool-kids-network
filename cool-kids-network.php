<?php
/**
 * Plugin Name: Cool Kids Network
 * Plugin URI: https://example.com/cool-kids-network
 * Description: A user management system for the Cool Kids Network game
 * Version: 1.0.0
 * Author: Meet Makadia
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cool-kids-network
 * Domain Path: /languages
 * @package CoolKidsNetwork
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
define( 'CKN_VERSION', '1.0.0' );
define( 'CKN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CKN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );