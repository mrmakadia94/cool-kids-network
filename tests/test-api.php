<?php
/**
 * API Test
 *
 * @package CoolKidsNetwork
 */

class API_Test extends WP_UnitTestCase {
    private $server;
    private $api;
    private $admin_user;
    private $regular_user;

    public function setUp(): void {
        parent::setUp();
        
        global $wp_rest_server;
        $this->server = $wp_rest_server = new WP_REST_Server;
        do_action( 'rest_api_init' );

        $this->api = new Cool_Kids_API();
        
        // Create test users
        $this->admin_user = $this->factory->user->create( array(
            'role' => 'coolest_kid'
        ) );
        
        $this->regular_user = $this->factory->user->create( array(
            'role' => 'cool_kid'
        ) );
    }

    public function test_register_routes() {
        $routes = $this->server->get_routes();
        $this->assertArrayHasKey( '/cool-kids-network/v1/assign-role', $routes );
    }

    public function test_assign_role_permission() {
        wp_set_current_user( $this->regular_user );
        
        $request = new WP_REST_Request( 'POST', '/cool-kids-network/v1/assign-role' );
        $response = $this->server->dispatch( $request );
        
        $this->assertEquals( 403, $response->get_status() );
    }

    public function test_assign_role_success() {
        wp_set_current_user( $this->admin_user );
        
        $request = new WP_REST_Request( 'POST', '/cool-kids-network/v1/assign-role' );
        $request->set_param( 'user_id', $this->regular_user );
        $request->set_param( 'role', 'cooler_kid' );
        
        $response = $this->server->dispatch( $request );
        
        $this->assertEquals( 200, $response->get_status() );
        $this->assertTrue( $response->get_data()['success'] );
        
        $user = new WP_User( $this->regular_user );
        $this->assertTrue( in_array( 'cooler_kid', $user->roles ) );
    }

    public function test_assign_role_invalid_user() {
        wp_set_current_user( $this->admin_user );
        
        $request = new WP_REST_Request( 'POST', '/cool-kids-network/v1/assign-role' );
        $request->set_param( 'user_id', 999999 );
        $request->set_param( 'role', 'cooler_kid' );
        
        $response = $this->server->dispatch( $request );
        
        $this->assertEquals( 404, $response->get_status() );
    }

    public function test_assign_role_invalid_role() {
        wp_set_current_user( $this->admin_user );
        
        $request = new WP_REST_Request( 'POST', '/cool-kids-network/v1/assign-role' );
        $request->set_param( 'user_id', $this->regular_user );
        $request->set_param( 'role', 'invalid_role' );
        
        $response = $this->server->dispatch( $request );
        
        $this->assertEquals( 400, $response->get_status() );
        $this->assertStringContainsString( 'Invalid role', $response->get_data()['message'] );
    }

    public function test_assign_role_missing_parameters() {
        wp_set_current_user( $this->admin_user );
        
        $request = new WP_REST_Request( 'POST', '/cool-kids-network/v1/assign-role' );
        $response = $this->server->dispatch( $request );
        
        $this->assertEquals( 400, $response->get_status() );
        $this->assertStringContainsString( 'Missing parameter(s)', $response->get_data()['message'] );
    }
}