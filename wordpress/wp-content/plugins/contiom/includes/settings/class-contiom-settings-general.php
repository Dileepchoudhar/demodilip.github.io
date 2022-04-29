<?php
/**
 * The General settings.
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
class Contiom_Settings_General {

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
			check_admin_referer( 'contiom-general-settings' );
			if ( isset( $_POST['using_bulk_content'] ) ) {
				update_option( 'contiom_using_bulk_content', 'on' );
			} else {
				update_option( 'contiom_using_bulk_content', 'off' );
			}

			if ( isset( $_POST['responses_per_query'] ) ) {
				$responses_per_query = sanitize_text_field( wp_unslash( $_POST['responses_per_query'] ) );
				update_option( 'contiom_responses_per_query', $responses_per_query );
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
		$using_bulk_content  = get_option( 'contiom_using_bulk_content', 'off' );
		$responses_per_query = get_option( 'contiom_responses_per_query', 20 );

		?>
		<div class="wrap contiom">
			<h1><?php echo esc_html__( 'Contiom', 'contiom' ); ?></h1>
			<p><?php echo esc_html__( 'The settings on this page allow you to choose which content types able to edit and update by contiom.', 'contiom' ); ?></p>
			
			<?php if ( '' != $this->message ) { ?>
			<div class="notice <?php echo ( $this->error ) ? 'error' : 'updated'; ?>">
				<p><?php echo esc_html( $this->message ); ?></p>
			</div>
			<?php } ?>
			
			<div class="general_settings_form">
					<form action="" method="post" class="contiom_settings_form">
					<div class="contiom-admin-tabs">
						  <ul class="contiom-admin-tabs-nav"> 
							<li><a href="#contiom-general-settings-tab"><?php echo esc_html__( 'General', 'contiom' ); ?></a></li>
							<li><a href="#contiom-about-tab"><?php echo esc_html__( 'about', 'contiom' ); ?></a></li>
						</ul>
						<div class="contiom-admin-tabs-content">
							<div id="contiom-general-settings-tab" class="contiom-admin-tab-content">
								<div class="contiom-admin-accordion">
									<h3><?php echo esc_html__( 'Bulk content', 'contiom' ); ?> <span class="dashicons dashicons-arrow-up"></span> <span class="dashicons dashicons-arrow-down"></span> </h3>
									<div class="contiom-admin-accordion-content">
										<p><?php echo esc_html__( 'Settings for bulk content', 'contiom' ); ?></p>
										
										<table width="100%">
										<tr>
										<td width="50%">
										
										<p><label><?php echo esc_html__( 'Using bulk content', 'contiom' ); ?></label>
 <label class="contiom-admin-switch"><input <?php echo ( 'on' == $using_bulk_content ) ? 'checked' : ''; ?> type="checkbox" id="togBtn" name="using_bulk_content" ><span class="contiom-admin-slider contiom-admin-round"><span class="on"><?php echo esc_html__( 'On', 'contiom' ); ?></span><span class="off"><?php echo esc_html__( 'Off', 'contiom' ); ?></span></span></label></p>
										</td>
										<td>
										<p>
											<label><?php echo esc_html__( 'Responses per query', 'contiom' ); ?> </label>

											<input type="number" value="<?php echo esc_attr( $responses_per_query ); ?>" step="1" min="1" name="responses_per_query" />
										</p>
										</td>
										</tr>
										</table>
									</div>
								</div>
								
								<input type="submit" name="form_submit" class="button button-primary" value="<?php echo esc_html__( 'Save', 'contiom' ); ?>">
								
							</div>
							
							<div id="contiom-about-tab" class="contiom-admin-tab-content">
								about content here
							</div>
							
						</div>
					</div>    
							
						<?php
							wp_nonce_field( 'contiom-general-settings' );
						?>
						 
					</form>
			</div>        
			
		</div>
		<?php
	}
}
