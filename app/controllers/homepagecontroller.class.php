<?

class HomepageController {
  
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

    // page type
    $showHomepage = FALSE;
    if (!$searchContext->isComplete() || // no query provided?
      ($searchContext->hasLastfmUser() && 
        (is_null($artists) || count($artists)==0))) {// could not load last.fm data?

      // show default search
      $showHomepage = TRUE;
    }

    // query
    $searchService = new SearchService($db, $solr);
    if ($showHomepage) {
      // temporarily override how many entries we display, just for this search
      $n = $searchContext->getN();
      $searchContext->setN($request->envVar('display.numHomepageItems'));
      $entries = $searchService->defaultSearch($searchContext, $request->envVar('feedcache.user'));
      $searchContext->setN($n);
    }
    else {
      $entries = $searchService->filteredSearch($searchContext, $request->envVar('feedcache.user'), $artists);
    }
    
    // display
    $view->setParam('search', $searchContext);
    $view->setParam('entries', $entries);
    $view->setParam('artists', $artists);
    $view->setParam('showHomepage', $showHomepage);
    
    $view->display('homepage');
    
    $db->close();
  }
}

?>