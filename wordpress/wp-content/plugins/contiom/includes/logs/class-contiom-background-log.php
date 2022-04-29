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
 
class Contiom_Background_Log extends WP_List_Table{
	
	private $current_page;
	
	public function prepare_items(){
		
		$this->current_page   = ( ! empty( $this->input_fields['paged'] ) ) ? $this->input_fields['paged'] : 1;
		
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();

        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
	
	public function get_columns(){
        $columns = array(
            'title'       => 'Title',
            'post_link'   => 'Post Link',
            'last_update' => 'Last Update',
            'status'      => 'Status',
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
		$contiom_content_types_auto_update = get_option('contiom_content_types_auto_update');
		$current_time = current_time( 'timestamp' );
		$args = array(
			'post_status' => 'any', 
		    'meta_query' => array(
		   	'relation' => 'AND',
			   array(
				   'key' => 'contiom_next_auto_update',
				   'value' => $current_time,
				   'compare' => '>',
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
		
		$data = array();
		$query = new WP_Query($args);
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_id();
			$data[]=array('id' => $post_id);
		}
		wp_reset_postdata();
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
				$last_update = get_post_meta($item['id'], 'contiom_last_update', true);
				return ($last_update)?date('Y-m-d H:i:s', $last_update):'';
            case 'status':
                return get_post_status($item[ 'id' ]);

            default:
                return print_r( $item, true ) ;
        }
    }
	
}