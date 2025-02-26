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
}