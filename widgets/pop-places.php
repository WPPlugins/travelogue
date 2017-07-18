<?php 

if(!class_exists('travelogue_Pop_Places')) {

	class travelogue_Pop_Places extends WP_Widget {

		public function __construct() {
			
			parent::__construct(
				'pop_places', // Base ID
				__('Top Places', 'travelogue'), // Name
				array( 'description' => __( 'Prints a list of the top cities associated with your posts', 'travelogue' ) ) // Args
			);
	
		}

	
		public function widget( $args, $instance ) {
			$blog_id = get_current_blog_id();
			$title = apply_filters( 'widget_title', $instance['title'] );
			$num_selected = intval( $instance['select'] );

			echo $args['before_widget'];
			
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			
			$cities = travelogue_plugin::list_addresses($blog_id);
			
			//Stop here if there are no cities
			if ( count( $cities ) == 0 ) {
				echo 'No cities are associated with any post.';
				return;
			}
			
			$city_count = array_count_values( $cities ); //array with cities as keys and number of posts as value
			arsort( $city_count, SORT_NUMERIC );  //sorts array from largest to smallest
			
			//Don't want to throw error if there are fewer than 5 cities.
			if ( count($city_count) < $num_selected ) {
				$max_items = count($city_count);
			} else {
				$max_items = $num_selected;
			}
			
			//Check the permalink structure and build url accordingly
			if ( get_option('permalink_structure') ) {
				$before = '<a href="' . home_url() . '/address/';
			} else {
				$before = '<a href="' . home_url() . '/?travelogue_address=';
			}

			$closea = '">';
         
			reset( $city_count );
			echo '<ul style="list-style:none;">';
			for ( $i = 1; $i <= $max_items; $i++ ) {
				echo '<li>' . $i . '.) ' . $before . rawurlencode( key($city_count) ) . $closea . key($city_count) . '</a></li>';
				next($city_count);
			}
			echo '</ul>';				
			echo $args['after_widget'];
			
		}

		
		public function form( $instance ) {
		
			if ( isset( $instance['title'] ) ) {
				$title = $instance[ 'title' ];
			} else {
				$title = __( 'Most Blogged Cities', 'travelogue' );
			}
			
			if ( isset( $instance['select'] ) ) {
				$select = $instance[ 'select' ];
			} else {
				$select = 5;
			}
			
			?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'travelogue' ); ?></label> 
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Number of items:', 'travelogue'); ?></label>
			<select name="<?php echo $this->get_field_name('select'); ?>" id="<?php echo $this->get_field_id('select'); ?>" >
			<?php
			$sel_status = '';
			for ( $i = 1; $i <= 30; $i++) {
				if ( $select == $i ) {
					$sel_status = 'selected="selected"';
				} else {
					$sel_status = '';
				}
				
				echo '<option value="' . $i . '" id="' . $i . '" ' . $sel_status . '>', $i, '</option>';
			} ?>
			</select>
			</p>
			<?php 
		
		}


		public function update( $new_instance, $old_instance ) {
		
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['select'] = ( ! empty( $new_instance['select'] ) ) ? strip_tags( $new_instance['select'] ) : '5';

			return $instance;
		
		}
		
	} //END travelogue_Pop_Places

} //END if(!class_exists('travelogue_Pop_Places'))

?>