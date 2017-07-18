=== Travelogue ===
Contributors: ImNotSoup
Tags: location, travel, google places, geotags
Requires at least: 3.9.1
Tested up to: 3.9.1
Stable tag: 0.2.1
License: MIT License

Travelogue is a plugin for travel writers. You can add location info to posts, view an archive of all posts related to a particular place, and dispay the most blogged about cities in a widget. 

== Description ==

Travelogue is a plugin for travel writers. You can add location info to posts, view an archive of all posts related to a particular place, and dispay the most blogged about cities in a widget.

Checkout the [live demo](https://demo.themocity.com/restless/).

== Installation ==

1. Download and unzip the plugin from the WordPress Plugin Directory.
2. Upload the `travelogue` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. Add a location to posts.
2. Create a widget listing the most blogged about places (including links to cooresponding archive pages). 
3. Find exact locations with Google Places API.

== Changelog ==

=0.2.0=
* add support for pretty permalinks
* add screenshots
* change to MIT License

=0.1.7=
* Show address in metabox
* Use single database for multisite
* Uninstall.php
* is_travelogue_archive template tag

=0.1.3=
* Add option to pop-places widget
* Add multisite support
* Add changelog (better late than never)

=0.1.0=
* Got to start somewhere


== Template Tags ==

Travelogue currently provides two functions that are meant to be called from inside themes. More will be added as Travelogue picks up new features. 

`is_travelogue_archive()` returns true is the current page is a travelogue archive (a list of posts associated with a particular place). You should wrap this function inside an if statement that checks if the function exists: 
	`<?php if (function_exists('is_travelogue_archive')) : result = is_travelogue_archive(); ?>`

`travelogue_archive_name()` returns the name of the current archive. Right now, this is always going to be the formatted address of a city (City, State, Country). This might change somewhat in future releases.

One obvious way these tags can be used is to display the name of the city at the top of travelogue archives:
	`<?php if (function_exists('is_travelogue_archive') && is_travelogue_archive())
		echo travelogue_archive_name(); ?>`
		
