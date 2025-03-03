<?php
/**
 * User listing template
 *
 * @package CoolKidsNetwork
 */

// Get current user role.
$current_user_id = get_current_user_id();
$current_user = get_userdata( $current_user_id );
$current_user_role = reset( $current_user->roles );

// Get all users with the plugin roles.
$args = array(
    'role__in' => array( 'cool_kid', 'cooler_kid', 'coolest_kid' ),
);

$users = get_users( $args );

// Role display names.
$role_names = array(
    'cool_kid'    => __( 'Cool Kid', 'cool-kids-network' ),
    'cooler_kid'  => __( 'Cooler Kid', 'cool-kids-network' ),
    'coolest_kid' => __( 'Coolest Kid', 'cool-kids-network' ),
);

?>
<?php if ( 'cool_kid' !== $current_user_role ) : ?>
<div class="ckn-user-listing">
    <h2><?php esc_html_e( 'Cool Kids Network Users', 'cool-kids-network' ); ?></h2>
    
    <?php if ( empty( $users ) ) : ?>
        <p><?php esc_html_e( 'No users found.', 'cool-kids-network' ); ?></p>
    <?php else : ?>
        <table class="ckn-users-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Name', 'cool-kids-network' ); ?></th>
                    <th><?php esc_html_e( 'Country', 'cool-kids-network' ); ?></th>
                    <?php if ( 'coolest_kid' === $current_user_role ) : ?>
                        <th><?php esc_html_e( 'Email', 'cool-kids-network' ); ?></th>
                        <th><?php esc_html_e( 'Role', 'cool-kids-network' ); ?></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $users as $user ) : 
                    $first_name = get_user_meta( $user->ID, 'first_name', true );
                    $last_name = get_user_meta( $user->ID, 'last_name', true );
                    $country = get_user_meta( $user->ID, 'ckn_country', true );
                    $user_role = reset( $user->roles );
                    
                    // Skip users without character data
                    if ( empty( $first_name ) || empty( $last_name ) || empty( $country ) ) {
                        continue;
                    }
                ?>
                    <tr>
                        <td><?php echo esc_html( $first_name . ' ' . $last_name ); ?></td>
                        <td><?php echo esc_html( $country ); ?></td>
                        <?php if ( 'coolest_kid' === $current_user_role ) : ?>
                            <td><?php echo esc_html( $user->user_email ); ?></td>
                            <td><?php echo esc_html( isset( $role_names[ $user_role ] ) ? $role_names[ $user_role ] : $user_role ); ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php endif;