<?php
/**
 * Loadmore Single Posts
 *
 * @package WeMakeContentCMS
 */

namespace WEMAKECONTENTCMS_THEME\Inc;

use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;
use \WP_Query;

class Dashboard {
	use Singleton;
	public $allowedPages = null;
	protected function __construct() {
		// load class.
    $this->allowedPages = [ 'crm_dashboard' ];
		$this->setup_hooks();
	}
	protected function setup_hooks() {
    add_filter( 'futurewordpress/project/admin/allowedpage', [ $this, 'allowedpage' ], 10, 0 );
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 10, 0 );
    add_action( 'wp_after_admin_bar_render', [ $this, 'wp_after_admin_bar_render' ], 10, 0 );
	}
  public function admin_menu() {
    add_menu_page(
      __(  'CRM Dashboard', 'domain' ),     // page title
      __(  'Dashboard', 'domain' ),     // menu title
      'manage_options',   // capability
      'crm_dashboard',     // menu slug  
      [ $this, 'crm_dashboard' ], // callback function
      WEMAKECONTENTCMS_BUILD_URI . '/icons/dashboard-alt.svg',
      10
    );
  }
  public function crm_dashboard() {
    global $title;
    ?>
    <div class="wrap dashboard__body-start">
      <?php include WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/admin/crm_dashboard.php'; ?>
    </div>
    <?php
  }
  public function wp_after_admin_bar_render() {
    if( ! isset( $_GET[ 'page' ] ) || ! in_array( $_GET[ 'page' ], $this->allowedPages ) ) {return;}
    include WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/admin/admin_bar_render.php';
  }
  public function allowedpage() {
    return $this->allowedPages;
  }

}
