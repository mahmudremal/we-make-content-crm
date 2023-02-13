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
	private $clientId;
	private $clientSecret;
	private $refreshToken;
	protected function __construct() {
		$this->clientId = "<CLIENT_ID>";
		$this->clientSecret = "<CLIENT_SECRET>";
		$this->refreshToken = "<REFRESH_TOKEN>";
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		/**
		 * Actions.
		 */
		// add_filter( 'block_categories_all', [ $this, 'add_block_categories' ] );
	}
	/**
	 * Authenticate Google Drive and get access token
	 * @return string
	 */
	private function authenticate() {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://oauth2.googleapis.com/token");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id={$this->clientId}&client_secret={$this->clientSecret}&refresh_token={$this->refreshToken}&grant_type=refresh_token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($response);
		$accessToken = $response->access_token;
		$this->refreshToken = $accessToken;
		return $accessToken;
	}
	/**
	 * Upload a file using CURL.
	 */
	private function saveAFile( $file = false ) {
		if( ! $file ) {return;}
		$fileName = "example_file.txt";
		$parentFolderId = "<PARENT_FOLDER_ID>";
		$mimeType = "text/plain";

		$file = new CURLFile($fileName, $mimeType, $fileName);

		$postData = [
			'name' => $fileName,
			'parents' => [$parentFolderId],
			'mimeType' => $mimeType
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Authorization: Bearer $this->accessToken",
			"Content-Type: multipart/related"
		]);
		curl_setopt($ch, CURLOPT_POSTFIELDS, [
			"metadata" => json_encode($postData),
			"file" => $file
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($responseCode == 200) {
			echo "File successfully uploaded.";
		} else {
			echo "File upload failed with response code: $responseCode.";
		}
	}
	private function retrieveTheFileMetadata() {
		$fileId = "<FILE_ID>";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/drive/v3/files/$fileId");
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Authorization: Bearer $accessToken"
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($response);
		$fileName = $response->name;
		$fileMimeType = $response->mimeType;
	}
	private function tempDownloadLink() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/drive/v3/files/$fileId?alt=media");
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Authorization: Bearer $accessToken",
			"Content-Type: $fileMimeType"
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		header("Content-Type: $fileMimeType");
		header("Content-Disposition: attachment; filename=\"$fileName\"");
		echo $response;
	}
	private function searchOnDrive( $fileName = false ) {
		if( ! $fileName ) {return;}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/drive/v3/files?q=name='$fileName'");
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Authorization: Bearer $accessToken"
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response);
		$file = $response->files[0];
		$fileId = $file->id;
	}
}
