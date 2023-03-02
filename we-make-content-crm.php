<?php
/**
 * This plugin ordered by a client and done by Remal Mahmud (fiverr.com/mahmud_remal). Authority dedicated to that cient.
 *
 * @wordpress-plugin
 * Plugin Name:       We Make Content CRM
 * Plugin URI:        https://github.com/mahmudremal/we-make-content-crm/
 * Description:       Client relationship management with Facebook lead generation, Calendly APi, Google APi and subscription system.
 * Version:           1.0.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Remal Mahmud
 * Author URI:        https://github.com/mahmudremal/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       we-make-content-crm
 * Domain Path:       /languages
 * 
 * @package WeMakeContentCMS
 * @author  Remal Mahmud (https://github.com/mahmudremal)
 * @version 1.0.2
 * @link https://github.com/mahmudremal/we-make-content-crm/
 * @category	WooComerce Plugin
 * @copyright	Copyright (c) 2023-25
 * 
 * payment custom link https://mysite.com/checkout/payment/39230/?pay_for_order=true&key=wc_order_UWdhxxxYYYzzz or get link $order->get_checkout_payment_url();
 */

/**
 * Bootstrap the plugin.
 */



defined( 'WEMAKECONTENTCMS_PROJECT__FILE__' ) || define( 'WEMAKECONTENTCMS_PROJECT__FILE__', untrailingslashit( __FILE__ ) );
defined( 'WEMAKECONTENTCMS_DIR_PATH' ) || define( 'WEMAKECONTENTCMS_DIR_PATH', untrailingslashit( plugin_dir_path( WEMAKECONTENTCMS_PROJECT__FILE__ ) ) );
defined( 'WEMAKECONTENTCMS_DIR_URI' ) || define( 'WEMAKECONTENTCMS_DIR_URI', untrailingslashit( plugin_dir_url( WEMAKECONTENTCMS_PROJECT__FILE__ ) ) );
defined( 'WEMAKECONTENTCMS_BUILD_URI' ) || define( 'WEMAKECONTENTCMS_BUILD_URI', untrailingslashit( WEMAKECONTENTCMS_DIR_URI ) . '/assets/build' );
defined( 'WEMAKECONTENTCMS_BUILD_PATH' ) || define( 'WEMAKECONTENTCMS_BUILD_PATH', untrailingslashit( WEMAKECONTENTCMS_DIR_PATH ) . '/assets/build' );
defined( 'WEMAKECONTENTCMS_BUILD_JS_URI' ) || define( 'WEMAKECONTENTCMS_BUILD_JS_URI', untrailingslashit( WEMAKECONTENTCMS_DIR_URI ) . '/assets/build/js' );
defined( 'WEMAKECONTENTCMS_BUILD_JS_DIR_PATH' ) || define( 'WEMAKECONTENTCMS_BUILD_JS_DIR_PATH', untrailingslashit( WEMAKECONTENTCMS_DIR_PATH ) . '/assets/build/js' );
defined( 'WEMAKECONTENTCMS_BUILD_IMG_URI' ) || define( 'WEMAKECONTENTCMS_BUILD_IMG_URI', untrailingslashit( WEMAKECONTENTCMS_DIR_URI ) . '/assets/build/src/img' );
defined( 'WEMAKECONTENTCMS_BUILD_CSS_URI' ) || define( 'WEMAKECONTENTCMS_BUILD_CSS_URI', untrailingslashit( WEMAKECONTENTCMS_DIR_URI ) . '/assets/build/css' );
defined( 'WEMAKECONTENTCMS_BUILD_CSS_DIR_PATH' ) || define( 'WEMAKECONTENTCMS_BUILD_CSS_DIR_PATH', untrailingslashit( WEMAKECONTENTCMS_DIR_PATH ) . '/assets/build/css' );
defined( 'WEMAKECONTENTCMS_BUILD_LIB_URI' ) || define( 'WEMAKECONTENTCMS_BUILD_LIB_URI', untrailingslashit( WEMAKECONTENTCMS_DIR_URI ) . '/assets/build/library' );
defined( 'WEMAKECONTENTCMS_ARCHIVE_POST_PER_PAGE' ) || define( 'WEMAKECONTENTCMS_ARCHIVE_POST_PER_PAGE', 9 );
defined( 'WEMAKECONTENTCMS_SEARCH_RESULTS_POST_PER_PAGE' ) || define( 'WEMAKECONTENTCMS_SEARCH_RESULTS_POST_PER_PAGE', 9 );
defined( 'FUTUREWORDPRESS_PROJECT_OPTIONS' ) || define( 'FUTUREWORDPRESS_PROJECT_OPTIONS', get_option( 'we-make-content-crm' ) );

require_once WEMAKECONTENTCMS_DIR_PATH . '/inc/helpers/autoloader.php';
// require_once WEMAKECONTENTCMS_DIR_PATH . '/inc/helpers/template-tags.php';

if( ! function_exists( 'wemakecontentcrm_get_theme_instance' ) ) {
	function wemakecontentcrm_get_theme_instance() {\WEMAKECONTENTCMS_THEME\Inc\Project::get_instance();}
}
wemakecontentcrm_get_theme_instance();



