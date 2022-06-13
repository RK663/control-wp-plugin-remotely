# control-wp-plugin-remotely
Send notices, set messages to the plugin settings page remotely. It will help you to publish new plugin to the free or paid customers without updating their plugin.


## Client side:
Add the __"control-wp-plugin-remotely.php"__ to the plugin and use __\<?php echo do_action('wpptsh_right_side_notice'); \?>__ this action to your plugin settings page. Don't forget to add your site address in __"control-wp-plugin-remotely.php"__ in 12 line.

## Server side:
Add the __cwppr_notices.php__ file to your server root directory or you may upload anywhere in your site, just set the path in the __"control-wp-plugin-remotely.php"__ in 12 line.
