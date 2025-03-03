<?php
/**
 * Character Generator class
 *
 * @package CoolKidsNetwork
 */

/**
 * Character Generator class
 */
class Character_Generator {
    /**
     * API URL
     *
     * @var string
     */
    private $api_url = 'https://randomuser.me/api/';

    /**
     * Generate character data
     *
     * @param string $email User email.
     * @return array
     */
    public function generate( $email ) {
        $response = wp_remote_get( $this->api_url );

        if ( is_wp_error( $response ) ) {
            return $this->get_fallback_data( $email );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( ! isset( $data['results'][0] ) ) {
            return $this->get_fallback_data( $email );
        }

        $user = $data['results'][0];

        return array(
            'first_name' => $user['name']['first'],
            'last_name'  => $user['name']['last'],
            'country'    => $user['location']['country'],
            'email'      => $email,
        );
    }

    /**
     * Get fallback data if API fails
     *
     * @param string $email User email.
     * @return array
     */
    private function get_fallback_data( $email ) {
        $first_names = array( 'Alex', 'Jordan', 'Taylor', 'Morgan', 'Casey' );
        $last_names = array( 'Smith', 'Johnson', 'Williams', 'Brown', 'Jones' );
        $countries = array( 'USA', 'Canada', 'UK', 'Australia', 'New Zealand' );

        return array(
            'first_name' => $first_names[ array_rand( $first_names ) ],
            'last_name'  => $last_names[ array_rand( $last_names ) ],
            'country'    => $countries[ array_rand( $countries ) ],
            'email'      => $email,
        );
    }
}