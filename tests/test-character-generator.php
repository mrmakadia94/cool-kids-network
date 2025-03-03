<?php
/**
 * Character Generator Test
 *
 * @package CoolKidsNetwork
 */

class Character_Generator_Test extends WP_UnitTestCase {
    public function test_generate_returns_valid_data() {
        $generator = new Character_Generator();
        $email = 'test@example.com';
        $data = $generator->generate( $email );

        $this->assertIsArray( $data );
        $this->assertArrayHasKey( 'first_name', $data );
        $this->assertArrayHasKey( 'last_name', $data );
        $this->assertArrayHasKey( 'country', $data );
        $this->assertArrayHasKey( 'email', $data );
        $this->assertEquals( $email, $data['email'] );
    }

    public function test_fallback_data_is_valid() {
        $generator = new Character_Generator();
        $email = 'test@example.com';
        
        // Use reflection to access private method
        $reflection = new ReflectionClass( $generator );
        $method = $reflection->getMethod( 'get_fallback_data' );
        $method->setAccessible( true );
        
        $data = $method->invoke( $generator, $email );

        $this->assertIsArray( $data );
        $this->assertArrayHasKey( 'first_name', $data );
        $this->assertArrayHasKey( 'last_name', $data );
        $this->assertArrayHasKey( 'country', $data );
        $this->assertArrayHasKey( 'email', $data );
        $this->assertEquals( $email, $data['email'] );
    }

    public function test_generated_names_are_valid() {
        $generator = new Character_Generator();
        $data = $generator->generate('test@example.com');
        
        $this->assertNotEmpty($data['first_name']);
        $this->assertIsString($data['first_name']);
        $this->assertNotEmpty($data['last_name']);
        $this->assertIsString($data['last_name']);
    }

    public function test_country_code_format() {
        $generator = new Character_Generator();
        $data = $generator->generate('test@example.com');
        
        $this->assertMatchesRegularExpression('/^[A-Z]{2}$/', $data['country']);
    }

    public function test_email_is_valid() {
        $generator = new Character_Generator();
        $data = $generator->generate('valid-email@example.com');
        
        $this->assertTrue(filter_var($data['email'], FILTER_VALIDATE_EMAIL) !== false);
        
        // Test with invalid email input
        $invalid_data = $generator->generate('invalid-email');
        $this->assertTrue(filter_var($invalid_data['email'], FILTER_VALIDATE_EMAIL) !== false);
    }
}


