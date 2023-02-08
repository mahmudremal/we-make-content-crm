<?php
/**
 * LoadmorePosts
 *
 * @package WeMakeContentCMS
 */

namespace WEMAKECONTENTCMS_THEME\Inc;

use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;
use \WP_Query;

class Profile {

	use Singleton;

	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		add_action( 'get_avatar', [ $this, 'get_avatar' ], 10, 5 );
		add_filter( 'futurewordpress/project/filesystem/set_avater', [ $this, 'set_avater' ], 10, 2 );
	}
	public function get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
		$user = false;
		if( is_numeric( $id_or_email ) ) {
			$id = (int) $id_or_email;
			$user = get_user_by( 'id', $id );
		} elseif( is_object( $id_or_email ) ) {
			if( ! empty( $id_or_email->user_id ) ) {
				$id = (int) $id_or_email->user_id;
				$user = get_user_by( 'id', $id );
			}
		} else {
			$user = get_user_by( 'email', $id_or_email ); 
		}
		if( $user && is_object( $user ) ) {
			// Set your custom avatar URL
			$custom_avatar = get_user_meta( $user->ID, 'custom_avatar', true );
			if( $custom_avatar ) {
				$avatar = '<img alt="' . $alt . '" src="' . $custom_avatar . '" class="avatar avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';
			}
		}
		return $avatar;
	}
	public function set_avater( $status, $file ) {
		if( ! is_user_logged_in() ) {return;}
		$current_user = wp_get_current_user();
		if( ! current_user_can( 'upload_files' ) ) {return;}
		if( isset( $file['name'] ) ) {
			// Handle the uploaded file | $_FILES['my_custom_avatar_file']
			if( ! empty( $file['name'] ) ) {
				$file = $file;
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $file, $upload_overrides );
				if( $movefile && ! isset( $movefile['error'] ) ) {
					update_user_meta( $current_user->ID, 'custom_avatar', $movefile['url'] );
					return $movefile;
				} else {
					return false;
				}
			}
		}
	}
}
