<?php
/**
 * Checkout video clip shortner template.
 * 
 * @package WeMakeContentCMS
 */
$userInfo = get_user_by( 'id', hex2bin( get_query_var( 'lead_registration' ) ) );
// if( is_user_logged_in() ) {
//   $user_slug = apply_filters( 'futurewordpress/project/system/getoption', 'permalink-dashboard', 'dashboard' ) . '/' . ( ( apply_filters( 'futurewordpress/project/system/getoption', 'permalink-userby', 'id' ) == 'id' ) ? $userInfo->ID : $userInfo->data->user_login );
//   wp_redirect( site_url( $user_slug ) );
// }

$userMeta = array_map( function( $a ){ return $a[0]; }, (array) get_user_meta( $userInfo->ID ) );
$userInfo = (object) wp_parse_args( $userInfo, [
  'meta'          => (object) apply_filters( 'futurewordpress/project/usermeta/defaults', (array) $userMeta )
] );
$errorHappens = false;


$payment_link = ( empty( $userInfo->meta->monthly_retainer ) || $userInfo->meta->monthly_retainer <= 0 ) ? false : apply_filters( 'futurewordpress/project/payment/stripe', [
  'quantity'	=> 1,
  'price_data' => [
    'currency' => apply_filters( 'futurewordpress/project/system/getoption', 'stripe-currency', 'usd' ),
    'unit_amount' => $userInfo->meta->monthly_retainer,
    'product_data' => [
      'name' => apply_filters( 'futurewordpress/project/system/getoption', 'stripe-productname', __( 'Subscription', 'domain' ) ),
      'description' => apply_filters( 'futurewordpress/project/system/getoption', 'stripe-productdesc', __( 'Payment for', 'domain' ) . get_option( 'blogname', 'We Make Content' ) ),
      'images' => [ apply_filters( 'futurewordpress/project/system/getoption', 'stripe-productimg', esc_url( WEMAKECONTENTCMS_BUILD_URI . '/icons/Online payment_Flatline.svg' ) ) ],
    ],
  ]
], true );
if( $payment_link && ! empty( $payment_link ) ) {
  wp_redirect( $payment_link );
} else {
  wp_die( __( 'Something error happens with the backend. please contact with site administrative, for this coincident.', 'domain' ), __( 'Technical error', 'domain' ) );
}

?>