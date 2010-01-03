<?

class Dispatcher {
	
	function _getControllerClassName($controller, $namespace=null) {
		global $APP_CONTROLLERS;
		
		$path = $APP_CONTROLLERS . '/';
		if ($namespace) {
			$path .=  $namespace . '/';
		}
		$controllerClassName = $controller . 'controller';
		$path .= $controllerClassName . '.class.php';

		if(file_exists($path)) {
			require_once($path);
			if (class_exists($controllerClassName)) {
				return $controllerClassName;
			}
		}
		return null;
	}
	
	function _getControllerClass($controller, $namespace=null) {
		$controllerClassName = $this->_getControllerClassName($controller, $namespace);
		if($controllerClassName){
			return new $controllerClassName();
		}
		return null;
	}
	
	function send404($request, $view, $msg=null) {
		header('HTTP/1.0 404 Not Found');
		$view->setParam("msg", $msg);
		$view->display("404.tpl");
		exit;
	}
	
	function displayException($e=null) {
		print '<pre>';
		print $e;
		print '</pre>';
	}
	
	function dispatch($request, $view) {
		$controllerObj = $this->_getControllerClass($request->getController(), $request->getNamespace());
		if ($controllerObj) {
			if (method_exists($controllerObj, $request->getAction())) {
				try {
					call_user_func(array($controllerObj, $request->getAction()), $request, $view);
				}
				catch (Exception $e) {
					$this->displayException($e);
				}
			}
			else {
				$this->send404($request, $view, "Unknown action: " . $request->getAction());
			}
		}
		else {
			$this->send404($request, $view, "Unknown namespace and controller: " . $request->getNamespace() . ', ' . $request->getController());
		}
	}	
}
?>