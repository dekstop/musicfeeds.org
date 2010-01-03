<?

/**
 * Global initialisation for all apps. Loaded from app/init.php
 */

ini_set('include_path', 
	$base_dir . '/lib:' . 
	$base_dir . '/external:' . 
	ini_get('include_path'));

$PKG_DISPLAY = $base_dir . '/display';
$PKG_ETC = $base_dir . '/etc';
$PKG_LIB = $base_dir . '/lib';
$PKG_VAR = $base_dir . '/var';

# Global includes: externals
require_once('Smarty.class.php');

# Global includes: own
require_once('Request.class.php');
require_once('View.class.php');
require_once('Dispatcher.class.php');

?>