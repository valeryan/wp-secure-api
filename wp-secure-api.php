<?php
/**
 * The plugin bootstrap file.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/valeryan/wp-secure-api/
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Secure Rest API
 * Plugin URI:        https://github.com/valeryan/wp-secure-api/
 * Description:       Extends the WP REST API require an authentication method.
 * Version:           1.0.0
 * Author:            Samuel Hilson
 * Author URI:        https://github.com/valeryan/
 */

/**
 * Allow request to authentication
 * @return bool
 */
function is_not_auth_uri()
{
    $known_auth_uris = [
        '/wp-json/jwt-auth/v1/token',
        '/wp-json/jwt-auth/v1/token/validate',
    ];

    $uri = rtrim($_SERVER['REQUEST_URI'], '/');
    return ! in_array($uri, $known_auth_uris);
}

/**
 * Add filter to secure api request
 */
add_filter('rest_authentication_errors', function ($result) {
    if (! empty($result)) {
        return $result;
    }

    if (! is_user_logged_in() && is_not_auth_uri()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', ['status' => 401]);
    }
    return $result;
});
