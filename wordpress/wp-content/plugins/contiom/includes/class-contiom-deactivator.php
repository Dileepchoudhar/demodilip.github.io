<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://apptech.com.tr
 * @since      1.0.0
 *
 * @package    Contiom
 * @subpackage Contiom/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Contiom
 * @subpackage Contiom/includes
 * @author     AppTech <dev@procomsoftsol.com>
 */
class Contiom_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$timestamp = wp_next_scheduled( 'contiom_five_sec' );
		wp_unschedule_event( $timestamp, 'contiom_five_sec' );
	}

}
