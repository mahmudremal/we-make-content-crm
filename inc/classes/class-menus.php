<?php
/**
 * Register Menus
 *
 * @package WeMakeContentCMS
 */
namespace WEMAKECONTENTCMS_THEME\Inc;
use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;
class Menus {
	use Singleton;
	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		/**
		 * Actions.
		 */
		// add_action( 'init', [ $this, 'register_menus' ] );
		
    add_filter( 'futurewordpress/project/settings/general', [ $this, 'general' ], 10, 1 );
    add_filter( 'futurewordpress/project/settings/fields', [ $this, 'menus' ], 10, 1 );
	}
	public function register_menus() {
		register_nav_menus([
			'aquila-header-menu' => esc_html__( 'Header Menu', 'we-make-content-crm' ),
			'aquila-footer-menu' => esc_html__( 'Footer Menu', 'we-make-content-crm' ),
		]);
	}
	/**
	 * Get the menu id by menu location.
	 *
	 * @param string $location
	 *
	 * @return integer
	 */
	public function get_menu_id( $location ) {
		// Get all locations
		$locations = get_nav_menu_locations();
		// Get object id by location.
		$menu_id = ! empty($locations[$location]) ? $locations[$location] : '';
		return ! empty( $menu_id ) ? $menu_id : '';
	}
	/**
	 * Get all child menus that has given parent menu id.
	 *
	 * @param array   $menu_array Menu array.
	 * @param integer $parent_id Parent menu id.
	 *
	 * @return array Child menu array.
	 */
	public function get_child_menu_items( $menu_array, $parent_id ) {
		$child_menus = [];
		if ( ! empty( $menu_array ) && is_array( $menu_array ) ) {
			foreach ( $menu_array as $menu ) {
				if ( intval( $menu->menu_item_parent ) === $parent_id ) {
					array_push( $child_menus, $menu );
				}
			}
		}
		return $child_menus;
	}

  /**
   * WordPress Option page.
   * 
   * @return array
   */
	public function general( $args ) {
		return $args;
	}
	public function menus( $args ) {
    // get_FwpOption( 'key', 'default' ) | apply_filters( 'futurewordpress/project/system/getoption', 'key', 'default' )
		// is_FwpActive( 'key' ) | apply_filters( 'futurewordpress/project/system/isactive', 'key' )
		$args = [];
		$args['standard'] 		= [
			'title'							=> __( 'General', 'we-make-content-crm' ),
			'description'				=> __( 'Generel fields comst commonly used to changed.', 'we-make-content-crm' ),
			'fields'						=> [
				[
					'id' 						=> 'general-enable',
					'label'					=> __( 'Enable', 'we-make-content-crm' ),
					'description'		=> __( 'Mark to enable function of this Plugin.', 'we-make-content-crm' ),
					'type'					=> 'checkbox',
					'default'				=> true
				],
			]
		];
		$args['permalink'] 		= [
			'title'						=> __( 'Permalink', 'we-make-content-crm' ),
			'description'			=> __( 'Setup some permalink like dashboard and like this kind of things.', 'we-make-content-crm' ),
			'fields'					=> [
				[
					'id' 							=> 'permalink-dashboard',
					'label'						=> __( 'Dashboard Slug', 'we-make-content-crm' ),
					'description'			=> __( 'Enable dashboard parent Slug. By default it is "/dashboard". Each time you change this field you\'ve to re-save permalink settings.', 'we-make-content-crm' ),
					'type'						=> 'text',
					'default'					=> 'dashboard'
				],
				[
					'id' 						=> 'permalink-userby',
					'label'					=> __( 'Dashboard Slug', 'we-make-content-crm' ),
					'description'		=> __( 'Enable dashboard parent Slug. By default it is "/dashboard".', 'we-make-content-crm' ),
					'type'					=> 'radio',
					'default'				=> 'id',
					'options'				=> [ 'id' => __( 'User ID', 'we-make-content-crm' ), 'slug' => __( 'User Unique Name', 'we-make-content-crm' ) ]
				],
			]
		];
		$args['dashboard'] 		= [
			'title'							=> __( 'Dashboard', 'we-make-content-crm' ),
			'description'				=> __( 'Dashboard necessery fields, text and settings can configure here. Some tags on usable fields can be replace from here.', 'we-make-content-crm' ) . $this->commontags( true ),
			'fields'						=> [
				[
					'id' 						=> 'dashboard-title',
					'label'					=> __( 'Dashboard title', 'we-make-content-crm' ),
					'description'		=> __( 'The title on dahsboard page. make sure you user tags properly.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> sprintf( __( 'Client Dashoard | %s | %s', 'we-make-content-crm' ), '{username}', '{sitename}' )
				],
				[
					'id' 						=> 'dashboard-yearstart',
					'label'					=> __( 'Year Starts', 'we-make-content-crm' ),
					'description'		=> __( 'The Year range on dashboard starts from.', 'we-make-content-crm' ),
					'type'					=> 'number',
					'default'				=> date( 'Y' )
				],
				[
					'id' 						=> 'dashboard-yearend',
					'label'					=> __( 'Yeah Ends with', 'we-make-content-crm' ),
					'description'		=> __( 'The Year range on dashboard ends on.', 'we-make-content-crm' ),
					'type'					=> 'number',
					'default'				=> ( date( 'Y' ) + 3 )
				],
			]
		];
		$args['docs'] 		= [
			'title'							=> __( 'Documentation', 'we-make-content-crm' ),
			'description'				=> __( 'Documentation feature and their links can be change from here. If you leave blank anything then these "Learn More" never display.', 'we-make-content-crm' ) . $this->commontags( true ),
			'fields'						=> [
				[
					'id' 						=> 'docs-title',
					'label'					=> __( 'docs title', 'we-make-content-crm' ),
					'description'		=> __( 'The title on dahsboard page. make sure you user tags properly.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> sprintf( __( 'Client Dashoard | %s | %s', 'we-make-content-crm' ), '{username}', '{sitename}' )
				],
				[
					'id' 						=> 'docs-yearstart',
					'label'					=> __( 'Year Starts', 'we-make-content-crm' ),
					'description'		=> __( 'The Year range on docs starts from.', 'we-make-content-crm' ),
					'type'					=> 'number',
					'default'				=> date( 'Y' )
				],
				[
					'id' 						=> 'docs-yearend',
					'label'					=> __( 'Yeah Ends with', 'we-make-content-crm' ),
					'description'		=> __( 'The Year range on docs ends on.', 'we-make-content-crm' ),
					'type'					=> 'number',
					'default'				=> ( date( 'Y' ) + 3 )
				],
			]
		];
		return $args;
	}
	/**
	 * Supply necessry tags that could be replace on frontend.
	 * 
	 * @return string
	 * @return array
	 */
	public function commontags( $html = false ) {
		$arg = [];$tags = [
			'username', 'sitename', 
		];
		if( $html === false ) {return $tags;}
		foreach( $tags as $tag ) {
			$arg[] = sprintf( "%s{$tag}%s", '<code>{', '}</code>' );
		}
		return implode( ', ', $arg );
	}
}
