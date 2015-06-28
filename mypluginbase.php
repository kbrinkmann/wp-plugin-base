<?php 

/**
 * @desc core files for (my) plugin developement in wordpress, 
 * file is used to bind dependencies for plugin development
 * @author rudi
 */

if(!class_exists('PluginTools')) {  
	
	require_once plugin_dir_path( __FILE__ ) . "plugintools.class.php";
	require_once plugin_dir_path( __FILE__ ) . "myplugin.class.php"; 
}

?>