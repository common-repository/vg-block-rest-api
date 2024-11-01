<?php
/**
 * Plugin Name: VG Block REST API
 * Plugin URI: http://guptavishal.in/works/vg-block-rest-api/
 * Description: Block the use of the JSON REST API on your website to all user exclude Admin
 * Version: 1.0.0
 * Author: Vishal Gupta
 * Author URI: http://guptavishal.in
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

$vgbra_WP_version = get_bloginfo('version');

/**
 * Returning an authentication error if a user who is not Admin and try to access the REST API
 * @param $access
 * @return WP_Error/access
 */
function vgbra_only_allow_logged_in_rest_access( $access ) {
    if( ! is_admin() ) {
        return new WP_Error( 'rest_cannot_access', __( 'Only Admin users can access the REST API.', 'disable-json-api' ), array( 'status' => rest_authorization_required_code() ) );
    }
    return $access;
}

if ( version_compare( $vgbra_WP_version, '4.7', '>=' ) ) {
    add_filter( 'rest_authentication_errors', 'vgbra_only_allow_logged_in_rest_access' );
} else {
    // Filters for WP-API version 1.x
    add_filter( 'json_enabled', '__return_false' );
    add_filter( 'json_jsonp_enabled', '__return_false' );

    // Filters for WP-API version 2.x
    add_filter( 'rest_enabled', '__return_false' );
    add_filter( 'rest_jsonp_enabled', '__return_false' );

    // Remove REST API info from head and headers
    remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'template_redirect', 'rest_output_link_header', 11 );
}