<?php 
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

// For site options in multisite
delete_site_option( 'travelogue_table_name' ); 
delete_site_option( 'travelogue_db_version' );

//drop custom db table
global $wpdb;
$table_name = $wpdb->base_prefix . 'travelogue_posts';
$wpdb->query( 'DROP TABLE IF EXISTS ' . $table_name );

?>