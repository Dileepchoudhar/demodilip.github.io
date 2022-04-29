<?php

/**
 * Fired during plugin activation
 *
 * @link       https://apptech.com.tr
 * @since      1.0.0
 *
 * @package    Contiom
 * @subpackage Contiom/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Contiom
 * @subpackage Contiom/includes
 * @author     AppTech <dev@procomsoftsol.com>
 */
class Contiom_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'contiom_log';
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			update_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			name tinytext DEFAULT '' NOT NULL,
			title tinytext DEFAULT '' NOT NULL,
			post_id mediumint(9) NOT NULL,
			status varchar(50) NOT NULL,
			log_type varchar(50) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
