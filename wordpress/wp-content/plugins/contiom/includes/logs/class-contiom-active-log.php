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
 
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
 
class Contiom_Active_Log extends WP_List_Table{
	
	private $current_page;
	
	public function prepare_items(){
		global $wpdb;
		$this->current_page   = ( ! empty( $this->input_fields['paged'] ) ) ? $this->input_fields['paged'] : 1;
		
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();

        $perPage = 20;
        $currentPage = $this->get_pagenum();
		
		
		$query_count = "SELECT COUNT(id) FROM {$wpdb->prefix}contiom_log";
		$totalItems = $wpdb->get_var( $query_count );
		
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
	
	public function get_columns(){
        $columns = array(
            'title'       => 'Title',
            'post_link'   => 'Post Link',
            'last_update' => 'Last Update',
            'status'      => 'Status',
			'log_type'      => 'Type',
        );

        return $columns;
    }
	
	public function get_hidden_columns(){
        return array();
    }


    public function get_sortable_columns(){
        return array('title' => array('title', false));
    }
	
	private function table_data(){
		
		global $wpdb;
		$per_page  = 20;
		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}
		
		$query = "select * from ".$wpdb->prefix."contiom_log ORDER BY id DESC LIMIT {$offset} , {$per_page} ";
		$data = array();
		$results = $wpdb->get_results($query);
		if($results){
			foreach($results as $r){
				$data[]=array('id' => $r->post_id, 'last_update' => $r->update_time, 'status' => $r->status, 'log_type' => $r->log_type);	
			}
		}
		
		return $data;
	}
	
	public function column_default( $item, $column_name ){
        switch( $column_name ) {
            case 'title':
				return get_the_title($item['id']);
            case 'post_link':
				$link = get_edit_post_link($item['id'], 'edit'); 
				return '<a href="'.$link.'">'.$link.'</a>';
            case 'last_update':
				return $item['last_update'];
            case 'status':
                return $item['status'];
			case 'log_type':
                return $item['log_type'];	

            default:
                return print_r( $item, true ) ;
        }
    }
	
}