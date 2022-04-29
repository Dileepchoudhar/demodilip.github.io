<?php
/**
 * The processes
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
class Contiom_Settings_Processes {

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
		
		require_once(CONTIOM_DIR.'/includes/logs/class-contiom-background-log.php');
		require_once(CONTIOM_DIR.'/includes/logs/class-contiom-active-log.php');
		
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
			check_admin_referer( 'contiom-bulk-content-settings' );


		}
	}


	/**
	 * Display form fields.
	 *
	 * @since    1.0.0
	 */
	public function display_form_fields() {
		?>
		<div class="wrap contiom">
			<h1><?php echo esc_html__( 'Processes', 'contiom' ); ?></h1>
			
			<?php if ( '' != $this->message ) { ?>
			<div class="notice <?php echo ( $this->error ) ? 'error' : 'updated'; ?>">
				<p><?php echo esc_html( $this->message ); ?></p>
			</div>
			<?php } ?>
			<?php
			$type = (isset($_GET['log-type']))?"active":"background";
			
			?>
            <ul class="contiom-admin-tabs-nav contiom-admin-logs-nav ">
            	<li class="<?php echo ('background' == $type)?"active":"";?>"><a href="<?php echo admin_url('admin.php?page=contiom-processes');?>">Background</a></li>
                <li class="<?php echo ('active' == $type)?"active":"";?>"><a href="<?php echo admin_url('admin.php?page=contiom-processes&log-type=active');?>">active log</a></li>
            </ul>
            <?php
			
			if('background' == $type){
				$background_log = new Contiom_Background_Log();
				$background_log->prepare_items();
				$background_log->display();
			}else{
				$active_log = new Contiom_Active_Log();
				$active_log->prepare_items();
				$active_log->display();
			}
            ?>
		</div>
		<?php
	}
	
}
