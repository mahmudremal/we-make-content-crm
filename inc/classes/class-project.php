<?php
/**
 * Bootstraps the Theme.
 *
 * @package WeMakeContentCMS
 */

namespace WEMAKECONTENTCMS_THEME\Inc;

use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;

class Project {
	use Singleton;

	protected function __construct() {

		// Load class.
		Assets::get_instance();
		Core::get_instance();
		Helpers::get_instance();
		// Widgets::get_instance();
		// Notices::get_instance();
		// Admin::get_instance();
		// Bulks::get_instance();

		// Blocks::get_instance();
		Option::get_instance();
		Menus::get_instance();
		// Meta_Boxes::get_instance();
		// Update::get_instance();
		Rewrite::get_instance();
		// Shortcode::get_instance();
		// Register_Post_Types::get_instance();
		// Register_Taxonomies::get_instance();

		$this->setup_hooks();
	}

	protected function setup_hooks() {
		add_action( 'body_class', [ $this, 'body_class' ], 10, 1 );
		add_action( 'init', [ $this, 'init' ], 10, 0 );
	}
	public function body_class( $classes ) {
		$classes = (array) $classes;
		$classes[] = 'fwp-body';
		return $classes;
	}
	public function init() {
		/**
		 * loco translator Lecto AI: api: V13Y91F-DR14RP6-KP4EAF9-S44K7SX
		 */
		load_plugin_textdomain( 'we-make-content-crm', false, dirname( plugin_basename( WEMAKECONTENTCMS_PROJECT__FILE__ ) ) . '/languages' );
		
		// add_action ( 'wp', function() {load_theme_textdomain( 'theme-name-here' );}, 1, 0 );
	}
}
