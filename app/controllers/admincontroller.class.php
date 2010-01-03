<?

class AdminController {
	
	function index($request, $view) {
		
		global $feedcache_user;
		
		$db = getDb();
		if (!$db) {
			die("Can't connect to the database.");
		}

		// params: check POST first
		$feedUrl = $request->postString('feed_url');
		$user = $request->postString('user', $feedcache_user); // TODO: global conf for default app username

		// handle form
		if ($feedUrl) {
			$r = submitBatchimportUrl($db, $feedUrl, $user);
			if (!($r===FALSE)) {
				$host = $_SERVER['HTTP_HOST'];
				$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				header("Location: http://${host}${uri}/a/"); // TODO: auto-detect current fake path (here: /a/)
			}
			else {
				// TODO: display proper error page
			}
		} else {

			// see if pre-fill param values were provided
			$feedUrl = $request->getString('feed_url');
			$user = $request->getString('user', $feedcache_user); // TODO: global conf for default app username

			$batchimports = getRecentBatchimports($db, 7);
			$failedFeeds = getRecentFailedFeeds($db, 30);
			$feeds = getRecentFeeds($db, 50);
			
			$view->setParam('feedUrl', $feedUrl);
			$view->setParam('user', $user);
			$view->setParam('batchimports', $batchimports);
			$view->setParam('failedFeeds', $failedFeeds);
			$view->setParam('feeds', $feeds);
			
			$view->setParam('urlExcerptLen', 50);
			$view->setParam('titleExcerptLen', 35);
			
			$view->display('admin.tpl');
		}
		
		$db->close();
	}
}

?>