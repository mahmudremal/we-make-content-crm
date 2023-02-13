<?php
/**
 * Checkout video clip shortner template.
 * 
 * @package WeMakeContentCMS
 */
// print_r( hex2bin( get_query_var( 'pay_retainer' ) ) );
$userInfo = get_user_by( 'id', hex2bin( get_query_var( 'pay_retainer' ) ) );
if( $userInfo ) {
  $userMeta = array_map( function( $a ){ return $a[0]; }, (array) get_user_meta( $userInfo->ID ) );
  $userInfo = (object) wp_parse_args( $userInfo, [
    'meta'          => (object) apply_filters( 'futurewordpress/project/usermeta/defaults', (array) $userMeta )
  ] );
  $errorHappens = false;


  $payment_link = ( empty( $userInfo->meta->monthly_retainer ) || $userInfo->meta->monthly_retainer <= 0 ) ? false : apply_filters( 'futurewordpress/project/payment/stripe/paymentlink', [
    'quantity'	=> 1,
    'price_data' => [
      'currency' => apply_filters( 'futurewordpress/project/system/getoption', 'stripe-currency', 'usd' ),
      'unit_amount' => (int) ( $userInfo->meta->monthly_retainer * 100 ), // Unit amount in cent | number_format( $userInfo->meta->monthly_retainer, 2 ),
      'product_data' => [
        'name' => apply_filters( 'futurewordpress/project/system/getoption', 'stripe-productname', __( 'Subscription',   'we-make-content-crm' ) ),
        'description' => apply_filters( 'futurewordpress/project/system/getoption', 'stripe-productdesc', __( 'Payment for',   'we-make-content-crm' ) . ' ' . get_option( 'blogname', 'We Make Content' ) ),
        'images' => [ apply_filters( 'futurewordpress/project/system/getoption', 'stripe-productimg', esc_url( WEMAKECONTENTCMS_BUILD_URI . '/icons/Online payment_Flatline.svg' ) ) ],
      ],
    ]
  ], true );
  if( $payment_link && ! empty( $payment_link ) ) {
    wp_redirect( $payment_link );
  } else {
    wp_die( __( 'Something error happens with the backend. please contact with site administrative, for this coincident.',   'we-make-content-crm' ), __( 'Technical error',   'we-make-content-crm' ) );
  }
} else {
  wp_die( __( 'We\'re in a trap. or maybe we did some wrong thing.',   'we-make-content-crm' ), __( 'Permalink structure mismatched.',   'we-make-content-crm' ) );
}


// https://wemakecontent.net/pay_retainer/3234/
?>
