# Cool Kids Network

A WordPress plugin for managing users in the Cool Kids Network game.

## Description

Cool Kids Network is a WordPress plugin that provides a user management system for a fictional game. It allows users to sign up with just an email address, automatically generates character profiles, and implements a role-based permission system.

## Features

- **Simple Signup Process**: Users can register with just an email address
- **Character Generation**: Automatically generates character profiles using the RandomUser API
- **Role-Based Permissions**: Three user roles with different capabilities:
    - Cool Kid (basic access)
    - Cooler Kid (can view character details)
    - Coolest Kid (can view sensitive info and edit roles)
- **User Listing**: Display all users with filtering based on user role
- **REST API**: Endpoints for managing user roles
- **Shortcodes**: Easy integration with WordPress pages

## Installation

1. Upload the `cool-kids-network` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create pages for signup, login, character profile, and user listing
4. Add the appropriate shortcodes to each page

## Shortcodes

The plugin provides the following shortcodes:

- `[cool_kids_signup]` - Displays the signup form
- `[cool_kids_login]` - Displays the login form
- `[character_profile]` - Displays the current user's character profile
- `[user_listing]` - Displays a list of all users in the network

## Page Setup

### Signup Page
Create a page titled "Signup" and add the shortcode:
```
[cool_kids_signup]
```

### Login Page
Create a page titled "Login" and add the shortcode:
```
[cool_kids_login]
```

### Character Profile Page
Create a page titled "My Character" and add the shortcode:
```
[character_profile]
```

### User Listing Page
Create a page titled "Network Members" and add the shortcode:
```
[user_listing]
```

## User Roles

### Cool Kid
- Basic access to the site
- Can view their own character profile
- Can see limited information in the user listing

### Cooler Kid
- All Cool Kid capabilities
- Can view additional character details
- Can see email addresses in the user listing

### Coolest Kid
- All Cooler Kid capabilities
- Can view sensitive information
- Can edit character roles through the API
- Can see all user information in the listing

## REST API

The plugin provides a REST API endpoint for managing user roles:

### Assign Role Endpoint

**Endpoint:** `/wp-json/cool-kids-network/v1/assign-role`

**Method:** POST

**Parameters:**
- `email` : The email address of the user to update
- `first_name` : The first name of the user to update
- `last_name` : The last name of the user to update
- `role` (required): The new role to assign (cool_kid, cooler_kid, or coolest_kid)

**Note:** You must provide either an email address OR both first and last names to identify the user.

**Authentication:**
- Requires administrator privileges

**Example Request using Email:**
```bash
curl -X POST \
  'https://example.com/wp-json/cool-kids-network/v1/assign-role' \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer your_generated_jwt_token' \
  -d '{
    "email": "user@example.com",
    "role": "cooler_kid"
  }'
```

**Example Request using Name:**
```bash
curl -X POST \
  'https://example.com/wp-json/cool-kids-network/v1/assign-role' \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer your_generated_jwt_token' \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "role": "cooler_kid"
  }'
```

**Example Response:**
```json
{
  "success": true,
  "message": "Role updated successfully."
}
```

## Testing the API with Postman

1. Log in to your WordPress site as an administrator
2. Generate JWT Token for Authentication. `https://yourwebsite.com/wp-json/jwt-auth/v1/token
   ` with Body (raw JSON format): 
    ```json
      {
        "username": "admin",
        "password": "your_admin_password"
      }
    ```
3. Create a new request in Postman:
    - Method: POST
    - URL: `https://your-site.com/wp-json/cool-kids-network/v1/assign-role`
    - Headers:
        - Content-Type: application/json
        - Authorization: Bearer your_generated_jwt_token
    - Body (raw JSON):
      ```json
      {
        "email": "user@example.com",
        "role": "cooler_kid"
      }
      ```
    - Or alternatively:
      ```json
      {
        "first_name": "John",
        "last_name": "Doe",
        "role": "cooler_kid"
      }
      ```
4. Send the request

## Development

### Requirements

- PHP 7.4 or higher
- WordPress 5.0 or higher

### Testing

The plugin includes PHPUnit tests for the main functionality. To run the tests:

1. Set up the WordPress test environment
2. Run `composer install` to install dependencies
3. Run `composer test` to execute the test suite

### Coding Standards

The plugin follows the WordPress Coding Standards. To check compliance:

```
composer phpcs
```

To automatically fix coding standards issues:

```
composer phpcbf
```

## File Structure

```
cool-kids-network/
├── .github/
│   └── workflows/
│       └── ci.yml              # GitHub Actions CI configuration
├── assets/
│   └── css/
│       └── style.css           # Plugin styles
├── includes/
│   ├── class-api.php           # REST API functionality
│   ├── class-character-generator.php  # Character generation logic
│   ├── class-cool-kids-network.php    # Main plugin class
│   └── class-user-roles.php    # User role management
├── templates/
│   ├── character-profile.php   # Character profile template
│   ├── login-form.php          # Login form template
│   ├── signup-form.php         # Signup form template
│   └── user-listing.php        # User listing template
├── tests/
│   ├── bootstrap.php           # Test bootstrap file
│   ├── test-api.php            # API tests
│   ├── test-character-generator.php  # Character generator tests
│   └── test-user-roles.php     # User roles tests
├── composer.json               # Composer configuration
├── cool-kids-network.php       # Main plugin file
├── phpcs.xml                   # PHP CodeSniffer configuration
├── phpunit.xml                 # PHPUnit configuration
└── README.md                   # This file
```

## Hooks and Filters

### Actions

- `cool_kids_network_user_registered` - Fired when a new user is registered
- `cool_kids_network_user_login` - Fired when a user logs in

### Filters

- `cool_kids_network_character_data` - Filter character data before saving
- `cool_kids_network_user_roles` - Filter available user roles

## Frequently Asked Questions

### How do users log in?

Users log in using only their email address. No password is required for simplicity.

### How are character profiles generated?

Character profiles are generated using the RandomUser API. If the API is unavailable, the plugin falls back to a set of predefined names and countries.

### Can I customize the user roles?

Yes, you can use the `cool_kids_network_user_roles` filter to modify the available roles and their capabilities.

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- RandomUser API: https://randomuser.me/
- WordPress Plugin Boilerplate: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate