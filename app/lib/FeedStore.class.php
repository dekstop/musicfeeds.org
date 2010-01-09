<?

class FeedStore {

  var $_db;

  function FeedStore($db) {
    $this->_db = $db;
  }
  
  function addBatchimport($feedUrl, $user=null) {
    if (is_null($user) || $user=='') {
      return $this->_db->insert("INSERT INTO batchimports(url) VALUES(?)", array($feedUrl));
    }
    return $this->_db->insert("INSERT INTO batchimports(url, user_id) " .
      "VALUES(?, (SELECT id FROM users WHERE name=?))",
      array($feedUrl, $user)); 
  }
  
  function getRecentBatchimports($num, $offset=0) {
    return $this->_db->query("SELECT id, date_modified, active, url, user_id, imported, date_added, date_last_fetched, fail_count " .
      "FROM batchimports ORDER BY date_added DESC LIMIT ? OFFSET ?",
      array($num, $offset));
  }
  
  function getRecentFeeds($num, $offset=0) {
    return $this->_db->query("SELECT id, date_modified, active, url, actual_url, title, date_added, date_last_fetched, fail_count, " .
      "http_last_modified, http_etag, unique_id, ttl, date_updated " .
      "FROM feeds ORDER BY date_modified DESC LIMIT ? OFFSET ?",
      array($num, $offset));
  }
  
  function getRecentFailedFeeds($num, $offset=0) {
    return $this->_db->query("SELECT id, date_modified, active, url, actual_url, title, date_added, date_last_fetched, fail_count, " .
      "http_last_modified, http_etag, unique_id, ttl, date_updated " .
      "FROM feeds WHERE fail_count>0 ORDER BY date_modified DESC LIMIT ? OFFSET ?",
      array($num, $offset));
  }
  
  
}

?>
