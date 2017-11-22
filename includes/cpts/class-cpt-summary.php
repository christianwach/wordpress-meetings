<?php

/**
 * WordPress Meetings Custom Post Type Class.
 *
 * A class that encapsulates a Custom Post Type for WordPress Meetings.
 *
 * @package WordPress_Meetings
 */
class WordPress_Meetings_CPT_Summary extends WordPress_Meetings_CPT_Common {

	/**
	 * Custom Post Type name.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $post_type_name The name of the Custom Post Type.
	 */
	public $post_type_name = 'summary';



	/**
	 * Constructor.
	 *
	 * @since 2.0
	 *
	 * @param object $parent The parent object.
	 */
	public function __construct( $parent ) {

		// store plugin reference
		parent::__construct( $parent );

	}



	/**
	 * Register WordPress hooks.
	 *
	 * @since 2.0
	 */
	public function register_hooks() {

		// common hooks
		parent::register_hooks();

		// remove unwanted metaboxes
		add_action( 'admin_menu', array( $this, 'metaboxes_remove' ) );

		// add menu item
		add_action( 'admin_menu', array( $this, 'add_to_menu' ) );

		// filter the title
		add_filter( 'the_title', array( $this, 'title_filter' ), 10, 2 );

	}



	// #########################################################################



	/**
	 * Create our Custom Post Type.
	 *
	 * @since 2.0
	 */
	public function post_type_create() {

		/**
		 * Allow customization of the default post type slug.
		 *
		 * @since 2.0
		 *
		 * @param str $slug The default slug.
		 * @return str $slug The modified slug.
		 */
		$slug = apply_filters( 'wordpress_meetings_cpt_' . $this->post_type_name . '_slug', $this->post_type_name );

		/**
		 * Allow customization of the default capabilities.
		 *
		 * @since 2.0
		 *
		 * @param array $capabilities The default capabilities.
		 * @return array $capabilities The modified capabilities.
		 */
		$capabilities = apply_filters( 'wordpress_meetings_cpt_' . $this->post_type_name . '_caps', $this->capabilities() );

		$labels = array(
			'name'                => _x( 'Summaries', 'Post Type General Name', 'wordpress-meetings' ),
			'singular_name'       => _x( 'Summary', 'Post Type Singular Name', 'wordpress-meetings' ),
			'menu_name'           => __( 'Summaries', 'wordpress-meetings' ),
			'name_admin_bar'      => __( 'Summary', 'wordpress-meetings' ),
			'parent_item_colon'   => __( 'Parent Summary:', 'wordpress-meetings' ),
			'all_items'           => __( 'All Summaries', 'wordpress-meetings' ),
			'add_new_item'        => __( 'Add New Summary', 'wordpress-meetings' ),
			'add_new'             => __( 'Add New Summary', 'wordpress-meetings' ),
			'new_item'            => __( 'New Summary', 'wordpress-meetings' ),
			'edit_item'           => __( 'Edit Summary', 'wordpress-meetings' ),
			'update_item'         => __( 'Update Summary', 'wordpress-meetings' ),
			'view_item'           => __( 'View Summary', 'wordpress-meetings' ),
			'search_items'        => __( 'Search Summaries', 'wordpress-meetings' ),
			'not_found'           => __( 'Not found', 'wordpress-meetings' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'wordpress-meetings' ),
		);

		$rewrite = array(
			'slug' => $slug,
			'with_front' => false,
			'pages' => true,
			'feeds' => true,
		);

		$supports = array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'custom-fields',
			'wpcom-markdown',
			'revisions',
		);

		$taxonomies = array(
			'organization',
			'meeting_tag',
		);

		$config = array(
			'label' => __( 'Summary', 'wordpress-meetings' ),
			'description' => __( 'Custom post type for summaries', 'wordpress-meetings' ),
			'labels' => $labels,
			'supports' => $supports,
			'taxonomies' => $taxonomies,
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => false,
			'menu_position' => 30,
			'menu_icon' => 'dashicons-list-view',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => 'summaries',
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'query_var' => $slug,
			'rewrite' => $rewrite,
			'show_in_rest' => true,
			'rest_base' => $slug,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'capability_type' => 'meeting',
			'capabilities' => $capabilities,
			'map_meta_cap' => true,
		);

		/**
		 * Allow customization of the default post type configuration.
		 *
		 * @since 2.0
		 *
		 * @param array $config The default config params.
		 * @param str $slug The default slug.
		 * @return array $config The modified config params.
		 */
		$config = apply_filters( 'wordpress_meetings_cpt_' . $this->post_type_name . '_config', $config, $slug );

		register_post_type( $this->post_type_name, $config );

	}



	/**
	 * Override messages for a custom post type.
	 *
	 * @since 2.0
	 *
	 * @param array $messages The existing messages.
	 * @return array $messages The modified messages.
	 */
	public function post_type_messages( $messages ) {

		// access relevant globals
		global $post, $post_ID;

		// define custom messages for our custom post type
		$messages[$this->post_type_name] = array(

			// unused - messages start at index 1
			0 => '',

			// item updated
			1 => sprintf(
				__( 'Summary updated. <a href="%s">View summary</a>', 'wordpress-meetings' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// custom fields
			2 => __( 'Custom field updated.', 'wordpress-meetings' ),
			3 => __( 'Custom field deleted.', 'wordpress-meetings' ),
			4 => __( 'Summary updated.', 'wordpress-meetings' ),

			// item restored to a revision
			5 => isset( $_GET['revision'] ) ?

					// revision text
					sprintf(
						// translators: %s: date and time of the revision
						__( 'Summary restored to revision from %s', 'wordpress-meetings' ),
						wp_post_revision_title( (int) $_GET['revision'], false )
					) :

					// no revision
					false,

			// item published
			6 => sprintf(
				__( 'Summary published. <a href="%s">View summary</a>', 'wordpress-meetings' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// item saved
			7 => __( 'Summary saved.', 'wordpress-meetings' ),

			// item submitted
			8 => sprintf(
				__( 'Summary submitted. <a target="_blank" href="%s">Preview summary</a>', 'wordpress-meetings' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// item scheduled
			9 => sprintf(
				__( 'Summary scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview summary</a>', 'wordpress-meetings' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ),
				strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) )
			),

			// draft updated
			10 => sprintf(
				__( 'Summary draft updated. <a target="_blank" href="%s">Preview summary</a>', 'wordpress-meetings' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			)

		);

		// --<
		return $messages;

	}



	/**
	 * Hide any unwanted metaboxes.
	 *
	 * @since 2.0
	 *
	 * @link https://codex.wordpress.org/Function_Reference/remove_meta_box
	 */
	public function metaboxes_remove() {

		// remove custom fields metabox
		remove_meta_box( 'postcustom', $this->post_type_name, 'side' );

	}



	/**
	 * Move Admin Menus.
	 *
	 * Display admin as submenu under Meetings.
	 *
	 * @since 2.0
	 */
	public function add_to_menu() {

		add_submenu_page(
			'edit.php?post_type=meeting',
			__( 'All Summaries', 'wordpress-meetings' ),
			__( 'All Summaries', 'wordpress-meetings' ),
			'edit_meetings',
			'edit.php?post_type=summary'
		);

		add_submenu_page(
			'edit.php?post_type=meeting',
			__( 'New Summary', 'wordpress-meetings' ),
			__( 'New Summary', 'wordpress-meetings' ),
			'edit_meetings',
			'post-new.php?post_type=summary'
		);

	}



	/**
	 * Filter the title.
	 *
	 * @since 2.0
	 *
	 * @param str $title The existing title.
	 * @param int $id The numeric ID of the WordPress post.
	 * @return str $title The modifed title.
	 */
    public function title_filter( $title, $id = null ) {

		// bail when not required
		if ( is_admin() || ! in_the_loop() || ! is_main_query() ) {
			return $title;
		}

		// bail if not one of our CPT pages
		if ( ! is_singular( $this->post_type_name ) AND ! is_post_type_archive( $this->post_type_name ) ) {
			return $title;
		}

		// use common function
		$title = wordpress_meetings_cpt_title( 'meeting_to_summary' );

		// --<
		return $title;

	}



} // class ends



