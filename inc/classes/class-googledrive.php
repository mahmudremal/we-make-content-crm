<?php
/**
 * Blocks
 *
 * @package WeMakeContentCMS
 */
namespace WEMAKECONTENTCMS_THEME\Inc;
use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;
class GoogleDrive {
	use Singleton;
	private $theTable;
	private $clientId;
	private $redirectURL;
	private $accessToken;
	private $clientSecret;
	private $refreshToken;
	protected function __construct() {
		global $wpdb;$this->theTable = $wpdb->prefix . 'fwp_googledrive';
		$this->clientId = apply_filters( 'futurewordpress/project/system/getoption', 'auth-googleclientid', '719552948296-d14739tknbd33tv16b932pb859kcvums.apps.googleusercontent.com' );
		$this->clientSecret = apply_filters( 'futurewordpress/project/system/getoption', 'auth-googleclientsecret', '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' );
		$this->refreshToken = "<REFRESH_TOKEN>";// AIzaSyAvwGtzotd9YWfndpmLJDQCOpqQMsspQro
		$this->redirectURL = apply_filters( 'futurewordpress/project/socialauth/redirect', '/handle/google', 'google' );
		$this->accessToken = "<REFRESH_TOKEN>";// AIzaSyAvwGtzotd9YWfndpmLJDQCOpqQMsspQro

		$this->setup_hooks();
	}
	protected function setup_hooks() {
		add_action( 'wp_ajax_futurewordpress/project/action/submitarchives', [ $this, 'submitArchives' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/action/deletearchives', [ $this, 'deleteArchives' ], 10, 0 );
		add_filter( 'futurewordpress/project/filesystem/ziparchives', [ $this, 'zipArchives' ], 10, 2 );

		// $this->searchOnDrive( 'a' );
	}
	public function upload_file() {
		// Prepare the file
		$file = 'example.txt';
		$access_token = '';
		$file_size = filesize($file);
		$file_data = file_get_contents($file);
		$file_name = 'example.txt';
		$boundary = uniqid();
		// Prepare the API request
		$url = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart';
		$headers = array(
			'Authorization: Bearer ' . $access_token,
			'Content-Type: multipart/related; boundary="' . $boundary . '"'
		);
		$post_data = '--' . $boundary . "\r\n";
		$post_data .= 'Content-Type: application/json; charset=UTF-8' . "\r\n\r\n";
		$post_data .= json_encode(array(
			'name' => $file_name
		)) . "\r\n";
		$post_data .= '--' . $boundary . "\r\n";
		$post_data .= 'Content-Type: ' . mime_content_type($file) . "\r\n";
		$post_data .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
		$post_data .= base64_encode($file_data) . "\r\n";
		$post_data .= '--' . $boundary . '--' . "\r\n";

		// Send the API request
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		// Check for errors
		if (!$response) {
			die('Error: API request failed');
		}

		// Decode the API response
		$response_data = json_decode($response, true);

		// Check for errors
		if (isset($response_data['error'])) {
			die('Error: ' . $response_data['error']['message']);
		}

		// Print the file ID
		echo 'File ID: ' . $response_data['id'];
	}
	public function zipArchives( $default, $user_id ) {
		global $wpdb;
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->theTable} WHERE user_id=%d ORDER BY id DESC LIMIT 0, 500;", $user_id ) );
		return $rows;
	}
	public function submitArchives() {
		global $wpdb;
		$data = (object) wp_parse_args( $_POST, [ 'title' => '', 'month' => date( 'M' ), 'year' => date( 'Y' ), 'userid' => get_current_user_id() ] );
		$newMeta = (array) WC()->session->get( 'uploaded_files_to_archive' );$file_list = [];
		foreach( $newMeta as $i => $meta ) {
			if( isset( $meta[ 'full_path' ] ) && file_exists( $meta[ 'full_path' ] ) && ! is_dir( $meta[ 'full_path' ] ) ) {
				$file_list[] = $meta[ 'full_path' ];
			}
		}
		// print_r( Helpers()->uploadDir() );
		$archive_path = apply_filters( 'futurewordpress/project/filesystem/uploaddir', false ) . '/archive-' . $data->userid . '-' . strtolower( date( 'M-d-Y' ) ) . '.zip';
		$result = $this->archiveFiles( $file_list, $archive_path );
		if( $result ) {
			$user_id = is_admin() ? $data->userid : get_current_user_id();$month = $data->month . ' ' . $data->year;
			$record_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$this->theTable} WHERE user_id=%d AND formonth=%s;", $user_id, $month ) );
			if( $record_count <= 0 ) {
				$wpdb->insert( $this->theTable, [
					'user_id' => $user_id,
					'title' => $data->title,
					'formonth' => $month,
					'drive_id' => '',
					'file_path' => site_url( str_replace( [ ABSPATH ], [ '' ], $archive_path ) ),
					'status' => 'active',
					'archived' => maybe_serialize( json_encode( $newMeta ) ) // Temporary Meta value. But infuture, google drive info
				], [ '%d', '%s', '%s', '%s', '%s', '%s', '%s' ] );
			} else {
				$wpdb->update( $wpdb->theTable, [
					'title' => $data->title,
					'drive_id' => '',
					'file_path' => site_url( str_replace( [ ABSPATH ], [ '' ], $archive_path ) ),
					'status' => 'active',
					'archived' => maybe_serialize( json_encode( $newMeta ) )
				], [
					'user_id' => $user_id,
					'formonth' => $month,
				], [ '%d', '%s' ] );
			}
			foreach( $file_list as $file) {if( file_exists( $file ) && ! is_dir( $file ) ) {unlink( $file );}}
			WC()->session->set( 'uploaded_files_to_archive', [] );
			wp_send_json_success( [ 'message' => __( 'Response recieved but nothing happen until we setup google drive.', 'we-make-content-crm' ), 'hooks' => [ 'reload-page' ] ], 200 );
		} else {
			wp_send_json_error( __( 'Problem detected while creating archive.', 'we-make-content-crm' ), 200 );
		}
	}
	public function deleteArchives() {
		global $wpdb;if( ! apply_filters( 'futurewordpress/project/system/isactive', 'general-archivedelete' ) ) {return;}
		$user_id = is_admin() ? $_POST[ 'userid' ] : get_current_user_id();
		$archive = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->theTable} WHERE user_id=%d AND id=%s;", $user_id, $_POST[ 'archive' ] ) );
		$file_path = str_replace( [ site_url( '/' ) ], [ ABSPATH ], $archive->file_path );
		if( file_exists( $file_path ) && ! is_dir( $file_path ) ) {unlink( $file_path );}
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$this->theTable} WHERE user_id=%d AND id=%s;", $user_id, $_POST[ 'archive' ] ) );
		wp_send_json_success( [ 'message' => __( 'Archive removed from server successfully!', 'we-make-content-crm' ), 'hooks' => [ 'reload-page' ] ], 200 );
	}
	public function archiveFiles( $file_list, $destination ) {
		// error_reporting(E_ALL);
		// ini_set('display_errors', true);
		$zip = new \ZipArchive();$errors = false;
		if( $zip->open( $destination, \ZIPARCHIVE::CREATE | \ZIPARCHIVE::OVERWRITE ) !== TRUE ) {
			return false;
		}
		foreach( $file_list as $file) {
			if( $zip->addFile( $file, basename( $file ) ) ) {
				// unlink( $file ); // Unlink from here is not creating archive.
			} else {
				// echo "Error adding file: " . basename( $file ) . "\n";
				$errors = true;
			}
		}
		$zip->close();
		return ( ! $errors );
	}
	private function searchOnDrive( $fileName = false ) {
		if( ! $fileName ) {return;}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/drive/v3/files?q=name='$fileName'");
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Authorization: Bearer {$this->clientSecret}"
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response);
		$file = $response->files[0];
		$fileId = $file->id;
		print_r( $response );wp_die();
	}


	private function upload_to_google_drive($file, $fileName) {
		$filePath = realpath($file);
		$mimeType = mime_content_type($filePath);
		// Set up the cURL request
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => array('file' => curl_file_create($filePath, $mimeType, $fileName)),
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer {$this->accessToken}",
				"Content-Type: multipart/related"
			),
		));
		// Send the request and get the response
		$response = curl_exec($curl);
		$err = curl_error($curl);
		// Close the cURL session
		curl_close($curl);
		// Return the response or error
		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}
	private function get_access_token($authorizationCode) {
		// Set up the cURL request
		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_URL => "https://oauth2.googleapis.com/token",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => array(
				'code' => $authorizationCode,
				'client_id' => $this->clientId,
				'client_secret' => $this->clientSecret,
				'redirect_uri' => $this->redirectURL,
				'grant_type' => 'authorization_code'
			),
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/x-www-form-urlencoded"
			),
		) );
		// Send the request and get the response
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		// Check for errors
		if( $err ) {
			return "cURL Error #:" . $err;
		} else {
			// Parse the response and return the access token
			$response = json_decode( $response, true );
			if( isset( $response[ 'access_token' ] ) ) {
				$this->accessToken = $response[ 'access_token' ];
			}
			return $response[ 'access_token' ];
		}
	}	
}
