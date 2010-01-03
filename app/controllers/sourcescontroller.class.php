<?

class SourcesController {
	
	function index($request, $view) {
		
		global $feedcache_user;
		
		$db = getDb();
		if (!$db) {
			die("Can't connect to the database.");
		}
		
		// TODO: global conf entry for $feedcache_user username
		// TODO: move this query to a utility class
		$feeds = $db->query("SELECT f.id AS id, active, url, actual_url, date_added, date_last_fetched, fail_count, title, description, link " .
			"FROM feeds f " .
			"INNER JOIN users_feeds uf ON uf.feed_id=f.id " .
			"INNER JOIN users u on uf.user_id=u.id " .
			"WHERE f.active " .
			"AND u.name=? " .
			"ORDER BY title ASC",
			array($feedcache_user));
		
		$view->setParam('feeds', $feeds);
		$view->display('sources.tpl');
		
		$db->close();
	}
}

?>