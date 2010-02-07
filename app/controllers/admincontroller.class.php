<?

class AdminController {
  
  function index($request, $view) {
    
    $db = getDb();
    if (!$db) {
      die("Can't connect to the database."); // TODO show proper error page
    }
    $fs = new FeedStore($db);

    // params: check POST first
    $feedUrl = $request->postString('feed_url');
    $user = $request->postString('user', $request->envVar('feedcache.user'));

    // handle form
    if ($feedUrl) {
      $r = $fs->addBatchimport($feedUrl, $user);
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

      $batchimports = $fs->getRecentBatchimports(7);
      $failedFeeds = $fs->getRecentFailedFeeds(30);
      $feeds = $fs->getRecentFeeds(50);

      $uc = new Usercomments($db);
      $comments = $uc->get(10);
      
      $view->setParam('feedUrl', $feedUrl);
      $view->setParam('user', $user);

      $view->setParam('batchimports', $batchimports);
      $view->setParam('failedFeeds', $failedFeeds);
      $view->setParam('feeds', $feeds);
      $view->setParam('comments', $comments);
      
      $view->setParam('urlExcerptLen', 50);
      $view->setParam('titleExcerptLen', 35);
      
      $view->display('admin');
    }
    
    $db->close();
  }
}

?>