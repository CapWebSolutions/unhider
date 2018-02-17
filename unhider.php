<?php
/*
Plugin Name: Plugin UnHider
Description: Alerts you when a plugin is attempting to hide itself from you
Author: Cory Hughart
Author URI: http://coryhughart.com
Version: 0.1.0
Text Domain: unhider
*/

$unhider_all_plugins = array();
$unhider_end_plugins = array();
$unhider_diff = array();

function unhider_plugins_start($plugins) {
	global $unhider_all_plugins;
	
	// Store original list of plugins
	$unhider_all_plugins = $plugins;
	
	return $plugins;
}

function unhider_plugins_end($plugins) {
	global $unhider_all_plugins, $unhider_end_plugins, $unhider_diff;
	
	// Store (possibly) altered plugins list
	$unhider_end_plugins = $plugins;
	
	// Check for differences in the plugin list
	$unhider_diff = strcmp( json_encode($unhider_all_plugins), json_encode($plugins) );
	
	// Restore original list of plugins
	return $unhider_all_plugins;
}

add_filter( 'all_plugins', 'unhider_plugins_start', PHP_INT_MIN );
add_filter( 'all_plugins', 'unhider_plugins_end', PHP_INT_MAX );

function unhider_admin_notice() {
	global $unhider_all_plugins, $unhider_end_plugins, $unhider_diff;

	if ( $unhider_diff !== 0 ) {
?>
<div class="notice notice-warning is-dismissible">
	<h4><?php _e('Plugin UnHider', 'unhider'); ?></h4>
	<p><?php _e('The following plugins have attempted to hide themselves from you:', 'unhider'); ?></p>
	<ul>
<?php
	foreach ( $unhider_all_plugins as $k => $v ) :
		if ( ! array_key_exists( $k, $unhider_end_plugins ) ) :
?>
		<li><pre><?php print_r($v); ?></pre></li>
<?php
		endif;
	endforeach;
?>
	</ul>
</div>
<?php
	}
}

add_action( 'admin_notices', 'unhider_admin_notice' );
