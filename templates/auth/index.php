<?php
/**
 * Checkout video clip shortner template.
 * 
 * @package WeMakeContentCMS
 */

if( ! is_user_logged_in() ) {
  $userInfo = wp_get_current_user();
  wp_redirect( apply_filters( 'futurewordpress/project/user/dashboardpermalink', $userInfo->ID, $userInfo->data->user_nicename ) );
} else {
  $auth_provider = get_query_var( 'auth_provider' );$behaveing = get_query_var( 'behaveing' );

  if( $behaveing == 'redirect' ) {
    // print_r
    wp_redirect( apply_filters( 'futurewordpress/project/socialauth/link', false, $auth_provider ) );
  } else {
    // Handle Social Data from Callback $_GET[ 'code' ]. This data is access token.
    wp_die( __( 'Is not function yet', 'domain' ) );
  }
}
?>