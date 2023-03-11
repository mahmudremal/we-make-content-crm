<?php
/**
 * Register Custom Taxonomies
 *
 * @package WeMakeContentCMS
 */

namespace WEMAKECONTENTCMS_THEME\Inc;

use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;

class Taxonomies {
	use Singleton;

	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {

		/**
		 * Actions.
		 */
		add_action( 'init', [ $this, 'create_genre_taxonomy' ] );
		add_action( 'init', [ $this, 'create_year_taxonomy' ] );

	}

	// Register Taxonomy Genre
	public function create_genre_taxonomy() {

		$labels = [
			'name'              => _x( 'Genres', 'taxonomy general name', 'we-make-content-crm' ),
			'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'we-make-content-crm' ),
			'search_items'      => __( 'Search Genres', 'we-make-content-crm' ),
			'all_items'         => __( 'All Genres', 'we-make-content-crm' ),
			'parent_item'       => __( 'Parent Genre', 'we-make-content-crm' ),
			'parent_item_colon' => __( 'Parent Genre:', 'we-make-content-crm' ),
			'edit_item'         => __( 'Edit Genre', 'we-make-content-crm' ),
			'update_item'       => __( 'Update Genre', 'we-make-content-crm' ),
			'add_new_item'      => __( 'Add New Genre', 'we-make-content-crm' ),
			'new_item_name'     => __( 'New Genre Name', 'we-make-content-crm' ),
			'menu_name'         => __( 'Genre', 'we-make-content-crm' ),
		];
		$args   = [
			'labels'             => $labels,
			'description'        => __( 'Movie Genre', 'we-make-content-crm' ),
			'hierarchical'       => true,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		];

		register_taxonomy( 'genre', [ 'movies' ], $args );

	}

	// Register Taxonomy Year
	public function create_year_taxonomy() {

		$labels = [
			'name'              => _x( 'Years', 'taxonomy general name', 'we-make-content-crm' ),
			'singular_name'     => _x( 'Year', 'taxonomy singular name', 'we-make-content-crm' ),
			'search_items'      => __( 'Search Years', 'we-make-content-crm' ),
			'all_items'         => __( 'All Years', 'we-make-content-crm' ),
			'parent_item'       => __( 'Parent Year', 'we-make-content-crm' ),
			'parent_item_colon' => __( 'Parent Year:', 'we-make-content-crm' ),
			'edit_item'         => __( 'Edit Year', 'we-make-content-crm' ),
			'update_item'       => __( 'Update Year', 'we-make-content-crm' ),
			'add_new_item'      => __( 'Add New Year', 'we-make-content-crm' ),
			'new_item_name'     => __( 'New Year Name', 'we-make-content-crm' ),
			'menu_name'         => __( 'Year', 'we-make-content-crm' ),
		];
		$args   = [
			'labels'             => $labels,
			'description'        => __( 'Movie Release Year', 'we-make-content-crm' ),
			'hierarchical'       => false,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		];
		register_taxonomy( 'movie-year', [ 'movies' ], $args );

	}

}
