<?

class FeedInfoController {
  
  function index($request, $view) {
    
    $url = $request->getString('url');

    if ($url) {
      $db = getDb();
      if (!$db) {
        die("Can't connect to the database."); // TODO display error page
      }
      
      $fs = new FeedStore($db);
      $feedinfo = $fs->getFeedInfo($url);
      $db->close();
      
      $view->setParam('data', $feedinfo);

      header('Content-type: application/json');
      $view->display('ajax/json_response.tpl');
    } else {
      // TODO: how do we signal missing parameters in JSON responses? via HTTP status codes?
    }
  }
}

?>