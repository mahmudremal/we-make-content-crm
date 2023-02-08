<?php
/**
 * LoadmorePosts
 *
 * @package WeMakeContentCMS
 */

namespace WEMAKECONTENTCMS_THEME\Inc;

use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;
use \WP_Query;

class Stripe {

	use Singleton;
	private $theTable;
	private $lastResult;
	private $stripeSecretKey;
	private $stripePublishAble;
	
	protected function __construct() {
		// Replace with your own Stripe secret key
		$this->stripePublishAble = apply_filters( 'futurewordpress/project/system/getoption', 'stripe-publishablekey', 'sk_test_51MYvdBI8VOGXMyoFiYpojuTUhvmS1Cxwhke4QK6jfJopnRN4fT8Qq6sy2Rmf2uvyHBtbafFpWVqIHBFoZcHp0vqq00HaOBUh1P' );
		$this->stripeSecretKey = apply_filters( 'futurewordpress/project/system/getoption', 'stripe-secretkey', 'sk_test_51MYvdBI8VOGXMyoFiYpojuTUhvmS1Cxwhke4QK6jfJopnRN4fT8Qq6sy2Rmf2uvyHBtbafFpWVqIHBFoZcHp0vqq00HaOBUh1P' );
		$this->productID = 'prod_NJlPpW2S6i75vM';
		$this->lastResult = false;
		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		global $wpdb;$this->theTable = $wpdb->prefix . 'fwp_stripe_subscriptions';
		add_filter( 'futurewordpress/project/payment/stripe', [ $this, 'thePaymentlink' ], 10, 2 );
		add_filter( 'template_include', [ $this, 'template_include' ], 10, 1 );
	}
	public function template_include( $template ) {
    $pay_retainer = get_query_var( 'pay_retainer' );// $order_id = get_query_var( 'order_id' );
		if ( $pay_retainer == false || $pay_retainer == '' ) {
      return $template;
    } else {
			$file = WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/cards/pay_retainer.php';
			if( file_exists( $file ) && ! is_dir( $file ) ) {
          return $file;
        } else {
          return $template;
        }
		}
	}

	private function insertIntoTable( $json ) {
		global $wpdb;$args = (array) json_decode( $json, true );
		$user_id = get_current_user_id();

		// $status = $wpdb->query( $wpdb->prepare(
		// 	"INSERT INTO {$table}(user_id, user_email, subsc_id, user_object, user_address, invoice, phone, archived) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
		// 	$user_id, $args[ 'email' ], $args[ 'id' ], $args[ 'object' ], $args[ 'address' ], $args[ 'invoice_prefix' ], $args[ 'phone' ], maybe_serialize( $json )
		// ) );
		foreach( $args as $key => $val ) {
			if( $val == NULL ) {
				$args[ $key ] = false;
			}
		}
		$wpdb->insert( $this->theTable, [
			'user_id' => $user_id,
			'user_email' => $args[ 'email' ],
			'subsc_id' => $args[ 'id' ],
			'user_object' => $args[ 'object' ],
			'user_address' => $args[ 'address' ],
			'invoice' => $args[ 'invoice_prefix' ],
			'phone' => $args[ 'phone' ],
			'archived' => $json
		] );
	}
	public function getUserData( $user_id ) {
		global $wpdb;$args = (array) json_decode( $json );
		$user_id = get_current_user_id();
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->theTable} user_id=%d", $user_id ) );
		return $rows;
	}

	public function thePaymentlink( $args, $default = false ) {
		// Your secret key from the Dashboard
		$secret_key						= $this->stripeSecretKey;
		$stripe_public_key		= $this->stripePublishAble;
		
		// $product_id						= "prod_NJj26vTpYGKnfA"; // Your Stripe product/plan ID
		$session = $this->create_stripe_checkout_session( $args );
		// print_r( $session );
    // $payment_link = "https://checkout.stripe.com/pay/" . $session_id;
		$payment_link = isset( $session[ 'url' ] ) ? $session[ 'url' ] : 'https://checkout.stripe.com/pay/'  . $session[ 'url' ];
    return $payment_link;
	}
	public function create_stripe_checkout_session( $args = false ) {
		$stripe_public_key = $this->stripePublishAble;
    $curl = curl_init();
		$param = [
			'success_url'								=> site_url( 'success_url' ),
			'cancel_url'								=> site_url( 'cancel_url' ),
			'payment_method_types'			=> [ 'acss_debit', 'affirm', 'afterpay_clearpay', 'alipay', 'au_becs_debit', 'bacs_debit', 'bancontact', 'blik', 'boleto', 'card', 'customer_balance', 'eps', 'fpx', 'giropay', 'grabpay', 'ideal', 'klarna', 'konbini', 'link', 'oxxo', 'p24', 'paynow', 'pix', 'promptpay', 'sepa_debit', 'sofort', 'us_bank_account', 'wechat_pay' ],
			'line_items'								=> [
				// [
				// 	'quantity'	=> 1,
				// 	'price_data' => [
				// 		'currency' => 'usd',
				// 		'unit_amount' => 300,
				// 		'product_data' => [
				// 			'name' => 'T-shirt',
				// 			'description' => 'Comfortable cotton t-shirt',
				// 			'images' => [ esc_url( WEMAKECONTENTCMS_BUILD_URI . '/icons/Online payment_Flatline.svg' ) ],
				// 		],
				// 	]
				// ]
			],
			'mode'											=> 'payment',
		];
		$param[ 'line_items' ] = false;
		if( $args ) {$param[ 'line_items' ] = [$args];}
		if( $param[ 'line_items' ] === false ) {return false;}

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.stripe.com/v1/checkout/sessions",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => http_build_query( $param ),
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer " . $this->stripeSecretKey,
        "Content-Type: application/x-www-form-urlencoded"
      ),
    ));

    $result = curl_exec($curl);
    curl_close($curl);

    $result = json_decode( $result, true);
		$this->lastResult = $result;
		// if( $result[ 'error' ] ) {return false;}
    // $session_id = isset( $result[ 'id' ] ) ? $result[ 'id' ] : false;
    return $result;
	}
	public function handleSuccess() {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.stripe.com/v1/payment_intents",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "amount=999&currency=usd&payment_method_types[]=card&success_url=https://example.com/success&cancel_url=https://example.com/cancel",
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer sk_test_your_secret_key",
				"Content-Type: application/x-www-form-urlencoded"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}

	}
}
