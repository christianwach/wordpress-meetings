<?php

/**
 * WordPress Meetings Summaries Post Type
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   WordPress_Meetings
 */



/**
 * Add Custom Post Type.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wordpress_summary_post_type' ) ) {

	// Register Custom Post Type
	function wordpress_summary_post_type() {

		$slug = apply_filters( 'wordpress_summary_post_type', 'summary' );

		$labels = array(
			'name'                => _x( 'Summaries', 'Post Type General Name', 'wordpress-meetings' ),
			'singular_name'       => _x( 'Summary', 'Post Type Singular Name', 'wordpress-meetings' ),
			'menu_name'           => __( 'Summaries', 'wordpress-meetings' ),
			'name_admin_bar'      => __( 'Summaries', 'wordpress-meetings' ),
			'parent_item_colon'   => __( 'Parent Summary:', 'wordpress-meetings' ),
			'all_items'           => __( 'All Summaries', 'wordpress-meetings' ),
			'add_new_item'        => __( 'Add New Summary', 'wordpress-meetings' ),
			'add_new'             => __( 'Add New Summary', 'wordpress-meetings' ),
			'new_item'            => __( 'New Summary', 'wordpress-meetings' ),
			'edit_item'           => __( 'Edit Summary', 'wordpress-meetings' ),
			'update_item'         => __( 'Update Summary', 'wordpress-meetings' ),
			'view_item'           => __( 'View Summary', 'wordpress-meetings' ),
			'search_items'        => __( 'Search Summary', 'wordpress-meetings' ),
			'not_found'           => __( 'Not found', 'wordpress-meetings' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'wordpress-meetings' ),
		);

		$capabilities = wordpress_meetings_capabilities();

		$rewrite = array(
			'slug'                => $slug,
			'with_front'          => false,
			'pages'               => true,
			'feeds'               => true,
		);

		$default_config = array(
			'label'               => __( 'Summary', 'wordpress-meetings' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'wpcom-markdown', 'revisions' ),
			'taxonomies'          => array(
				'organization',
				'meeting_tag',
			),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 30,
			'menu_icon'			  => 'dashicons-list-view',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => 'summaries',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'query_var'           => $slug,
			'rewrite'             => $rewrite,
			'show_in_rest'        => true,
	  		'rest_base'           => $slug,
	  		'rest_controller_class' => 'WP_REST_Posts_Controller',
			'capability_type'	  => 'meeting',
			'capabilities'		  => apply_filters( 'meetings_summary_capabilities', $capabilities ),
			'map_meta_cap' 		  => true
		);
		// Allow customization of the default post type configuration via filter.
        $config = apply_filters( 'summary_post_type_defaults', $default_config, $slug );

        register_post_type( $slug, $config );

	}
	add_action( 'init', 'wordpress_summary_post_type', 0 );

}



/**
 * Move Admin Menus.
 *
 * Display admin as submenu under Meetings.
 *
 * @uses `add_submenu_page` with $cap set to `edit_summaries`
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wordpress_summary_add_to_menu' ) ) {

    function wordpress_summary_add_to_menu() {

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

    add_action( 'admin_menu', 'wordpress_summary_add_to_menu' );

}


