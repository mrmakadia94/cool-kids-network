<?php
$user_id = get_current_user_id();
$roles = new User_Roles();

$user = get_userdata( $user_id );
$first_name = get_user_meta( $user_id, 'first_name', true );
$last_name = get_user_meta( $user_id, 'last_name', true );
$country = get_user_meta( $user_id, 'ckn_country', true );

if ( empty( $first_name ) || empty( $last_name ) || empty( $country ) ) {
    echo '<p>' . esc_html__( 'Character not found.', 'cool-kids-network' ) . '</p>';
    return;
}
?>

<div class="ckn-character-profile">
    <h2><?php esc_html_e( 'Your Character', 'cool-kids-network' ); ?></h2>
    
    <div class="ckn-character-info">
        <p>
            <strong><?php esc_html_e( 'Name:', 'cool-kids-network' ); ?></strong>
            <?php echo esc_html( $first_name . ' ' . $last_name ); ?>
        </p>
        
        <p>
            <strong><?php esc_html_e( 'Country:', 'cool-kids-network' ); ?></strong>
            <?php echo esc_html( $country ); ?>
        </p>
        
        <p>
            <strong><?php esc_html_e( 'Email:', 'cool-kids-network' ); ?></strong>
            <?php echo esc_html( $user->user_email ); ?>
        </p>
        
        <p>
            <strong><?php esc_html_e( 'Role:', 'cool-kids-network' ); ?></strong>
            <?php
            $role_names = array(
                'cool_kid'    => __( 'Cool Kid', 'cool-kids-network' ),
                'cooler_kid'  => __( 'Cooler Kid', 'cool-kids-network' ),
                'coolest_kid' => __( 'Coolest Kid', 'cool-kids-network' ),
            );
            $user_role = reset( $user->roles );
            echo esc_html( isset( $role_names[ $user_role ] ) ? $role_names[ $user_role ] : $user_role );
            ?>
        </p>
    </div>
</div>