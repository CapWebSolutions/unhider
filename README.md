# Plugin UnHider

A WordPress plugin that alerts you when another plugin is attempting to hide itself from you.

This is probably only useful if your site is getting hit with malware. Some WordPress malware will install a plugin to inject JavaScript into `wp_head` and also conceal itself from the admin plugins list like so:

```
define('hidden__BASENAME', basename( __DIR__ ) );
define('hidden__PLUGIN', hidden__BASENAME . DIRECTORY_SEPARATOR . basename( __FILE__ ) );

function hide_me($plugins) {

	if( is_plugin_active( hidden__PLUGIN ) ) {
		unset( $plugins[ hidden__PLUGIN ] );
	}

	return $plugins;
}

add_filter('all_plugins', 'hide_me');
```

This plugin detects if `all_plugins` has been filtered to exclude any plugins, "un-hides" them so they show up in your plugins list, and displays an alert on admin pages with information about the offending plugin(s).
