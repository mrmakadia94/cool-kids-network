<?php
/**
 * User Roles class
 * @package CoolKidsNetwork
 */

/**
 * User Roles class
 */
class User_Roles {
	/**
	 * Create custom roles
	 */
	public function create_roles() {
		add_role(
			'cool_kid',
			__( 'Cool Kid', 'cool-kids-network' ),
			array(
				'read' => true,
			)
		);

		add_role(
			'cooler_kid',
			__( 'Cooler Kid', 'cool-kids-network' ),
			array(
				'read'                   => true,
				'view_character_details' => true,
			)
		);

		add_role(
			'coolest_kid',
			__( 'Coolest Kid', 'cool-kids-network' ),
			array(
				'read'                   => true,
				'view_character_details' => true,
				'view_sensitive_info'    => true,
				'edit_character_roles'   => true,
			)
		);
	}

	/**
	 * Remove custom roles
	 */
	public function remove_roles() {
		remove_role( 'cool_kid' );
		remove_role( 'cooler_kid' );
		remove_role( 'coolest_kid' );
	}
}