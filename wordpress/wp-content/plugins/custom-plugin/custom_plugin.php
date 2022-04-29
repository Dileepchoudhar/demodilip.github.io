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
 * Plugin Name: Custom menu plugin
 * Plugin URI: https://github.com/WebDevStudios/custom-post-type-ui/
 * Description: Admin panel for creating custom post types and custom taxonomies in WordPress
 * Author: dileep
 * Version: 1.10.1
 * Author URI: https://webdevstudios.com/
 * Text Domain: custom-menu-plugin
 * Domain Path: /languages
 * License: GPL-2.0+
 */

// phpcs:disable WebDevStudios.All.RequireAuthor

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CPT_VERSION', '1.10.1' ); // Left for legacy purposes.
define( 'CPTUI_VERSION', '1.10.1' );
define( 'CPTUI_WP_VERSION', get_bloginfo( 'version' ) );

/**
 * Load our Admin UI class that powers our form inputs.
 *
 * @since 1.0.0
 *
 * @internal
 */
function custom_add_menu_page(){
    add_menu_page( 
        __( 'testing', 'textdomain' ),
        'testing',
        'manage_options',
        'testing',
        'testing_callback',
        'dashicons-menu',
        6
    ); 

    add_submenu_page(
        'testing',
        'My Custom Submenu Page',
        'My Custom Submenu Page',
        'manage_options',
        'my-custom-submenu-page',
        ''
    );

}
add_action( 'admin_menu', 'custom_add_menu_page' );

//function testing_callback(){
  //  echo 'this is admin sub page';
//}

function testing_callback(){
	include 'data_display/display_form_data.php';
		} 
        /* datatables css and js */
        
        function add_datatables_scripts() {
            wp_register_script('datatables', 'https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js', array('jquery'), true);
            wp_enqueue_script('datatables');
          
            wp_register_script('datatables_bootstrap', 'https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js', array('jquery'), true);
            wp_enqueue_script('datatables_bootstrap');
            wp_register_style('bootstrap_style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
            wp_enqueue_style('bootstrap_style');
           wp_register_style('datatables_style', 'https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css');
        wp_enqueue_style('datatables_style');
        wp_localize_script( 'movie_datatables', 'ajax_url', admin_url('admin-ajax.php?action=movie_datatables'));

          }
           
      /*   function add_datatables_style() {
           wp_register_style('bootstrap_style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
            wp_enqueue_style('bootstrap_style');
           wp_register_style('datatables_style', 'https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css');
        wp_enqueue_style('datatables_style');
        wp_localize_script( 'movie_datatables', 'ajax_url', admin_url('admin-ajax.php?action=movie_datatables'));

         }*/
           
         // add_action('wp_enqueue_scripts', 'add_datatables_scripts');
          //add_action('wp_enqueue_scripts', 'add_datatables_style');
         // function movie_datatables_scripts() {
         //   wp_enqueue_script( 'movie_datatables', get_stylesheet_directory_uri(). '/js/contact_form_table.js', array(), '1.0', true );
         //   wp_localize_script( 'movie_datatables', 'ajax_url', admin_url('admin-ajax.php?action=movie_datatables'));
         // }
          add_action('admin_enqueue_scripts','add_datatables_scripts');
          //add_action('admin_head','add_datatables_scripts');
         //add_action('admin_head','add_datatables_style');
    
    

