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
require_once('view/lib/View.php');

# Global includes: own
require_once('Request.class.php');
require_once('Dispatcher.class.php');

# Template engine: View.php
require_once('ViewViewable.class.php');
$VIEW_CLASSNAME = 'ViewViewable';
ini_set('include_path', 
  $PKG_LIB . '/view/renderers:' .
  $APP_LIB . '/view/renderers:' .
  ini_get('include_path'));

# Template engine: Smarty
// require_once('SmartyViewable.class.php');
// $VIEW_CLASSNAME = 'SmartyViewable';
# smarty has its own plugin loader mechanism -> no include_path mod needed
?>