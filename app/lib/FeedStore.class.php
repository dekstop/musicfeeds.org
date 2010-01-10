<?

class FeedStore {
  
  private static $BATCHIMPORT_COLUMNS = array(
    'id', 'date_modified', 'active', 'url', 'user_id', 'imported', 'date_added', 
    'date_last_fetched', 'fail_count'
    );

  private static $FEED_COLUMNS = array(
    'id', 'date_modified', 'active', 'url', 'actual_url', 'title', 'date_added', 
    'date_last_fetched', 'fail_count', 'http_last_modified', 'http_etag', 
    'unique_id', 'ttl', 'date_updated'
    );

  var $_db;

  function FeedStore($db) {
    $this->_db = $db;
  }
  
  private function _getColumnsAsString($columns) {
    $ec = array();
    foreach ($columns as $c) {
      $ec[] = $this->_db->quoteIdentifier($c);
    }
    return '"' . implode('", "', $ec) . '"';
  }
  
  function addBatchimport($feedUrl, $user=null) {
    if (is_null($user) || $user=='') {
      return $this->_db->insert('INSERT INTO batchimports(url) VALUES(?)', array($feedUrl));
    }
    return $this->_db->insert('INSERT INTO batchimports(url, user_id) ' .
      'VALUES(?, (SELECT id FROM users WHERE name=?))',
      array($feedUrl, $user)); 
  }
  
  function getRecentBatchimports($num, $offset=0) {
    return $this->_db->query('SELECT ' . 
      $this->_getColumnsAsString(FeedStore::$BATCHIMPORT_COLUMNS) .
      ' FROM batchimports ORDER BY date_added DESC LIMIT ? OFFSET ?',
      array($num, $offset));
  }
  
  function getFeedInfo($feedUrl) {
    return $this->_db->query('SELECT ' . 
      $this->_getColumnsAsString(FeedStore::$FEED_COLUMNS) .
      ' FROM feeds WHERE url=? OR actual_url=? ORDER BY id ASC',
      array($feedUrl, $feedUrl));
  }
  
  function getRecentFeeds($num, $offset=0) {
    return $this->_db->query('SELECT ' . 
      $this->_getColumnsAsString(FeedStore::$FEED_COLUMNS) .
      'FROM feeds ORDER BY date_modified DESC LIMIT ? OFFSET ?',
      array($num, $offset));
  }
  
  function getRecentFailedFeeds($num, $offset=0) {
    return $this->_db->query('SELECT ' . 
      $this->_getColumnsAsString(FeedStore::$FEED_COLUMNS) .
      'FROM feeds WHERE fail_count>0 ORDER BY date_modified DESC LIMIT ? OFFSET ?',
      array($num, $offset));
  }
}

?>
