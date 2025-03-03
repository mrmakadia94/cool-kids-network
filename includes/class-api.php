<?php
/**
 * API class
 * @package CoolKidsNetwork
 */

/**
 * API class
 */
class Cool_Kids_API {
	/**
	 * Initialize API
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register API routes
	 */
	public function register_routes() {
		register_rest_route(
			'cool-kids-network/v1',
			'/assign-role',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'assign_role' ),
				'permission_callback' => array( $this, 'check_permission' ),
				'args'                => array(
					'email'      => array(
						'required'          => false,
						'validate_callback' => function ( $param ) {
							return is_email( $param );
						},
					),
					'first_name' => array(
						'required'          => false,
						'validate_callback' => function ( $param ) {
							return ! empty( $param );
						},
					),
					'last_name'  => array(
						'required'          => false,
						'validate_callback' => function ( $param ) {
							return ! empty( $param );
						},
					),
					'role'       => array(
						'required'          => true,
						'validate_callback' => function ( $param ) {
							return in_array( $param, array( 'cool_kid', 'cooler_kid', 'coolest_kid' ), true );
						},
					),
				),
			)
		);
	}

	/**
	 * Check API permission using JWT authentication
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return bool|WP_Error
	 */
	public function check_permission( $request ) {
		// Get the current user
		$user = wp_get_current_user();

		if ( empty( $user->ID ) || ! in_array( 'administrator', (array) $user->roles, true ) ) {
			return new WP_Error(
				'rest_forbidden',
				__( 'You do not have permission to access this API.', 'cool-kids-network' ),
				array( 'status' => 403 )
			);
		}

		return true;
	}

	/**
	 * Assign role to user
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function assign_role( $request ) {
		$email      = $request->get_param( 'email' );
		$first_name = $request->get_param( 'first_name' );
		$last_name  = $request->get_param( 'last_name' );
		$role       = $request->get_param( 'role' );

		// Validate that at least one identifier is provided
		if ( empty( $email ) && ( empty( $first_name ) || empty( $last_name ) ) ) {
			return new WP_Error(
				'missing_parameters',
				__( 'Please provide an email or both first and last names.', 'cool-kids-network' ),
				array( 'status' => 400 )
			);
		}

		// Retrieve user by email
		if ( ! empty( $email ) ) {
			$user = get_user_by( 'email', $email );
		} else {
			// Retrieve user by first and last name
			$users = get_users( array(
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'   => 'first_name',
						'value' => $first_name,
					),
					array(
						'key'   => 'last_name',
						'value' => $last_name,
					),
				),
			) );

			$user = ! empty( $users ) ? $users[0] : false;
		}

		// Check if user exists
		if ( ! $user ) {
			return new WP_Error(
				'user_not_found',
				__( 'User not found.', 'cool-kids-network' ),
				array( 'status' => 404 )
			);
		}

		// Assign the new role
		$user = new WP_User( $user->ID );
		$user->set_role( $role );

		return rest_ensure_response(
			array(
				'success' => true,
				'message' => __( 'Role updated successfully.', 'cool-kids-network' ),
			)
		);
	}
}

/**
 * Initialize the API
 */
function initialize_cool_kids_api() {
	new Cool_Kids_API();
}

add_action( 'init', 'initialize_cool_kids_api' );
