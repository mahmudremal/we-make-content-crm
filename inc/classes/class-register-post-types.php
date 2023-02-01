<?php
/**
 * Register Post Types
 *
 * @package WeMakeContentCMS
 */

namespace WEMAKECONTENTCMS_THEME\Inc;

use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;

class Register_Post_Types {
	use Singleton;

	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {

		/**
		 * Actions.
		 */
		add_action( 'init', [ $this, 'create_movie_cpt' ], 0 );

	}

	// Register Custom Post Type Movie
	public function create_movie_cpt() {

		$labels = [
			'name'                  => _x( 'Movies', 'Post Type General Name', 'we-make-content-crm' ),
			'singular_name'         => _x( 'Movie', 'Post Type Singular Name', 'we-make-content-crm' ),
			'menu_name'             => _x( 'Movies', 'Admin Menu text', 'we-make-content-crm' ),
			'name_admin_bar'        => _x( 'Movie', 'Add New on Toolbar', 'we-make-content-crm' ),
			'archives'              => __( 'Movie Archives', 'we-make-content-crm' ),
			'attributes'            => __( 'Movie Attributes', 'we-make-content-crm' ),
			'parent_item_colon'     => __( 'Parent Movie:', 'we-make-content-crm' ),
			'all_items'             => __( 'All Movies', 'we-make-content-crm' ),
			'add_new_item'          => __( 'Add New Movie', 'we-make-content-crm' ),
			'add_new'               => __( 'Add New', 'we-make-content-crm' ),
			'new_item'              => __( 'New Movie', 'we-make-content-crm' ),
			'edit_item'             => __( 'Edit Movie', 'we-make-content-crm' ),
			'update_item'           => __( 'Update Movie', 'we-make-content-crm' ),
			'view_item'             => __( 'View Movie', 'we-make-content-crm' ),
			'view_items'            => __( 'View Movies', 'we-make-content-crm' ),
			'search_items'          => __( 'Search Movie', 'we-make-content-crm' ),
			'not_found'             => __( 'Not found', 'we-make-content-crm' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'we-make-content-crm' ),
			'featured_image'        => __( 'Featured Image', 'we-make-content-crm' ),
			'set_featured_image'    => __( 'Set featured image', 'we-make-content-crm' ),
			'remove_featured_image' => __( 'Remove featured image', 'we-make-content-crm' ),
			'use_featured_image'    => __( 'Use as featured image', 'we-make-content-crm' ),
			'insert_into_item'      => __( 'Insert into Movie', 'we-make-content-crm' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Movie', 'we-make-content-crm' ),
			'items_list'            => __( 'Movies list', 'we-make-content-crm' ),
			'items_list_navigation' => __( 'Movies list navigation', 'we-make-content-crm' ),
			'filter_items_list'     => __( 'Filter Movies list', 'we-make-content-crm' ),
		];
		$args   = [
			'label'               => __( 'Movie', 'we-make-content-crm' ),
			'description'         => __( 'The movies', 'we-make-content-crm' ),
			'labels'              => $labels,
			'menu_icon'           => 'dashicons-video-alt',
			'supports'            => [
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions',
				'author',
				'comments',
				'trackbacks',
				'page-attributes',
				'custom-fields',
			],
			'taxonomies'          => [],
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'show_in_rest'        => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		];

		register_post_type( 'movies', $args );

	}


}
