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
	private $parentDirectory;
	private $theFiletoUpload;
	private $authorizationCode;
	protected function __construct() {
		global $wpdb;$this->theTable = $wpdb->prefix . 'fwp_googledrive';
		$this->clientId					= '719552948296-d14739tknbd33tv16b932pb859kcvums.apps.googleusercontent.com'; // apply_filters( 'futurewordpress/project/system/getoption', 'auth-googleclientid', '719552948296-d14739tknbd33tv16b932pb859kcvums.apps.googleusercontent.com' );
		$this->clientSecret			= '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'; // apply_filters( 'futurewordpress/project/system/getoption', 'auth-googleclientsecret', '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' );
		$this->refreshToken			= "<REFRESH_TOKEN>";// AIzaSyAvwGtzotd9YWfndpmLJDQCOpqQMsspQro
		$this->redirectURL			= false;
		$this->authorizationCode = isset( $_GET[ 'code' ] ) ? $_GET[ 'code' ] : get_user_meta( get_current_user_id(), 'google_auth_code', true );'4/0AWtgzh6sAkiOikwI0NBvmHErOHE4bmzC9KIVvJeNo6VxMaDU7eVrh53SuoqZnnLvoQD1sg';
		$this->accessToken			= "<REFRESH_TOKEN>";// AIzaSyAvwGtzotd9YWfndpmLJDQCOpqQMsspQro
		$this->parentDirectory	= "1Rk6Rm-8W43T6BrEQ6z-WoeZyULeG8vFW";// 1MliOhH16m413OmiBGJM90cTmWUcwNUP4
		$this->theFiletoUpload	= '/home3/wemakeco/public_html/wp-content/uploads/futurewordpress/archive-57-feb-17-2023.zip';

		$this->setup_hooks();
	}
	protected function setup_hooks() {
		add_action( 'init', [ $this, 'init' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/action/submitarchives', [ $this, 'submitArchives' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/action/deletearchives', [ $this, 'deleteArchives' ], 10, 0 );
		add_filter( 'futurewordpress/project/filesystem/ziparchives', [ $this, 'zipArchives' ], 10, 2 );
		
	}
	public function init() {
		$this->redirectURL = apply_filters( 'futurewordpress/project/socialauth/redirect', '/handle/google', 'google' );
		// print_r( "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id={$this->clientId}&redirect_uri={$this->redirectURL}&scope=" . urlencode( 'https://www.googleapis.com/auth/drive.file' ) . "\r\n" );

		// print_r( $this->upload_to_google_drive_full() );
		// wp_die();
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

		print_r( [ $this->clientSecret, $response ] );wp_die();
	}
	private function uploadToGoogleDrive( $file_path, $access_token, $parent_folder_id = false ) {
		$parent_folder_id = ( $parent_folder_id ) ? $parent_folder_id : $this->parentDirectory;
		$url = "https://www.googleapis.com/upload/drive/v3/files?uploadType=resumable";
		$headers = [
			"Authorization: Bearer " . $access_token,
			"Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
			"X-Upload-Content-Type: application/zip",
			"X-Upload-Content-Length: " . filesize( $file_path )
		];
		$data = [
			"name" => basename( $file_path ),
			"parents" => [ $parent_folder_id ]
		];
		$json_data = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($ch, CURLOPT_HEADER, true);
		$response = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		curl_close($ch);

		print_r( $response );
		$location = "";
		if (preg_match('/location:\s*(\S+)/i', $response, $match)) {
			$location = trim($match[1]);
		}
		if( ! empty( $location ) ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $location );
			curl_setopt( $ch, CURLOPT_PUT, true );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HEADER, false );
			$fh = fopen( $file_path, 'r' );
			curl_setopt( $ch, CURLOPT_INFILE, $fh );
			curl_setopt( $ch, CURLOPT_INFILESIZE, filesize( $file_path) );
			$result = curl_exec( $ch );
			fclose( $fh );
			curl_close( $ch );
			print_r( $result );
			return $result;
		}

		return false;
	}
	public function serverToDriveUpload() {
		$file_path = $this->theFiletoUpload;
		$access_token = $this->get_access_token( '' );
		$parent_folder_id = $this->parentDirectory;
		$result = $this->uploadToGoogleDrive( $file_path, $access_token, $parent_folder_id );

		if( $result !== false ) {
			echo "File uploaded successfully!";
		} else {
			echo "Failed to upload file.";
		}

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
	private function get_access_token() {
		// Set up the cURL request
		$body = [
			'code' => $this->authorizationCode,
			'client_id' => $this->clientId,
			'client_secret' => $this->clientSecret,
			'redirect_uri' => $this->redirectURL,
			'grant_type' => 'authorization_code'
		];
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
			CURLOPT_POSTFIELDS => http_build_query( $body ),
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/x-www-form-urlencoded"
			),
		) );
		// Send the request and get the response
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		// print_r( $response );
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


	private function getAccessToken() {
		$body = [
			'code' 							=> get_user_meta( get_current_user_id(), 'google_auth_code', true ),
			'client_id' 				=> $this->clientId,
			'client_secret'			=> $this->clientSecret,
			'redirect_uri'			=> $this->redirectURL,
			'grant_type'				=> 'authorization_code'
		];
		print_r( $body );
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $body ) );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		print_r( $response );wp_die();
		$accessToken = json_decode($response)->access_token;
		return $accessToken;
	}
	public function uploadZipFile( $zipFilePath ) {
    $ch = curl_init();
    $postData = array(
			'file' => new \CURLFile($zipFilePath)
		);
		$headers = array(
				'Authorization: Bearer ' . $this->getAccessToken(),
				'Content-Type: multipart/related; boundary=foo_bar_baz'
		);
		curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart' );
		curl_setopt($ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData );
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		$response = curl_exec($ch);
    if (curl_errno($ch)) {
			$error_msg = curl_error($ch);
			curl_close($ch);
			return "cURL Error: " . $error_msg;
    }
    curl_close($ch);
    return $response;
	}

	public function updateTokens() {
		$clientId				= $this->clientId;
		$clientSecret		= $this->clientSecret;
		$redirectUri		= $this->redirectURL;
		$code						= $this->authorizationCode;

		$ch = curl_init('https://accounts.google.com/o/oauth2/token');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
			'code' => $code,
			'client_id' => $clientId,
			'client_secret' => $clientSecret,
			'redirect_uri' => $redirectUri,
			'grant_type' => 'authorization_code',
		)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);

		if (!$response) {
			die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
		}

		$responseData = json_decode($response, true);

		print_r( $responseData );

		$this->accessToken = $responseData['access_token'];
		$this->refreshToken = $responseData['refresh_token'];
	}
	public function upload_to_google_drive_full() {
		// Define variables
		$client_id				= $this->clientId;
		$client_secret		= $this->clientSecret;
		$refresh_token		= $this->refreshToken;
		$folder_id				= $this->parentDirectory;
		$file_path				= $this->theFiletoUpload;
		$access_token			= $this->get_access_token();
		print_r( [ $access_token ] );return false;

		// Get an access token
		$url = 'https://oauth2.googleapis.com/token';
		$data = array(
				'client_id' => $client_id,
				'client_secret' => $client_secret,
				'refresh_token' => $refresh_token,
				'grant_type' => 'refresh_token'
		);

		$options = array(
				'http' => array(
						'header' => "Content-type: application/x-www-form-urlencoded\r\n",
						'method' => 'POST',
						'content' => http_build_query($data),
				),
		);

		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		if ($result) {
				$response_data = json_decode($result, true);
				if (isset($response_data['access_token'])) {
						$access_token = $response_data['access_token'];
				}
		}

		if (!$access_token) {
			wp_die('Could not get access token');
		}

		// Upload the file to Google Drive
		$url = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart';

		// Set the file metadata
		$file_name = basename($file_path);
		$file_mime_type = mime_content_type($file_path);
		$file_metadata = array(
				'name' => $file_name,
				'parents' => array($folder_id)
		);

		$data = array(
				'data' => json_encode($file_metadata),
				'content' => file_get_contents($file_path),
				'mimeType' => $file_mime_type,
		);

		$headers = array(
				'Authorization: Bearer ' . $access_token,
				'Content-Type: multipart/related; boundary=foo_bar_baz',
				'Content-Length: ' . strlen(json_encode($file_metadata)) + filesize($file_path) + 101
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		if (!$response) {
			wp_die('Error uploading file');
		}

		$response_data = json_decode($response, true);

		// Print the file ID of the uploaded file
		echo 'File ID: ' . $response_data['id'];

	}
}
