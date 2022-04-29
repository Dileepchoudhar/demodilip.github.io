<?php
/**
 * The Bulk Content settings.
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
class Contiom_Settings_Bulk_Content {

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
		
		wp_enqueue_script( $this->plugin_name.'-datatables', CONTIOM_URL . '/admin/js/datatables.min.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_style( $this->plugin_name.'-datatables', CONTIOM_URL . '/admin/css/datatables.min.css' );
		
		wp_enqueue_script( $this->plugin_name.'-contiom-bulk-content', CONTIOM_URL . '/admin/js/contiom-bulk-content.js', array( 'jquery', 'wp-lists', 'tags-box' ), $this->version, false );
		
		$data = array(
			'data_table_length' => get_option('contiom_responses_per_query', 10),
		);
		wp_localize_script( $this->plugin_name.'-contiom-bulk-content', "contiom_bulk_content_params",  $data );
		
		
		

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
			<h1><?php echo esc_html__( 'Bulk Content', 'contiom' ); ?></h1>
			
			<?php if ( '' != $this->message ) { ?>
			<div class="notice <?php echo ( $this->error ) ? 'error' : 'updated'; ?>">
				<p><?php echo esc_html( $this->message ); ?></p>
			</div>
			<?php } ?>
			<form method="post" id="bulk-content-form">
            
            
            
			<div class="bulk_content_settings_form">
				<div class="bulk_content_settings_form_left">
                <div class="contiom-post-settings-loader">
                    <div class="loadersmall"></div>
                </div>
                	<div class="bulk_content_settings_form_left_body">
                    	
                    </div>
                    <div class="bulk_content_settings_form_left_footer">
                    	<a href="javascript:void(0)" id="contiom-bulk-content-add-to-draft" class=" button button-primary button-large">Add to draft</a>
                    </div>
                </div>
                
                <div class="bulk_content_settings_form_right">
                <?php
					$this->contiom_meta_box();
				?>
                </div>
                
			</div>
            <br class="clear">        
			</form>
		</div>
		<?php
	}
	
	public function contiom_meta_box(){
		
		$api     = new Contiom_Api();
		$projects= $api->get_projects();
				
		?>
        <div class="contiom-post-settings">
        	<div class="contiom-post-settings-head">
            	<ul>
                	<li class="active"><a href="javascript:void(0)" data-href="#contiom-post-settings-tab1" >Contiom WP</a></li>
                    <li><a href="javascript:void(0)" data-href="#contiom-post-settings-tab2">Advance Mode</a></li>
                </ul>
            </div>
            
            
            <div class="contiom-post-settings-loader">
                <div class="loadersmall"></div>
            </div>
            <div class="contiom-post-settings-body">
            	
            	<div class="contiom-post-settings-body-content active" id="contiom-post-settings-tab1">
                	<h3>Connect with data</h3>
                    
                    <div class="contiom-data-row contiom-single-project">
                    	<label>Project</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-project">
                            	<option value="" >Select Project</option>
                                <?php
								if(is_array($projects)){
									foreach($projects as $project){
										if(false == $project->advancedMode){
										?>
                                        <option value="<?php echo $project->id;?>"><?php echo $project->name;?></option>
                                        <?php
										}
									}
								}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-language">
                    	<label>Language</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-language">
                            	<option value="" >Select Language</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-template">
                    	<label>Template</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-template">
                            	<option value="" >Select Template</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-story">
                    	<label>Story</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-story">
                            	<option value="" >Select Story</option>
                            </select>
                        </div>
                    </div>
                    
                    
                    <h3>Table Columns</h3>
                    <div class="contiom-data-row  contiom-single-columns">
                    <div class="contiom-data-row-field full">
                        <select multiple="multiple" class="contiom-select2-columns" name="contiom-single-columns[]">
                            <option value="" >Select Column</option>
                        </select>
                    </div>
                </div>
                    
                    
                    <h3 class="contion-row-identify">Data row identify</h3>
                    
                    <?php
					$columns = array();
					$this->add_filter_by_column_field($columns, '', '', '');			
					?>
                    
                    <div class="contiom-data-row contiom-single-filter-add-more">
                    <a href="javascript:void(0)"> <span class="dashicons dashicons-insert"></span> </a>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-search-by-value">
                    	<a href="javascript:void(0)" id="contiom-get-bulk-content" class="button button-primary button-large">Get Content</a>
                        
                    </div>
                    
                    <div class="contiom-data-row">
                    	<div class="row-divider"> </div>
                    </div>
                    
                    
                    <h3>Auto Update Settings</h3>

                    <div class="contiom-data-row contiom-single-auto-update-settings">
                        <div class="contiom-data-row-field">
                        	<label class="contiom-admin-switch"><input type="checkbox" name="contiom-single-auto-update" class="contiom-single-auto-update" ><span class="contiom-admin-slider contiom-admin-round"><span class="on"><?php echo esc_html__( 'Enable', 'contiom' ); ?></span><span class="off"><?php echo esc_html__( 'Disable', 'contiom' ); ?></span></span></label>
                        </div>
                    </div>
                    
                    
                    
                    <div class="contiom-data-row contiom-single-auto-update-settings-type hide">
                    	<div class="contiom-data-row-field full">
                        	<select name="contiom-single-auto-update-settings-type">
                            	<option value="daily">Daily</option>
                                <option selected="selected" value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-auto-update-settings-date-time hide">
                    	<div class="contiom-data-row-field half contiom-data-row-field-date">
                        	<select name="contiom-single-auto-update-settings-date">
                            	<?php
								foreach(array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') as $day){
								?>
								<option value="<?php echo strtolower($day);?>"><?php echo $day;?></option>
								<?php	
								}								
								?>
                            	
                            </select>
                        </div>
                        <div class="contiom-data-row-field half contiom-data-row-field-time">
                        	<input type="time" name="contiom-single-auto-update-settings-time" value="" />
                        </div>
                    </div>
                    
                    
                    
                                  
                </div>
                
                <div class="contiom-post-settings-body-content" id="contiom-post-settings-tab2">
                	<h3>Connect with data</h3>
                    <div class="contiom-data-row contiom-single-advance-project">
                    	<label>Project</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-advance-project">
                            	<option value="" >Select Project</option>
                                <?php
								if(is_array($projects)){
									foreach($projects as $project){
										if(true == $project->advancedMode){
										?>
                                        <option  <?php echo ($contiom_advance_project == $project->id)?"selected=\"selected\"":"";?> value="<?php echo $project->id;?>"><?php echo $project->name;?></option>
                                        <?php
										}
									}
								}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-advance-language">
                    	<label>Language</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-advance-language">
                            	<option value="" >Select Language</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-advance-story">
                    	<label>Story</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-advance-story">
                            	<option value="" >Select Story</option>
                            </select>
                        </div>
                    </div>
                    
                    <h3 class="contion-advance-row-identify">Data row identify</h3>
                    
                    <?php
					$columns = array();
					$this->add_filter_by_column_field($columns, 'advanced', '', '');			
					?>
                    
                    
                    
                    
                    
                    
                    <div class="contiom-data-row contiom-single-advance-filter-add-more">
                    <a href="javascript:void(0)"> <span class="dashicons dashicons-insert"></span> </a>
                    </div>
                    
                    <div class="contiom-data-row">
                    	<a href="javascript:void(0)" id="contiom-get-bulk-content-advance" class="button button-primary button-large">Get Content</a>
                          
                    </div>
                    
                    
                    
                    
                    <div class="contiom-data-row">
                    	<div class="row-divider"> </div>
                    </div>
                    
                    
                    <h3>Auto Update Settings</h3>
        
                    <div class="contiom-data-row contiom-single-advance-auto-update-settings">
                        <div class="contiom-data-row-field">
                        	<label class="contiom-admin-switch"><input type="checkbox" name="contiom-single-advance-auto-update" ><span class="contiom-admin-slider contiom-admin-round"><span class="on"><?php echo esc_html__( 'Enable', 'contiom' ); ?></span><span class="off"><?php echo esc_html__( 'Disable', 'contiom' ); ?></span></span></label>
                        </div>
                    </div>
                    
                    
                    
                    <div class="contiom-data-row contiom-single-advance-auto-update-settings-type hide">
                    	<div class="contiom-data-row-field full">
                        	<select name="contiom-single-advance-auto-update-settings-type">
                            	<option value="daily">Daily</option>
                                <option selected="selected" value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-advance-auto-update-settings-date-time hide">
                    	<div class="contiom-data-row-field half contiom-data-row-field-advance-date">
                        	<select name="contiom-single-advance-auto-update-settings-date">
                            	<?php
								foreach(array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') as $day){
								?>
								<option><?php echo $day;?></option>
								<?php	
								}
								?>
                            	
                            </select>
                        </div>
                        <div class="contiom-data-row-field half contiom-data-row-field-advance-time">
                        	<input type="time" name="contiom-single-advance-auto-update-settings-time" value="<?php echo $contiom_advance_auto_update_time;?>" />
                        </div>
                    </div>
                </div>
                
                <div class="contiom-data-row">
                    	<div class="row-divider"> </div>
                    </div>
                
                <h3>Content Type</h3>
                <div class="contiom-data-row contiom-single-content-type">
                    	
                        <div class="contiom-data-row-field">
                        	<?php
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
							?>
                            <select name="contiom-single-content-type">
                            	<option value="">Select Content Type</option>
                                <?php foreach($post_types as $type){ ?>
                                <option value="<?php echo $type->name;?>"><?php echo $type->label;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-bulk-content-taxonomy">
                    	
                    </div>
                
            </div>
        </div>	
        <?php
	}
	
	
	
	public function add_filter_by_column_field($columns, $mode, $filter_by, $filter_by_value){
		if($mode == 'advanced'){
			$_mode = "-advance";	
		}
		?>
        <div class="contiom-data-row contiom-single<?php echo $_mode;?>-filter-by">
            <label>Filter by</label>
            <div class="contiom-data-row-field">
                <select name="contiom-single<?php echo $_mode;?>-filter-by[]">
                    <option value="" >Select Column</option>
                    <?php
                    if($columns){
                        foreach($columns as $column){
                            ?>
                            <option  <?php echo ($filter_by == $column)?"selected=\"selected\"":"";?> value="<?php echo $column;?>"><?php echo $column;?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="contiom-data-row contiom-single<?php echo $_mode;?>-filter-by-value">
            <label> <a href="javascript:void(0)" class="single<?php echo $_mode;?>-filter-remove" title="Remove filter"> <span class="dashicons dashicons-remove"></span> </a> </label>
            <div class="contiom-data-row-field">
                <input type="text" name="contiom-single<?php echo $_mode;?>-filter-by-value[]" value="<?php echo $filter_by_value;?>"  />
            </div>
        </div>
        <?php		
	}

}
