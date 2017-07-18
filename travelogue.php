<?php
/*
Plugin Name: Travelogue
Plugin URI: https://www.themocity.com/plugins/travelogue
Description: Nifty plugin for travel bloggers. Allows you to add geolocation information to posts and displays the most blogged about cities in a widget.
Version: 0.2.1
Author: Themocity
Author URI: https://www.themocity.com/
License: MIT License 

Copyright (C) 2014 Themocity

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/



if(!class_exists('travelogue_plugin')) {

	class travelogue_plugin {
		
		const travelogue_db_version = "1.0"; 
		
		
		public function __construct() {
			
			require_once(sprintf('%s/inc/assign-places.php', dirname(__FILE__)));
			$this->assign_place = new travelogue_Assign_Place();
			
			require_once(sprintf('%s/inc/custom-query.php', dirname(__FILE__)));
			$this->custom_query = new travelogue_Custom_Query();
			
				
			register_activation_hook( __FILE__, array( &$this, 'activate' ) );
			register_deactivation_hook(__FILE__, array( &$this, 'deactivate'));

			add_action( 'widgets_init', array( &$this, 'register_custom_widgets' ) );
			add_action( 'plugins_loaded', array( &$this, 'update_db_check' ) );
			
			add_action('admin_init', 'flush_rewrite_rules');
			add_action('generate_rewrite_rules', array( &$this, 'add_custom_rewrites' ) );
			

		} // END public function __construct


		public function activate() {
			//Add a table to the database if one does not already exist
			global $wpdb;
			
			//storing the table name as an option
			add_site_option( 'travelogue_table_name', $wpdb->base_prefix . 'travelogue_posts' );
			$table_name = get_site_option( 'travelogue_table_name' );
						
			if($wpdb->get_var( 'SHOW TABLES LIKE "' . $table_name . '"') != $table_name ) {
				$table_creation = $this->create_table($table_name);
				add_site_option( "travelogue_db_version", self::travelogue_db_version );
			}
		}//END _activate
		
		
		
		public function deactivate() {
			delete_site_option( 'travelogue_table_name' );
			delete_site_option( 'travelogue_db_version' );
		}

			
		
		//Create a table to store location data
		public function create_table($table_name) {
			global $wpdb;
   			
			
			$sql = "CREATE TABLE $table_name (
  				id mediumint(9) NOT NULL AUTO_INCREMENT,
  				post_id INT,
  				blog_id INT,
  				locality VARCHAR(40),
  				admin_1 VARCHAR(40),
  				admin_2 VARCHAR(40),
  				admin_3 VARCHAR(40),
  				country VARCHAR(40),
  				lng VARCHAR(40),
  				lat VARCHAR(40),
  				address TEXT,
  				reference VARCHAR(40),    
  				UNIQUE KEY id (id)
    			);";

   			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   			dbDelta( $sql );
		
		} //END public function create_table

		
		public function update_db_check() {
			
    			$table_name = get_site_option( 'travelogue_table_name' );
    			
    			if (get_site_option( 'travelogue_db_version' ) != self::travelogue_db_version ) {
        			$this->create_table($table_name);
        			update_site_option( "travelogue_db_version", self::travelogue_db_version );
    			}
		
		}
		
		
		
		public function register_custom_widgets() {
			
			require_once(sprintf('%s/widgets/pop-places.php', dirname(__FILE__)));
		 	register_widget( 'travelogue_Pop_Places' );
		
		}
		
		static function list_addresses($blog_id, $post_id = 0) {
		
			global $wpdb;
			$table_name = get_site_option( 'travelogue_table_name' );
			
			
			if ( $post_id != 0 ) {
				$addresses = $wpdb->get_col( "SELECT address FROM $table_name WHERE post_id=$post_id AND blog_id=$blog_id" );
			} else {
				$addresses = $wpdb->get_col( "SELECT address FROM $table_name WHERE blog_id=$blog_id" );
			}
			
			return $addresses;
		}
		
		//Add rewrite rules for permalinks
		public function add_custom_rewrites ($wp_rewrite) {
		
		  	$new_rules = array( 
	     			'address/(.+)' => 'index.php?travelogue_address=' .
       				$wp_rewrite->preg_index(1) 
       			);

  			//Add the new rewrite rule into the top of the global rules array
  			$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
		
		} // END add_custom_rewrites
		
	} // END class travelogue_plugin

} // END if(!class_exists('travelogue_plugin'))

if(class_exists('travelogue_plugin'))	{
	
	$travelogue = new travelogue_plugin();
	require_once( sprintf('%s/inc/template-tags.php', dirname(__FILE__)) );

}

?>