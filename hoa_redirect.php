<?php
/* 
Plugin Name: HOA Redirect
URI: http://onlinehoa.com
Description: Redirect user requests
Version: 1.0.2
Author: Satake
Author URI: http://objectfoo.com
License: GPLv2
*/

/*
if there is a request for home page e.g. http://site.com/
and the user is not logged 
redirect to the welcome page if not the root blog
use temporary redirect 302
*/

add_action( 'wp', 'logged_out_redirect_hoa', 3);
function logged_out_redirect_hoa( $ref ) {
	global $blog_id;

	if( is_multisite()						// is multisite install
		&& $blog_id != 1					// not root blog
		&& strlen($ref->request) == 0		// is request for home page
		&& !is_user_logged_in() )			// not logged in
	{
		wp_redirect( site_url() . '/welcome/', 302 );
		die();
	}
}

// login_redirect gets called 2 times
// before the user has logged in and the login form is shown
// after the user is logged in and before redirect to the landing page

// If there is a user object (user has attempted to log in)
add_filter( 'login_redirect', 'login_redirect_hoa', 100, 3 );
function login_redirect_hoa( $redirect_to, $request_redirect_to, $user ) {
	
	if( is_object($user) && 'WP_User' == get_class($user) ) {
		// if request redirect for somewhere other than admin section
		$is_wp_admin = preg_match( '@wp-admin/$@', $request_redirect_to );
		if( strlen($request_redirect_to) > 0 && !$is_wp_admin ) {
			return $request_redirect_to;
		}

		// if user can't publish post (roles: homeowners & board)
		if( !$user->has_cap('publish_posts') ) {
			$primary_blog = get_user_meta( $user->ID, 'primary_blog', true );
			if( false !== $primary_blog ) {
				wp_redirect( get_blogaddress_by_id( $primary_blog ), 301 );
				die();
			}
		}
	}
	return $redirect_to;
}
?>