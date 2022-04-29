<?php
/**
 * The Content Types settings.
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
class Contiom_Settings_Content_Types {

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
			check_admin_referer( 'contiom-content-types-settings' );

			if ( isset( $_POST['content_types_auto_update'] ) ) {
				$content_types_auto_update = array_map( 'sanitize_text_field', wp_unslash( $_POST['content_types_auto_update'] ) );
				update_option( 'contiom_content_types_auto_update', $content_types_auto_update );
			}else{
				update_option( 'contiom_content_types_auto_update', array() );
			}

			if ( isset( $_POST['content_types_show_settings'] ) ) {
				$content_types_show_settings = array_map( 'sanitize_text_field', wp_unslash( $_POST['content_types_show_settings'] ) );
				update_option( 'contiom_content_types_show_settings', $content_types_show_settings );
			}else{
				update_option( 'contiom_content_types_show_settings', array() );	
			}

			if ( isset( $_POST['content_types_taxonomy_show_settings'] ) ) {
				$content_types_taxonomy_show_settings = array_map( 'sanitize_text_field', wp_unslash( $_POST['content_types_taxonomy_show_settings'] ) );
				update_option( 'contiom_content_types_taxonomy_show_settings', $content_types_taxonomy_show_settings );
			}else{
				update_option( 'contiom_content_types_taxonomy_show_settings', array() );	
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
		$content_types_auto_update            = get_option( 'contiom_content_types_auto_update', array() );
		$content_types_show_settings          = get_option( 'contiom_content_types_show_settings', array() );
		$content_types_taxonomy_show_settings = get_option( 'contiom_content_types_taxonomy_show_settings', array() );

		$post_types = get_post_types(
			array(
				'public'  => true,
				'show_ui' => true,
			),
			'objects'
		);
		if ( isset( $post_types['attachment'] ) ) {
			unset( $post_types['attachment'] );
		}

		$taxonomies = get_taxonomies(
			array(
				'public'  => true,
				'show_ui' => true,
			),
			'objects'
		);

		?>
		<div class="wrap contiom">
			<h1><?php echo esc_html__( 'Content Types', 'contiom' ); ?></h1>
			<p>The settings on this page allow you to choose which content types able to edit and update by contiom.</p>
			
			<?php if ( '' != $this->message ) { ?>
			<div class="notice <?php echo ( $this->error ) ? 'error' : 'updated'; ?>">
				<p><?php echo esc_html( $this->message ); ?></p>
			</div>
			<?php } ?>
			
			<div class="content_types_settings_form">
					<form action="" method="post" class="contiom_settings_form">
					<div class="contiom-admin-tabs">
						  <ul class="contiom-admin-tabs-nav"> 
							<li><a href="#contiom-content-types-tab"><?php echo esc_html__( 'Content Types', 'contiom' ); ?></a></li>
							<li><a href="#contiom-taxonomies-tab"><?php echo esc_html__( 'Taxonomies', 'contiom' ); ?></a></li>
						</ul>
						<div class="contiom-admin-tabs-content">
							<div id="contiom-content-types-tab" class="contiom-admin-tab-content">
								<div class="contiom-admin-accordion">
									<?php foreach ( $post_types as $post ) { ?>
									<h3><?php echo sprintf( '%s (%s)', esc_html( $post->label ), esc_html( $post->name ) ); ?> <span class="dashicons dashicons-arrow-up"></span> <span class="dashicons dashicons-arrow-down"></span> </h3>
									<div class="contiom-admin-accordion-content">
										<h4><?php echo esc_html__( 'Settings for single', 'contiom' ); ?> <?php echo esc_html( $post->labels->singular_name ); ?></h4>
									   <table width="100%">
									   <tr>
									   <td width="50%">
										<p><?php echo esc_html__( 'Show settings for', 'contiom' ); ?> <?php echo esc_html( $post->label ); ?> <label class="contiom-admin-switch"><input <?php echo ( in_array( $post->name, $content_types_show_settings ) ) ? 'checked' : ''; ?> type="checkbox" name="content_types_show_settings[]" value="<?php echo esc_attr( $post->name ); ?>" ><span class="contiom-admin-slider contiom-admin-round"><span class="on"><?php echo esc_html__( 'On', 'contiom' ); ?></span><span class="off"><?php echo esc_html__( 'Off', 'contiom' ); ?></span></span></label></p>
										</td>
										<td width="50%">
										<p><?php echo esc_html__( 'Auto update', 'contiom' ); ?> <label class="contiom-admin-switch"><input <?php echo ( in_array( $post->name, $content_types_auto_update ) ) ? 'checked' : ''; ?> type="checkbox" name="content_types_auto_update[]" value="<?php echo esc_attr( $post->name ); ?>"  ><span class="contiom-admin-slider contiom-admin-round"><span class="on"><?php echo esc_html__( 'On', 'contiom' ); ?></span><span class="off"><?php echo esc_html__( 'Off', 'contiom' ); ?></span></span></label></p>
										</td>
										</tr>
										</table>
									</div>
										<?php
									}
									?>
								</div>
								
								<input type="submit" name="form_submit" class="button button-primary" value="<?php echo esc_html__( 'Save', 'contiom' ); ?>">
								
							</div>
							
							<div id="contiom-taxonomies-tab" class="contiom-admin-tab-content">
								<div class="contiom-admin-accordion">
									<?php foreach ( $taxonomies as $taxonomy ) { ?>
									<h3><?php echo sprintf( '%s (%s)', esc_html( $taxonomy->label ), esc_html( $taxonomy->name ) ); ?> <span class="dashicons dashicons-arrow-up"></span> <span class="dashicons dashicons-arrow-down"></span> </h3>
									<div class="contiom-admin-accordion-content">
										<h4><?php echo esc_html__( 'Settings for ', 'contiom' ); ?> <?php echo esc_html( $taxonomy->labels->singular_name ); ?></h4>
										
										<p><?php echo esc_html__( 'Show settings for', 'contiom' ); ?> <?php echo esc_html( $taxonomy->label ); ?> <label class="contiom-admin-switch"><input <?php echo ( in_array( $taxonomy->name, $content_types_taxonomy_show_settings ) ) ? 'checked' : ''; ?> type="checkbox" name="content_types_taxonomy_show_settings[]" value="<?php echo esc_attr( $taxonomy->name ); ?>" ><span class="contiom-admin-slider contiom-admin-round"><span class="on"><?php echo esc_html__( 'On', 'contiom' ); ?></span><span class="off"><?php echo esc_html__( 'Off', 'contiom' ); ?></span></span></label></p>
										
										
									</div>
										<?php
									}
									?>
								</div>
								
								<input type="submit" name="form_submit" class="button button-primary" value="<?php echo esc_html__( 'Save', 'contiom' ); ?>">
							</div>
							
						</div>
					</div>    
							
						<?php
							wp_nonce_field( 'contiom-content-types-settings' );
						?>
						 
					</form>
			</div>        
			
		</div>
		<?php
	}
}
