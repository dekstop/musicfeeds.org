<?

class FeedController {
  
  function index($request, $view) {
    
    // setup
    $db = getDb();
    if (!$db) {
      die("Can't connect to the database."); // TODO: show error page instead
    }
    $solr = getSolr();
    if (!$solr || !$solr->ping()) {
      die("Can't connect to Solr."); // TODO: show error page instead
    }

    $searchContext = new SearchContext($request);

    // Last.fm
    $artists = array();
    if ($searchContext->hasLastfmUser()) {
      $lfm = new LastfmService($db, $request->envVar('lastfm.key'));
      $artists = $lfm->getArtists($searchContext->getLastfmUser(), $searchContext->getA());
    }

    // query
    $searchService = new SearchService($db, $solr);
    $entries = $searchService->filteredSearch($searchContext, $request->envVar('feedcache.user'), $artists);
    
    // display
    $view->setParam('search', $searchContext);
    $view->setParam('entries', $entries);
    $view->setParam('artists', $artists);
    $view->setParam('now', time());
    
    header('Content-type: application/atom+xml');
    $view->display('atom_feed');
    
    $db->close();
  }
}

?>