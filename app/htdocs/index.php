<?

require_once('../init.php');

# Prepare
$request = new Request();
$request->setEnv($APP_ENV);
$view = new View($request);

# Execute and display
$dispatcher = new Dispatcher();
$dispatcher->dispatch($request, $view);

?>