<?
$base_dir = dirname(dirname(__FILE__));
require_once($base_dir . '/lib/init.php');

# Vars
$APP_ROOT = $base_dir . '/app';

$APP_CONF = $APP_ROOT . '/conf';
$APP_CONTROLLERS = $APP_ROOT . '/controllers';
$APP_HTDOCS = $APP_ROOT . '/htdocs';
$APP_LIB = $APP_ROOT . '/lib';
$APP_TEMPLATES = $APP_ROOT . '/templates';

ini_set('include_path', 
	$APP_LIB . ':' . 
	ini_get('include_path'));

# Includes
require_once('DB.class.php');
require_once('HTML.class.php');

require_once('Corpus.class.php');
require_once('LastFm.class.php');
require_once('Solr.class.php');

require_once('app.php');

require_once($APP_CONF . '/globals.php');

?>