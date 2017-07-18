//Adds Google Place autocomplete to text field and handles ajax 

jQuery(document).ready(function($) {
	var input = document.getElementById('location-text');
	var options = {types: ['(cities)']};
	
	var autocomplete = new google.maps.places.Autocomplete(input, options);	
	
	$("#travelogue_add_button").click(function() {
		var place = autocomplete.getPlace();

		if (typeof place != "undefined") {
			
			//Create object with address_components.types as properties and long_name as values
			var components={}; 
			jQuery.each( place.address_components, function(k,v1) {
				jQuery.each(v1.types, function(k2, v2){
					components[v2]=v1.long_name
				});
			});

			//Create object to pass to ajax request
			data = {
				'action': 'assign_place_to_post',	//PHP method that adds all this to wp database
				'address': place.formatted_address,
				'locality': ' ',
				'admin_1': ' ',
				'admin_2': ' ',
				'admin_3': ' ',
				'country': ' ',
				'lng': place.geometry.location.lng(),
				'lat': place.geometry.location.lat(),
				'reference': place.reference,
				'post_id': passed_data.post_id
			};
			
			//Not every place has every component
			if ( components.hasOwnProperty( 'locality' ) ) data.locality = components.locality;
			if ( components.hasOwnProperty( 'administrative_area_level_1' ) ) data.admin_1 = components.administrative_area_level_1;
			if ( components.hasOwnProperty( 'administrative_area_level_2' ) ) data.admin_2 = components.administrative_area_level_2;
			if ( components.hasOwnProperty( 'administrative_area_level_3' ) ) data.admin_3 = components.administrative_area_level_3;
			if ( components.hasOwnProperty( 'country' ) ) data.country = components.country;
			
			//ajax post request
			$.post( ajaxurl, data, function(response) {
				$('#added_places').html(response);
			} );
			
		} //END if (typeof place != "undefined")
	
	} ); //END $("#travelogue_add_button").click(function()

}); //END jQuery(document).ready(function($)


