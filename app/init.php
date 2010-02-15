<?
$base_dir = dirname(dirname(__FILE__));
require_once($base_dir . '/lib/init.php');

# Vars
$APP_ROOT = $base_dir . '/app';

$APP_CONF = $APP_ROOT . '/conf';
$APP_CONTROLLERS = $APP_ROOT . '/controllers';
$APP_HELPERS = $APP_ROOT . '/helpers';
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
require_once('FeedStore.class.php');
require_once('LastfmService.class.php');
require_once('SearchContext.class.php');
require_once('SearchService.class.php');
require_once('Solr.class.php');
require_once('Usercomments.class.php');

require_once($APP_HELPERS . '/global_helpers.php');
require_once('app.php'); // TODO: get rid of this legacy file

require_once($APP_CONF . '/app_env.php');
require_once($APP_CONF . '/display_vars.php');

?>