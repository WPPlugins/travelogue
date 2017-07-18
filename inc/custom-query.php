<?php
if (!class_exists('travelogue_Custom_Query')) {

	class travelogue_Custom_Query {
	
		public $is_travelogue_archive = FAlSE;
		public $archive_name = '';
		
		public function __construct() {
			
			//Set the priority to 100 to reduce chance that it is overwritten by theme or other plugin. 
			add_filter( 'wp_title', array( &$this, 'archive_title' ), 100, 2 );
			
			//Filters for modifying the query
			add_filter( 'query_vars', array( &$this, 'places_queryvars' ) );
			add_filter( 'posts_join', array( &$this, 'join_travelogue_table' ) );
			add_filter( 'posts_where', array( &$this, 'city_archive_where' ) );
			
			//Determine if the query should be a travelogue archive
			add_action( 'pre_get_posts', array( &$this, 'travelogue_archive_check' ) );
		
		}
		

		public function places_queryvars( $qvars ) {
  			
  			$qvars[] = 'travelogue_address';
  			return $qvars;

  		}
  		
  		//Check if custom query should be used
  		public function travelogue_archive_check ( $query ) {
  			  			
  			if( isset( $query->query_vars['travelogue_address'] ) ) {
  				$this->is_travelogue_archive = TRUE;
  				$query->is_home = false;
  				$this->archive_name = rawurldecode( $query->query_vars['travelogue_address'] );
  			}
  		
  		}
  		
  		//Join travelogue_table to posts
  		public function join_travelogue_table($join) {
  			
  			global $wp_query, $wpdb;
  			$table_name = get_site_option( 'travelogue_table_name' );
  			
  			if( $this->is_travelogue_archive ) {
  				$join .= " LEFT JOIN $table_name ON " . 
       					$wpdb->posts . ".ID = " . $table_name . 
       					".post_id ";
  			}
  			
  			return $join;
  		
  		} //END public function join_travelogue_table($join)
  		
  		
  		
		public function city_archive_where($where) {
			
			global $wp_query;
			
			if( $this->is_travelogue_archive ) {
				$where .= ' AND address = "' . $this->archive_name . '" ';
			}

			return $where;
		}
		
				
		public function archive_title($title, $sep) {
			global $wp_query;

			if( $this->is_travelogue_archive ) {
				$title = $this->archive_name . ' ' . $sep . ' ' . get_bloginfo( 'name' );
			}

			return $title;
		}
			
	
	} //END travelogue_Custom_Query 
	
} //END if (!class_exists('travelogue_Custom_Query'))

?>