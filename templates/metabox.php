<label class="screen-reader-text" for="location-text">Add a location</label>
	
<span class="location-label">add a city:</span>
<input type="text" id="location-text" name="location-text" class="travelogue_text" size="25" />
<input id="travelogue_add_button" type="button" class="travelogue_button" value="Add" />
<div id="added_places">

	<ul>
	<li>
	<?php 
		if ( isset($cities) ) {
			$address_list = implode("</li><li>", $cities);
			printf( esc_html__( 'Associated City: %s', 'travelogue' ), $address_list );
		}
	?>
	</li>
	</ul>
</div>