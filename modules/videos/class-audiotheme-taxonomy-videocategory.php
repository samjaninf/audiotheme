<?php
/**
 * Video category taxonomy registration and integration.
 *
 * @package AudioTheme\Videos
 * @since 1.9.0
 */

/**
 * Class for registering the video category taxonomy and integration.
 *
 * @package AudioTheme\Videos
 * @since 1.9.0
 */
class AudioTheme_Taxonomy_VideoCategory {
	/**
	 * Module.
	 *
	 * @since 1.9.0
	 * @var AudioTheme_Module_Videos
	 */
	protected $module;

	/**
	 * Taxonomy name.
	 *
	 * @since 1.9.0
	 * @var string
	 */
	protected $taxonomy = 'audiotheme_video_category';

	/**
	 * Constructor method.
	 *
	 * @since 1.9.0
	 *
	 * @param AudioTheme_Module_Videos $module Videos module.
	 */
	public function __construct( $module ) {
		$this->module = $module;
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.9.0
	 */
	public function register_hooks() {
		add_action( 'init',                  array( $this, 'register_taxonomy' ) );
		add_action( 'term_updated_messages', array( $this, 'term_updated_messages' ) );
	}

	/**
	 * Register taxonomies.
	 *
	 * @since 1.9.0
	 */
	public function register_taxonomy() {
		register_taxonomy( 'audiotheme_video_category', 'audiotheme_video', $this->get_args() );
	}

	/**
	 * Term updated messages.
	 *
	 * @since 1.9.0
	 *
	 * @param array $messages Term update messages.
	 * @return array
	 */
	public function term_updated_messages( $messages ) {
		$messages[ $this->taxonomy ] = array(
			0 => '', // 0 = unused. Messages start at index 1.
			1 => esc_html__( 'Category added.', 'audiotheme' ),
			2 => esc_html__( 'Category deleted.', 'audiotheme' ),
			3 => esc_html__( 'Category updated.', 'audiotheme' ),
			4 => esc_html__( 'Category not added.', 'audiotheme' ),
			5 => esc_html__( 'Category not updated.', 'audiotheme' ),
			6 => esc_html__( 'Categories deleted.', 'audiotheme' ),
		);

		return $messages;
	}

	/**
	 * Retrieve taxonomy registration arguments.
	 *
	 * @since 1.9.0
	 *
	 * @param array
	 */
	protected function get_args() {
		return array(
			'args'              => array( 'orderby' => 'term_order' ),
			'hierarchical'      => true,
			'labels'            => $this->get_labels(),
			'public'            => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'          => $this->module->get_rewrite_base() . '/category',
				'with_front'    => false,
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
		);
	}

	/**
	 * Retrieve taxonomy labels.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	protected function get_labels() {
		return array(
			'name'                       => esc_html_x( 'Categories', 'taxonomy general name', 'audiotheme' ),
			'singular_name'              => esc_html_x( 'Category', 'taxonomy singular name', 'audiotheme' ),
			'search_items'               => esc_html__( 'Search Categories', 'audiotheme' ),
			'popular_items'              => esc_html__( 'Popular Categories', 'audiotheme' ),
			'all_items'                  => esc_html__( 'All Categories', 'audiotheme' ),
			'parent_item'                => esc_html__( 'Parent Category', 'audiotheme' ),
			'parent_item_colon'          => esc_html__( 'Parent Category:', 'audiotheme' ),
			'edit_item'                  => esc_html__( 'Edit Category', 'audiotheme' ),
			'view_item'                  => esc_html__( 'View Category', 'audiotheme' ),
			'update_item'                => esc_html__( 'Update Category', 'audiotheme' ),
			'add_new_item'               => esc_html__( 'Add New Category', 'audiotheme' ),
			'new_item_name'              => esc_html__( 'New Category Name', 'audiotheme' ),
			'separate_items_with_commas' => esc_html__( 'Separate categories with commas', 'audiotheme' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove categories', 'audiotheme' ),
			'choose_from_most_used'      => esc_html__( 'Choose from most used categories', 'audiotheme' ),
			'menu_name'                  => esc_html_x( 'Categories', 'admin menu name', 'audiotheme' ),
			'not_found'                  => esc_html__( 'No categories found.', 'audiotheme' ),
			'no_terms'                   => esc_html__( 'No categories', 'audiotheme' ),
			'items_list_navigation'      => esc_html__( 'Categories list navigation', 'audiotheme' ),
			'items_list'                 => esc_html__( 'Categories list', 'audiotheme' ),
		);
	}
}