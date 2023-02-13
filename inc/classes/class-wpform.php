<?php
/**
 * LoadmorePosts
 *
 * @package WeMakeContentCMS
 */

namespace WEMAKECONTENTCMS_THEME\Inc;

use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;
use \WP_Query;

class Wpform {

	use Singleton;

	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		// add_action( 'init', [ $this, 'wp_init' ], 10, 0 );
		// add_action( 'admin_init', [ $this, 'admin_init' ], 10, 0 );
		// add_filter( 'pre_get_posts', [ $this, 'pre_get_posts' ], 10, 1 );
		add_action( 'wpforms_process_complete', [ $this, 'wpforms_process_complete' ], 10, 4 );
		add_filter( 'wpforms_process_filter', [ $this, 'wpforms_process_filter' ], 10, 3 );
		// add_filter( 'wpforms_user_registration_email_exists', [ $this, 'wpforms_user_registration_email_exists' ], 10, 1 );

		add_filter( 'wpforms_user_registration_process_registration_get_data', [ $this, 'wpforms_user_registration_process_registration_get_data' ], 10, 3 );
		add_filter( 'wp_pre_insert_user_data', [ $this, 'wp_pre_insert_user_data' ], 10, 4 );
		add_filter( 'wpforms_field_data', [ $this, 'wpforms_field_data' ], 10, 2 );
		add_filter( 'wpforms_frontend_form_data', [ $this, 'wpforms_frontend_form_data' ], 10, 1 );
		add_filter( 'wpforms_process_before_form_data', [ $this, 'wpforms_frontend_form_data' ], 10, 1 );
		add_filter( 'wpforms_field_properties_email', [ $this, 'wpforms_field_properties_email' ], 10, 3 );
	}
	public function wp_init() {}
	public function admin_init() {}
	public function pre_get_posts( $query ) {}

	
	public function is_allowed( $form_data ) {
		$allowed_ids = [ 660 ];
		return in_array( absint( $form_data['id'] ), $allowed_ids );
	}

	/**
	 * This will fire at the very end of a (successful) form entry.
	 *
	 * @link  https://wpforms.com/developers/wpforms_process_complete/
	 *
	 * @param array  $fields    Sanitized entry field values/properties.
	 * @param array  $entry     Original $_POST global.
	 * @param array  $form_data Form data and settings.
	 * @param int    $entry_id  Entry ID. Will return 0 if entry storage is disabled or using WPForms Lite.
	 */
	public function wpforms_process_complete( $fields, $entry, $form_data, $entry_id ) {
		if( ! $this->is_allowed( $form_data ) ) {return;}
		if( ! function_exists( 'wpforms' ) ) {return;}
		// Get the full entry object
		// $entry = wpforms()->entry->get( $entry_id );

		if( defined( 'WPFORMS_PROCESS_FILTER_HANDLED_EMAIL' ) ) {
			$theEmail = WPFORMS_PROCESS_FILTER_HANDLED_EMAIL;
			$userInfo = get_user_by( 'email', $theEmail[0] );
			if( ! $userInfo ) {
				$userInfo = get_users( ['meta_key' => 'email','meta_value' => $theEmail[0],'fields'=>'ids'] );
				if( $userInfo && is_array( $userInfo ) && count( $userInfo ) > 0 ) {
					$userInfo = get_user_by( 'id', $userInfo[0] );
				}
			}
			if( $userInfo ) {
				$auth = [];
				foreach( $fields as $field ) {
					switch( $field[ 'type' ] ) {
						case 'email' :
							update_user_meta( $userInfo->ID, 'email', $theEmail[0] );
							$auth[ 'user_login' ] = $theEmail[0];
							break;
						case 'name' :
							update_user_meta( $userInfo->ID, 'first_name', $field[ 'first' ] );
							update_user_meta( $userInfo->ID, 'last_name', $field[ 'last' ] );
							break;
						case 'address' :
							update_user_meta( $userInfo->ID, 'address1', $field[ 'address1' ] );
							update_user_meta( $userInfo->ID, 'address2', $field[ 'address2' ] );
							update_user_meta( $userInfo->ID, 'address', $field[ 'value' ] );
							update_user_meta( $userInfo->ID, 'country', $field[ 'country' ] );
							update_user_meta( $userInfo->ID, 'state', $field[ 'state' ] );
							update_user_meta( $userInfo->ID, 'zip', $field[ 'postal' ] );
							update_user_meta( $userInfo->ID, 'city', $field[ 'city' ] );
							break;
						case 'url' :
							if( strpos( strtolower( $field[ 'value' ] ), 'website' ) ) {
								update_user_meta( $userInfo->ID, 'website', $field[ 'value' ] );
							}
							break;
						case 'text' :
							if( strpos( strtolower( $field[ 'name' ] ), 'tiktok' ) ) {update_user_meta( $userInfo->ID, 'tiktok', $field[ 'value' ] );}
							if( strpos( strtolower( $field[ 'name' ] ), 'youtube' ) ) {update_user_meta( $userInfo->ID, 'YouTube_url', $field[ 'value' ] );}
							if( strpos( strtolower( $field[ 'name' ] ), 'instagram' ) ) {update_user_meta( $userInfo->ID, 'instagram_url', $field[ 'value' ] );}
							if( strpos( strtolower( $field[ 'name' ] ), 'company' ) ) {update_user_meta( $userInfo->ID, 'company_name', $field[ 'value' ] );}
							if( strpos( strtolower( $field[ 'name' ] ), 'phone' ) ) {update_user_meta( $userInfo->ID, 'phone', $field[ 'value' ] );}
							break;
						case 'password' :
							$auth[ 'user_password' ] = $field[ 'value' ];
							break;
						default :
							break;
					}
				}
				if( isset( $auth[ 'user_login' ] ) ) {
					wp_clear_auth_cookie();
					wp_set_current_user ( $userInfo->ID );
					wp_set_auth_cookie  ( $userInfo->ID );
				}
			}
		}
		// print_r( json_encode( [$fields, $entry, $form_data, $entry_id] ) );wp_die();
	}
	/**
	 * Check email address exists.
	 *
	 * @link    https://wpforms.com/developers/wpforms_user_registration_email_exists/
	 *
	 * @param   string   $msg  Message that displays if email exists.
	 * @return  string
	 */
 
	public function wpforms_user_registration_email_exists( $msg ) {
		// This is the message that would appear
		$msg =  __('We\'re sorry! A user with that email already exists. Please update the email address and try again, or user information will update only!', 'text-domain');
		return $msg;
	}
	public function wpforms_user_registration_process_registration_get_data( $user_data, $fields, $form_data ) {
		if( ! $this->is_allowed( $form_data ) ) {return $user_data;}
		if( ! function_exists( 'wpforms' ) ) {return $user_data;}
		// print_r( [ 'theChangedEmail', WPFORMS_PROCESS_FILTER_HANDLED_EMAIL, $user_data ] );wp_die();

		if( defined( 'WPFORMS_PROCESS_FILTER_HANDLED_EMAIL' ) ) {
			$theEmail = WPFORMS_PROCESS_FILTER_HANDLED_EMAIL;
			$has_user = get_user_by_email( $theEmail[0] );
			if( ! is_wp_error( $has_user ) && $has_user && $has_user->ID !== 0 ) {
				$user_data[ 'ID' ] = $has_user->ID;$user_data[ 'user_email' ] = $theEmail[0];
			} else {
				$user_data[ 'user_email' ] = $theEmail[0];
			}
		}

		return $user_data;
	}
	public function wpforms_process_filter( $fields, $entry, $form_data ) {
		if( ! $this->is_allowed( $form_data ) ) {return $fields;}
		$theEmail = false;
		foreach( $fields as $i => $row ) {
			if( isset( $row[ 'type' ] ) && $row[ 'type' ] == 'email' ) {

				$has_user = get_user_by( 'email', $row[ 'value' ] );
				if( ! $has_user ) {
					$has_user = get_users( ['meta_key' => 'email','meta_value' => $row[ 'value' ],'fields'=>'ids'] );
					if( $has_user && is_array( $has_user ) && count( $has_user ) > 0 ) {
						$has_user = get_user_by( 'id', $has_user[0] );
					}
				}
				$theEmail = [ $row[ 'value' ], time() . '___' . $row[ 'value' ] ];
				$fields[ $i ][ 'value' ] = $theEmail[1];
			}
		}
		defined( 'WPFORMS_PROCESS_FILTER_HANDLED_EMAIL' ) || define( 'WPFORMS_PROCESS_FILTER_HANDLED_EMAIL', $theEmail );
		return $fields;
	}
	public function wp_pre_insert_user_data( $data, $update, $is_createorID, $userdata ) {
		if( ! function_exists( 'wpforms' ) ) {return $data;}
		if( defined( 'WP_PRE_INSERT_USER_DATA' ) ) {return $data;}
		if( defined( 'WPFORMS_PROCESS_FILTER_HANDLED_EMAIL' ) ) {
			$theEmail = WPFORMS_PROCESS_FILTER_HANDLED_EMAIL;
			$has_user = get_user_by( 'email', $theEmail[0] );
			if( ! $has_user ) {
				$has_user = get_users( ['meta_key' => 'email','meta_value' => $row[ 'value' ],'fields'=>'ids'] );
				if( $has_user && is_array( $has_user ) && count( $has_user ) > 0 ) {
					$has_user = get_user_by( 'id', $has_user[0] );
				}
			}
			if( $has_user && $has_user->ID !== 0 ) {
				$data[ 'ID' ] = $has_user->ID;$data[ 'user_email' ] = $theEmail[0];
				defined( 'WP_PRE_INSERT_USER_DATA' ) || define( 'WP_PRE_INSERT_USER_DATA', true );
				if( isset( $data[ 'user_pass' ] ) ) {unset( $data[ 'user_pass' ] );}
				wp_update_user( $data );
			}
			// print_r( $data );wp_die();
		}
		return $data;
	}
	public function wpforms_field_data( $field, $form_data ) {
		if( ! $this->is_allowed( $form_data ) ) {return $field;}
		if( isset( $field[ 'price' ] ) ) {
			// $userID = get_transient( md5( wp_remote_get( site_url() ) ) . '_lead_user_registration' );
			$userID = get_transient( '_lead_user_registration-' . apply_filters( 'futurewordpress/project/user/visitorip', '' ) );
			$meta = get_user_meta( $userID, 'monthly_retainer', true );
			if( $meta && ! empty( $meta ) && (int) $meta > 0 ) {
				$field[ 'price' ] = (int) $meta;
			}
			$field[ 'price' ] = $meta;
		}
		if( $field[ 'type' ] == 'email' ) {
			$userID = get_transient( '_lead_user_registration-' . apply_filters( 'futurewordpress/project/user/visitorip', '' ) );
			$meta = get_user_meta( $userID, 'email', true );
			$field[ 'default_value' ] = $meta;
		}
		// print_r( $field );
		return $field;
	}
	public function wpforms_frontend_form_data( $form_data, $entry = false ) {
		if( ! $this->is_allowed( $form_data ) ) {return $form_data;}
		foreach( $form_data[ 'fields' ] as $i => $field ) {
			if( isset( $field[ 'price' ] ) ) {
				$userID = get_transient( '_lead_user_registration-' . apply_filters( 'futurewordpress/project/user/visitorip', '' ) ); // hex2bin( get_query_var( 'registration' ) );
				$meta = ( $userID) ? get_user_meta( $userID, 'monthly_retainer', true ) : false;
				if( $meta && ! empty( $meta ) && (int) $meta > 0 ) {
					$form_data[ 'fields' ][$i][ 'price' ] = (int) $meta;
				}
			}
			if( $field[ 'type' ] == 'email' ) {
				$userID = get_transient( '_lead_user_registration-' . apply_filters( 'futurewordpress/project/user/visitorip', '' ) );
				$meta = get_user_meta( $userID, 'email', true );
				$field[ 'default_value' ] = $meta;
			}
		}
		// print_r( json_encode( $form_data ) );wp_die();
		return $form_data;
	}
	public function wpforms_field_properties_email( $properties, $field, $form_data ) {
		if( ! $this->is_allowed( $form_data ) ) {return $properties;}
		// $properties[ 'inputs' ][ 'primary' ][ 'attr' ][ 'disabled' ] = true;
		$properties[ 'inputs' ][ 'primary' ][ 'attr' ][ 'readonly' ] = true;
		// print_r( [$properties, $field, $form_data] );
		
		return $properties;
	}
}