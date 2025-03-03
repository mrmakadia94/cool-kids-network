<?php
/**
 * User Roles Test
 *
 * @package CoolKidsNetwork
 */

class User_Roles_Test extends WP_UnitTestCase {
    private $roles;

    public function setUp(): void {
        parent::setUp();
        $this->roles = new User_Roles();
        $this->roles->create_roles();
    }

    public function tearDown(): void {
        $this->roles->remove_roles();
        parent::tearDown();
    }

    public function test_roles_are_created() {
        $this->assertTrue( wp_roles()->is_role( 'cool_kid' ) );
        $this->assertTrue( wp_roles()->is_role( 'cooler_kid' ) );
        $this->assertTrue( wp_roles()->is_role( 'coolest_kid' ) );
    }

    public function test_role_capabilities() {
        $coolest_kid = get_role( 'coolest_kid' );
        
        $this->assertTrue( $coolest_kid->has_cap( 'read' ) );
        $this->assertTrue( $coolest_kid->has_cap( 'view_character_details' ) );
        $this->assertTrue( $coolest_kid->has_cap( 'view_sensitive_info' ) );
        $this->assertTrue( $coolest_kid->has_cap( 'edit_character_roles' ) );
    }

    public function test_can_view_character_details() {
        $user_id = $this->factory->user->create( array( 'role' => 'cooler_kid' ) );
        $this->assertTrue( $this->roles->can_view_character_details( $user_id ) );
        
        $user_id = $this->factory->user->create( array( 'role' => 'cool_kid' ) );
        $this->assertFalse( $this->roles->can_view_character_details( $user_id ) );
    }

    public function test_can_view_sensitive_info() {
        $user_id = $this->factory->user->create( array( 'role' => 'coolest_kid' ) );
        $this->assertTrue( $this->roles->can_view_sensitive_info( $user_id ) );
        
        $user_id = $this->factory->user->create( array( 'role' => 'cooler_kid' ) );
        $this->assertFalse( $this->roles->can_view_sensitive_info( $user_id ) );
    }

    public function test_can_edit_character_roles() {
        $user_id = $this->factory->user->create( array( 'role' => 'coolest_kid' ) );
        $this->assertTrue( $this->roles->can_edit_character_roles( $user_id ) );
        
        $user_id = $this->factory->user->create( array( 'role' => 'cooler_kid' ) );
        $this->assertFalse( $this->roles->can_edit_character_roles( $user_id ) );
    }
}
