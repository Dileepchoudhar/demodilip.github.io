<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://apptech.com.tr
 * @since      1.0.0
 *
 * @package    Contiom
 * @subpackage Contiom/admin   
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Contiom
 * @subpackage Contiom/admin
 * @author     AppTech <dev@procomsoftsol.com>
 */
class Contiom_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		
		$api_key = get_option( 'contiom_api_key' );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 9 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 9999 );
		
		add_action( 'in_admin_header', array($this, 'reorder_meta_box'), 10 );
				
		
		if($api_key){
			
			add_action( 'add_meta_boxes', array($this, 'register_meta_boxes') );
			add_action( 'save_post', array($this, 'save_meta_box' ) );
			
			add_action('wp_ajax_contiom-load-languages', array($this, 'contiom_load_languages'));
			add_action('wp_ajax_contiom-load-templates', array($this, 'contiom_load_templates'));
			add_action('wp_ajax_contiom-load-stories', array($this, 'contiom_load_stories'));
			add_action('wp_ajax_contiom-load-template-columns', array($this, 'contiom_load_template_columns'));
			add_action('wp_ajax_contiom-load-template-column-values', array($this, 'contiom_load_template_column_values'));
			
			add_action('wp_ajax_contiom-load-advance-languages', array($this, 'contiom_load_advance_languages'));
			add_action('wp_ajax_contiom-load-advance-stories', array($this, 'contiom_load_advance_stories'));
			add_action('wp_ajax_contiom-load-advance-columns', array($this, 'contiom_load_advance_columns'));
			
			
			add_action('wp_ajax_contiom-post-get-content', array($this, 'contiom_post_get_content'));
			add_action('wp_ajax_contiom-post-refresh-content', array($this, 'contiom_post_refresh_content'));
			
			add_action('wp_ajax_contiom-post-get-advanced-content', array($this, 'contiom_post_get_advanced_content'));
			add_action('wp_ajax_contiom-post-refresh-advance-content', array($this, 'contiom_post_refresh_advanced_content'));
			
			add_action( 'init', array($this, 'register_blocks') );
			
			add_action('wp_ajax_load_bulk_content_taxonomies', array($this, 'load_bulk_content_taxonomies'));
			add_action('wp_ajax_contiom-get-bulk-content', array($this, 'contiom_get_bulk_content'));
			
			add_action('wp_ajax_contiom-get-bulk-content-advance', array($this, 'contiom_get_bulk_content_advance'));
			
			add_action('wp_ajax_contiom-bulk-content-add-articles', array($this, 'contiom_bulk_content_add_articles'));
			add_action('wp_ajax_contiom-bulk-content-add-advance-articles', array($this, 'contiom_bulk_content_add_advance_articles'));
			
			add_action('contiom_cron_update_posts', array($this, 'cron_update_posts'));
			
			
		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/contiom-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'select2/css/select2.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_script( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'js/contiom-admin.js', array( 'jquery' ), $this->version, false );
		
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'select2/js/select2.min.js', array( 'jquery' ), $this->version, false );
		$data = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'default_options' => array(
				'project' => esc_html__('Select project', 'contiom'),
				'language' => esc_html__('Select language', 'contiom'),
				'template' => esc_html__('Select template', 'contiom'),
				'story' => esc_html__('Select story', 'contiom'),
				'filter-by' => esc_html__('Select column', 'contiom'),
				'search-by' => esc_html__('Select column', 'contiom'),
				'article' => esc_html__('Select article', 'contiom'),
			)
		);
		wp_localize_script( $this->plugin_name, $this->plugin_name."_params",  $data );

	}

	/**
	 * Add admin menus.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu() {
		
		if($this->is_user_can_access_settings()){
			add_menu_page( esc_html__( 'Contiom', 'contiom' ), esc_html__( 'Contiom', 'contiom' ), 'edit_posts', $this->plugin_name, array( $this, 'display_admin_dashboard' ), 'dashicons-admin-settings' );
			
			$api_key = get_option( 'contiom_api_key' );
			
			if($api_key){
				add_submenu_page( $this->plugin_name, esc_html__( 'Contiom Settings', 'contiom' ), esc_html__( 'Settings', 'contiom' ), 'edit_posts', $this->plugin_name );
		
				add_submenu_page( $this->plugin_name, esc_html__( 'Content Types', 'contiom' ), esc_html__( 'Content Types', 'contiom' ), 'edit_posts', $this->plugin_name . '-content-types', array( $this, 'display_plugin_content_types_settings' ) );
		
				add_submenu_page( $this->plugin_name, esc_html__( 'Role Manager', 'contiom' ), esc_html__( 'Role Manager', 'contiom' ), 'edit_posts', $this->plugin_name . '-role-manager', array( $this, 'display_plugin_role_manager_settings' ) );
				$using_bulk_content = get_option( 'contiom_using_bulk_content');
				if('on' == $using_bulk_content){
					add_submenu_page( $this->plugin_name, esc_html__( 'Bulk content', 'contiom' ), esc_html__( 'Bulk content', 'contiom' ), 'edit_posts', $this->plugin_name . '-bulk-content', array( $this, 'display_plugin_bulk_content_settings' ) );
				}
				
				add_submenu_page( $this->plugin_name, esc_html__( 'Processes', 'contiom' ), esc_html__( 'Processes', 'contiom' ), 'edit_posts', $this->plugin_name . '-processes', array( $this, 'display_plugin_processes_settings' ) );
				
				add_submenu_page( $this->plugin_name, esc_html__( 'License', 'contiom' ), esc_html__( 'License', 'contiom' ), 'edit_posts', $this->plugin_name . '-license', array( $this, 'display_plugin_license_settings' ) );
			}
		
		}else{
			if($this->is_user_can_access_bulk_content()){
				$api_key = get_option( 'contiom_api_key' );
				if($api_key){
					$using_bulk_content = get_option( 'contiom_using_bulk_content');
					if('on' == $using_bulk_content){
						add_menu_page( esc_html__( 'Contiom Bulk content', 'contiom' ), esc_html__( 'Contiom Bulk content', 'contiom' ), 'edit_posts', $this->plugin_name . '-bulk-content', array( $this, 'display_plugin_bulk_content_settings' ) );						
					}	
				}
			}
		}

		

	}

	/**
	 * General settings display.
	 *
	 * @since    1.0.0
	 */
	public function display_admin_dashboard() {
		$api_key = get_option( 'contiom_api_key' );
		
		if($api_key){
			require_once CONTIOM_DIR . '/includes/settings/class-contiom-settings-general.php';
			new Contiom_Settings_General( $this->plugin_name, $this->version );
		}else{
			require_once CONTIOM_DIR . '/includes/settings/class-contiom-settings-license.php';
			new Contiom_Settings_License( $this->plugin_name, $this->version );	
		}
	}
	
	/**
	 * Process settings display.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_processes_settings(){
		require_once CONTIOM_DIR . '/includes/settings/class-contiom-settings-processes.php';
		new Contiom_Settings_Processes( $this->plugin_name, $this->version );
	}

	/**
	 * License settings display.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_license_settings() {
		require_once CONTIOM_DIR . '/includes/settings/class-contiom-settings-license.php';
		new Contiom_Settings_License( $this->plugin_name, $this->version );
	}

	/**
	 * Content types settings display.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_content_types_settings() {
		require_once CONTIOM_DIR . '/includes/settings/class-contiom-settings-content-types.php';
		new Contiom_Settings_Content_Types( $this->plugin_name, $this->version );
	}

	/**
	 * Role manager settings display.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_role_manager_settings() {
		require_once CONTIOM_DIR . '/includes/settings/class-contiom-settings-role-manager.php';
		new Contiom_Settings_Role_Manager( $this->plugin_name, $this->version );
	}
	
	/**
	 * Bulk content settings display.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_bulk_content_settings(){
		require_once CONTIOM_DIR . '/includes/settings/class-contiom-settings-bulk-content.php';
		new Contiom_Settings_Bulk_Content( $this->plugin_name, $this->version );
	}

	/**
	 * Add body classes
	 *
	 * @since    1.0.0
	 * @param      string $classes       Body classes.
	 * @return      string $classes       Body classes.
	 */
	public function admin_body_class( $classes ) {
		$screen = get_current_screen();
		if ( false !== strpos( $screen->base, 'contiom' ) ) {
			$classes .= 'contiom-admin-page';
		}
		return $classes;
	}
	
	public function reorder_meta_box(){
		global $pagenow;
		if($pagenow == 'post.php' || $pagenow == 'post-new.php'){
			global $post;
			$post_type = get_post_type($post);
			$user_id = get_current_user_id();
			$sorted = get_user_meta( $user_id, "meta-box-order_{$post_type}", true );
			if($sorted){
				$side = $sorted['side'];
				$normal = $sorted['normal'];
				if('' != $normal){
					$normal = explode(',', $normal);
					$normal = array_filter($normal);
					if(in_array('contiom-box', $normal)){
						$key = array_search('contiom-box', $normal);
						unset($normal[$key]);
						$side = explode(',', $side);
						$side = array_filter($side);
						$side[]='contiom-box';
						
						$normal = implode(',', $normal);
						$side = implode(',', $side);
					}
				}
				$sorted['normal'] = $normal;
				$sorted['side'] = $side;
				update_user_meta( $user_id, "meta-box-order_{$post_type}", $sorted );
			}
		}
	}
	
	public function contiom_load_languages(){
		$project = $_POST['project'];
		$api     = new Contiom_Api();
		$languages = 	$api->get_languages($project);
		$data = array(array('label' => esc_html__('Select language', 'contiom'), 'value' => ''));
		if(is_array($languages)){
			foreach($languages as $language){
				$data[] = array('label' => $language->displayName, 'value' => $language->name);	
			}
		}
		wp_send_json_success($data);
	}
	
	public function contiom_load_advance_languages(){
		$project = $_POST['project'];
		$api     = new Contiom_Api();
		$languages = 	$api->get_advance_languages($project);
		$data = array(array('label' => esc_html__('Select language', 'contiom'), 'value' => ''));
		if(is_array($languages)){
			foreach($languages as $language){
				$data[] = array('label' => $language->displayName, 'value' => $language->name);	
			}
		}
		wp_send_json_success($data);
	}
	
	public function contiom_load_templates(){
		$project = $_POST['project'];
		$language = $_POST['language'];
		$api     = new Contiom_Api();
		$templates = 	$api->get_templates($project, $language);
		$data = array(array('label' => esc_html__('Select template', 'contiom'), 'value' => ''));
		if(is_array($templates)){
			foreach($templates as $template){
				$data[] = array('label' => $template->name, 'value' => $template->id);	
			}
		}
		wp_send_json_success($data);
	}
	
	public function contiom_load_stories(){
		$project = $_POST['project'];
		$language = $_POST['language'];
		$template = $_POST['template'];
		$api     = new Contiom_Api();
		$stories = 	$api->get_stories($project, $language, $template);
		$data = array(array('label' => esc_html__('Select story', 'contiom'), 'value' => ''));
		if(is_array($stories)){
			foreach($stories as $story){
				$data[] = array('label' => $story->item2, 'value' => $story->item1);	
			}
		}
		wp_send_json_success($data);
	}
	
	public function contiom_load_advance_stories(){
		$project = $_POST['project'];
		$language = $_POST['language'];
		$api     = new Contiom_Api();
		$stories = 	$api->get_advance_stories($project, $language, $template);
		$data = array(array('label' => esc_html__('Select story', 'contiom'), 'value' => ''));
		if(is_array($stories)){
			foreach($stories as $story){
				$data[] = array('label' => $story->item2, 'value' => $story->item1);	
			}
		}
		wp_send_json_success($data);
	}
	
	public function contiom_load_template_columns(){
		$project = $_POST['project'];
		$language = $_POST['language'];
		$template = $_POST['template'];
		$api     = new Contiom_Api();
		$columns = 	$api->get_template_columns($project, $language, $template);
		$data = array(array('label' => esc_html__('Select column', 'contiom'), 'value' => ''));
		if($columns){
			foreach($columns->columns as $column){
				$data[] = array('label' => $column->name, 'value' => $column->id);	
			}
		}
		wp_send_json_success($data);
	}
	
	public function contiom_load_advance_columns(){
		$project = $_POST['project'];
		$language = $_POST['language'];
		$story = $_POST['story'];
		$api     = new Contiom_Api();
		$columns = 	$api->get_advance_columns($project, $language, $story);
		$data = array(array('label' => esc_html__('Select column', 'contiom'), 'value' => ''));
		if($columns){
			foreach($columns as $column){
				$data[] = array('label' => $column, 'value' => $column);	
			}
		}
		wp_send_json_success($data);
	}
	
	public function contiom_load_template_column_values(){
		$project = $_POST['project'];
		$language = $_POST['language'];
		$template = $_POST['template'];
		$story = $_POST['story'];
		$column = $_POST['column'];
		$api     = new Contiom_Api();
		$columns = 	$api->get_template_column_values($project, $language, $template, $story, $column);
		$data = array(array('label' => esc_html__('Select column value', 'contiom'), 'value' => ''));
		if($columns){
			foreach($columns->columns as $column){
				$data[] = array('label' => $column->name, 'value' => $column->id);	
			}
		}
		wp_send_json_success($data);
	}
	
	public function contiom_post_refresh_advanced_content(){
		$post_id = $_REQUEST['post_id'];
		
		$status = $this->update_contiom_post_content($post_id);
		

		if(false == $status){
			$this->record_log('', '', $post_id, 'faild', 'refresh_content');
			wp_send_json_success(array('type' => 'faild'));		
		}
		
		$this->record_log('', '', $post_id, 'success', 'refresh_content');
		
		$_data = array('type' => 'article');
		$url = get_edit_post_link($post_id, 'edit');
		$_data['url'] = add_query_arg(array('message' => 1), $url);
		wp_send_json_success($_data);	
	}
	
	public function contiom_post_get_advanced_content(){
		$post = $_POST;
		if(isset($post['filter-by'])){
			parse_str($post['filter-by'], $filter_by);	
			$post['filter-by'] = $filter_by['contiom-single-advance-filter-by'];
		}
		if(isset($post['filter-by-value'])){
			parse_str($post['filter-by-value'], $filter_by_value);	
			$post['filter-by-value'] = $filter_by_value['contiom-single-advance-filter-by-value'];
		}
		
		$data = array();
		$data['advance_project'] = $post['project'];
		$data['advance_language'] = $post['language'];
		$data['advance_story'] = $post['story'];
		$data['advance_filter_by'] = $post['filter-by'];
		$data['advance_filter_by_value'] = $post['filter-by-value'];
		$api     = new Contiom_Api();
		$advanced_records = $api->get_advanced_records($data);
		if($advanced_records){
			
			
			
			$found_record = array();
			foreach($advanced_records as $record){
				if($record->resultValues && !empty($record->resultValues) && $record->exportedJson && !empty($record->exportedJson) && empty($found_record)){
					$found_record = $record->resultValues;
				}
			}
			
			if(!empty($found_record)){
				$post_id = $_POST['post_id'];
				$id = $found_record[0]->resultId;	
				$data['ids'] = array($id);
				$advanced_data = $api->get_advanced_text($data);
				$result_json = $advanced_data[0]->resultJson;
				
				update_post_meta($post_id, 'contiom_advance_data', $data);
				update_post_meta($post_id, 'contiom_content_mode', 'advance');
				
				update_post_meta($post_id, 'contiom_advance_article_id', $id);
				
				update_post_meta($post_id, 'contiom_article_id', $id);
				
				delete_post_meta($post_id, 'contiom_data');
				delete_post_meta($post_id, 'contiom_skip_block_updates');
				
				
				$article_html = $this->prepare_html_from_article($result_json, $post_id);
				
				/*$skip_block_updates = array();
			  	if(!empty($result_json)){
			  		foreach($result_json as $v){
						$skip_block_updates[]=array('id' => $v->id, 'name' => $v->name, 'skip_update' => 'off');
					}
			  	}
				
			  	update_post_meta($post_id, 'contiom_skip_block_updates_advance', $skip_block_updates);*/
				
				
				
				$this->record_log('', '', $post_id, 'success', 'get_content_advance');
				
				$url = get_edit_post_link($post_id, 'edit');
				$_data = array('type' => 'article');
			  	$_data['url'] = add_query_arg(array('message' => 1), $url);
				wp_send_json_success($_data);		
			}else{
				wp_send_json_success(array('type' => 'faild'));		
			}
		}
		wp_send_json_success(array('type' => 'faild'));		
	}
	
	public function contiom_get_bulk_content(){
		$post = $_POST;
		if(isset($post['filter-by'])){
			parse_str($post['filter-by'], $filter_by);	
			$post['filter-by'] = $filter_by['contiom-single-filter-by'];
		}
		if(isset($post['filter-by-value'])){
			parse_str($post['filter-by-value'], $filter_by_value);	
			$post['filter-by-value'] = $filter_by_value['contiom-single-filter-by-value'];
		}
		
		$columns = $post['columns'];
		
		$data=array();
		$data['project'] = $post['project'];
		$data['language'] = $post['language'];
		$data['template'] = $post['template'];
		$data['story'] = $post['story'];
		$data['filter_by'] = $post['filter-by'];
		$data['filter_by_value'] = $post['filter-by-value'];
		$api     = new Contiom_Api();
		$data_lines = 	$api->get_data_lines($data);
		$_data = array();
		if(empty($data_lines)){
			wp_send_json_success(array('type' => 'faild'));	
		}
		
		$template_columns = 	$api->get_template_columns($data['project'], $data['language'], $data['template']);
		
		$table = $this->generate_bulk_content_table($data_lines, $template_columns, $columns);
		
		wp_send_json_success(array('type' => 'table', 'table' => $table));	
		
	}
	
	public function generate_bulk_content_table($data_lines, $template_columns, $columns){
		if($data_lines){
			ob_start();
			$_columns = array('name' => 'Name');
			
			if(!empty($columns)){
				foreach($template_columns->columns as $c){
					if(in_array($c->id, $columns)){
						$k = str_replace('-', '_', sanitize_title($c->name));
						$_columns[$k]=$c->name;	
					}
				}
			}
			
			$_columns['last_update'] = "Last Update";
			$_columns['status'] = "Status";
				
			?>
            <table class="contiom-bulk-content-table" cellpadding="0" cellspacing="0">
            	<thead>
                	<tr>
                    	<th><input type="checkbox" name="" id="check_all_bulk" /></th>
                    <?php foreach($_columns as $k=>$v){ ?>
                    	<th data-aa-id="<?php echo $k;?>"><?php echo $v;?></th>
                    <?php } ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($data_lines as $line){ ?>
                	<tr>
                    	<td><input type="checkbox" class="contiom-bulk-content-article" name="article[]" value="<?php echo $line->_id;?>" /></td>
                        <?php foreach($_columns as $k=>$v){ 
							if($k == 'name'){
								?>
                                <td>
                                	<?php echo $line->name;?>
                                </td>
                                <?php	
							}elseif($k == 'last_update'){
								?>
                                <td>
                                	<?php echo $line->UpdatedDate;?>
                                </td>
                                <?php	
							}elseif($k == 'status'){
								?>
                                <td>
                                <?php
								$post_id = $this->get_post_id_from_article_id($line->_id);
								if($post_id){
								$status = get_post_status($post_id);
								if($status){
									echo "In ".$status;	
								}
								}else{
									echo 'New';	
								}
								?>
                                </td>
                                <?php	
							}else{
								?>
                                <td>
                                <?php
									if(isset($line->$k)){
										echo $line->$k;	
									}else{
										if(isset($line->$v)){
											echo $line->$v;	
										}		
									}
								?>
                                </td>
                                <?php	
							}
						} ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="action" value="contiom-bulk-content-add-articles" />
            <?php
			return ob_get_clean();
		}	
	}
	
	public function get_post_id_from_article_id($article_id){
		global $wpdb;
		$post_id = $wpdb->get_var("select post_id from ".$wpdb->prefix."postmeta where meta_key='contiom_article_id' and meta_value='".$article_id."' order by post_id desc");
		if($post_id){
			$query = "SELECT ID FROM $wpdb->posts WHERE ID = $post_id";
			return (int) $wpdb->get_var($query);
		}
		return false;
		
	}
	
	public function contiom_get_bulk_content_advance(){
		$post = $_POST;
		if(isset($post['filter-by'])){
			parse_str($post['filter-by'], $filter_by);	
			$post['filter-by'] = $filter_by['contiom-single-advance-filter-by'];
		}
		if(isset($post['filter-by-value'])){
			parse_str($post['filter-by-value'], $filter_by_value);	
			$post['filter-by-value'] = $filter_by_value['contiom-single-advance-filter-by-value'];
		}
		
		$data = array();
		$data['advance_project'] = $post['project'];
		$data['advance_language'] = $post['language'];
		$data['advance_story'] = $post['story'];
		$data['advance_filter_by'] = $post['filter-by'];
		$data['advance_filter_by_value'] = $post['filter-by-value'];
		$api     = new Contiom_Api();
		$advanced_records = $api->get_advanced_records($data);
		if($advanced_records){
			$table = $this->generate_bulk_content_advance_table($advanced_records);
			wp_send_json_success(array('type' => 'table', 'table' => $table));	
		}
		wp_send_json_success(array('type' => 'faild'));			
	}
	
	public function generate_bulk_content_advance_table($data_lines){
		if($data_lines){
			ob_start();
			$columns = array('name' => 'Name', 'last_update' => "Last Update" , 'status' => "Status");	
			?>
            <table class="contiom-bulk-content-table" cellpadding="0" cellspacing="0">
            	<thead>
                	<tr>
                    	<th><input type="checkbox" name="" id="check_all_bulk" /></th>
                    <?php foreach($columns as $k=>$v){ ?>
                    	<th><?php echo $v;?></th>
                    <?php } ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($data_lines as $line){ 
						if($line->resultValues && !empty($line->resultValues) && $line->exportedJson && !empty($line->exportedJson)){
							$name = array();
							$id = 0;
							foreach($line->resultValues as $value){
								$name[]=$value->value;
								$id = $value->resultId;	
							}
				?>
                	<tr>
                    	<td><input type="checkbox" class="contiom-bulk-content-article" name="article[]" value="<?php echo $id;?>" /></td>
                        <?php foreach($columns as $k=>$v){ 
							if($k == 'name'){
								?>
                                <td>
                                	<?php echo  implode(', ', $name);?>
                                </td>
                                <?php	
							}elseif($k == 'last_update'){
								?>
                                <td>
                                	<?php echo $line->lastModificationTime;?>
                                </td>
                                <?php	
							}elseif($k == 'status'){
								?>
                                <td>
                                <?php
								$post_id = $this->get_post_id_from_article_id($id);
								if($post_id){
								$status = get_post_status($post_id);
								if($status){
									echo "In ".$status;	
								}
								}else{
									echo 'New';	
								}
								?>
                                </td>
                                <?php	
							}
						  }
						}?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="action" value="contiom-bulk-content-add-advance-articles" />
            <?php
			return ob_get_clean();
		}	
	}
	
	public function contiom_post_refresh_content(){
		$post_id = $_REQUEST['post_id'];
		
		$status = $this->update_contiom_post_content($post_id);
		
		if(false == $status){
			$this->record_log('', '', $post_id, 'faild', 'refresh_advance_content');
			wp_send_json_success(array('type' => 'faild'));		
		}
		
		$this->record_log('', '', $post_id, 'success', 'refresh_advance_content');
		
		$_data = array('type' => 'article');
		$url = get_edit_post_link($post_id, 'edit');
		$_data['url'] = add_query_arg(array('message' => 1), $url);
		wp_send_json_success($_data);		
			
	}
	
	public function update_contiom_post_content($post_id){
		$content_mode = get_post_meta($post_id, 'contiom_content_mode', true);
		
		if('simple' != $content_mode){
			$contiom_data = get_post_meta($post_id, 'contiom_advance_data', true);
			$article_id = get_post_meta($post_id, 'contiom_advance_article_id', true);
		
			if(empty($article_id)){
				return false;
			}
			
			$api     = new Contiom_Api();
			$contiom_data['ids'] = array($article_id);
			$advanced_data = $api->get_advanced_text($contiom_data);
			if($advanced_data && isset($advanced_data[0]->resultJson)){
				$result_json = $advanced_data[0]->resultJson;
				$article_html = $this->prepare_html_from_article($result_json, $post_id, true);	
			}
			
		}else{
			$contiom_data = get_post_meta($post_id, 'contiom_data', true);
			$article_id = get_post_meta($post_id, 'contiom_article_id', true);
		
			if(empty($article_id)){
				return false;
			}
			$api     = new Contiom_Api();
			$article = $api->get_articles($contiom_data);
			if(!empty($article)){
				$this->prepare_html_from_article($article[0]->resultJson, $post_id, true);	
			}
		}
		return true;
	}
	
	public function contiom_post_get_content(){
		
		$post = $_POST;
		if(isset($post['filter-by'])){
			parse_str($post['filter-by'], $filter_by);	
			$post['filter-by'] = $filter_by['contiom-single-filter-by'];
		}
		if(isset($post['filter-by-value'])){
			parse_str($post['filter-by-value'], $filter_by_value);	
			$post['filter-by-value'] = $filter_by_value['contiom-single-filter-by-value'];
		}
		
		$data=array();
		$data['project'] = $post['project'];
		$data['language'] = $post['language'];
		$data['template'] = $post['template'];
		$data['story'] = $post['story'];
		$data['filter_by'] = $post['filter-by'];
		$data['filter_by_value'] = $post['filter-by-value'];
		$api     = new Contiom_Api();
		$data_lines = 	$api->get_data_lines($data);
		$_data = array();
		if(empty($data_lines)){
			wp_send_json_success(array('type' => 'faild'));	
		}

		if(isset($post['article']) && '' != $post['article']){
			$found = false;
			foreach($data_lines as $data_line){
				if($data_line->_id == $post['article'] ){
					$found = $data_line;	
				}
			}
			if(false != $found){
				$data_lines = array($found);	
			}
		}
		
		if(sizeof($data_lines) == 1){
			$_data = array('type' => 'article');
			$data['ids'] = array($data_lines[0]->_id);
			$article_id = $data_lines[0]->_id;
			$article = $api->get_articles($data);
			if(!empty($article)){
				$post_id = $_POST['post_id'];
				
				
				$data['article_id'] = $article_id;
				
				update_post_meta($post_id, 'contiom_data', $data);
				
				update_post_meta($post_id, 'contiom_article_id', $article_id);
				
				update_post_meta($post_id, 'contiom_content_mode', 'simple');
				
				delete_post_meta($post_id, 'contiom_advance_data');
				
				
				$_data['article_html'] = $this->prepare_html_from_article($article[0]->resultJson, $post_id);
				
			  	/*$skip_block_updates = array();
			  	if(!empty($article[0]->resultJson) && is_array($article[0]->resultJson)){
			  		foreach($article[0]->resultJson as $v){
						$skip_block_updates[]=array('id' => $v->id, 'name' => $v->name, 'skip_update' => 'off');
					}
			  	}
				
			  	update_post_meta($post_id, 'contiom_skip_block_updates', $skip_block_updates);*/
				
				
				
				$this->record_log('', '', $post_id, 'success', 'get_content');
				
				$url = get_edit_post_link($post_id, 'edit');
			  	$_data['url'] = add_query_arg(array('message' => 1), $url);
			}else{
				wp_send_json_success(array('type' => 'faild'));	
			}
			wp_send_json_success($_data);
		}else{
			$_data = array('type' => 'select_article');
			$selection = array(array('label' => esc_html__('Select an article', 'contiom'), 'value' => ''));
			if($data_lines){
				foreach($data_lines as $data_line){
					$d = array('value' => $data_line->_id, 'label' => '');
					if(property_exists($data_line, 'name')){
						$d['label'] = $data_line->name;	
					}else{
						$columns =	$api->get_template_columns($data['project'], $data['language'], $data['template']);
						if($columns){
							foreach($columns->columns as $column){
								if('' == $d['label'] && property_exists($column, 'name')){
									$column_name = $column->name;
									if(property_exists($data_line, $column_name)){
										$d['label'] = $data_line->{$column_name};
									}
								}
							}
						}
					}
					//$selection[] = array('label' => $data_line->name, 'value' => $data_line->_id);	
					$selection[] = $d;
				}
			}
			$_data['selection'] = $selection;
		}
		
		wp_send_json_success($_data);
	}
	
	public function prepare_html_from_article($article, $post_id, $update=false){
		global $wpdb;
		$title = '';
		if(false == $update){
			$contiom_content_mode = get_post_meta($post_id, 'contiom_content_mode', true);
				
			$blocks = array();
			$skip_block_updates = array();
			if(!empty($article) && is_array($article)){
				foreach($article as $v){
					
					if( $title == '' && (trim($v->name) == 'Title' || trim($v->name) == 'title' || trim($v->name) == "Article Title")){
						$title = strip_tags($v->result);	
					}else{
						$result = $v->result;
						$result = str_replace('<br/>', '<br>', $result);
						$result = str_replace('<p>', '', $result);
						$result = str_replace('</p>', '', $result);
						$block = array('blockName' => 'contiom/content-block-p');
						$block['attrs']['block_id'] = strval($v->id);
						$block['attrs']['block_name'] = strval($v->name);
						$block['innerBlocks'] = array();
						$block['innerHTML'] = '<div class="wp-block-contiom-content-block-p"><p>'.$result.'</p></div>';
						$block['innerContent'] = array($block['innerHTML']);
						$block['attrs']['content'] = strval($result);
						$blocks[]=$block;
						$skip_block_updates[]=array('id' => $v->id, 'name' => $v->name, 'skip_update' => 'off');
					}
					//$html.='<div class="contiom-content" data-contiom-name="'.$v->name.'" data-contiom-id="'.$v->id.'">'.$v->result.'</div>';
				}
			}
			
			$html = serialize_blocks($blocks);
			$wpdb->update($wpdb->posts, array('post_content' => $html), array('ID' => $post_id));
			
			if('advance' != $contiom_content_mode){
				update_post_meta($post_id, 'contiom_skip_block_updates', $skip_block_updates);
			}else{
				update_post_meta($post_id, 'contiom_skip_block_updates_advance', $skip_block_updates);	
			}
			
		}else{
			//echo $post_id;
			
			$contiom_content_mode = get_post_meta($post_id, 'contiom_content_mode', true);
			
			if('advance' != $contiom_content_mode){
				$skip_block_updates = get_post_meta($post_id, 'contiom_skip_block_updates', true); 	
			}else{
				$skip_block_updates = get_post_meta($post_id, 'contiom_skip_block_updates_advance', true);	
			}
			$skip_block_update_ids = array();
			if(!empty($skip_block_updates)){
				foreach($skip_block_updates as $k1=>$v1){
					if($v1['skip_update'] != 'off'){
						$skip_block_update_ids[]=$v1['id'];	
					}
				}
			}
			
			//update_option('db_test_skip_here', $skip_block_update_ids);
			//update_option('db_test_skip_here1', $skip_block_updates);
			
			
			$post = get_post($post_id);
			$post_content = $post->post_content;
			$post_blocks = parse_blocks($post_content);
			foreach($post_blocks as $k=>$block){
				if($block['blockName'] == 'contiom/content-block-p'){
					foreach($article as $v){
						
						if( $title == '' && (trim($v->name) == 'Title' || trim($v->name) == 'title' || trim($v->name) == "Article Title")){
							$title = strip_tags($v->result);								
						}else{						
							if($v->id == $block['attrs']['block_id'] && !in_array(absint($v->id), $skip_block_update_ids)){
								$result = $v->result;
								$result = str_replace('<br/>', '<br>', $result);
								$result = str_replace('<p>', '', $result);
								$result = str_replace('</p>', '', $result);
								//$block['attrs']['block_id'] = strval($v->id);
								$block['attrs']['block_name'] = strval($v->name);
								$block['innerBlocks'] = array();
								//$block['innerHTML'] = '<div class="wp-block-contiom-content-block-p"><p>'.$result.'</p></div>';
								$block['innerHTML'] = preg_replace('#<div class="wp-block-contiom-content-block-p(.*?)"(.*?)>(.*?)</div>#si', '<div class="wp-block-contiom-content-block-p ${1}" ${2}><p>'.$result.'</p></div>', $block['innerHTML']);
								
								/*$doc = new DOMDocument();
								$doc->loadHTML($block['innerHTML']);
								$doc->getElementsByTagName('div')->nodeValue = '<p>'.$result.'</p>';
								$block['innerHTML'] = $doc->saveHTML();*/
								
								$block['innerContent'] = array($block['innerHTML']);
								$block['attrs']['content'] = strval($result);
								$post_blocks[$k]=$block;
							}
						}
					}
				}
			}
			$html = serialize_blocks($post_blocks);
			$wpdb->update($wpdb->posts, array('post_content' => $html), array('ID' => $post_id));
		}
		
		if('' == $title){
			$api     = new Contiom_Api();
			$contiom_content_mode = get_post_meta($post_id, 'contiom_content_mode', true);
			
			if('advance' != $contiom_content_mode){
				$contiom_data = get_post_meta($post_id, 'contiom_data', true);
				$contiom_project = $contiom_data && isset($contiom_data['project'])?$contiom_data['project']:"";
				$contiom_language = $contiom_data &&  isset($contiom_data['language'])?$contiom_data['language']:"";
				$contiom_template = $contiom_data &&  isset($contiom_data['template'])?$contiom_data['template']:"";
				$contiom_story = $contiom_data &&  isset($contiom_data['story'])?$contiom_data['story']:"";
				
				if($contiom_project && $contiom_language && $contiom_template){
					$columns = 	$api->get_template_columns($contiom_project, $contiom_language, $contiom_template);
					if($columns){
						
						$data_line = $api->get_data_line($contiom_data);
						if($data_line){
							$data_line = $data_line[0];
							foreach($columns->columns as $column){
								if('' == $title && property_exists($column, 'name')){
									$column_name = $column->name;
									if(property_exists($data_line, $column_name)){
										$title = $data_line->{$column_name};
									}	
								}
							}
						}
					}
				}
				
			}else{
				$contiom_advance_data = get_post_meta($post_id, 'contiom_advance_data', true);			
				
				$contiom_advance_article_id = get_post_meta($post_id, 'contiom_advance_article_id', true);
				
				$contiom_advance_project = $contiom_advance_data && isset($contiom_advance_data['advance_project'])? $contiom_advance_data['advance_project']:'';
				$contiom_advance_language = $contiom_advance_data && isset($contiom_advance_data['advance_language'])?$contiom_advance_data['advance_language']:'';
				$contiom_advance_story = $contiom_advance_data && isset($contiom_advance_data['advance_story'])?$contiom_advance_data['advance_story']:'';
					
				if($contiom_advance_project && $contiom_advance_language && $contiom_advance_story){
					$columns = 	$api->get_advance_columns($contiom_project, $contiom_language, $contiom_advance_story);
				}
				
				$data_lines = $api->get_advanced_records($contiom_advance_data);
				$name = array();
				foreach($data_lines as $line){ 
					if($line->resultValues && !empty($line->resultValues) && $line->exportedJson && !empty($line->exportedJson)){
						foreach($line->resultValues as $value){
							if($value->resultId == $contiom_advance_article_id){
								$name[]=$value->value;
							}
						}
					}
				}
				if(!empty($name)){
					$title = implode(', ', $name);	
				}
				
			}
		}
		
		
		if(('Auto Draft' == get_the_title($post_id) || __( 'Auto Draft' ) == get_the_title($post_id) ) && '' != $title ){
			$wpdb->update($wpdb->posts, array('post_title' => $title), array('ID' => $post_id));
		}
		
		return true;
	}
	
	public function register_meta_boxes(){
		
		$content_types_show_settings = get_option('contiom_content_types_show_settings');
		if($content_types_show_settings){
			add_meta_box( 'contiom-box', esc_html__( 'Contiom', 'contiom' ), array($this, 'contiom_meta_box'), $content_types_show_settings, 'side', 'core' );	
		}
	}
	
	public function is_user_can_access_settings(){
		if ( current_user_can( 'manage_options' ) ) {
			return true;	
		}
		
		$role_manager_settings = get_option( 'contiom_role_manager_settings' );	
		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;
		if(isset($roles[0]) && !empty($role_manager_settings)){
			$role = $roles[0];
			if(in_array($role, $role_manager_settings)){
				return true;	
			}
		}
		return false;
	}
	
	public function is_user_can_access_bulk_content(){
		if ( current_user_can( 'manage_options' ) ) {
			return true;	
		}
		
		$role_manager_bulk_content = get_option( 'contiom_role_manager_bulk_content' );	
		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;
		if(isset($roles[0]) && !empty($role_manager_bulk_content)){
			$role = $roles[0];
			if(in_array($role, $role_manager_bulk_content)){
				return true;
			}
		}
		return false;
	}
	
	public function is_user_can_refresh_content(){
		if ( current_user_can( 'manage_options' ) ) {
			return true;	
		}
		$role_manager_refresh_content = (array) get_option( 'contiom_role_manager_refresh_content' );	
		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;
		if(isset($roles[0]) && !empty($role_manager_refresh_content)){
			$role = $roles[0];
			if(in_array($role, $role_manager_refresh_content)){
				return true;	
			}
		}
		return false;
	}
	
	public function contiom_meta_box( $post ){
		
		$api     = new Contiom_Api();
		$projects= $api->get_projects();
		
		
		
		$contiom_content_types_auto_update = get_option('contiom_content_types_auto_update');
		
		$contiom_content_mode = get_post_meta($post->ID, 'contiom_content_mode', true);
		
		$contiom_data = get_post_meta($post->ID, 'contiom_data', true);
		
		$contiom_project = $contiom_data && isset($contiom_data['project'])?$contiom_data['project']:"";
		$contiom_language = $contiom_data &&  isset($contiom_data['language'])?$contiom_data['language']:"";
		$contiom_template = $contiom_data &&  isset($contiom_data['template'])?$contiom_data['template']:"";
		$contiom_story = $contiom_data &&  isset($contiom_data['story'])?$contiom_data['story']:"";
		
		$contiom_filter_by = $contiom_data &&  isset($contiom_data['filter_by'])?$contiom_data['filter_by']:"";
		$contiom_filter_by_value = $contiom_data &&  isset($contiom_data['filter_by_value'])?$contiom_data['filter_by_value']:"";
		
		$contiom_article_id = $contiom_data &&  isset($contiom_data['article_id'])?$contiom_data['article_id']:"";
		
		$contiom_advance_data = get_post_meta($post->ID, 'contiom_advance_data', true);
		$contiom_advance_article_id = get_post_meta($post->ID, 'contiom_advance_article_id', true);
				
		
		?>
        <div class="contiom-post-settings <?php echo (!empty($contiom_advance_article_id) || !empty($contiom_article_id))?"has_article":"";?>">
        	<div class="contiom-post-settings-head">
            	<ul>
                	<li class="<?php echo ('simple' == $contiom_content_mode || '' == $contiom_content_mode)?"active":"";?>"><a href="javascript:void(0)" data-href="#contiom-post-settings-tab1" >Contiom WP</a></li>
                    <li class="<?php echo ('advance' == $contiom_content_mode)?"active":"";?>"><a href="javascript:void(0)" data-href="#contiom-post-settings-tab2">Advance Mode</a></li>
                </ul>
            </div>
            
            
            <div class="contiom-post-settings-loader">
                <div class="loadersmall"></div>
            </div>
            <div class="contiom-post-settings-body">
            	
            	<div class="contiom-post-settings-body-content <?php echo ('simple' == $contiom_content_mode || '' == $contiom_content_mode)?"active":"";?>" id="contiom-post-settings-tab1">
                	<h3>Connect with data</h3>
                    
                    
                    
                    <div class="contiom-data-row contiom-single-project">
                    	<label>Project</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-project"  <?php echo (!empty($contiom_article_id) || $contiom_advance_article_id)?"disabled=\"disabled\"":"";?> >
                            	<option value="" >Select Project</option>
                                <?php
								if(is_array($projects)){
									foreach($projects as $project){
										if(false == $project->advancedMode){
										?>
                                        <option  <?php echo ($contiom_project == $project->id)?"selected=\"selected\"":"";?> value="<?php echo $project->id;?>"><?php echo $project->name;?></option>
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
                        	<select name="contiom-single-language" <?php echo (!empty($contiom_article_id) || $contiom_advance_article_id)?"disabled=\"disabled\"":"";?>>
                            	<option value="" >Select Language</option>
                                <?php 
									if($contiom_project){
										$languages = 	$api->get_languages($contiom_project);
										if(is_array($languages)){
											foreach($languages as $language){
												?>
                                            <option  <?php echo ($contiom_language == $language->name)?"selected=\"selected\"":"";?> value="<?php echo $language->name;?>"><?php echo $language->displayName;?></option>
                                            <?php
                                            }
										}
									}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-template">
                    	<label>Template</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-template" <?php echo (!empty($contiom_article_id) || $contiom_advance_article_id)?"disabled=\"disabled\"":"";?>>
                            	<option value="" >Select Template</option>
                                <?php 
								if($contiom_language && $contiom_project){
									$templates = 	$api->get_templates($contiom_project, $contiom_language);
									if(is_array($templates)){
										foreach($templates as $template){
										?>
                                        <option  <?php echo ($contiom_template == $template->id)?"selected=\"selected\"":"";?> value="<?php echo $template->id;?>"><?php echo $template->name;?></option>
                                        <?php	
										}
									}
								}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-story">
                    	<label>Story</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-story" <?php echo (!empty($contiom_article_id) || $contiom_advance_article_id)?"disabled=\"disabled\"":"";?>>
                            	<option value="" >Select Story</option>
                                <?php
								if($contiom_template && $contiom_project && $contiom_language){
									$stories = 	$api->get_stories($contiom_project, $contiom_language, $contiom_template);
									if(is_array($stories)){
										foreach($stories as $story){
											$data[] = array('label' => $story->item2, 'value' => $story->item1);	
											?>
                                            <option  <?php echo ($contiom_story == $story->item1)?"selected=\"selected\"":"";?> value="<?php echo $story->item1;?>"><?php echo $story->item2;?></option>
                                            <?php
										}
									}	
								}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <h3 class="contion-row-identify">Data row identify</h3>
                    
                    
                 <?php /*?>   <div class="contiom-data-row contiom-single-filter-by">
                    	<label>Filter by</label>
                        <div class="contiom-data-row-field">
                        	<select class="contiom-select2" name="contiom-single-filter-by">
                            	<option value="" >Select Column</option>
                                <?php
								if($contiom_project && $contiom_language && $contiom_template){
									$columns = 	$api->get_template_columns($contiom_project, $contiom_language, $contiom_template);
									if($columns){
										foreach($columns->columns as $column){
											$data[] = array('label' => $column->name, 'value' => $column->id);	
											?>
                                            <option  <?php echo ($contiom_filter_by == $column->id)?"selected=\"selected\"":"";?> value="<?php echo $column->id;?>"><?php echo $column->name;?></option>
                                            <?php
										}
									}	
								}
								?>
                            </select>
                        </div>
                    </div>
                    
                    
                    <div class="contiom-data-row contiom-single-filter-by-value">
                    	<label> </label>
                        <div class="contiom-data-row-field">
                        	<input type="text" name="contiom-single-filter-by-value" value="<?php echo $contiom_filter_by_value;?>"  />
                        </div>
                    </div><?php */?>
                    
                    
                    
                    
                    <?php
						$columns = array();
						if($contiom_project && $contiom_language && $contiom_template){
								$columns = 	$api->get_template_columns($contiom_project, $contiom_language, $contiom_template);
						}
					
					$_contiom_article_id = $contiom_article_id;
					if(!empty($contiom_advance_article_id)) $_contiom_article_id = $contiom_advance_article_id;	
					
					if(!empty($contiom_filter_by) && !empty($contiom_filter_by_value)){
						foreach($contiom_filter_by as $k=>$filter_by){
							$this->add_filter_by_column_field($columns, '', $filter_by, $contiom_filter_by_value[$k], $_contiom_article_id);		
						}
					}else{
						$this->add_filter_by_column_field($columns, '', '', '', $_contiom_article_id);			
					}
					
					?>
                    <?php if(empty($contiom_article_id)){ ?>                    
                    <div class="contiom-data-row contiom-single-filter-add-more">
                    <a href="javascript:void(0)"> <span class="dashicons dashicons-insert"></span> </a>
                    </div>
                    <?php } ?>
                    
                    
                    
                    
                    
                 
                    
                    
                    <?php
						$article_selection = 'hide';
						if($contiom_project && $contiom_language && $contiom_template && $contiom_story && $contiom_article_id ){
							$data['project'] = $contiom_project;
							$data['language'] = $contiom_language;
							$data['template'] = $contiom_template;
							$data['story'] = $contiom_story;
							//$data['search_by'] = $contiom_search_by;
							//$data['search_by_value'] = $contiom_search_by_value;
							$data['filter_by'] = $contiom_filter_by;
							$data['filter_by_value'] = $contiom_filter_by_value;
							$api     = new Contiom_Api();
							$data_lines = 	$api->get_data_lines($data);
							if(!empty($data_lines) && count($data_lines) > 1){
								$article_selection = 'show';
							}
						}
					?>
                    
                    <div class="contiom-data-row contiom-single-article <?php echo $article_selection;?>">
                    	<label>Article</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-article" <?php echo (!empty($contiom_article_id) || $contiom_advance_article_id)?"disabled=\"disabled\"":"";?>>
                            	<option value="" >Select an article</option>
                                <?php
									if($contiom_project && $contiom_language && $contiom_template && $contiom_story && $contiom_article_id && $data_lines ){
										if(!empty($data_lines)){
											foreach($data_lines as $data_line){
												$value = $data_line->_id;
												$label = '';
												if(property_exists($data_line, 'name')){
													$label = $data_line->name;	
												}else{
													if($columns){
														foreach($columns->columns as $column){
															if(property_exists($column, 'name')){
																$column_name = $column->name;
																if('' == $label && property_exists($data_line, $column_name)){
																	$label = $data_line->{$column_name};
																}
															}
														}
													}	
												}
												?>
                                            <option  <?php echo ($contiom_article_id == $data_line->_id)?"selected=\"selected\"":"";?> value="<?php echo $value; ?>"><?php echo $label;?></option>
                                            <?php
											}	
										}
									}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-search-by-value">
                    	
                        <?php
						if(empty($contiom_article_id) && empty($contiom_advance_article_id)){ ?>
                        <a href="javascript:void(0)" id="contiom-single-get-content" class="button button-primary button-large <?php echo ('publish' == get_post_status($post->ID))?"disabled":"";?>">Get Content</a>
                        
                        <?php		
						}else{
							if($this->is_user_can_refresh_content() && empty($contiom_advance_article_id) ){
							 ?>
                        <a href="javascript:void(0)" id="contiom-single-refresh-content" class="button button-primary button-large">Refresh Content</a>
                        <?php } } ?>
                        
                    </div>
                    
                    <div class="contiom-data-row">
                    	<div class="row-divider"> </div>
                    </div>
                    
                    <?php if(in_array(get_post_type($post), $contiom_content_types_auto_update)){ ?>
                    <h3>Auto Update Settings</h3>
                    <?php
						$contiom_auto_update = get_post_meta($post->ID, 'contiom_auto_update', true);
						
						$contiom_auto_update_type = get_post_meta($post->ID, 'contiom_auto_update_type', true);
						$contiom_auto_update_date = get_post_meta($post->ID, 'contiom_auto_update_date', true);
						$contiom_auto_update_time = get_post_meta($post->ID, 'contiom_auto_update_time', true);
						
						$contiom_auto_update_type = ($contiom_auto_update_type)?$contiom_auto_update_type:"weekly";
						
					?>
                    <div class="contiom-data-row contiom-single-auto-update-settings">
                        <div class="contiom-data-row-field">
                        	<label class="contiom-admin-switch"><input <?php echo ('on' == $contiom_auto_update)?"checked=\"checked\"":"";?> type="checkbox" name="contiom-single-auto-update" ><span class="contiom-admin-slider contiom-admin-round"><span class="on"><?php echo esc_html__( 'Enable', 'contiom' ); ?></span><span class="off"><?php echo esc_html__( 'Disable', 'contiom' ); ?></span></span></label>
                        </div>
                    </div>
                    
                    
                    
                    <div class="contiom-data-row contiom-single-auto-update-settings-type <?php echo ('on'!= $contiom_auto_update)?"hide":"";?>">
                    	<div class="contiom-data-row-field full">
                        	<select name="contiom-single-auto-update-settings-type">
                            	<option <?php echo ("daily" == $contiom_auto_update_type)?"selected=\"selected\"":"";?> value="daily">Daily</option>
                                <option <?php echo ("weekly" == $contiom_auto_update_type)?"selected=\"selected\"":"";?> value="weekly">Weekly</option>
                                <option <?php echo ("monthly" == $contiom_auto_update_type)?"selected=\"selected\"":"";?> value="monthly">Monthly</option>
                                
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-auto-update-settings-date-time <?php echo ('on'!= $contiom_auto_update)?"hide":"";?>">
                    	<div class="contiom-data-row-field half contiom-data-row-field-date <?php echo ('daily'== $contiom_auto_update_type)?"hide":"";?>">
                        	<select name="contiom-single-auto-update-settings-date">
                            	<?php if('weekly' == $contiom_auto_update_type){
									foreach(array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday	', 'Saturday') as $day){
									?>
                                    <option <?php echo ($contiom_auto_update_date == strtolower($day))?"selected=\"selected\"":"";?> value="<?php echo strtolower($day);?>"><?php echo $day;?></option>
                                    <?php	
									}
								}else{
									for($i=1;$i<=31; $i++){
									?>
                                    <option <?php echo ($contiom_auto_update_date == $i)?"selected=\"selected\"":"";?> value="<?php echo $i;?>"><?php echo $i;?></option>
                                    <?php
									}
								}
								?>
                            	
                            </select>
                        </div>
                        <div class="contiom-data-row-field half contiom-data-row-field-time">
                        	<input type="time" name="contiom-single-auto-update-settings-time" value="<?php echo $contiom_auto_update_time;?>" />
                        </div>
                    </div>
                    
                    
                    <div class="contiom-data-row">
                    	<div class="row-divider"> </div>
                    </div>
                    
                    <?php } ?>
                    
                    <?php 
						$skip_block_updates = get_post_meta($post->ID, 'contiom_skip_block_updates', true);
						if($skip_block_updates){
					?>                    
                    <h3>Skip block update</h3>
                    <?php foreach($skip_block_updates as $d){ ?>
                    	<div class="contiom-data-row contiom-single-skip-block-update">
                            <div class="contiom-data-row-field">
                                <label class="contiom-admin-switch"><?php echo $d['name'];?> <input <?php echo ('on' == $d['skip_update'])?"checked=\"checked\"":"";?> type="checkbox" name="contiom_skip_block_updates[<?php echo $d['id'];?>]" ><span class="contiom-admin-slider contiom-admin-round"></span></label>
                            </div>
                        </div>
                    <?php } 
						}
					?>
                    
                    
                                  
                </div>
                
                <?php
					 
					
				
					//$contiom_advance_project = get_post_meta($post->ID, 'contiom_advance_project', true);
					//$contiom_advance_language = get_post_meta($post->ID, 'contiom_advance_language', true);
					//$contiom_advance_story = get_post_meta($post->ID, 'contiom_advance_story', true);
										
					$contiom_advance_project = $contiom_advance_data && isset($contiom_advance_data['advance_project'])? $contiom_advance_data['advance_project']:'';
					$contiom_advance_language = $contiom_advance_data && isset($contiom_advance_data['advance_language'])?$contiom_advance_data['advance_language']:'';
					$contiom_advance_story = $contiom_advance_data && isset($contiom_advance_data['advance_story'])?$contiom_advance_data['advance_story']:'';
					
					$contiom_advance_filter_by = $contiom_advance_data && isset($contiom_advance_data['advance_filter_by'])?$contiom_advance_data['advance_filter_by']:'';
					$contiom_advance_filter_by_value = $contiom_advance_data && isset($contiom_advance_data['advance_filter_by_value'])?$contiom_advance_data['advance_filter_by_value']:'';
					
					
				?>
                
                <div class="contiom-post-settings-body-content <?php echo ('advance' == $contiom_content_mode)?"active":"";?>" id="contiom-post-settings-tab2">
                	<h3>Connect with data</h3>
                    <div class="contiom-data-row contiom-single-advance-project">
                    	<label>Project</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-advance-project" <?php echo (!empty($contiom_article_id) || $contiom_advance_article_id)?"disabled=\"disabled\"":"";?>> 
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
                        	<select name="contiom-single-advance-language" <?php echo (!empty($contiom_article_id) || $contiom_advance_article_id)?"disabled=\"disabled\"":"";?>>
                            	<option value="" >Select Language</option>
                                <?php 
									if($contiom_advance_project){
										$languages = 	$api->get_advance_languages($contiom_advance_project);
										if(is_array($languages)){
											foreach($languages as $language){
												?>
                                            <option  <?php echo ($contiom_advance_language == $language->name)?"selected=\"selected\"":"";?> value="<?php echo $language->name;?>"><?php echo $language->displayName;?></option>
                                            <?php
                                            }
										}
									}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-advance-story">
                    	<label>Story</label>
                        <div class="contiom-data-row-field">
                        	<select name="contiom-single-advance-story" <?php echo (!empty($contiom_article_id) || $contiom_advance_article_id)?"disabled=\"disabled\"":"";?>>
                            	<option value="" >Select Story</option>
                                <?php
								if($contiom_advance_project && $contiom_advance_language){
									$stories = 	$api->get_advance_stories($contiom_advance_project, $contiom_advance_language);
									if(is_array($stories)){
										foreach($stories as $story){
											$data[] = array('label' => $story->item2, 'value' => $story->item1);	
											?>
                                            <option  <?php echo ($contiom_advance_story == $story->item1)?"selected=\"selected\"":"";?> value="<?php echo $story->item1;?>"><?php echo $story->item2;?></option>
                                            <?php
										}
									}	
								}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <h3 class="contion-advance-row-identify">Data row identify</h3>
                    
                    <?php
						$columns = array();
						if($contiom_advance_project && $contiom_advance_language && $contiom_advance_story){
								$columns = 	$api->get_advance_columns($contiom_project, $contiom_language, $contiom_advance_story);
						}
						
					$_contiom_article_id = $contiom_advance_article_id;
					if(!empty($contiom_article_id)) $_contiom_article_id = $contiom_article_id;		
					
					if(!empty($contiom_advance_filter_by) && !empty($contiom_advance_filter_by_value)){
						foreach($contiom_advance_filter_by as $k=>$filter_by){
							$this->add_filter_by_column_field($columns, 'advanced', $filter_by, $contiom_advance_filter_by_value[$k], $_contiom_article_id);		
						}
					}else{
						$this->add_filter_by_column_field($columns, 'advanced', '', '', $_contiom_article_id);			
					}
					
					?>
                    
                    <?php if(empty($contiom_advance_article_id) && empty($contiom_article_id)){ ?>
                    <div class="contiom-data-row contiom-single-advance-filter-add-more">
                    <a href="javascript:void(0)"> <span class="dashicons dashicons-insert"></span> </a>
                    </div>
                    <?php } ?>
                    
                    
                    <div class="contiom-data-row">
                    	
                        <?php if(empty($contiom_advance_article_id) && empty($contiom_article_id)){ ?>
                        
                        <a href="javascript:void(0)" id="contiom-single-get-content-advance" class="button button-primary button-large <?php echo ('publish' == get_post_status($post->ID))?"disabled":"";?>">Get Content</a>
                    
                    <?php		
						}else{
							if($this->is_user_can_refresh_content() && empty($contiom_article_id) ){
							 ?>
					<a href="javascript:void(0)" id="contiom-single-advance-refresh-content" class="button button-primary button-large">Refresh Content</a>
					<?php } 
					
						}?>
                    
                    </div>
                    
                    
                    
                    
                    <div class="contiom-data-row">
                    	<div class="row-divider"> </div>
                    </div>
                    
                    <?php if(in_array(get_post_type($post), $contiom_content_types_auto_update)){ ?>
                    
                    <h3>Auto Update Settings</h3>
                    <?php
						$contiom_advance_auto_update = get_post_meta($post->ID, 'contiom_advance_auto_update', true);
						
						$contiom_advance_auto_update_type = get_post_meta($post->ID, 'contiom_advance_auto_update_type', true);
						$contiom_advance_auto_update_date = get_post_meta($post->ID, 'contiom_advance_auto_update_date', true);
						$contiom_advance_auto_update_time = get_post_meta($post->ID, 'contiom_advance_auto_update_time', true);
						
						$contiom_advance_auto_update_type = ($contiom_advance_auto_update_type)?$contiom_advance_auto_update_type:"weekly";
						
					?>
                    <div class="contiom-data-row contiom-single-advance-auto-update-settings">
                        <div class="contiom-data-row-field">
                        	<label class="contiom-admin-switch"><input <?php echo ('on' == $contiom_advance_auto_update)?"checked=\"checked\"":"";?> type="checkbox" name="contiom-single-advance-auto-update" ><span class="contiom-admin-slider contiom-admin-round"><span class="on"><?php echo esc_html__( 'Enable', 'contiom' ); ?></span><span class="off"><?php echo esc_html__( 'Disable', 'contiom' ); ?></span></span></label>
                        </div>
                    </div>
                    
                    
                    
                    <div class="contiom-data-row contiom-single-advance-auto-update-settings-type <?php echo ('on'!= $contiom_advance_auto_update)?"hide":"";?>">
                    	<div class="contiom-data-row-field full">
                        	<select name="contiom-single-advance-auto-update-settings-type">
                            	<option <?php echo ("daily" == $contiom_advance_auto_update_type)?"selected=\"selected\"":"";?> value="daily">Daily</option>
                                <option <?php echo ("weekly" == $contiom_advance_auto_update_type)?"selected=\"selected\"":"";?> value="weekly">Weekly</option>
                                <option <?php echo ("monthly" == $contiom_advance_auto_update_type)?"selected=\"selected\"":"";?> value="monthly">Monthly</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="contiom-data-row contiom-single-advance-auto-update-settings-date-time <?php echo ('on'!= $contiom_advance_auto_update)?"hide":"";?>">
                    	<div class="contiom-data-row-field half contiom-data-row-field-advance-date <?php echo ('daily'== $contiom_advance_auto_update_type)?"hide":"";?>">
                        	<select name="contiom-single-advance-auto-update-settings-date">
                            	<?php if('weekly' == $contiom_advance_auto_update_type){
									foreach(array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') as $day){
									?>
                                    <option <?php echo ($contiom_advance_auto_update_date == strtolower($day))?"selected=\"selected\"":"";?> value="<?php echo strtolower($day);?>"><?php echo $day;?></option>
                                    <?php	
									}
								}else{
									for($i=1;$i<=31; $i++){
									?>
                                    <option <?php echo ($contiom_advance_auto_update_date == $i)?"selected=\"selected\"":"";?> value="<?php echo $i;?>"><?php echo $i;?></option>
                                    <?php
									}
								}
								?>
                            	
                            </select>
                        </div>
                        <div class="contiom-data-row-field half contiom-data-row-field-advance-time">
                        	<input type="time" name="contiom-single-advance-auto-update-settings-time" value="<?php echo $contiom_advance_auto_update_time;?>" />
                        </div>
                    </div>
                 
                 
                 <div class="contiom-data-row">
                    	<div class="row-divider"> </div>
                    </div>
                    
                    <?php 
					}
					?>
                    
                    <?php 
						$skip_block_updates = get_post_meta($post->ID, 'contiom_skip_block_updates_advance', true);
						if($skip_block_updates){
					?>                    
                    <h3>Skip block update</h3>
                    <?php foreach($skip_block_updates as $d){ ?>
                    	<div class="contiom-data-row contiom-single-skip-block-update-advance">
                            <div class="contiom-data-row-field">
                                <label class="contiom-admin-switch"><?php echo $d['name'];?> <input <?php echo ('on' == $d['skip_update'])?"checked=\"checked\"":"";?> type="checkbox" name="contiom_skip_block_updates_advance[<?php echo $d['id'];?>]" ><span class="contiom-admin-slider contiom-admin-round"></span></label>
                            </div>
                        </div>
                    <?php } 
						}
					?>
               
                    
                    
                    
                    
                </div>
            </div>
        </div>	
        <?php
	}
	
	public function add_filter_by_column_field($columns, $mode, $filter_by, $filter_by_value, $contiom_article_id = ''){
		if($mode == 'advanced'){
			$_mode = "-advance";	
		}
		?>
        <div class="contiom-data-row contiom-single<?php echo $_mode;?>-filter-by">
            <label>Filter by</label>
            <div class="contiom-data-row-field">
                <select name="contiom-single<?php echo $_mode;?>-filter-by[]" <?php echo (!empty($contiom_article_id))?"disabled=\"disabled\"":"";?>>
                    <option value="" >Select Column</option>
                    <?php
					if($mode == 'advanced'){
						if($columns){
							foreach($columns as $column){
								?>
								<option  <?php echo ($filter_by == $column)?"selected=\"selected\"":"";?> value="<?php echo $column;?>"><?php echo $column;?></option>
								<?php
							}
						}
					}else{
						if($columns){
							//print_r($columns);
							foreach($columns->columns as $column){
								?>
								<option  <?php echo ($filter_by == $column->id)?"selected=\"selected\"":"";?> value="<?php echo $column->id;?>"><?php echo $column->name;?></option>
								<?php
							}
						}
					}
                    ?>
                </select>
            </div>
        </div>
        <div class="contiom-data-row contiom-single<?php echo $_mode;?>-filter-by-value">
            <label> <a href="javascript:void(0)" class="single<?php echo $_mode;?>-filter-remove" title="Remove filter"> <span class="dashicons dashicons-remove"></span> </a> </label>
            <div class="contiom-data-row-field">
                <input type="text" name="contiom-single<?php echo $_mode;?>-filter-by-value[]" value="<?php echo $filter_by_value;?>" <?php echo (!empty($contiom_article_id))?"disabled=\"disabled\"":"";?>  />
            </div>
        </div>
        <?php		
	}
	
	public function save_meta_box_data($post_id){
		$fields = array( 'project', 'language', 'template', 'story', 'filter_by', 'filter_by_value', 'search_by', 'search_by_value', 'article');
		foreach ( $fields as $field ) {
			$field_key = "contiom-single-".str_replace('_','-', $field);
        	if ( array_key_exists( $field_key, $_POST ) ) {
				update_post_meta( $post_id, 'contiom_'.$field, sanitize_text_field( $_POST[$field_key] ) );
			}
		}
		
		
		$field_values = array();
		foreach ( $fields as $field ) {
			$field_key = "contiom-single-".str_replace('_','-', $field);
        	if ( array_key_exists( $field_key, $_POST ) ) {
				$field_values[$field] = $_POST[$field_key];
			}
		}
		
		if(isset($_POST['contiom-single-article'])){
			update_post_meta( $post_id, 'contiom_article_id', $_POST['contiom-single-article'] );
			$field_values['article_id'] = $_POST['contiom-single-article'];
		}
		
		update_post_meta( $post_id, 'contiom_data', $field_values );
		
		$advanced_fields = array('project', 'language', 'story', 'filter_by', 'filter_by_value');
		$field_values = array();
		foreach ( $fields as $field ) {
			$field_key = "contiom-single-advance-".str_replace('_','-', $field);
        	if ( array_key_exists( $field_key, $_POST ) ) {
				//contiom_advance_data
				$field_values['advance_'.$field] = $_POST[$field_key];
			}
		}
		
		update_post_meta( $post_id, 'contiom_advance_data', $field_values );
		
		
	}
	
	public function save_meta_box( $post_id ){
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( $parent_id = wp_is_post_revision( $post_id ) ) {
			$post_id = $parent_id;
		}
		
		
		
		
		$content_mode = get_post_meta($post_id, 'contiom_content_mode', true);
		
		if(!$content_mode){
			$this->save_meta_box_data($post_id);	
		}
		
		
		if('simple' == $content_mode){
			if(isset($_POST['contiom-single-auto-update'])){
				update_post_meta($post_id, 'contiom_auto_update', 'on');	
				
				if(isset($_POST['contiom-single-auto-update-settings-type'])){
					$type = $_POST['contiom-single-auto-update-settings-type'];
					update_post_meta($post_id, 'contiom_auto_update_type', $type);	
				}
				
				if(isset($_POST['contiom-single-auto-update-settings-date'])){
					$date =$_POST['contiom-single-auto-update-settings-date'];
					update_post_meta($post_id, 'contiom_auto_update_date', $date);	
				}
				
				if(isset($_POST['contiom-single-auto-update-settings-time'])){
					$time = $_POST['contiom-single-auto-update-settings-time'];
					update_post_meta($post_id, 'contiom_auto_update_time', $time);	
				}
				
				$this->set_next_auto_update_time_for_post($post_id, $type, $date, $time);
				
			}else{
				update_post_meta($post_id, 'contiom_auto_update', 'off');	
				delete_post_meta($post_id, 'contiom_next_auto_update');
			}
			
			
			$contiom_skip_block_updates = isset($_POST['contiom_skip_block_updates'])?$_POST['contiom_skip_block_updates']:array();
			$skip_block_updates = get_post_meta($post_id, 'contiom_skip_block_updates', true);
			if($skip_block_updates){
				foreach($skip_block_updates as $k=>$v){
					if(isset($contiom_skip_block_updates[$v['id']])){
						$skip_block_updates[$k]['skip_update']='on';	
					}else{
						$skip_block_updates[$k]['skip_update']='off';	
					}
				}
			}
			update_post_meta($post_id, 'contiom_skip_block_updates', $skip_block_updates);
			
			
		}
		
		
		
		
		
		
		
		
		if('advance' == $content_mode){
		
			if(isset($_POST['contiom-single-advance-auto-update'])){
				update_post_meta($post_id, 'contiom_advance_auto_update', 'on');
				
				if(isset($_POST['contiom-single-advance-auto-update-settings-type'])){
					$type = $_POST['contiom-single-advance-auto-update-settings-type'];
					update_post_meta($post_id, 'contiom_advance_auto_update_type', $type);	
				}
				
				if(isset($_POST['contiom-single-advance-auto-update-settings-date'])){
					$date = $_POST['contiom-single-advance-auto-update-settings-date'];
					update_post_meta($post_id, 'contiom_advance_auto_update_date', $date);	
				}
				
				if(isset($_POST['contiom-single-advance-auto-update-settings-time'])){
					$time = $_POST['contiom-single-advance-auto-update-settings-time'];
					update_post_meta($post_id, 'contiom_advance_auto_update_time', $time);	
				}
				
				$this->set_next_auto_update_time_for_post($post_id, $type, $date, $time);	
			}else{
				update_post_meta($post_id, 'contiom_advance_auto_update', 'off');	
				delete_post_meta($post_id, 'contiom_next_auto_update');
			}
			
			$contiom_skip_block_updates = isset($_POST['contiom_skip_block_updates_advance'])?$_POST['contiom_skip_block_updates_advance']:array();
			$skip_block_updates = get_post_meta($post_id, 'contiom_skip_block_updates_advance', true);
			if($skip_block_updates){
				foreach($skip_block_updates as $k=>$v){
					if(isset($contiom_skip_block_updates[$v['id']])){
						$skip_block_updates[$k]['skip_update']='on';	
					}else{
						$skip_block_updates[$k]['skip_update']='off';	
					}
				}
			}
			update_post_meta($post_id, 'contiom_skip_block_updates_advance', $skip_block_updates);
			
		}
		
		
		
		
	}
	
	public function register_blocks() {
	
		// Check if Gutenberg is active.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		
	
		// Add block script.
		wp_register_script(
			$this->plugin_name.'-block',
			plugin_dir_url( __FILE__ ) . 'js/block.js',
			array( 'wp-blocks', 'wp-element', 'wp-editor' ),
			$this->version
		);
	
		// Add block style.
		wp_register_style(
			$this->plugin_name.'-block',
			plugin_dir_url( __FILE__ ) . 'css/block.css',
			array(),
			$this->version
		);
	
		// Register block script and style.
		register_block_type( 'contiom/content-block', array(
			'style' => $this->plugin_name.'-block', // Loads both on editor and frontend.
			'editor_script' => $this->plugin_name.'-block', // Loads only on editor.
		) );
	}
	
	public function load_bulk_content_taxonomies(){
		$post_type = $_POST['post_type'];
		ob_start();
		$this->get_post_type_taxonomy($post_type);
		$data = ob_get_clean();
		wp_send_json_success($data);	
	}
	
	public function get_post_type_taxonomy($post_type){
		$args=array(
		  'object_type' => array($post_type),
		  'public'   => true,
		  'publicly_queryable' => true,
		  'show_ui' => true
		);
		
		$taxonomies = get_taxonomies($args,'objects');
		foreach($taxonomies as $taxonomy){
			?>
            <div class="contiom-taxonomy-met-box">
            	<h4><?php echo $taxonomy->label;?></h4>
            	<?php
					if($taxonomy->hierarchical){
						$this->post_categories_meta_box(array(), array('args' =>array('taxonomy' => $taxonomy->name)));		
					}else{
						$this->post_tags_meta_box(array(), array('args' =>array('taxonomy' => $taxonomy->name)));		
					}
				?>
            </div>
            <?php	
		}
	}
	
	public function post_categories_meta_box( $post, $box ) {
		$defaults = array( 'taxonomy' => 'category' );
		if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) ) {
			$args = array();
		} else {
			$args = $box['args'];
		}
		$parsed_args = wp_parse_args( $args, $defaults );
		$tax_name    = esc_attr( $parsed_args['taxonomy'] );
		$taxonomy    = get_taxonomy( $parsed_args['taxonomy'] );
		?>
		<div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv contiom-categorydiv">
			<ul id="<?php echo $tax_name; ?>-tabs" class="category-tabs" style="display: none;">
				<li class="tabs"><a href="#<?php echo $tax_name; ?>-all"><?php echo $taxonomy->labels->all_items; ?></a></li>
			</ul>
	
			<div id="<?php echo $tax_name; ?>-pop" class="tabs-panel" style="display: none;">
				<ul id="<?php echo $tax_name; ?>checklist-pop" class="categorychecklist form-no-clear" >
					<?php $popular_ids = wp_popular_terms_checklist( $tax_name ); ?>
				</ul>
			</div>
            
            <div class="taxonomy-<?php echo $tax_name; ?>-search">
            <label for="taxonomy-<?php echo $tax_name; ?>-search-filter">Search Categories</label>
            <input type="text" id="taxonomy-<?php echo $tax_name; ?>-search-filter" class="contiom-taxonomies-terms-filter" value="" />
            </div>
	
			<div id="<?php echo $tax_name; ?>-all" class="tabs-panel">
				<?php
				$name = ( 'category' === $tax_name ) ? 'post_category' : 'tax_input[' . $tax_name . ']';
				// Allows for an empty term set to be sent. 0 is an invalid term ID and will be ignored by empty() checks.
				echo "<input type='hidden' name='{$name}[]' value='0' />";
				?>
				<ul id="<?php echo $tax_name; ?>checklist" data-wp-lists="list:<?php echo $tax_name; ?>" class="categorychecklist contiom-categorychecklist form-no-clear">
					<?php
					wp_terms_checklist(
						0,
						array(
							'taxonomy'     => $tax_name,
							'popular_cats' => $popular_ids,
						)
					);
					?>
				</ul>
			</div>
		<?php if ( current_user_can( $taxonomy->cap->edit_terms ) ) : ?>
				<div id="<?php echo $tax_name; ?>-adder" class="contiom-taxonomy-add-new-adder wp-hidden-children">
					<a id="<?php echo $tax_name; ?>-add-toggle" href="#<?php echo $tax_name; ?>-add" class="hide-if-no-js taxonomy-add-new contiom-taxonomy-add-new">
						<?php
							/* translators: %s: Add New taxonomy label. */
							printf( __( '+ %s' ), $taxonomy->labels->add_new_item );
						?>
					</a>
					<p id="<?php echo $tax_name; ?>-add" class="category-add wp-hidden-child">
						<label class="screen-reader-text" for="new<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->add_new_item; ?></label>
						<input type="text" name="new<?php echo $tax_name; ?>" id="new<?php echo $tax_name; ?>" class="form-required form-input-tip" value="" aria-required="true" />
						<label class="screen-reader-text" for="new<?php echo $tax_name; ?>_parent">
							<?php echo $taxonomy->labels->parent_item_colon; ?>
						</label>
						<?php
						$parent_dropdown_args = array(
							'taxonomy'         => $tax_name,
							'hide_empty'       => 0,
							'name'             => 'new' . $tax_name . '_parent',
							'orderby'          => 'name',
							'hierarchical'     => 1,
							'show_option_none' => '&mdash; ' . $taxonomy->labels->parent_item . ' &mdash;',
						);
	

						$parent_dropdown_args = apply_filters( 'post_edit_category_parent_dropdown_args', $parent_dropdown_args );
	
						wp_dropdown_categories( $parent_dropdown_args );
						?>
						<input type="button" id="<?php echo $tax_name; ?>-add-submit" data-wp-lists="add:<?php echo $tax_name; ?>checklist:<?php echo $tax_name; ?>-add" class="button category-add-submit" value="<?php echo esc_attr( $taxonomy->labels->add_new_item ); ?>" />
						<?php wp_nonce_field( 'add-' . $tax_name, '_ajax_nonce-add-' . $tax_name, false ); ?>
						<span id="<?php echo $tax_name; ?>-ajax-response"></span>
					</p>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
	
	public function post_tags_meta_box( $post, $box ) {
		$defaults = array( 'taxonomy' => 'post_tag' );
		if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) ) {
			$args = array();
		} else {
			$args = $box['args'];
		}
		$parsed_args           = wp_parse_args( $args, $defaults );
		$tax_name              = esc_attr( $parsed_args['taxonomy'] );
		$taxonomy              = get_taxonomy( $parsed_args['taxonomy'] );
		$user_can_assign_terms = current_user_can( $taxonomy->cap->assign_terms );
		$comma                 = _x( ',', 'tag delimiter' );
		$terms_to_edit         = get_terms_to_edit( $post->ID, $tax_name );
		if ( ! is_string( $terms_to_edit ) ) {
			$terms_to_edit = '';
		}
		?>
	<div class="tagsdiv contiom-tagsdiv" id="<?php echo $tax_name; ?>">
		<div class="jaxtag">
		<div class="nojs-tags hide-if-js">
			<label for="tax-input-<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->add_or_remove_items; ?></label>
			<p><textarea name="<?php echo "tax_input[$tax_name]"; ?>" rows="3" cols="20" class="the-tags" id="tax-input-<?php echo $tax_name; ?>" <?php disabled( ! $user_can_assign_terms ); ?> aria-describedby="new-tag-<?php echo $tax_name; ?>-desc"><?php echo str_replace( ',', $comma . ' ', $terms_to_edit ); // textarea_escaped by esc_attr() ?></textarea></p>
		</div>
		<?php if ( $user_can_assign_terms ) : ?>
		<div class="ajaxtag hide-if-no-js">
			<label class="screen-reader-text" for="new-tag-<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->add_new_item; ?></label>
			<input data-wp-taxonomy="<?php echo $tax_name; ?>" type="text" id="new-tag-<?php echo $tax_name; ?>" name="newtag[<?php echo $tax_name; ?>]" class="newtag form-input-tip" size="16" autocomplete="off" aria-describedby="new-tag-<?php echo $tax_name; ?>-desc" value="" />
			<input type="button" class="button tagadd" value="<?php esc_attr_e( 'Add' ); ?>" />
		</div>
		<p class="howto" id="new-tag-<?php echo $tax_name; ?>-desc"><?php echo $taxonomy->labels->separate_items_with_commas; ?></p>
		<?php elseif ( empty( $terms_to_edit ) ) : ?>
			<p><?php echo $taxonomy->labels->no_terms; ?></p>
		<?php endif; ?>
		</div>
		<ul class="tagchecklist" role="list"></ul>
	</div>
		<?php if ( $user_can_assign_terms ) : ?>
	<p class="hide-if-no-js"><button type="button" class="button-link tagcloud-link" id="link-<?php echo $tax_name; ?>" aria-expanded="false"><?php echo $taxonomy->labels->choose_from_most_used; ?></button></p>
	<?php endif; ?>
		<?php
	}
	
	public function contiom_bulk_content_add_advance_articles(){
		$articles = $_POST['article'];
		$project = $_POST['contiom-single-advance-project'];
		$language = $_POST['contiom-single-advance-language'];
		$story = $_POST['contiom-single-advance-story'];
		$filter_by = $_POST['contiom-single-advance-filter-by'];
		$filter_by_value = $_POST['contiom-single-advance-filter-by-value'];
		
		$post_type = $_POST['contiom-single-content-type'];
		
		$data = array();
		$data['advance_project'] = $project;
		$data['advance_language'] = $language;
		$data['advance_story'] = $story;
		$data['advance_filter_by'] = $filter_by;
		$data['advance_filter_by_value'] = $filter_by_value;
		
		$api     = new Contiom_Api();
		$advanced_records = $api->get_advanced_records($data);
		
		if(empty($advanced_records)){
			wp_send_json_success(array('type' => 'faild'));	
		}
		
		$articles_data = array();
		
		foreach($articles as $article){
			foreach($advanced_records as $record){
				$name = array();
				$id = 0;
				foreach($record->resultValues as $value){
					$name[]=$value->value;
					$id = $value->resultId;	
				}
				
				if($id == $article){
					$articles_data[$article] = array('name' => implode(', ', $name));	
				}
			}
		}
		
		$contiom_data = $data;
		$contiom_data_ids = array();
		
		if(!empty($articles_data)){
			foreach($articles_data as $article_id => $article_data){
				$post_id = $this->get_post_id_from_article_id($article_id);
				if($post_id){
					
				}else{
					$post_data =  array(
						'post_title'    => wp_strip_all_tags( $article_data['name'] ),
						'post_status'   => 'draft',
						'post_type'     => $post_type
					);	
					$post_id = wp_insert_post( $post_data );	
				}
				
				$articles_data[$article_id]['post_id'] = $post_id;
				
				$contiom_data_ids[]=$article_id;
				
				$data['article_id'] = $article_id;
				update_post_meta($post_id, 'contiom_advance_data', $data);
				
				update_post_meta($post_id, 'contiom_article_id', $article_id);
				
				update_post_meta($post_id, 'contiom_advance_article_id', $article_id);

				update_post_meta($post_id, 'contiom_content_mode', 'advance');

				
				if(isset($_POST['contiom-single-auto-update'])){
					update_post_meta($post_id, 'contiom_advance_auto_update', 'on');	
					
					if(isset($_POST['contiom-single-auto-update-settings-type'])){
						$type = $_POST['contiom-single-auto-update-settings-type'];
						update_post_meta($post_id, 'contiom_advance_auto_update_type', $type);	
					}
					
					if(isset($_POST['contiom-single-auto-update-settings-date'])){
						$date = $_POST['contiom-single-auto-update-settings-date'];
						update_post_meta($post_id, 'contiom_advance_auto_update_date', $date);	
					}
					
					if(isset($_POST['contiom-single-auto-update-settings-time'])){
						$time = $_POST['contiom-single-auto-update-settings-time'];
						update_post_meta($post_id, 'contiom_advance_auto_update_time', $time);	
					}
					
					$this->set_next_auto_update_time_for_post($post_id, $type, $date, $time);	
					
				}else{
					update_post_meta($post_id, 'contiom_advance_auto_update', 'off');
					delete_post_meta($post_id, 'contiom_next_auto_update');	
				}
				
				
			}
		}
		//print_r($contiom_data_ids); 
		if(!empty($contiom_data_ids)){
			$contiom_data['ids'] = $contiom_data_ids;
			$articles = $api->get_advanced_text($contiom_data);
			//print_k($articles);
			if(!empty($articles)){
				foreach($articles as $article){
					$id = $article->resultValues[0]->resultId;	
					$post_id = $articles_data[$id]['post_id'];
					$article_html = $this->prepare_html_from_article($article->resultJson, $post_id);	
					
					/*$skip_block_updates = array();
					if(isset($article->resultJson) && !empty($article->resultJson)){
						foreach($article->resultJson as $v){
							$skip_block_updates[]=array('id' => $v->id, 'name' => $v->name, 'skip_update' => 'off');
						}
					}
					
					update_post_meta($post_id, 'contiom_skip_block_updates_advance', $skip_block_updates);*/
										
					$postarr = $_POST;
					
					$this->set_post_terms($post_id, $postarr, $post_type);
					
					$this->record_log('', '', $post_id, 'success', 'bulk_add_advanced');
				}
			}
				
		}
		
		$table = $this->generate_bulk_content_advance_table($advanced_records);
		wp_send_json_success(array('type' => 'table', 'table' => $table));	
		
	}
	
	public function contiom_bulk_content_add_articles(){
		$articles = $_POST['article'];
		$project = $_POST['contiom-single-project'];
		$language = $_POST['contiom-single-language'];
		$template = $_POST['contiom-single-template'];
		$story = $_POST['contiom-single-story'];
		$filter_by = $_POST['contiom-single-filter-by'];
		$filter_by_value = $_POST['contiom-single-filter-by-value'];
		
		$post_type = $_POST['contiom-single-content-type'];
		
		//print_k($_POST);
				
		$data=array();
		$data['project'] = $project;
		$data['language'] = $language;
		$data['template'] = $template;
		$data['story'] = $story;
		$data['filter_by'] = $filter_by;
		$data['filter_by_value'] = $filter_by_value;
		$api     = new Contiom_Api();
		$data_lines = 	$api->get_data_lines($data);
		
		if(empty($data_lines)){
			wp_send_json_success(array('type' => 'faild'));	
		}
		
		$_columns = $_POST['contiom-single-columns'];
		
		$articles_data = array();
		
		
		
		foreach($articles as $article){
			foreach($data_lines as $data_line){
				if($data_line->_id == $article){
					if(property_exists($data_line, 'name')){
						//$d['label'] = $data_line->name;	
						$articles_data[$article] = array('name' => $data_line->name);	
					}else{
						$name = '';
						$columns =	$api->get_template_columns($data['project'], $data['language'], $data['template']);
						if($columns){
							foreach($columns->columns as $column){
								if('' == $name && property_exists($column, 'name')){
									$column_name = $column->name;
									if(property_exists($data_line, $column_name)){
										$name = $data_line->{$column_name};
									}
								}
							}
						}
						$articles_data[$article] = array('name' => $name);	
						
					}
					
				}
			}
		}
		
				
		$contiom_data = $data;
		$contiom_data_ids = array();
		if(!empty($articles_data)){
			foreach($articles_data as $article_id => $article_data){
				
				$post_id = $this->get_post_id_from_article_id($article_id);
				if($post_id){
					
				}else{
					$post_data =  array(
						'post_title'    => wp_strip_all_tags( $article_data['name'] ),
						'post_status'   => 'draft',
						'post_type'     => $post_type
					);	
					$post_id = wp_insert_post( $post_data );
				}
				$articles_data[$article_id]['post_id'] = $post_id;
				
				$contiom_data_ids[]=$article_id;
				
				$data['article_id'] = $article_id;
				update_post_meta($post_id, 'contiom_data', $data);
				
				update_post_meta($post_id, 'contiom_article_id', $article_id);
				
				
				
				update_post_meta($post_id, 'contiom_content_mode', 'simple');
				
				if(isset($_POST['contiom-single-auto-update'])){
					update_post_meta($post_id, 'contiom_auto_update', 'on');	
					
					if(isset($_POST['contiom-single-auto-update-settings-type'])){
						$type = $_POST['contiom-single-auto-update-settings-type'];
						update_post_meta($post_id, 'contiom_auto_update_type', $type);	
					}
					
					if(isset($_POST['contiom-single-auto-update-settings-date'])){
						$date = $_POST['contiom-single-auto-update-settings-date'];
						update_post_meta($post_id, 'contiom_auto_update_date', $date);	
					}
					
					if(isset($_POST['contiom-single-auto-update-settings-time'])){
						$time = $_POST['contiom-single-auto-update-settings-time'];
						update_post_meta($post_id, 'contiom_auto_update_time', $time);	
					}
					
					$this->set_next_auto_update_time_for_post($post_id, $type, $date, $time);	
				}else{
					update_post_meta($post_id, 'contiom_auto_update', 'off');	
					delete_post_meta($post_id, 'contiom_next_auto_update');
				}
				
				
			}
		}
		
		if(!empty($contiom_data_ids)){
			$contiom_data['ids'] = $contiom_data_ids;
			$articles = $api->get_articles($contiom_data);
			if(!empty($articles)){
				foreach($articles as $article){
					$post_id = $articles_data[$article->recordId]['post_id'];
					$article_html = $this->prepare_html_from_article($article->resultJson, $post_id);	
					
					/*$skip_block_updates = array();
					if(!empty($article->resultJson) && is_array($article->resultJson)){
						foreach($article->resultJson as $v){
							$skip_block_updates[]=array('id' => $v->id, 'name' => $v->name, 'skip_update' => 'off');
						}
					}
					update_post_meta($post_id, 'contiom_skip_block_updates', $skip_block_updates);*/
					
					
					
					$postarr = $_POST;
					
					$this->set_post_terms($post_id, $postarr, $post_type);
					
					$this->record_log('', '', $post_id, 'success', 'bulk_add');
					
				}
			}
				
		}
		
		$template_columns = 	$api->get_template_columns($data['project'], $data['language'], $data['template']);
		
		$table = $this->generate_bulk_content_table($data_lines, $template_columns, $_columns);
		wp_send_json_success(array('type' => 'table', 'table' => $table));	
				
	}
	
	public function set_post_terms($post_id, $postarr, $post_type){
		
		if ( isset($postarr['post_category']) && ! empty( $postarr['post_category'] ) ) {
			$post_category = array_filter( $postarr['post_category'] );
			
			if ( empty( $post_category ) || 0 === count( $post_category ) || ! is_array( $post_category ) ) {
				// 'post' requires at least one category.
				if ( 'post' === $post_type && 'auto-draft' !== $post_status ) {
					$post_category = array( get_option( 'default_category' ) );
				} else {
					$post_category = array();
				}
			}
			
		}
		
		if ( is_object_in_taxonomy( $post_type, 'category' ) ) {
			wp_set_post_categories( $post_id, $post_category );
		}
		
		if ( isset( $postarr['tags_input'] ) && is_object_in_taxonomy( $post_type, 'post_tag' ) ) {
			wp_set_post_tags( $post_id, $postarr['tags_input'] );
		}
					
		
		// Add default term for all associated custom taxonomies.
		foreach ( get_object_taxonomies( $post_type, 'object' ) as $taxonomy => $tax_object ) {

			if ( ! empty( $tax_object->default_term ) ) {

				// Filter out empty terms.
				if ( isset( $postarr['tax_input'][ $taxonomy ] ) && is_array( $postarr['tax_input'][ $taxonomy ] ) ) {
					$postarr['tax_input'][ $taxonomy ] = array_filter( $postarr['tax_input'][ $taxonomy ] );
				}

				// Passed custom taxonomy list overwrites the existing list if not empty.
				$terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
				if ( ! empty( $terms ) && empty( $postarr['tax_input'][ $taxonomy ] ) ) {
					$postarr['tax_input'][ $taxonomy ] = $terms;
				}

				if ( empty( $postarr['tax_input'][ $taxonomy ] ) ) {
					$default_term_id = get_option( 'default_term_' . $taxonomy );
					if ( ! empty( $default_term_id ) ) {
						$postarr['tax_input'][ $taxonomy ] = array( (int) $default_term_id );
					}
				}
			}
		}
		
		if ( ! empty( $postarr['tax_input'] ) ) {
			foreach ( $postarr['tax_input'] as $taxonomy => $tags ) {
				$taxonomy_obj = get_taxonomy( $taxonomy );
	
				if ( ! $taxonomy_obj ) {
					/* translators: %s: Taxonomy name. */
					_doing_it_wrong( __FUNCTION__, sprintf( __( 'Invalid taxonomy: %s.' ), $taxonomy ), '4.4.0' );
					continue;
				}
	
				// array = hierarchical, string = non-hierarchical.
				if ( is_array( $tags ) ) {
					$tags = array_filter( $tags );
				}
	
				if ( current_user_can( $taxonomy_obj->cap->assign_terms ) ) {
					wp_set_post_terms( $post_id, $tags, $taxonomy );
				}
			}
		}
	}

	public function set_next_auto_update_time_for_post($post_id, $type, $date, $time){
		$current_time = current_time( 'timestamp' );
		if( 'daily' == $type){
			$next_update_time = strtotime(current_time( 'Y-m-d ' ).$time);
			if($current_time > $next_update_time){
				$next_update_time = $next_update_time+86400;
				update_post_meta($post_id, 'contiom_next_auto_update', $next_update_time);
			}else{
				update_post_meta($post_id, 'contiom_next_auto_update', $next_update_time);
			}
		}elseif('weekly' == $type){
			$_date = '';
			$days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
			$day_number = isset($days[$date])?$days[$date]:0;
			$today_date = date('Y-m-d', $current_time);
			$dayofweek = date('w', strtotime($today_date));
		    $_date    = date('Y-m-d', strtotime(($day_number - $dayofweek).' day', strtotime($today_date)));
			if(!$_date) $_date = $today_date;
			$next_update_time = strtotime($_date.' '.$time);
			if($current_time > $next_update_time){
				$next_update_time = strtotime(date("Y-m-d H:i:s", $next_update_time) . " +1 week");
				update_post_meta($post_id, 'contiom_next_auto_update', $next_update_time);
			}else{
				update_post_meta($post_id, 'contiom_next_auto_update', $next_update_time);
			}
		}elseif('monthly' == $type){
			if(absint($date) <=0) $date = 1;
			$next_update_time = strtotime(date('Y-m', $current_time).'-'.$date.' '.$time);	
			if($current_time > $next_update_time){
				$next_update_time = strtotime(date("Y-m-d H:i:s", $next_update_time) . " +1 month");
				update_post_meta($post_id, 'contiom_next_auto_update', $next_update_time);
			}else{
				update_post_meta($post_id, 'contiom_next_auto_update', $next_update_time);
			}
		}
	}
	
	public function cron_update_posts(){
		
		$contiom_content_types_auto_update = get_option('contiom_content_types_auto_update');
		
		$current_time = current_time( 'timestamp' );
		$args = array(
			'post_status' => 'any',
		    'meta_query' => array(
		   	'relation' => 'AND',
			   array(
				   'key' => 'contiom_next_auto_update',
				   'value' => $current_time,
				   'compare' => '<',
			   ),
			   array(
			   	   'relation' => 'OR',
				   array(
					   'key' => 'contiom_auto_update',
					   'value' => 'on',
					   'compare' => '=',
				   ),
				   array(
					   'key' => 'contiom_advance_auto_update',
					   'value' => 'on',
					   'compare' => '=',
				   )
				)
		   )
		);
		
		if($contiom_content_types_auto_update && !empty($contiom_content_types_auto_update) ){
			$args['post_type'] = $contiom_content_types_auto_update;
		}else{
			return;	
		}
		
		global $wpdb;
		$query = new WP_Query($args);
		while ( $query->have_posts() ) {
			$query->the_post();
			//echo '<li>' . get_the_id().get_the_title() . '</li>';
			$post_id = get_the_id();
			$status = $this->update_contiom_post_content($post_id);
			
			$status_name = (false == $status )?"faild":"success";
			
			$this->record_log('', '', $post_id, $status_name, 'auto_update');
			
			update_post_meta($post_id, 'contiom_last_update', current_time('timestamp'));
			$content_mode = get_post_meta($post_id, 'contiom_content_mode', true);
			if('simple' == $content_mode){
				$type = get_post_meta($post_id, 'contiom_auto_update_type', true);	
				$date = get_post_meta($post_id, 'contiom_auto_update_date', true);	
				$time = get_post_meta($post_id, 'contiom_auto_update_time', true);
				$this->set_next_auto_update_time_for_post($post_id, $type, $date, $time);
			}else{
				$type = get_post_meta($post_id, 'contiom_advance_auto_update_type', true);	
				$date = get_post_meta($post_id, 'contiom_advance_auto_update_date', true);	
				$time = get_post_meta($post_id, 'contiom_advance_auto_update_time', true);
				$this->set_next_auto_update_time_for_post($post_id, $type, $date, $time);	
			}
			
		}
		wp_reset_postdata();
		//die();		
	}
	
	public function record_log($name, $title, $post_id, $status, $type){
		global $wpdb;
		$table_name = $wpdb->prefix . 'contiom_log';	
		$wpdb->insert( 
			$table_name, 
			array( 
				'update_time' => current_time( 'mysql' ), 
				'name' => '', 
				'title' => '',
				'post_id' => $post_id,
				'status' => $status,
				'log_type' => $type
			)
		);
		
		update_option('aa_dsdsdd', $wpdb->last_error);
	}

}