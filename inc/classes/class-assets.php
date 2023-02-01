<?php
/**
 * Enqueue theme assets
 *
 * @package WeMakeContentCMS
 */


namespace WEMAKECONTENTCMS_THEME\Inc;

use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;

class Assets {
	use Singleton;

	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {

		/**
		 * Actions.
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
		/**
		 * The 'enqueue_block_assets' hook includes styles and scripts both in editor and frontend,
		 * except when is_admin() is used to include them conditionally
		 */
		// add_action( 'enqueue_block_assets', [ $this, 'enqueue_editor_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 10, 1 );

		add_filter( 'futurewordpress/project/javascript/siteconfig', [ $this, 'siteConfig' ], 10, 1 );
	}

	public function register_styles() {
		// Register styles.
		// wp_register_style( 'bootstrap-css', WEMAKECONTENTCMS_BUILD_LIB_URI . '/css/bootstrap.min.css', [], false, 'all' );
		// wp_register_style( 'slick-css', WEMAKECONTENTCMS_BUILD_LIB_URI . '/css/slick.css', [], false, 'all' );
		// wp_register_style( 'slick-theme-css', WEMAKECONTENTCMS_BUILD_LIB_URI . '/css/slick-theme.css', ['slick-css'], false, 'all' );
		// wp_register_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css', [], false, 'all' );

		wp_register_style( 'WeMakeContentCMS', WEMAKECONTENTCMS_BUILD_CSS_URI . '/frontend.css', [], $this->filemtime( WEMAKECONTENTCMS_BUILD_CSS_DIR_PATH . '/frontend.css' ), 'all' );

		// Enqueue Styles.
		wp_enqueue_style( 'WeMakeContentCMS' );
		// if( $this->allow_enqueue() ) {}

		// wp_enqueue_style( 'bootstrap' );
		// wp_enqueue_style( 'slick-css' );
		// wp_enqueue_style( 'slick-theme-css' );

	}

	public function register_scripts() {
		// Register scripts.
		// wp_register_script( 'slick-js', WEMAKECONTENTCMS_BUILD_LIB_URI . '/js/slick.min.js', ['jquery'], false, true );
		wp_register_script( 'WeMakeContentCMS', WEMAKECONTENTCMS_BUILD_JS_URI . '/frontend.js', ['jquery'], $this->filemtime( WEMAKECONTENTCMS_BUILD_JS_DIR_PATH . '/frontend.js' ), true );
		// wp_register_script( 'single-js', WEMAKECONTENTCMS_BUILD_JS_URI . '/single.js', ['jquery', 'slick-js'], $this->filemtime( WEMAKECONTENTCMS_BUILD_JS_DIR_PATH . '/single.js' ), true );
		// wp_register_script( 'author-js', WEMAKECONTENTCMS_BUILD_JS_URI . '/author.js', ['jquery'], $this->filemtime( WEMAKECONTENTCMS_BUILD_JS_DIR_PATH . '/author.js' ), true );
		// wp_register_script( 'bootstrap', WEMAKECONTENTCMS_BUILD_LIB_URI . '/js/bootstrap.min.js', ['jquery'], false, true );
		// wp_register_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js', ['jquery'], false, true );
		wp_register_script( 'prismjs', 'https://preview.keenthemes.com/start/assets/plugins/custom/prismjs/prismjs.bundle.js', ['jquery'], false, true );
		wp_register_script( 'popperjs', 'https://unpkg.com/@popperjs/core@2', ['jquery'], false, true );
		wp_register_script( 'plugins-bundle', WEMAKECONTENTCMS_BUILD_LIB_URI . '/js/keenthemes.plugins.bundle.js', ['jquery'], false, true );
		wp_register_script( 'scripts-bundle', WEMAKECONTENTCMS_BUILD_LIB_URI . '/js/keenthemes.scripts.bundle', ['jquery'], false, true );

		// Enqueue Scripts.
		// Both of is_order_received_page() and is_wc_endpoint_url( 'order-received' ) will work to check if you are on the thankyou page in the frontend.
		wp_enqueue_script( 'WeMakeContentCMS' );
		// wp_enqueue_script( 'prismjs' );wp_enqueue_script( 'popperjs' );wp_enqueue_script( 'bootstrap' );
		// if( $this->allow_enqueue() ) {}
		
		// wp_enqueue_script( 'bootstrap-js' );
		// wp_enqueue_script( 'slick-js' );

		// If single post page
		// if ( is_single() ) {
		// 	wp_enqueue_script( 'single-js' );
		// }

		// If author archive page
		// if ( is_author() ) {
		// 	wp_enqueue_script( 'author-js' );
		// }
		// 

		wp_localize_script( 'WeMakeContentCMS', 'fwpSiteConfig', apply_filters( 'futurewordpress/project/javascript/siteconfig', [
			'videoClips'		=> [],
		] ) );
	}
	private function allow_enqueue() {
		return ( function_exists( 'is_checkout' ) && ( is_checkout() || is_order_received_page() || is_wc_endpoint_url( 'order-received' ) ) );
	}

	/**
	 * Enqueue editor scripts and styles.
	 */
	public function enqueue_editor_assets() {

		$asset_config_file = sprintf( '%s/assets.php', WEMAKECONTENTCMS_BUILD_PATH );

		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}

		$asset_config = require_once $asset_config_file;

		if ( empty( $asset_config['js/editor.js'] ) ) {
			return;
		}

		$editor_asset    = $asset_config['js/editor.js'];
		$js_dependencies = ( ! empty( $editor_asset['dependencies'] ) ) ? $editor_asset['dependencies'] : [];
		$version         = ( ! empty( $editor_asset['version'] ) ) ? $editor_asset['version'] : $this->filemtime( $asset_config_file );

		// Theme Gutenberg blocks JS.
		if ( is_admin() ) {
			wp_enqueue_script(
				'aquila-blocks-js',
				WEMAKECONTENTCMS_BUILD_JS_URI . '/blocks.js',
				$js_dependencies,
				$version,
				true
			);
		}

		// Theme Gutenberg blocks CSS.
		$css_dependencies = [
			'wp-block-library-theme',
			'wp-block-library',
		];

		wp_enqueue_style(
			'aquila-blocks-css',
			WEMAKECONTENTCMS_BUILD_CSS_URI . '/blocks.css',
			$css_dependencies,
			$this->filemtime( WEMAKECONTENTCMS_BUILD_CSS_DIR_PATH . '/blocks.css' ),
			'all'
		);

	}
	public function admin_enqueue_scripts( $curr_page ) {
		global $post;
		// if( ! in_array( $curr_page, [ 'edit.php', 'post.php' ] ) || 'shop_order' !== $post->post_type ) {return;}
		wp_register_style( 'WeMakeContentCMSBackendCSS', WEMAKECONTENTCMS_BUILD_CSS_URI . '/backend.css', [], $this->filemtime( WEMAKECONTENTCMS_BUILD_CSS_DIR_PATH . '/backend.css' ), 'all' );
		wp_register_script( 'WeMakeContentCMSBackendJS', WEMAKECONTENTCMS_BUILD_JS_URI . '/backend.js', [ 'jquery' ], $this->filemtime( WEMAKECONTENTCMS_BUILD_JS_DIR_PATH . '/backend.js' ), true );
		
		wp_enqueue_style( 'WeMakeContentCMSBackendCSS' );
		wp_enqueue_script( 'WeMakeContentCMSBackendJS' );

		wp_localize_script( 'WeMakeContentCMSBackendJS', 'fwpSiteConfig', apply_filters( 'futurewordpress/project/javascript/siteconfig', [] ) );
	}
	private function filemtime( $file ) {
		return apply_filters( 'futurewordpress/project/filesystem/filemtime', false, $file );
	}
	public function siteConfig( $args ) {
		return wp_parse_args( [
			'ajaxUrl'    		=> admin_url( 'admin-ajax.php' ),
			'ajax_nonce' 		=> wp_create_nonce( 'futurewordpress_project_nonce' ),
			'buildPath'  		=> WEMAKECONTENTCMS_BUILD_URI,
			'i18n'					=> [
				'sureToSubmit'								=> __( 'Want to submit it? You can retake.', 'we-make-content-crm' ),
				'uploading'									=> __( 'Uploading', 'we-make-content-crm' ),
				'click_here'								=> __( 'Click here', 'we-make-content-crm' ),
				'video_exceed_dur_limit'					=> __( 'Video exceed it\'s duration limit.', 'we-make-content-crm' ),
				'file_exceed_siz_limit'						=> __( 'Filesize exceed it maximum limit 30MB.', 'we-make-content-crm' ),
				'audio_exceed_dur_limit'					=> __( 'Audio exceed it\'s duration limit.', 'we-make-content-crm' ),
				'invalid_file_formate'						=> __( 'Invalid file formate.', 'we-make-content-crm' ),
				'device_error'								=> __( 'Device Error', 'we-make-content-crm' ),
				'confirm_cancel_subscribe'					=> __( 'Do you really want to cancel this Subscription?', 'we-make-content-crm' ),
				'i_confirm_it'								=> __( 'Yes I confirm it', 'we-make-content-crm' ),
				'confirming'								=> __( 'Confirming', 'we-make-content-crm' ),
				'request_failed'							=> __( 'Request failed', 'we-make-content-crm' ),
				'submit'									=> __( 'Submit', 'we-make-content-crm' ),
				'give_your_old_password'						=> __( 'Give here your old password', 'we-make-content-crm' ),
			],
		], (array) $args );
	}

}