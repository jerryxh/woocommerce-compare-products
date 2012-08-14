<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
/**
 * WC Compare Upgrade
 *
 * Table Of Contents
 *
 * upgrade_version_1_0_1()
 * upgrade_version_2_0()
 * upgrade_version_2_0_1()
 */					
class WC_Compare_Upgrade{
	function upgrade_version_1_0_1(){
		WC_Compare_Categories_Data::install_database();
		WC_Compare_Categories_Fields_Data::install_database();
	}
	
	function upgrade_version_2_0() {
		global $wpdb;
		$sql = "ALTER TABLE ". $wpdb->prefix . "woo_compare_fields CHANGE `field_name` `field_name` blob NOT NULL";
		$wpdb->query($sql);
		
		$sql = "ALTER TABLE ". $wpdb->prefix . "woo_compare_fields CHANGE `field_unit` `field_unit` blob NOT NULL";
		$wpdb->query($sql);
		
		$sql = "ALTER TABLE ". $wpdb->prefix . "woo_compare_fields CHANGE `field_description` `field_description` blob NOT NULL";
		$wpdb->query($sql);
		
		$sql = "ALTER TABLE ". $wpdb->prefix . "woo_compare_categories CHANGE `category_name` `category_name` blob NOT NULL";
		$wpdb->query($sql);
		
		$sql = "ALTER TABLE ". $wpdb->prefix . "woo_compare_fields CHANGE `default_value` `default_value` blob NOT NULL";
		$wpdb->query($sql);
		
		WC_Compare_Categories_Data::auto_add_master_category();
		WC_Compare_Data::add_features_to_master_category();
		WC_Compare_Functions::auto_assign_master_category_to_all_products();
	}
	
	function upgrade_version_2_0_1() {
		global $wpdb;
		$collate = '';
		if ( $wpdb->supports_collation() ) {
			if( ! empty($wpdb->charset ) ) $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			if( ! empty($wpdb->collate ) ) $collate .= " COLLATE $wpdb->collate";
		}
		$sql = "ALTER TABLE ".$wpdb->prefix . "woo_compare_fields $collate";
		$wpdb->query($sql);
		
		$sql = "ALTER TABLE ".$wpdb->prefix . "woo_compare_categories $collate";
		$wpdb->query($sql);
		
		$sql = "ALTER TABLE ".$wpdb->prefix . "woo_compare_cat_fields $collate";
		$wpdb->query($sql);
	}
}
?>
