<?

require_once('../init.php');

# Prepare
$request = new Request();
$request->setEnv($GLOBALS);
$view = new View($request);

# Execute and display
$dispatcher = new Dispatcher();
$dispatcher->dispatch($request, $view);

?>