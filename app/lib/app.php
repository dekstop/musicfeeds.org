<?

// TODO: move all of these to separate classes + smarty plugins

function getDb() {
  global $APP_ENV;
  $dsn = "dbname=${APP_ENV['db.name']} user=${APP_ENV['db.user']} password=${APP_ENV['db.password']}";
  if ($APP_ENV['db.host'] && $APP_ENV['db.host']!='localhost') {
    $dsn = "host=${APP_ENV['db.host']}" . $dsn;
  }
  return DB::connect($dsn);
}

function getSolr() {
  global $APP_ENV;
  return Solr::connect($APP_ENV['solr.url']);
}


/*
 * Contact Page
 */

function submitUserComment($db, $author_name, $author_email, $url, $comments) {
  return $db->insert("INSERT INTO musicfeeds_usercomments(author_name, author_email, url, comments) VALUES (?, ?, ?, ?)",
    array($author_name, $author_email, $url, $comments));
}


/*
 * Add Page
 */

function submitBatchimportUrl($db, $feed_url, $user=null) {
  if (is_null($user) || $user=='') {
    return $db->insert("INSERT INTO batchimports(url) VALUES(?)", array($feed_url));
  }
  return $db->insert("INSERT INTO batchimports(url, user_id) " .
    "VALUES(?, (SELECT id FROM users WHERE name=?))",
    array($feed_url, $user)); 
}

/*
 * Status Page
 */

function getRecentBatchimports($db, $num) {
  return $db->query("SELECT id, date_modified, active, url, user_id, imported, date_added, date_last_fetched, fail_count " .
    "FROM batchimports order by date_added desc limit ?",
    array($num));
}

function getRecentFeeds($db, $num) {
  return $db->query("SELECT id, date_modified, active, url, actual_url, title, date_added, date_last_fetched, fail_count, " .
    "http_last_modified, http_etag, unique_id, ttl, date_updated " .
    "FROM feeds order by date_modified desc limit ?",
    array($num));
}

function getRecentFailedFeeds($db, $num) {
  return $db->query("SELECT id, date_modified, active, url, actual_url, title, date_added, date_last_fetched, fail_count, " .
    "http_last_modified, http_etag, unique_id, ttl, date_updated " .
    "FROM feeds WHERE fail_count>0 order by date_modified desc limit ?",
    array($num));
}

/*
 * Main App (Homepage/Feed)
 */

// parses a facet URL query string as created by the web UI and converts it into a Solr facet list
// appends a '_facet' suffix to all field names, so these columns need to be in the index.
// FIXME: atm we only support one facet filter in $fq, we need a query parser for more complex ones
function parseFacets($fq) {
  if (!$fq) {
    return array();
  }
  // this breaks once a facet value contains a space:
  #$values = split(' ', urldecode($fq));
  // so instead we treat it as one.
  $values = array(urldecode($fq));
  $facets = array();
  foreach ($values as $value) {
    $cols = split(':', $value, 2);
    $key = $cols[0] . '_facet';
    $facets[$key] = $cols[1];
  }
  return $facets;
}

function formatFacets($facets) {
  $strings = array();                
  foreach ($facets as $field => $value) {
    $key = $field;
    
    # TODO: do we need to do this?
    #preg_match('/^(.*)(_facet)?$/', $facet, $matches);
    #print_r($matches);
    #if (strpos($field, '_facetstr...

    $strings[] = urlencode($key) . ":" . urlencode($value);
  }       
  return implode('+', $strings);
}

// matches terms against specific fields
function buildFilteredSolrQuery($base_q, $filterFields, $filterTerms){
  if (!$filterTerms || count($filterTerms)==0) {
    return $base_q;
  }
  $terms_quoted = array();
  foreach ($filterTerms as $term) {
    $terms_quoted[] = Solr::quoteTerm($term);
  }
  $filter_q = implode(" OR ", $terms_quoted);
  $f = array();
  foreach ($filterFields AS $field) {
    $f[] = $field . ":(" . $filter_q . ")";
  }
  $filter_q = implode(" OR ", $f);
  if (!is_null($base_q) && strlen($base_q)>0) {
    return "(" . $base_q . ") AND (" . $filter_q . ")";
  }
  return $filter_q;
}

// Optionally filter by username. Result is ordered by date_added DESC.
function db_load_entries($db, $ids, $username=null, $limit=null, $maxPerFeed=2) {
  $params = array($ids);
  if (!is_null($username)) {
    $params[] = $username;
  }
  $select = 
    "SELECT f.title AS feed_title, f.link AS feed_link, f.id AS feed_id, " .
      #"TO_CHAR(e.date, 'YYYY-MM-DD') AS date, " .
      "e.date AS date, " .
      #"TO_CHAR(e.date_added, 'YYYY-MM-DD HH:mm') AS date_added, " .
      #"TO_CHAR(e.date_published, 'YYYY-MM-DD HH:mm') AS date_published, " .
      "e.id AS id, e.title AS title, COALESCE(e.content, e.summary) AS content, e.link AS link, " .
      "CHAR_LENGTH(COALESCE(e.content, e.summary)) AS size " .
    "FROM feeds f INNER JOIN entries e ON f.id=e.feed_id " .
    (is_null($username) ? "" : 
      "INNER JOIN users_feeds uf ON f.id=uf.feed_id " .
      "INNER JOIN users u ON uf.user_id=u.id "
    ) .
    "WHERE e.id IN (?) " .
    (is_null($username) ? "" :
      "AND u.name=? "
    ) .
    "ORDER BY e.date DESC, e.id DESC ";
  if (!is_null($limit)) {
    $select .= "LIMIT ? ";
    $params[] = $limit * 2; // twice as much so we can drop some later
  }
  $result = $db->query($select, $params);
  
  if (is_null($limit) || count($result) <= $limit) {
    return $result;
  }
  
  // can drop some that come from the same feed to improve result quality
  #$MAX_FEED_REPETITIONS = 2;
  $last_feed_id = null;
  $last_feed_post_count = 0;
  $entries = array();
  $idx = 0;
  for ($i=0; $i<$limit; $i++) {
    $is_scanning = TRUE;
    while ($is_scanning && $idx<count($result)) {
      $entry = $result[$idx];
      $feed_id = $entry['feed_id'];
      if ($feed_id==$last_feed_id) {
        $last_feed_post_count++;
      }
      else {
        $last_feed_post_count = 1;
      }
      if (is_null($maxPerFeed) || ($maxPerFeed<1) || $last_feed_post_count <= $maxPerFeed) {
        $last_feed_id = $feed_id;
        $entries[] = $entry;
        $is_scanning = FALSE;
      }
      $idx++;
    }
  }
  return $entries;
}

function db_load_enclosures($db, $entry_ids) {
  return $db->getHash('key', "SELECT ee.entry_id AS key, en.url AS url, en.type AS type, en.length AS length " .
    "FROM entries_enclosures ee INNER JOIN enclosures en ON ee.enclosure_id=en.id " .
    "WHERE ee.entry_id IN (?)", array($entry_ids));
}

function db_load_feed_users($db, $feed_ids) {
  return $db->getHash('key', "SELECT uf.feed_id AS key, u.name, u.email, u.type " .
    "FROM users u INNER JOIN users_feeds uf ON uf.user_id=u.id " .
    "WHERE uf.feed_id IN (?)", array($feed_ids));
}

// main method to load entries, combines data from solr+db
function find_entries($db, $solr, $q, $facets, $feedcacheUser, $num, $maxPerFeed=2) {
  global $APP_ENV;
  $ids = array();
  $entry_meta = array();

  $solr->setFl('id,date,author,category,enclosure_url,enclosure_mimetype,score');
  $solr->setSort('date_added desc'); // instead we will sort when loading from DB
  $solr->setFacets(parseFacets($facets));
  $result = $solr->select($q, $APP_ENV['solr.fetchSize']);
  foreach ($result['result'] as $doc) {
    $id = $doc['id'];
    $ids[] = $id;   
    $entry_meta[$id] = array(
      'author' => $doc['author'],
      'category' => $doc['category'],
      'enclosure_url' => $doc['enclosure_url'],
      'enclosure_mimetype' => $doc['enclosure_mimetype'],
    );
  }
  $entries = array();
  if (count($ids) > 0) {
    $db_entries = db_load_entries($db, $ids, $feedcacheUser, $num, $maxPerFeed);
    // note: we're loading more enclosures than entries.
    $enclosures = db_load_enclosures($db, $ids);
      for ($i=0; $i<count($db_entries); $i++) {
        $entry = $db_entries[$i];
        $id = $entry['id'];
        $entry['enclosures'] = $enclosures[$id];
        $entry['authors'] = $entry_meta[$id]['author'];
        $entry['categories'] = $entry_meta[$id]['category'];
        $entries[] = $entry;
      }       
  }
  return $entries;
}

?>
