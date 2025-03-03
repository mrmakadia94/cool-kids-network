<?php
/**
 * User Roles class
 *
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
                'read'                      => true,
                'view_character_details'    => true,
                'view_sensitive_info'       => true,
                'edit_character_roles'      => true,
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

    /**
     * Check if user can view character details
     *
     * @param int $user_id User ID.
     * @return bool
     */
    public function can_view_character_details( $user_id ) {
        $user = new WP_User( $user_id );
        return $user->has_cap( 'view_character_details' );
    }

    /**
     * Check if user can view sensitive information
     *
     * @param int $user_id User ID.
     * @return bool
     */
    public function can_view_sensitive_info( $user_id ) {
        $user = new WP_User( $user_id );
        return $user->has_cap( 'view_sensitive_info' );
    }
}