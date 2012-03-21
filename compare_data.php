<?php
class WOO_Compare_Data{
	function install_database(){
		global $wpdb;
		$table_woo_compare_fields = $wpdb->prefix. "woo_compare_fields";
		if($wpdb->get_var("SHOW TABLES LIKE '$table_woo_compare_fields'") != $table_woo_compare_fields){
			$sql = "CREATE TABLE IF NOT EXISTS `{$table_woo_compare_fields}` (
				  `id` int(11) NOT NULL auto_increment,
				  `field_name` varchar(250) NOT NULL,
				  `field_key` varchar(250) NOT NULL,
				  `field_type` varchar(250) NOT NULL,
				  `default_value` text NOT NULL,
				  `field_unit` varchar(250) NOT NULL,
				  `field_description` text NOT NULL,
				  `field_order` int(11) NOT NULL,
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}
	
	function get_row($id, $where='', $output_type='OBJECT'){
		global $wpdb;
		$table_name = $wpdb->prefix. "woo_compare_fields";
		if(trim($where) != '')
			$where = ' AND '.$where;
		$result = $wpdb->get_row("SELECT * FROM {$table_name} WHERE id='$id' {$where}", $output_type);
		return $result;
	}
	
	function get_maximum_order($where=''){
		global $wpdb;
		$table_name = $wpdb->prefix . "woo_compare_fields";
		if(trim($where) != '')
			$where = " WHERE {$where} ";
		$maximum = $wpdb->get_var("SELECT MAX(field_order) FROM {$table_name} {$where}");
		
		return $maximum;
	}
	
	function get_count($where=''){
		global $wpdb;
		$table_name = $wpdb->prefix . "woo_compare_fields";
		if(trim($where) != '')
			$where = " WHERE {$where} ";
		$count = $wpdb->get_var("SELECT COUNT(id) FROM {$table_name} {$where}");
		
		return $count;
	}
	
	function get_results($where='', $order='', $limit ='', $output_type='OBJECT'){
		global $wpdb;
		$table_name = $wpdb->prefix . "woo_compare_fields";
		if(trim($where) != '')
			$where = " WHERE {$where} ";
		if(trim($order) != '')
			$order = " ORDER BY {$order} ";
		if(trim($limit) != '')
			$limit = " LIMIT {$limit} ";
		$result = $wpdb->get_results("SELECT * FROM {$table_name} {$where} {$order} {$limit}", $output_type);
		return $result;
	}
	
	function insert_row($args){
		global $wpdb;
		extract($args);
		$table_name = $wpdb->prefix. "woo_compare_fields";
		$field_name = strip_tags(addslashes($field_name));
		$default_value = strip_tags(addslashes($default_value));
		$field_unit = strip_tags(addslashes($field_unit));
		$field_description = strip_tags(addslashes($field_description));
		$field_order = WOO_Compare_Data::get_maximum_order();
		$field_key = sanitize_title( $field_key, '' );
		if(trim($field_key) == '') {
			$query = $wpdb->query("INSERT INTO {$table_name}(field_name, field_type, default_value, field_unit, field_description, field_order) VALUES('$field_name', '$field_type', '$default_value', '$field_unit', '$field_description', '$field_order')");
			if($query){
				$field_id = $wpdb->insert_id;
				$field_key = 'field-'.$field_id;
				WOO_Compare_Data::update_field_key($field_id, $field_key);
				return $field_id;
			}else{
				return false;
			}
		}else{
			if(WOO_Compare_Data::check_field_key($field_key)){
				$query = $wpdb->query("INSERT INTO {$table_name}(field_name, field_key, field_type, default_value, field_unit, field_description, field_order) VALUES('$field_name', '$field_key', '$field_type', '$default_value', '$field_unit', '$field_description', '$field_order')");
				if($query){
					$field_id = $wpdb->insert_id;
					return $field_id;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}
	
	function update_row($args){
		global $wpdb;
		extract($args);
		$table_name = $wpdb->prefix. "woo_compare_fields";
		$field_name = strip_tags(addslashes($field_name));
		$default_value = strip_tags(addslashes($default_value));
		$field_unit = strip_tags(addslashes($field_unit));
		$field_description = strip_tags(addslashes($field_description));
		$field_key = sanitize_title( $field_key, '' );
		if(trim($field_key) == '') {
			$field_key = 'field-'.$field_id;
			$query = $wpdb->query("UPDATE {$table_name} SET field_name='$field_name', field_key='$field_key', field_type='$field_type', default_value='$default_value', field_unit='$field_unit', field_description='$field_description' WHERE id='$field_id'");
			return $query;
		}else{
			if(WOO_Compare_Data::check_field_key_for_update($field_id, $field_key)){
				$query = $wpdb->query("UPDATE {$table_name} SET field_name='$field_name', field_key='$field_key', field_type='$field_type', default_value='$default_value', field_unit='$field_unit', field_description='$field_description' WHERE id='$field_id'");
				return $query;
			}else{
				return false;	
			}
		}
		
	}
	
	function update_field_key($field_id, $field_key){
		global $wpdb;
		$table_name = $wpdb->prefix. "woo_compare_fields";
		$query = $wpdb->query("UPDATE {$table_name} SET field_key='$field_key' WHERE id='$field_id'");
		return $query;
	}
	
	function update_items_order($item_orders=array()){
		if(is_array($item_orders) && count($item_orders) > 0){
			foreach($item_orders as $field_id => $field_order){
				WOO_Compare_Data::update_order($field_id, $field_order);
			}
		}
	}
	
	function update_order($field_id, $field_order=0){
		global $wpdb;
		$table_name = $wpdb->prefix. "woo_compare_fields";
		$query = $wpdb->query("UPDATE {$table_name} SET field_order='$field_order' WHERE id='$field_id'");
		return $query;
	}
	
	function delete_rows($items=array()){
		if(is_array($items) && count($items) > 0){
			foreach($items as $field_id){
				WOO_Compare_Data::delete_row($field_id);
			}
		}
	}
	
	function delete_row($field_id){
		global $wpdb;
		$table_name = $wpdb->prefix. "woo_compare_fields";
		$result = $wpdb->query("DELETE FROM {$table_name} WHERE id='{$field_id}'");
		return $result;
	}
	
	function check_field_key($field_key){
		$count = WOO_Compare_Data::get_count("field_key='$field_key'");
		if($count > 0) return false;
		else return true;
	}
	
	function check_field_key_for_update($field_id, $field_key){
		$count = WOO_Compare_Data::get_count("id!='$field_id' AND field_key='$field_key'");
		if($count > 0) return false;
		else return true;
	}
}
?>