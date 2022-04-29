<?php
/**
 * The API functionality of the plugin.
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
class Contiom_Api {

	/**
	 * The Api Key.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $api_key    The Api Key.
	 */
	public $api_key;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	/**
	 * Verify Api key.
	 *
	 * @since    1.0.0
	 * @param      string $api_key       The Api Key.
	 * @return      string $api_key       The Api Key.
	 */
	public function verify_api_key( $api_key ) {
		$api_url  = 'https://demoapi.contiom.com/api/VerifyKey';
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => array(
				'apiPublicKey' => $api_key,
			),
			'headers'     => array( 'Content-Type: application/x-www-form-urlencoded' ),
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			$message = esc_html__( 'Sorry We are not Verify APi Key, Please try again.', 'contiom' );
			$status  = false;
		} else {
			$data = json_decode( $response['body'] );
			if ( true != $data->result ) {
				$message = esc_html__( 'Please Enter Valid API Key.', 'contiom' );
				$status  = false;
			} else {
				$message = '';
				$status  = true;
			}
		}
		$data = array(
			'status'  => $status,
			'message' => $message,
		);
		return $data;
	}
	
	public function get_api_key(){
		return get_option( 'contiom_api_key' );
	}
	
	public function get_projects($api_key = false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/Projects';
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => array(
				'apiPublicKey' => $api_key,
			),
			'headers'     => array( 'Content-Type: text/plain' ),
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );	
		
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_languages($project, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		
		$args = array('ProjectId' => $project);
		
		$api_url  = 'https://demoapi.contiom.com/api/Languages';
		
		$api_url = add_query_arg($args, $api_url);
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => array(
				'apiPublicKey' => $api_key,
			),
			'headers'     => array( 'Content-Type: text/plain' ),
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );
		
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_advance_languages($project, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		
		$args = array('ProjectId' => $project);
		
		$api_url  = 'https://demoapi.contiom.com/api/Languages';
		
		$api_url = add_query_arg($args, $api_url);
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => array(
				'apiPublicKey' => $api_key,
			),
			'headers'     => array( 'Content-Type: text/plain' ),
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );
		
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_templates($project, $language, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/Templates';
		
		//$args = array('ProjectId' => strval($project), 'Language' => strval($language));
		//$api_url = add_query_arg($args, $api_url);
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => array(
				'ProjectId' => 	strval($project), 
				'Language' => strval($language),
				'apiPublicKey' => $api_key,
			),
			'headers'     => array( 'Content-Type: multipart/form-data' ),	
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );
				
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_stories($project, $language, $template='', $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/Stories';
		
		//$args = array('ProjectId' => strval($project), 'Language' => strval($language));
		//$api_url = add_query_arg($args, $api_url);
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => array(
				'ProjectId' => 	strval($project), 
				/*'Language' => strval($language),*/
				/*'Template' => strval($template),*/
				'apiPublicKey' => $api_key,
			),
			'headers'     => array( 'Content-Type: multipart/form-data' ),	
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );
				
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_advance_stories($project, $language, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/Stories';
		
		//$args = array('ProjectId' => strval($project), 'Language' => strval($language));
		//$api_url = add_query_arg($args, $api_url);
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => array(
				'ProjectId' => 	strval($project), 
				/*'Language' => strval($language),*/
				/*'Template' => strval($template),*/
				'apiPublicKey' => $api_key,
			),
			'headers'     => array( 'Content-Type: multipart/form-data' ),	
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );
				
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_template_columns($project, $language, $template, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/Template';
		
		//$args = array('ProjectId' => strval($project), 'Language' => strval($language));
		//$api_url = add_query_arg($args, $api_url);
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => array(
				/*'ProjectId' => 	strval($project), */
				/*'Language' => strval($language),*/
				/*'Template' => strval($template),*/
				'TemplateId' => strval($template),
				'apiPublicKey' => $api_key,
			),
			'headers'     => array( 'Content-Type: multipart/form-data' ),	
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );
				
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_advance_columns($project, $language, $story, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/AdvancedFilter';
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => json_encode(array(
				'advancedStoryId' => $story,
				'apiPublicKey' => $api_key,
			)),
			'headers'     => array( 'Content-Type' => 'application/json-patch+json' ),	
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );
						
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_template_column_values($project, $language, $template, $story, $column, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/Template';
		
		//$args = array('ProjectId' => strval($project), 'Language' => strval($language));
		//$api_url = add_query_arg($args, $api_url);
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => array(
				/*'ProjectId' => 	strval($project), */
				/*'Language' => strval($language),*/
				/*'Template' => strval($template),*/
				'TemplateId' => strval($template),
				'apiPublicKey' => $api_key,
			),
			'headers'     => array( 'Content-Type: multipart/form-data' ),	
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );
				
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	
	public function get_data_line($data, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/DataLine';
		
		$_data = array(
			'templateId' => strval($data['template']),
			'objectId'    => strval($data['article_id']),
			'apiPublicKey' => $api_key,
		);
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => json_encode($_data), 
			'headers'     => array( 'Content-Type' => 'application/json-patch+json' ),	
			'cookies'     => array(),
		);
		
		//print_k($options);
		
		$response = wp_remote_post( $api_url, $options );
		
		//print_k($response);
		
		//die();
				
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return false;
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	
	public function get_data_lines($data, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/DataLines';
		
		$_data = array(
			'templateId' => strval($data['template']),
			'storyId'    => strval($data['story']),
			'language'   => strval($data['language']),
			'apiPublicKey' => $api_key,
		);
		
		/*$columnFilters = array();
		if(!empty($data['filter_by']) && !empty($data['filter_by_value'])){
			$columnFilters[] = array('columnId' => $data['filter_by'], 'columnValue' => $data['filter_by_value']);
		}
		
		if(!empty($data['search_by']) && !empty($data['search_by_value'])){
			$columnFilters[] = array('columnId' => $data['search_by'], 'columnValue' => $data['search_by_value']);
		}*/
		
		$columnFilters = array();
		if(!empty($data['filter_by']) && !empty($data['filter_by_value'])){
			foreach($data['filter_by'] as $k=>$v){
				if(!empty($v) && !empty($data['filter_by_value'][$k])){
					$columnFilters[] = array('columnId' => $v, 'columnValue' => $data['filter_by_value'][$k]);	
				}
			}
		}
		
		
		if(!empty($columnFilters)){
			$_data['columnFilters'] = $columnFilters;	
		}
		
		//$_data = "{\"templateId\":101,\"storyId\":42,\"language\":\"en\",\"columnFilters\":[{\"columnId\":3723,\"columnValue\":\"Botanik Park\"},{\"columnId\":3842,\"columnValue\":\"OPERATIONAL\"}],\"apiPublicKey\":\"rvKuvEkhbSAzHj+gbSKGvlLZJ1Bh5zD2UG/8+fK1ugY=\"}";
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => json_encode($_data), 
			'headers'     => array( 'Content-Type' => 'application/json-patch+json' ),	
			'cookies'     => array(),
		);
		
		//print_k($options);
		
		$response = wp_remote_post( $api_url, $options );
		
		//print_k($response);
		
		//die();
				
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_advanced_records($data, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		
		//$logger = new WC_Logger();
		
		$api_url  = 'https://demoapi.contiom.com/api/AdvancedRecords';
		
		$_data = array(
			'projectId' => strval($data['advance_project']),
			'advancedStoryId'    => strval($data['advance_story']),
			'language'   => strval($data['advance_language']),
			'apiPublicKey' => $api_key,
		);
		
		$columnFilters = array();
		if(!empty($data['advance_filter_by']) && !empty($data['advance_filter_by_value'])){
			foreach($data['advance_filter_by'] as $k=>$v){
				if(!empty($v) && !empty($data['advance_filter_by_value'][$k])){
					$columnFilters[] = array('name' => $v, 'value' => $data['advance_filter_by_value'][$k]);	
				}
			}
		}
		
		
		if(!empty($columnFilters)){
			$_data['filters'] = $columnFilters;	
		}
		
		/*ob_start();
		print_r($_data);
		$d = ob_get_clean();
		
		$logger->debug( $d, array( 'source' => 'my-extension' ) );*/
		
		
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => json_encode($_data), 
			'headers'     => array( 'Content-Type' => 'application/json-patch+json' ),	
			'cookies'     => array(),
		);
		
		/*ob_start();
		print_r($options);
		$d = ob_get_clean();
		
		$logger->debug( $d, array( 'source' => 'my-extension' ) );*/
		
		$response = wp_remote_post( $api_url, $options );
		
		//print_k($options);
		//print_k($response);
				
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_advanced_text($data, $api_key=false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/AdvancedText';
		
		$_data = array(
			'projectId' => strval($data['advance_project']),
			'storyId'    => strval($data['advance_story']),
			'language'   => strval($data['advance_language']),
			'apiPublicKey' => $api_key,
		);
		
		if(!empty($data['ids'])){
			$_data['ids'] = $data['ids'];
		}
		
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => json_encode($_data), 
			'headers'     => array( 'Content-Type' => 'application/json-patch+json' ),	
			'cookies'     => array(),
		);
		$response = wp_remote_post( $api_url, $options );
		
				
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
	
	public function get_articles($data, $api_key = false){
		if(false == $api_key){
			$api_key = $this->get_api_key();	
		}
		$api_url  = 'https://demoapi.contiom.com/api/NoramlText';
				
		$body = array(
				'templateId' => $data['template'],
				'storyId'    => $data['story'],
				'language'   => strval($data['language']),
				'apiPublicKey' => $api_key,
			);
			
		if(!empty($data['ids'])){
			$body['ids'] = $data['ids'];	
		}elseif(isset($data['article'])){
			$body['ids'] = array($data['article']);	
		}
		
		
		
		$options  = array(
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'body'        => json_encode($body),
			'headers'     => array( 'Content-Type' => 'application/json-patch+json' ),	
			'cookies'     => array(),
		);
		
		
		$response = wp_remote_post( $api_url, $options );
		
		if ( is_array( $response ) && is_wp_error( $response ) ) {
			return esc_html__( 'Please try again.', 'contiom' );
		} else {
			$data = json_decode( $response['body'] );
			if ( $data->result ) {
				return $data->result;
			}
		}
	}
}

add_action('init', function(){
	if(isset($_GET['test1'])){
	$data=array();
	$data['project'] = 40;
	$data['language'] = 'en';
	$data['template'] = 101;
	$data['story'] = 42;
	$data['ids'] = array('6112b3b0f11ae4ef657fb3db');
	$api     = new Contiom_Api();
	//$stories = 	$api->get_template_columns($project, $language, $template);
	$article = $api->get_articles($data);
	print_k($article);	
	die();
	}
});

add_action('init', function(){
if(isset($_GET['test'])){
	$post_id = 38;
	$post = get_post($post_id);
	$blocks = parse_blocks($post->post_content);
	print_k($blocks);
	foreach ($blocks as $block) {
		echo render_block($block);
	}	
	die();
}
});

add_action('init', function(){
	if(isset($_GET['test2'])){
		$api     = new Contiom_Api();
		$data=array();
		$data['project'] = 76;
		$data['language'] = 'en';
		$data['template'] = 154;
		$data['story'] = 56;
		//$data['search_by'] = 3723;
		//$data['search_by_value'] = "Botanik Park";
		//$data['filter_by'] = 3842;
		//$data['filter_by_value'] = "OPERATIONAL";
		
		$stories = 	$api->get_template_columns(76, 'en', 154);
		
		//$data['template'] = $_GET['template'];
		//$data['story'] = $_GET['story'];
		
		$data_lines = $api->get_data_lines($data);
		echo count($data_lines);
		print_k($stories);
		print_k($data_lines);	
		die();
	}
});

add_action('init', function(){
if(isset($_GET['get_advance_columns'])){
	$api     = new Contiom_Api();
		$data_lines = $api->get_advance_columns(75, 'en', 54);
		//echo count($data_lines);
		print_k($data_lines);	
		die();
}
});

add_action('init', function(){
	if(isset($_GET['get_advanced_records'])){
		$api     = new Contiom_Api();
		$data = array();
		$data['advance_project'] = 35;
		$data['advance_language'] = 'en';
		$data['advance_story'] = 39;
		/*$data['advance_filter_by'] = array('country');
		$data['advance_filter_by_value'] = array('United Kingdoms');*/
		$advanced_records = $api->get_advanced_records($data);
		print_k($advanced_records);
		
		/*if($advanced_records){
			$found_record = array();
			foreach($advanced_records as $record){
				if($record->resultValues && !empty($record->resultValues) && $record->exportedJson && !empty($record->exportedJson) && empty($found_record)){
					$found_record = $record->resultValues;
				}
			}
			print_k($found_record);
			if(!empty($found_record)){
				$id = $found_record[0]->resultId;	
				$data['ids'] = array($id);
				$advanced_data = $api->get_advanced_text($data);
				print_k($advanced_data[0]->resultJson);
				
			}else{
				echo 'not found';	
			}
			
			
				
			
		}*/
		//echo count($data_lines);
		//print_k($data_lines);	
		die();
	}
});