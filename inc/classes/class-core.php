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
		add_action( 'wp_ajax_futurewordpress/project/action/switchleadstatus', [ $this, 'switchLeadStatus' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/action/deleteleadaccount', [ $this, 'deleteLeadAccount' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/action/sendregistration', [ $this, 'sendRegistration' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/action/sendpasswordreset', [ $this, 'sendPasswordReset' ], 10, 0 );

		// add_action( 'wp_ajax_futurewordpress/project/action/test', [ $this, 'testAjax' ], 10, 0 );

		add_filter( 'futurewordpress/project/action/statuses', [ $this, 'actionStatuses' ], 10, 2 );

		add_filter( 'futurewordpress/project/rewrite/rules', [ $this, 'rewriteRules' ], 10, 1 );
		add_filter( 'template_include', [ $this, 'template_include' ], 10, 1 );

	}
	public function requestDashboard() {
		// print_r( $_POST );
		if( ! wp_verify_nonce( $_POST['_nonce'], 'futurewordpress/project/nonce/dashboard' ) ) {
			wp_die( __( 'Nonce doesn\'t matched from your request. if you requested from an expired form, please do a re-submit', 'we-make-content-crm' ), __( 'Security verification mismatched.', 'we-make-content-crm' ) );
		}

		set_transient( 'futurewordpress/project/transiant/admin/' . get_current_user_id(), [
			'type'					=> 'warning', // primary | danger | success | warning | info
			'message'				=> __( 'Request detected but is staging mode.', 'we-make-content-crm' )
		], 200 );
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
		if( ! isset( $_POST[ 'field' ] ) || ! isset( $_POST[ 'value' ] ) || ! isset( $_POST[ '_nonce' ] ) || ! wp_verify_nonce( $_POST[ '_nonce' ], 'futurewordpress/project/verify/nonce' ) ) {
			wp_send_json_error( __( 'We\'ve detected you\'re requesting with an invalid security token or something went wrong with you', 'we-make-content-crm' ), 200 );
		}
		$field = $_POST[ 'field' ];$value = $_POST[ 'value' ];$type = ( substr( $field, 0, 5 ) == 'meta-' ) ? 'meta' : 'data';
		$userMeta = apply_filters( 'futurewordpress/project/usermeta/defaults', [] );
		if( ! empty( $field ) && isset( $_POST[ 'userid' ] ) ) {
			$field = substr( $field, 5 );
			$user_id = get_current_user_id();
			if( $type = 'meta' && array_key_exists( $field, $userMeta ) ) {
				update_user_meta( $user_id, $field, $value );
			} else if( $type = 'data' && array_key_exists( $field, [ 'email' ] ) ) {
				if( in_array( $field, [ 'display_name', 'user_email' ] ) ) {
					wp_update_user( [
						'ID'			=> ( ! is_admin() ) ? get_current_user_id() : $_POST[ 'userid' ],
						$field		=> $value
					] );
					if( $field == 'user_email' ) {update_user_meta( $user_id, 'email', $value );}
				} else {
					wp_send_json_error( __( 'Illigal request sent. Nothing happen. Request rejected.', 'we-make-content-crm' ), 200 );
				}
			} else {
				wp_send_json_error( __( 'Request properly not identified or not allowed to madify.', 'we-make-content-crm' ), 200 );
			}
			wp_send_json_success( __( 'Update successful', 'we-make-content-crm' ), 200 );
		} else {
			wp_send_json_error( __( 'Failed operation', 'we-make-content-crm' ), 200 );
		}
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
			wp_die( __( 'We\'ve detected you\'re requesting with an invalid security token or something went wrong with you', 'we-make-content-crm' ), __( 'Security mismatched', 'we-make-content-crm' ) );
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
			set_transient( 'futurewordpress/project/transiant/admin/' . get_current_user_id(), $msg, 200 );
		}
		// print_r( [ $args, $is_created, $msg ] );wp_die();
		wp_redirect( wp_get_referer() );
	}
	public function switchLeadStatus() {
		if( ! isset( $_POST[ '_nonce' ] ) || ! wp_verify_nonce( $_POST[ '_nonce' ], 'futurewordpress/project/verify/nonce' ) ) {
			wp_send_json_error( __( 'We\'ve detected you\'re requesting with an invalid security token or something went wrong with you', 'we-make-content-crm' ), 200 );
		}
		if( empty( $this->actionStatuses( [], $_POST[ 'value' ] ) ) ) {
			wp_send_json_error( __( 'Unexpected status requested.', 'we-make-content-crm' ), 200 );
		}
		if( isset( $_POST[ 'lead' ] ) && ! empty( $_POST[ 'value' ] ) ) {
			update_user_meta( $_POST[ 'lead' ], 'status', $_POST[ 'value' ] );
			wp_send_json_success( [ 'message' => __( 'Updated Successfully.', 'we-make-content-crm' ), 'hooks' => ['lead-status-' . $_POST[ 'lead' ] . '-' . $_POST[ 'value' ] ] ], 200 );
		} else {
			wp_send_json_error( __( 'Status request contains empty arguments.', 'we-make-content-crm' ), 200 );
		}
	}
	public function deleteLeadAccount() {
		if( ! isset( $_POST[ '_nonce' ] ) || ! wp_verify_nonce( $_POST[ '_nonce' ], 'futurewordpress/project/verify/nonce' ) ) {
			wp_send_json_error( __( 'We\'ve detected you\'re requesting with an invalid security token or something went wrong with you', 'we-make-content-crm' ), 200 );
		}
		if( isset( $_POST[ 'lead' ] ) && ! empty( $_POST[ 'lead' ] ) ) {
			wp_delete_user( $_POST[ 'lead' ] );
			wp_send_json_error( [ 'message' => __( 'Deleted User Successfully.', 'we-make-content-crm' ), 'hooks' => ['delete-lead-' . $_POST[ 'lead' ] ] ], 200 );
		} else {
			wp_send_json_error( __( 'Unexpected status requested.', 'we-make-content-crm' ), 200 );
		}
	}
	public function rewriteRules( $rules ) {
		$rules[] = [ 'lead-registration/source-email/([^/]*)/?', 'index.php?lead_registration=$matches[1]', 'top' ];
		return $rules;
	}
	public function template_include( $template ) {
    $lead_registration = get_query_var( 'lead_registration' );// $order_id = get_query_var( 'order_id' );
		if ( $lead_registration == false || $lead_registration == '' ) {
      return $template;
    } else {
			$file = WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/cards/lead_registration.php';
			if( file_exists( $file ) && ! is_dir( $file ) ) {
          return $file;
        } else {
          return $template;
        }
		}
	}
	public function sendRegistration() {
		if( ! isset( $_POST[ '_nonce' ] ) || ! wp_verify_nonce( $_POST[ '_nonce' ], 'futurewordpress/project/verify/nonce' ) ) {
			wp_send_json_error( __( 'We\'ve detected you\'re requesting with an invalid security token or something went wrong with you', 'we-make-content-crm' ), 200 );
		}
		if( isset( $_POST[ 'lead' ] ) && ! empty( $_POST[ 'lead' ] ) ) {
			$userInfo = get_user_by( 'id', $_POST[ 'lead' ] );
			$userMeta = array_map( function( $a ){ return $a[0]; }, (array) get_user_meta( $userInfo->ID ) );
			$userInfo = (object) wp_parse_args( $userInfo, [ 'id' => '', 'meta' => (object) wp_parse_args( $userMeta, apply_filters( 'futurewordpress/project/usermeta/defaults', (array) $userMeta ) ) ] );
			$args = ['id' => 0, 'to' => empty( $userInfo->data->user_email ) ? $userInfo->meta->email : $userInfo->data->user_email, 'name' => get_option( 'blogname' ), 'email' => get_option( 'admin_email' ), 'subject' => '', 'message' => ''];
			
			$instead = [
				'{{client_name}}',
				'{{client_address}}',
				'{{todays_date}}',
				'{{retainer_amount}}',
				'{{registration_link}}',
			];
			 $replace = [
				empty( $userInfo->meta->first_name . $userInfo->meta->last_name ) ? $userInfo->data->display_name : $userInfo->meta->first_name . ' ' . $userInfo->meta->last_name,
			! empty( $userInfo->meta->address1 ) ? $userInfo->meta->address1 : ( ! empty( $userInfo->meta->address2 ) ? $userInfo->meta->address2 : apply_filters( 'futurewordpress/project/system/getoption', 'signature-addressplaceholder', '' ) ),
			wp_date( apply_filters( 'futurewordpress/project/system/getoption', 'signature-dateformat', '' ), strtotime( date( 'Y-M-d' ) ) ),
			! empty( $userInfo->meta->monthly_retainer ) ? $userInfo->meta->monthly_retainer : apply_filters( 'futurewordpress/project/system/getoption', 'signature-emptyrrtainer', '' ),
			site_url( 'lead-registration/source-email/' . bin2hex( $userInfo->ID ) . '/' )
			];
			
			$args[ 'subject' ] = str_replace( $instead, $replace, apply_filters( 'futurewordpress/project/system/getoption', 'email-registationsubject', 'Invitation to Register for ' . get_option( 'blogname', site_url() ) ) );
			$args[ 'message' ] = str_replace( $instead, $replace, apply_filters( 'futurewordpress/project/system/getoption', 'email-registationbody', 'Email not set. Sorry for inturrupt.' ) );
			
			if( apply_filters( 'futurewordpress/project/mailsystem/sendmail', $args ) ) {
				wp_send_json_success( [ 'message' => __( 'Registration Link sent successfully!', 'we-make-content-crm' ), 'hooks' => [ 'sent-registration-' . $_POST[ 'lead' ] ] ], 200 );
			}
		}
		wp_send_json_error( __( 'Unexpected status requested.', 'we-make-content-crm' ), 200 );
	}
	public function sendPasswordReset() {
		$user = $userInfo = get_user_by( 'id', $_POST[ 'lead' ] );
		$userMeta = array_map( function( $a ){ return $a[0]; }, (array) get_user_meta( $userInfo->ID ) );
		$userInfo = (object) wp_parse_args( $userInfo, [ 'id' => '', 'meta' => (object) wp_parse_args( $userMeta, apply_filters( 'futurewordpress/project/usermeta/defaults', (array) $userMeta ) ) ] );
		if ( ! $userInfo ) {wp_send_json_error( __( 'User doesn\'t identified.', 'we-make-content-crm' ), 200 );}
		$key = get_password_reset_key( $user );
		$reset_password_link = network_site_url( "wp-login.php?action=rp&key={$key}&login=" . rawurlencode($user->user_login), 'login' );
		$message = 'You recently requested a password reset link. Here is your reset link: ' . $reset_password_link;
		$instead = [
			'{{client_name}}',
			'{{client_address}}',
			'{{todays_date}}',
			'{{retainer_amount}}',
			'{{registration_link}}',
			'{{site_name}}',
			'{{passwordreset_link}}',
		];
		 $replace = [
			empty( $userInfo->meta->first_name . $userInfo->meta->last_name ) ? $userInfo->data->display_name : $userInfo->meta->first_name . ' ' . $userInfo->meta->last_name,
			! empty( $userInfo->meta->address1 ) ? $userInfo->meta->address1 : ( ! empty( $userInfo->meta->address2 ) ? $userInfo->meta->address2 : apply_filters( 'futurewordpress/project/system/getoption', 'signature-addressplaceholder', '' ) ),
			wp_date( apply_filters( 'futurewordpress/project/system/getoption', 'signature-dateformat', '' ), strtotime( date( 'Y-M-d' ) ) ),
			! empty( $userInfo->meta->monthly_retainer ) ? $userInfo->meta->monthly_retainer : apply_filters( 'futurewordpress/project/system/getoption', 'signature-emptyrrtainer', '' ),
			site_url( 'lead-registration/source-email/' . base64_encode( $userInfo->ID ) . '/' ),
			get_option( 'blogname', 'We Make Content' ),
			$reset_password_link
		];
		
		$args = [ 'id' => 0, 'to' => empty( $userInfo->data->user_email ) ? $userInfo->meta->email : $userInfo->data->user_email, 'name' => get_option( 'blogname' ), 'email' => get_option( 'admin_email' ), 'subject' => '', 'message' => $message ];
		$args[ 'subject' ] = str_replace( $instead, $replace, apply_filters( 'futurewordpress/project/system/getoption', 'email-passresetsubject', 'Passwird Reset link for ' . get_option( 'blogname', site_url() ) ) );
		$args[ 'message' ] = str_replace( $instead, $replace, apply_filters( 'futurewordpress/project/system/getoption', 'email-passresetbody', 'Email not set. Sorry for inturrupt.' ) );
		// print_r( [ $args, $userInfo ] );
		if ( apply_filters( 'futurewordpress/project/mailsystem/sendmail', $args ) ) {
			wp_send_json_success( [ 'message' => __( 'Reset Link sent successfully!', 'we-make-content-crm' ), 'hooks' => [ 'sent-passreset-' . $_POST[ 'lead' ] ] ], 200 );
		} else {
			wp_send_json_error( __( 'Unexpected respond from backend.', 'we-make-content-crm' ), 200 );
		}
	}
	public function testAjax() {
		wp_send_json_success( [ 'message' => 'some text', 'hooks' => ['fuck'] ], 200 );
	}
}
