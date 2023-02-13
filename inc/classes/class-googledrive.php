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
	private $clientSecret;
	private $refreshToken;
	protected function __construct() {
		global $wpdb;$this->theTable = $wpdb->prefix . 'fwp_googledrive';
		$this->clientId = "<CLIENT_ID>";
		$this->clientSecret = "<CLIENT_SECRET>";
		$this->refreshToken = "<REFRESH_TOKEN>";// AIzaSyAvwGtzotd9YWfndpmLJDQCOpqQMsspQro
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		add_action( 'wp_ajax_futurewordpress/project/action/submitarchives', [ $this, 'submitArchives' ], 10, 0 );
		add_filter( 'futurewordpress/project/filesystem/ziparchives', [ $this, 'zipArchives' ], 10, 2 );
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
			$wpdb->insert( $this->theTable, [
				'user_id' => is_admin() ? $data->userid : get_current_user_id(),
				'title' => $data->title,
				'formonth' => $data->month . ' ' . $data->year,
				'drive_id' => '',
				'file_path' => site_url( str_replace( [ ABSPATH ], [ '' ], $archive_path ) ),
				'status' => 'active',
				'archived' => maybe_serialize( json_encode( $newMeta ) ) // Temporary Meta value. But infuture, google drive info
			], [ '%d', '%s', '%s', '%s', '%s', '%s', '%s' ] );
			WC()->session->set( 'uploaded_files_to_archive', [] );
			wp_send_json_success( [ 'message' => __( 'Response recieved but nothing happen until we setup google drive.', 'we-make-content-crm' ), 'hooks' => [ 'reload-page' ], 'data'	=> [$file_list, $archive_path, $this->theTable ] ], 200 );
		} else {
			wp_send_json_error( __( 'Problem detected while creating archive.', 'we-make-content-crm' ), 200 );
		}
	}
	public function archiveFiles( $file_list, $destination ) {
		$zip = new \ZipArchive();
		if( $zip->open( $destination, \ZIPARCHIVE::CREATE ) !== TRUE ) {
			return false;
		}
		foreach( $file_list as $file) {
			if( ! $zip->addFile( basename( $file ), $file ) ) {
				echo "Error adding file: " . basename( $file ) . "\n";
			}
		}
		$zip->close();
		return true;
	}
}
