<?

class HomepageController {
	
	function index($request, $view) {
		
		// TODO: better conf mechanism
		global $MAX_ENTRIES, $NUM_ENTRIES_WITHOUT_QUERY;
		global $feedcache_user;
		
		$q = $request->getString('q'); // query
		$f = $request->getString('f'); // facet
		$c = $request->getInt('c', 1000); // number of characters per post
		$n = min($MAX_ENTRIES, $request->getInt('n', 30)); // number of posts per page
		$u = $request->getString('lfm:user'); // last.fm user name
		$m = $request->getInt('m', 2); // max number of consecutive posts by the same blog
		$a = $request->getInt('a', 35); // max number of Last.fm artists to load

		$lastfmFailed = false;

		// setup
		$db = getDb();
		if (!$db) {
			die("Can't connect to the database."); // TODO: show error page instead
		}
		$solr = getSolr();
		if (!$solr || !$solr->ping()) {
			die("Can't connect to Solr."); // TODO: show error page instead
		}

		// lastfm
		$artists = array();
		$solr_q = $q;
		$numEntries = $n;
		if ($u) {
			// get last.fm artists
			$lfm = new LastFm($db, $lfm_key);

			$artistScores = $lfm->getTopArtistScores($u);
			if(is_null($artistScores)) {
				$lastfmFailed = true;
			}
			else {
				$corpus = new Corpus();
				$corpus->add($artistScores);
				$artists = $corpus->getRandom($a);	
				$solr_q = buildFilteredSolrQuery($q, array('title', 'category'), $artists);
			}
		}

		$lowerOpacity = FALSE;
		if ((empty($q) && empty($u)) || // no query at all
			$lastfmFailed ||	// could not load last.fm data
			(empty($q) && !empty($u) && count($artists)==0)) { // no query, empty lastfm result

			// no query -> show default search
			$numEntries = $NUM_ENTRIES_WITHOUT_QUERY;
			$lowerOpacity = TRUE;
		}

		// query
		$entries = find_entries($db, $solr, $solr_q, $f, $feedcache_user, $numEntries, $m);
		
		// display
		$view->setParam('q', $q);
		$view->setParam('f', $f);
		$view->setParam('c', $c);
		$view->setParam('n', $n);
		$view->setParam('lfmUser', $u);
		$view->setParam('entries', $entries);
		$view->setParam('lastfmFailed', $lastfmFailed);
		$view->setParam('lowerOpacity', $lowerOpacity);
		$view->setParam('artists', $artists);
		
		$view->display('homepage.tpl');
		
		$db->close();
	}
}

?>