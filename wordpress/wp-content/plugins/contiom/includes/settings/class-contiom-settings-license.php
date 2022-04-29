<?php
/**
 * The license settings.
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
class Contiom_Settings_License {

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
			check_admin_referer( 'contiom-license-settings' );
			if ( isset( $_POST['api_key'] ) ) {
				if ( ! empty( $_POST['api_key'] ) ) {
					$api_key = sanitize_text_field( wp_unslash( $_POST['api_key'] ) );
					$api     = new Contiom_Api();
					$data    = $api->verify_api_key( $api_key );
					if ( true == $data['status'] ) {
						update_option( 'contiom_api_key', $api_key );
						$this->error   = false;
						$this->message = esc_html__( 'API Key Activated', 'contiom' );
						wp_redirect(admin_url('admin.php?page=contiom-license&activated'));
						echo '<script>window.location="'.admin_url('admin.php?page=contiom-license&activated').'"</script>';
					} else {
						$this->error   = true;
						$this->message = $data['message'];
					}
				} else {
					$this->message = esc_html__( 'Please Enter API Key', 'contiom' );
					$this->error   = true;
				}
			} elseif ( isset( $_POST['license_action'] ) && 'deactivate' == $_POST['license_action'] ) {
				$this->message = esc_html__( 'API Key Deactivated', 'contiom' );
				$this->error   = false;
				delete_option( 'contiom_api_key' );
				wp_safe_redirect(admin_url('admin.php?page=contiom&deactivated'));
				echo '<script>window.location="'.admin_url('admin.php?page=contiom&deactivated').'"</script>';
				exit();
			}
		}
	}

	/**
	 * Display form fields.
	 *
	 * @since    1.0.0
	 */
	public function display_form_fields() {
		$api_key = get_option( 'contiom_api_key' );
		if(isset($_GET['deactivated']) && empty($this->message)){
			$this->message = esc_html__( 'API Key Deactivated', 'contiom' );
			$this->error   = false;
		}
		if(isset($_GET['activated']) && empty($this->message)){
			$this->error   = false;
			$this->message = esc_html__( 'API Key Activated', 'contiom' );
		}
		
		?>
		<div class="wrap contiom">
			<h1><?php echo esc_html__( 'License Settings', 'contiom' ); ?></h1>
			<p><?php echo esc_html__( 'Manage your Connect with contiom API', 'contiom' ); ?></p>
			
			<?php if ( '' != $this->message ) { ?>
			<div class="notice <?php echo ( $this->error ) ? 'error' : 'updated'; ?>">
				<p><?php echo esc_html( $this->message ); ?></p>
			</div>
			<?php } ?>
			
			<?php
			if ( ! $api_key ) {
				?>
				<div class="info notice">
					<p><?php echo sprintf( '%s <a href="%s" target="_blank">%s</a> %s', esc_html__( "If you don't have contiom  API key, visit this", 'contiom' ), 'https://apptech.com.tr/', esc_html__( 'URL', 'contiom' ), esc_html__( 'to create one!', 'contiom' ) ); ?></p>
				</div> 
				<?php
			}
			?>
			
			<div class="license_form">
					<form action="" method="post" class="contiom_settings_form">
					<div class="contiom-admin-accordion">
						<?php
						if ( $api_key ) {
							?>
							<h3> <?php echo sprintf( '%s : <span class="%s">%s</span>', esc_html__( 'Status', 'contiom' ), 'active', esc_html__( 'Active', 'contiom' ) ); ?> </h3>
							<div class="contiom-admin-accordion-content">
							<p>
								<table width="100%">
								<tr>
								<td width="70%">
								<label><?php echo esc_html__( 'Want to deactivate for any reason?', 'contiom' ); ?></label>
								</td>
								<td width="30%">
								<input type="submit" name="form_submit" value="<?php echo esc_html__( 'Deactivate', 'contiom' ); ?>" class="button" />
								</td>
								</tr>
								</table>
								<input type="hidden" name="license_action" value="deactivate" />
							</p>
							</div>
							<?php
						} else {
							?>
							<h3><?php echo esc_html__( 'Activate License', 'contiom' ); ?></h3>
							<div class="contiom-admin-accordion-content">
							<p>
								<input type="text" size="50" name="api_key" placeholder="<?php echo esc_html__( 'contiom API key', 'contiom' ); ?>">
								<input type="submit" name="form_submit" class="button button-primary" value="<?php echo esc_html__( 'Activate', 'contiom' ); ?>">
							</p>
							</div>
							<?php
						}
						?>
						 
						<?php
							wp_nonce_field( 'contiom-license-settings' );
						?>
						 
				   </div>     
					</form>
			</div>        
			
		</div>
		<?php
	}
}
