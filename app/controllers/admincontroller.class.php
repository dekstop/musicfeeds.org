<?

class AdminController {
  
  static function _getRecentBatchimports($db, $num) {
    return $db->query("SELECT id, date_modified, active, url, user_id, imported, date_added, date_last_fetched, fail_count " .
      "FROM batchimports order by date_added desc limit ?",
      array($num));
  }

  static function _getRecentFeeds($db, $num) {
    return $db->query("SELECT id, date_modified, active, url, actual_url, title, date_added, date_last_fetched, fail_count, " .
      "http_last_modified, http_etag, unique_id, ttl, date_updated " .
      "FROM feeds order by date_modified desc limit ?",
      array($num));
  }

  static function _getRecentFailedFeeds($db, $num) {
    return $db->query("SELECT id, date_modified, active, url, actual_url, title, date_added, date_last_fetched, fail_count, " .
      "http_last_modified, http_etag, unique_id, ttl, date_updated " .
      "FROM feeds WHERE fail_count>0 order by date_modified desc limit ?",
      array($num));
  }
  
  static function _getRecentComments($db, $num) {
    $c = new Usercomments($db);
    return $c->get($num);
  }

  function index($request, $view) {
    
    $db = getDb();
    if (!$db) {
      die("Can't connect to the database."); // TODO show proper error page
    }

    // params: check POST first
    $feedUrl = $request->postString('feed_url');
    $user = $request->postString('user', $request->envVar('feedcache.user'));

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
      // show admin page

      // see if pre-fill param values were provided
      $feedUrl = $request->getString('feed_url');
      $user = $request->getString('user', $request->envVar('feedcache.user'));

      $batchimports = AdminController::_getRecentBatchimports($db, 7);
      $failedFeeds = AdminController::_getRecentFailedFeeds($db, 30);
      $feeds = AdminController::_getRecentFeeds($db, 50);
      $comments = AdminController::_getRecentComments($db, 10);
      
      $view->setParam('feedUrl', $feedUrl);
      $view->setParam('user', $user);

      $view->setParam('batchimports', $batchimports);
      $view->setParam('failedFeeds', $failedFeeds);
      $view->setParam('feeds', $feeds);
      $view->setParam('comments', $comments);
      
      $view->setParam('urlExcerptLen', 50);
      $view->setParam('titleExcerptLen', 35);
      
      $view->display('admin.tpl');
    }
    
    $db->close();
  }
}

?>