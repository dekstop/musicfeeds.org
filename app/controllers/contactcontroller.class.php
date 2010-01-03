<?

class ContactController {
	
	function index($request, $view) {
		
		$url = $request->postString('url');
		$author_name = $request->postString('author_name');
		$author_email = $request->postString('author_email');
		$comments = $request->postString('comments');

		if ($url || $comments) {
			$db = getDb();
			if (!$db) {
				die("Can't connect to the database.");
			}

			submitUserComment($db, $author_name, $author_email, $url, $comments);
			$db->close();

			 // TODO: make a full-featured URL generator that gets rid of all these acrobatics incl knowing which fake directory we're currently pretending to be in (in this example: .../contact/)
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			header("Location: http://${host}${uri}/contact/thankyou.php");
		} else {
			// display
			$view->setParam('q', $q);
			$view->setParam('f', $f);
			$view->setParam('c', $c);
			$view->setParam('n', $n);
			$view->display('contact.tpl');
		}
	}
}

?>