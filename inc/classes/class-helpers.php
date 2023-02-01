<?php
/**
 * Theme Sidebars.
 *
 * @package WeMakeContentCMS
 */
namespace WEMAKECONTENTCMS_THEME\Inc;
use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;
/**
 * Class Widgets.
 */
 class Helpers {
	use Singleton;
	private $theUploadDir;
	private $dateFormate;
	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->theUploadDir = false;
		$this->dateFormate = 'd-M-Y H:i:s';
		$this->setup_hooks();
	}
	/**
	 * To register action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {
		if( ! defined( 'FUTUREWORDPRESS_PROJECT_OPTIONS' ) ) {define( 'FUTUREWORDPRESS_PROJECT_OPTIONS', (array) get_option( 'we-make-content-crm', [] ) );}
		add_filter( 'futurewordpress/project/system/getoption', [ $this, 'getOption' ], 10, 2 );
		add_filter( 'futurewordpress/project/system/isactive', [ $this, 'isActive' ], 10, 1 );


		add_filter( 'futurewordpress/project/filter/server/time', [ $this, 'serverTime' ], 10, 2 );
		add_filter( 'futurewordpress/project/filesystem/filemtime', [ $this, 'filemtime' ], 10, 2 );
		add_filter( 'futurewordpress/project/mailsystem/sendmail', [ $this, 'sendMail' ], 10, 1 );
		
		add_action( 'wp_ajax_futurewordpress/project/filesystem/upload', [ $this, 'uploadFile' ], 10, 0 );
		add_action( 'wp_ajax_nopriv_futurewordpress/project/filesystem/upload', [ $this, 'uploadFile' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/filesystem/remove', [ $this, 'removeFile' ], 10, 0 );
		add_action( 'wp_ajax_nopriv_futurewordpress/project/filesystem/remove', [ $this, 'removeFile' ], 10, 0 );
		add_action( 'admin_post_futurewordpress/project/filesystem/download', [ $this, 'downloadFile' ], 10, 0 );
		add_action( 'admin_post_nopriv_futurewordpress/project/filesystem/download', [ $this, 'downloadFile' ], 10, 0 );
	}
	/**
	 * Get and option value, return default. Default false.
	 * 
	 * @return string
	 */
	public function getOption( $option, $default ) {
    return isset( FUTUREWORDPRESS_PROJECT_OPTIONS[ $option ] ) ? FUTUREWORDPRESS_PROJECT_OPTIONS[ $option ] : $default;
	}
	/**
	 * Check if is active or not.
	 * 
	 * @return bool
	 */
	public function isActive( $option ) {
    return ( isset( FUTUREWORDPRESS_PROJECT_OPTIONS[ $option ] ) && FUTUREWORDPRESS_PROJECT_OPTIONS[ $option ] == 'on' );
	}
	/**
	 * Given a date in the timezone of the site, returns that date in UTC.
	 * 
	 * @return string
	 */
	public function serverTime( $time, $args = [] ) {
    return get_gmt_from_date( date( $this->dateFormate, $time ), $this->dateFormate );
  }
	/**
	 * Given a date in UTC or GMT timezone, returns that date in the timezone of the site.
	 * 
	 * @return string
	 */
	private function getLocalTime( $time, $args = [] ) {
    return get_date_from_gmt( date( $this->dateFormate, $time ), $this->dateFormate );
  }
	/**
	 * File Modification time.
	 * 
	 * @return string
	 */
	public function filemtime( $version, $path ) {
		return ( file_exists( $path ) && ! is_dir( $path ) ) ? filemtime( $path ) : $version;
	}
	/**
	 * Sending mail using filter.
	 * 
	 * @return void
	 */
	public function sendMail( $args = [] ) {
		$request = wp_parse_args( $args, [
			'id' => 0, 'to' => '', 'name' => '', 'email' => '', 'subject' => '', 'message' => ''
		] );
		// can be verify by "id" as company ID Author ID
		$to = $request[ 'to' ];
		$subject = $request[ 'subject' ];
		$body = $request[ 'message' ];
		$headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
		$headers[] = 'Reply-To: ' . $request[ 'name' ] . ' <' . $request[ 'email' ] . '>';

		wp_mail( $to, $subject, $body, $headers );
		// $msg = [ 'status' => 'success', 'message' => __( get_FwpOption( 'msg_profile_edit_success_txt', 'Changes saved' ), FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ) ];
		// set_transient( 'status_successed_message-' . get_current_user_id(), $msg, 300 );
		wp_safe_redirect( wp_get_referer() );
  }

	/**
	 * Filesystem Uploading contents.
	 * 
	 * @return string
	 */
	private function uploadDir( $file = false, $force = false ) {
		$uploadDir = $this->theUploadDir;
		if( $this->theUploadDir === false ) {
			$uploadDir = wp_get_upload_dir();
			$uploadDir[ 'basedir' ] = $uploadDir[ 'basedir' ] . '/checkout-video';
			$uploadDir[ 'baseurl' ] = $uploadDir[ 'baseurl' ] . '/checkout-video';
			$this->theUploadDir = $uploadDir;
		}
		// wp_die( print_r( $uploadDir ) );
		$basedir = $uploadDir[ 'basedir' ];
		if( ! is_dir( $basedir ) ) {wp_mkdir_p( $basedir );}
		return ( $file && file_exists( $basedir . '/' . $file ) ) ? $basedir . '/' . $file : ( ( $force ) ? $basedir . '/' . $file : $basedir );
	}
	public function uploadFile() {
		if( ! function_exists( 'WC' ) ) {wp_send_json_error( __( 'Woo not installed', 'we-make-content-crm' ), 200 );}
		check_ajax_referer( 'futurewordpress_project_nonce', '_nonce' );

		if( isset( $_FILES[ 'blobFile' ] ) || isset( $_FILES[ 'file' ] ) ) {
			$file = isset( $_FILES[ 'blobFile' ] ) ? $_FILES[ 'blobFile' ] : $_FILES[ 'file' ];
			$blobInfo = isset( $_POST[ 'blobInfo' ] ) ? (array) json_decode( $_POST[ 'blobInfo' ] ) : [];
			// ABSPATH . WP_CONTENT_URL . 
			$file[ 'name' ] = isset( $file[ 'name' ] ) ? (
				( $file[ 'name' ] == 'blob' ) ? ( isset( $blobInfo[ 'name' ] ) ? $blobInfo[ 'name' ] : 'captured.webm' ) : $file[ 'name' ]
			) : 'captured.' . explode( '/', $file[ 'type' ] )[1];
			$file[ 'full_path' ] = $this->uploadDir( time() . '-' . basename( $file[ 'name' ] ), true );$error = false;
			if( $file[ 'size' ] > 5000000000 ) {
				$error = sprintf( __( 'File is larger then allowed range. (%d)', 'we-make-content-crm' ), $file[ 'size' ] );
			}
			$extension = strtolower( pathinfo( $file[ 'name' ], PATHINFO_EXTENSION ) );
			$mime = mime_content_type( $file[ 'tmp_name' ] );$extension = empty( $extension ) ? $mime : $extension;
			if( ! in_array( $extension, [ 'mp4', 'text/html' ] ) && ! strstr( $mime, "video/" ) ) {
				$error = sprintf( __( 'File format (%s) is not allowed.', 'we-make-content-crm' ), $extension );
			}
			if( $error === false && move_uploaded_file( $file[ 'tmp_name' ], $file[ 'full_path' ] ) ) {
				$file[ 'full_url' ] = str_replace( [ $this->theUploadDir[ 'basedir' ] ], [ $this->theUploadDir[ 'baseurl' ] ], $file[ 'full_path' ] );
				$meta = [
					// 'time' => time(),
					'date' => date( 'Y:M:d H:i:s' ),
					'wp_date' => wp_date( 'Y:M:d H:i:s' ),
					...$file
				];
				$oldMeta = (array) WC()->session->get( 'checkout_video_clip' );
				if( isset( $oldMeta[ 'full_path' ] ) && ! empty( $oldMeta[ 'full_path' ] ) && file_exists( $oldMeta[ 'full_path' ] ) && ! is_dir( $oldMeta[ 'full_path' ] ) ) {unlink( $oldMeta[ 'full_path' ] );}
				$meta['type'] = apply_filters( 'futurewordpress/project/validate/format', $meta['type'], $meta );
				WC()->session->set( 'checkout_video_clip', $meta );
				wp_send_json_success( [
					'message'			=> __( 'Uploaded successfully', 'we-make-content-crm' ),
					'dropZone'		=> $meta
				], 200 );
			} else {
				$error = ( $error ) ? $error : __( 'Something went wrong while tring to upload short clip video.', 'we-make-content-crm' );
				wp_send_json_error( $error, 200 );
			}
		}
		wp_send_json_error( __( 'Error happens.', 'we-make-content-crm' ), 200 );

	}
	public function removeFile() {
		check_ajax_referer( 'futurewordpress_project_nonce', '_nonce' );
		$fileInfo = isset( $_POST[ 'fileinfo' ] ) ? (array) json_decode( str_replace( "\\", "", $_POST[ 'fileinfo' ] ) ) : [];

		// if( isset( $fileInfo[ 'full_path' ] ) ) {$_POST[ 'todelete' ] = $fileInfo[ 'full_path' ];}
		// if( isset( $_POST[ 'todelete' ] ) && file_exists( $this->uploadDir( basename( $_POST[ 'todelete' ] ), true ) ) && ! is_dir( $this->uploadDir( basename( $_POST[ 'todelete' ] ), true ) ) ) {

		if( isset( $fileInfo[ 'full_path' ] ) && file_exists( $fileInfo[ 'full_path' ] ) && ! is_dir( $fileInfo[ 'full_path' ] ) ) {
			// unlink( $this->uploadDir( basename( $fileInfo[ 'full_path' ] ), true ) );
			unlink( $fileInfo[ 'full_path' ] );
			WC()->session->set( 'checkout_video_clip', [] );
			wp_send_json_success( __( 'Clip removed from server.', 'we-make-content-crm' ), 200 );
		} else {
			wp_send_json_error( __( 'Failed to delete. Maybe File not found on server or your request doesn\'t contain file data enough.', 'we-make-content-crm' ), 400 );
		}
	}
	public function downloadFile() {
		check_ajax_referer( 'futurewordpress_project_nonce', '_nonce' );
		$order_id = isset( $_GET[ 'order_id' ] ) ? $_GET[ 'order_id' ] : false;$fileInfo = [];
		$meta = get_post_meta( $order_id, 'checkout_video_clip', true );
		if( $meta && !empty( $meta ) && isset( $meta[ 'name' ] ) ) {$fileInfo = $meta;}

		if( isset( $fileInfo[ 'full_url' ] ) && isset( $fileInfo[ 'full_path' ] ) && file_exists( $fileInfo[ 'full_path' ] ) && ! is_dir( $fileInfo[ 'full_path' ] ) ) {
			wp_redirect( $fileInfo[ 'full_url' ] );
		} else {
			print_r( $fileInfo );
			wp_die( __( 'File not found', 'we-make-content-crm' ), __( '404 not found', 'we-make-content-crm' ) );
		}
	}

}