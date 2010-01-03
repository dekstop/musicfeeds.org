<?

class StaticPageController {
	
	function index($request, $view) {
		$view->display($request->getVar('template'));
	}
}

?>