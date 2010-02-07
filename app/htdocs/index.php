<?

require_once('../init.php');

# Prepare
$request = new Request();
$request->setEnv($APP_ENV);
$view = new $VIEW_CLASSNAME();
$view->setRequest($request);
$view->setParams($DISPLAY_VARS);

# Execute and display
$dispatcher = new Dispatcher();
$dispatcher->dispatch($request, $view);

?>