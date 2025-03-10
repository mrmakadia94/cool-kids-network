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
 *
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

// Include required files.
require_once CKN_PLUGIN_DIR . 'includes/class-cool-kids-network.php';
require_once CKN_PLUGIN_DIR . 'includes/class-character-generator.php';
require_once CKN_PLUGIN_DIR . 'includes/class-user-roles.php';
require_once CKN_PLUGIN_DIR . 'includes/class-api.php';

// Initialize the plugin.
add_action( 'plugins_loaded', 'cool_kids_network_init' );

/**
 * Initialize the plugin.
 */
function cool_kids_network_init() {
    $plugin = new Cool_Kids_Network();
    $plugin->init();
}

// Activation hook.
register_activation_hook( __FILE__, 'cool_kids_network_activate' );

/**
 * Plugin activation callback.
 */
function cool_kids_network_activate() {
    // Create custom roles.
    $roles = new User_Roles();
    $roles->create_roles();
}

// Deactivation hook.
register_deactivation_hook( __FILE__, 'cool_kids_network_deactivate' );

/**
 * Plugin deactivation callback.
 */
function cool_kids_network_deactivate() {
    // Remove custom roles.
    $roles = new User_Roles();
    $roles->remove_roles();
}