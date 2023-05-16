<?php
/*
Plugin Name: Disable REST API for anonymous users
*/

/**
 * Remove all endpoints except SAML / oEmbed for unauthenticated users
 */
add_filter( 'rest_authentication_errors', function($result) {
  if ( ! empty( $result ) ) {
    return $result;
  }

  $current_route = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

  //Please note that allowing /wp-json/route/ is equal to whitelisting /wp-json/route/.*
  $whitelisted_routes = array_merge(['/wp-json/saml', '/wp-json/oembed'], apply_filters('rest_allowed_anonymous_routes', []));

  //search through whitelisted routes
  $route_allowed = false;
  foreach($whitelisted_routes as $whitelisted_route) {
    if(substr($current_route, 0, strlen($whitelisted_route)) === $whitelisted_route) {
      $route_allowed = true;
      break;
    }
  }

  //Handle whitelisting of routes for anonymous users (works for logged in as well)
  if($route_allowed) {
    return $result;
  }
  //Not whitelisted route, check if user is logged in and bail if not
  else if( !is_user_logged_in() ) {
    return new WP_Error( 'rest_cannot_access', __( 'Only authenticated users can access this endpoint.', 'disable-json-api' ), array( 'status' => rest_authorization_required_code() ) );
  }
  //User is logged in, approve all
  else {
    return $result;
  }
});