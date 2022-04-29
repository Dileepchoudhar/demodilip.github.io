<?php
/**
 * The Role Manager settings.
 *
 * @link       https://apptech.com.tr
 * @since      1.0.0
 *
 * @package    Contiom
 * @subpackage Contiom/includes/settings
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Contiom
 * @subpackage Contiom/includes/settings
 * @author     AppTech <dev@procomsoftsol.com>
 */
class Contiom_Settings_Role_Manager {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The error.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $error    The error.
	 */
	public $error;

	/**
	 * The message.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $message    The message.
	 */
	public $message;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->update_options();
		$this->display_form_fields();

	}

	/**
	 * Update settings.
	 *
	 * @since    1.0.0
	 */
	public function update_options() {
		if ( isset( $_POST['form_submit'] ) ) {
			check_admin_referer( 'contiom-role-manager-settings' );

			if ( isset( $_POST['role_manager_refresh_content'] ) ) {
				$role_manager_refresh_content = array_map( 'sanitize_text_field', wp_unslash( $_POST['role_manager_refresh_content'] ) );
				update_option( 'contiom_role_manager_refresh_content', $role_manager_refresh_content );
			}else{
				update_option( 'contiom_role_manager_refresh_content', array() );
			}

			if ( isset( $_POST['role_manager_settings'] ) ) {
				$role_manager_settings = array_map( 'sanitize_text_field', wp_unslash( $_POST['role_manager_settings'] ) );
				update_option( 'contiom_role_manager_settings', $role_manager_settings );
			}else{
				update_option( 'contiom_role_manager_settings', array() );	
			}

			if ( isset( $_POST['role_manager_bulk_content'] ) ) {
				$role_manager_bulk_content = array_map( 'sanitize_text_field', wp_unslash( $_POST['role_manager_bulk_content'] ) );
				update_option( 'contiom_role_manager_bulk_content', $role_manager_bulk_content );
			}else{
				update_option( 'contiom_role_manager_bulk_content', array() );	
			}

			$this->message = esc_html__( 'Settings updated', 'contiom' );
			$this->error   = false;

		}
	}


	/**
	 * Display form fields.
	 *
	 * @since    1.0.0
	 */
	public function display_form_fields() {
		$role_manager_bulk_content    = get_option( 'contiom_role_manager_bulk_content', array() );
		$role_manager_settings        = get_option( 'contiom_role_manager_settings', array() );
		$role_manager_refresh_content = get_option( 'contiom_role_manager_refresh_content', array() );

		global $wp_roles;

		?>
		<div class="wrap contiom">
			<h1><?php echo esc_html__( 'Role Manager', 'contiom' ); ?></h1>
			<p>Manage what your users can do with contiom.</p>
			
			<?php if ( '' != $this->message ) { ?>
			<div class="notice <?php echo ( $this->error ) ? 'error' : 'updated'; ?>">
				<p><?php echo esc_html( $this->message ); ?></p>
			</div>
			<?php } ?>
			
			<div class="role_manager_settings_form">
					<form action="" method="post" class="contiom_settings_form">
					
						
						
								<div class="contiom-admin-accordion">
									<?php foreach ( $wp_roles->get_names() as $role_key => $role_name ) { ?>
									<h3><?php echo sprintf( '%s (%s)', esc_html( $role_name ), esc_html( $role_key ) ); ?> <span class="dashicons dashicons-arrow-up"></span> <span class="dashicons dashicons-arrow-down"></span> </h3>
									<div class="contiom-admin-accordion-content">
										<p>
										<label><input <?php echo ( in_array( $role_key, $role_manager_settings ) || ('administrator' == $role_key) ) ? 'checked' : ''; ?> <?php echo ( 'administrator' == $role_key ) ? 'disabled="disabled"' : ''; ?> type="checkbox" name="role_manager_settings[]" value="<?php echo esc_attr( $role_key ); ?>"> <?php echo esc_html__( 'All Settings', 'contiom' ); ?></label>
										</p>
                                        <p>
										<label><input  <?php echo ( in_array( $role_key, $role_manager_bulk_content ) || ('administrator' == $role_key) ) ? 'checked' : ''; ?> <?php echo ( 'administrator' == $role_key ) ? 'disabled="disabled"' : ''; ?> type="checkbox" name="role_manager_bulk_content[]" value="<?php echo esc_attr( $role_key ); ?>"> <?php echo esc_html__( 'Bulk content', 'contiom' ); ?></label>
										</p>
										<p>
										<label><input <?php echo ( in_array( $role_key, $role_manager_refresh_content ) || ('administrator' == $role_key) ) ? 'checked' : ''; ?> <?php echo ( 'administrator' == $role_key ) ? 'disabled="disabled"' : ''; ?> type="checkbox" name="role_manager_refresh_content[]" value="<?php echo esc_attr( $role_key ); ?>"> <?php echo esc_html__( 'Refresh content', 'contiom' ); ?></label>
										</p>
										
									</div>
										<?php
									}
									?>
								</div>
								
								<input type="submit" name="form_submit" class="button button-primary" value="<?php echo esc_html__( 'Save', 'contiom' ); ?>">
								
								  
							
						<?php
							wp_nonce_field( 'contiom-role-manager-settings' );
						?>
						 
					</form>
			</div>        
			
		</div>
		<?php
	}
}
