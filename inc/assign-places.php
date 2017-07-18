<?php 

if(!class_exists('travelogue_Assign_Place')) {

	class travelogue_Assign_Place {
	
		public function __construct() {
			
			add_action( 'admin_enqueue_scripts', array(&$this, 'load_admin_scripts' ) );
			
			add_action( 'add_meta_boxes_post', array(&$this, 'add_a_meta_box' ) );
			
			add_action( 'wp_ajax_assign_place_to_post', array(&$this, 'assign_place_to_post') );
			
		}
		
		public function load_admin_scripts ($hook) {
		
			if( 'post.php' == $hook ) {
				wp_enqueue_script( 'google_places', 'https://maps.googleapis.com/maps/api/js?libraries=places&sensor=false' );
				wp_enqueue_script( 'add_places_js', plugins_url( 'add-places.js', __FILE__ ), array('jquery') );

				$data = array ( 'post_id' => $_GET['post'] );
				wp_localize_script( 'add_places_js', 'passed_data', $data);
			}
		}
		
		
		public function add_a_meta_box( $post ) {
			
			add_meta_box( 
				'travelogue_meta_box', //id
				__( 'Add a location', 'travelogue' ), //title
				array ( &$this, 'render_meta_box' ), //callback
				'post' //post-type
			);
		}


		public function render_meta_box () {
			$blog_id = get_current_blog_id();
			$cities = travelogue_plugin::list_addresses( $blog_id, $_GET['post'] );
			include(sprintf("%s/../templates/metabox.php", dirname(__FILE__)));	
						
		}
		
		public function assign_place_to_post () {
			global $wpdb;	
			
			$table_name = get_site_option( 'travelogue_table_name' );
			$data = $_POST;
			unset($data['action']);
			$data['blog_id'] = get_current_blog_id();
			
			//Ensure that each post is associated with only one place. This will change soon.
			$checkid = $wpdb->get_row('SELECT * FROM ' . $table_name . ' WHERE post_id = ' . $data['post_id'] . ' AND blog_id = ' .
				$data['blog_id'] );
			
			if (!$checkid) {
				$wpdb->insert( $table_name, $data );
			} else {
				$wpdb->update( $table_name, $data, array( 'post_id' => $data['post_id'], 'blog_id' => $data['blog_id'] ) );
			}
			
			//Response sent back to ajax request. 
			printf( esc_html__( 'Associated City: %s', 'travelogue' ), $data['address'] );
			die(); //Prevents extra content at end of response.	
			
		} //END public function assign_place_to_post
		
	} //END class travelogue_Asign_Place
	
} // END if(!class_exists('travelogue_Assign_Place'))

?>