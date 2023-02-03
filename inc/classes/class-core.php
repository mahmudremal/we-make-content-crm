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
		add_action( 'admin_post_futurewordpress/project/action/editsubscriber', [ $this, 'editSubscriber' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/action/changepassword', [ $this, 'cancelSubscription' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/database/contents', [ $this, 'contentLibraries' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/action/singlefield', [ $this, 'singleField' ], 10, 0 );

		add_filter( 'futurewordpress/project/action/statuses', [ $this, 'actionStatuses' ], 10, 2 );
	}
	public function requestDashboard() {
		// print_r( $_POST );
		if( ! wp_verify_nonce( $_POST['_nonce'], 'futurewordpress/project/nonce/dashboard' ) ) {
			wp_die( __( 'Nonce doesn\'t matched from your request. if you requested from an expired form, please do a re-submit', 'we-make-content-crm' ), __( 'Security verification mismatched.', 'we-make-content-crm' ) );
		}

		set_transient( 'futurewordpress/project/transiant/admin/' . get_current_user_id(), [
			'type'					=> 'warning', // primary | danger | success | warning | info
			'message'				=> __( 'Request detected but is staging mode.', 'we-make-content-crm' )
		], 300 );
		wp_redirect( wp_get_referer() );
	}
	public function cancelSubscription() {
		wp_send_json_success( __( 'Message recieved but is in staging mode.', 'we-make-content-crm' ), 200 );
	}
	public function contentLibraries() {
		$json = ['recordsFiltered' => 114, 'recordsTotal' => 114];
		wp_send_json_success( [], 200, $json );
	}
	public function singleField() {
		if( ! isset( $_POST[ 'field' ] ) || ! isset( $_POST[ 'value' ] ) || ! isset( $_POST[ '_nonce' ] ) || ! wp_verify_nonce( $_POST[ '_nonce' ], 'futurewordpress/project/nonce/editsubscriber' ) ) {
			wp_send_json_error( __( 'We\'ve detedted you\'re requesting with an invalid security token or something went wrong with you', 'domain' ), 300 );
		}
		if( isset( $_POST[ 'field' ] ) && array_key_exists( $_POST[ 'field' ], apply_filters( 'futurewordpress/project/usermeta/defaults', [] ) ) ) {
			$user_id = get_current_user_id();
			update_user_meta( $user_id, $_POST[ 'field' ], $_POST[ 'value' ] );
			wp_send_json_success( __( 'Update successful', 'domain' ), 200 );
		}
		wp_send_json_error( __( 'Failed operation', 'domain' ), 300 );
	}
	public function actionStatuses( $args, $specific = false ) {
		$actions = [
			'call_scheduled'            => __( 'Step 1.Call Scheduled', 'we-make-content-crm' ),
			'no_show'                   => __( 'Step 2a. No Show', 'we-make-content-crm' ),
			'call_rescheduled'          => __( 'Step 2b. Call Rescheduled', 'we-make-content-crm' ),
			'setfollowup'            		=> __( 'Step 3a. Set Follow Up', 'we-make-content-crm' ),
			'send_contract'            	=> __( 'Step 3b. Send Contract', 'we-make-content-crm' ),
			'contract_pending'					=> __( 'Step 4. Contract Pending', 'we-make-content-crm' ),
			'payment_pending'           => __( 'Step 5. Payment Pending', 'we-make-content-crm' ),
			'payment_confirmed'					=> __( 'Step 6. Payment Confirmed', 'we-make-content-crm' ),
			'retainer_scheduled'        => __( 'Step 7. Retainer Scheduled', 'we-make-content-crm' ),
			'payment_issues'            => __( 'Step 8. Payment Issues', 'we-make-content-crm' ),
			'refund_scheduled'          => __( 'Step 9. Refund Scheduled', 'we-make-content-crm' ),
			'retainer_cancelled'        => __( 'Step 10. Retainer Cancelled', 'we-make-content-crm' ),
			'other'           					=> __( 'Other', 'we-make-content-crm' ),
		];
		return ( $specific ) ? ( isset( $actions[ $specific ] ) ? $actions[ $specific ] : '' ) : $actions;
	}
	public function editSubscriber() {
		if( ! isset( $_POST[ '_nonce' ] ) || ! wp_verify_nonce( $_POST[ '_nonce' ], 'futurewordpress/project/nonce/editsubscriber' ) ) {
			wp_die( __( 'We\'ve detedted you\'re requesting with an invalid security token or something went wrong with you', 'domain' ), __( 'Security mismatched', 'domain' ) );
		}
		$user_id = $_POST[ 'userid' ];$is_edit_profile = ( $user_id != 'new' );
		if( isset( $_POST[ 'userdata' ] ) ) {
			$userdata = $_POST[ 'userdata' ];$userinfo = $_POST[ 'userinfo' ];
			$userinfo[ 'enable_subscription' ] = isset( $userinfo[ 'enable_subscription' ] ) ? $userinfo[ 'enable_subscription' ] : false;
			$args = [
				'display_name'	=> $userdata[ 'display_name' ],
				'first_name'		=> $userdata[ 'first_name' ],
				'last_name'			=> $userdata[ 'last_name' ],
				'user_email'		=> $userdata[ 'email' ],
				'meta_input'		=> (array) $userinfo
			];
			if( ! empty( $userdata[ 'newpassword' ] ) ) {
				$args[ 'user_pass' ] = $userdata[ 'newpassword' ]; // wp_hash_password
			}
			
			if( $is_edit_profile ) {
				$args[ 'ID' ] = $user_id;
			}
			$is_created = (  $is_edit_profile ) ? wp_update_user( $args ) : wp_insert_user( $args );
			if ( ! is_wp_error( $is_created ) ) {
				$msg = [
					'type'					=> 'success', // primary | danger | success | warning | info
					'message'				=> __( 'User Information has been successfully updated.', 'we-make-content-crm' )
				];
			} else {
				$errormessage = $is_created->get_error_message();
				$msg = [
					'type'					=> 'warning', // primary | danger | success | warning | info
					'message'				=> ( empty( $errormessage ) ) ? __( 'Failed to update user information.', 'we-make-content-crm' ) : $errormessage
				];
			}
			set_transient( 'futurewordpress/project/transiant/admin/' . get_current_user_id(), $msg, 300 );
		}
		// print_r( [ $args, $is_created, $msg ] );wp_die();
		wp_redirect( wp_get_referer() );
	}
}
