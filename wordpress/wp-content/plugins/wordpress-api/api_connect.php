<?php
/**
 * Custom Post Type UI.
 *
 * For all your post type and taxonomy needs.
 *
 * @package CPTUI
 * @subpackage Loader
 * @author WebDevStudios
 * @since 0.1.0.0
 * @license GPL-2.0+
 */

/**
 * Plugin Name: wordpress api
 * Plugin URI: https://github.com/WebDevStudios/custom-post-type-ui/
 * Description: Admin panel for creating custom post types and custom taxonomies in WordPress
 * Author: dileep
 * Version: 1.10.1
 * Author URI: https://webdevstudios.com/
 * Text Domain: wordpress-api-plugin
 * Domain Path: /languages
 * License: GPL-2.0+
 */

// phpcs:disable WebDevStudios.All.RequireAuthor

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//define( 'CPT_VERSION', '1.10.1' ); // Left for legacy purposes.
//define( 'CPTUI_VERSION', '1.10.1' );
//define( 'CPTUI_WP_VERSION', get_bloginfo( 'version' ) );

/**
 * Load our Admin UI class that powers our form inputs.
 *
 * @since 1.0.0
 *
 * @internal
 */

add_action('rest_api_init', function () {
	register_rest_route( 'mytwentynineteentheme/v1', 'latest-posts/(?P<category_id>\d+)',array(
				  'methods'  => 'GET',
				  'callback' => 'get_latest_posts_by_category'
		));
  });
  
  /**
 * Proper way to enqueue scripts and styles.
 */
