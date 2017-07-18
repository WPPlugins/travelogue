<?php
/* 
Template Tags

These are some functions that can be placed in theme files in order to take advantage of travelogue features.
*/

function is_travelogue_archive () {
	global $travelogue;
	
	return $travelogue->custom_query->is_travelogue_archive;
}


function travelogue_archive_name () {
	global $travelogue;
	
	if (is_travelogue_archive()) {
		return $travelogue->custom_query->archive_name;
	}
	
}
	
?>