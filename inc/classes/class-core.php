<?php
/**
 * Block Patterns
 *
 * @package WeMakeContentCMS
 */

namespace WEMAKECONTENTCMS_THEME\Inc;

use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;

class Core {
	use Singleton;
	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		add_action( 'admin_post_futurewordpress/project/action/dashboard', [ $this, 'requestDashboard' ], 10, 0 );

		add_action( 'wp_ajax_futurewordpress/project/action/cancelsubscription', [ $this, 'cancelSubscription' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/action/changepassword', [ $this, 'cancelSubscription' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/action/singlefield', [ $this, 'cancelSubscription' ], 10, 0 );
	}
	public function requestDashboard() {
		// print_r( $_POST );
		if( wp_verify_nonce( 'futurewordpress/project/nonce/dashboard', $_POST['_nonce'] ) ) {
			wp_die( __( 'Nonce doesn\'t matched from your request. if you requested from an expired form, please do a re-submit', 'we-make-content-crm' ), __( 'Security verification mismatched.', 'we-make-content-crm' ) );
		}

		set_transient( 'futurewordpress/project/transiant/dashboard/' . get_current_user_id(), [
			'type'					=> 'warning', // primary | danger | success | warning | info
			'message'				=> __( 'Request detected but not stored yet.', 'we-make-content-crm' )
		], 300 );
		wp_redirect( wp_get_referer() );
	}
	public function cancelSubscription() {
		wp_send_json_success( __( 'Message recieved but doesn\'t proceed.', 'domain' ), 200 );
	}
	public function singleField() {}
}
