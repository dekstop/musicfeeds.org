<?

/**
 * Provides Last.fm user profile web service calls, including
 * a simple DB caching mechanism.
 * 
 * TODO: nicer error handling, e.g. different responses for connection errors.
 */
class LastFm {
  
  const CHARTTYPE_OVERALL = 'overall';
  const CHARTTYPE_7DAY = '7day';
  const CHARTTYPE_3MONTH = '3month';
  const CHARTTYPE_6MONTH = '6month';
  const CHARTTYPE_12MONTH = '12month';

  private $_key = null;
  private $_secret = null;

  private $_db = null;

  function LastFm($db, $key, $secret=null) {
    $this->_db = $db;
    $this->_key = $key;
    $this->_secret = $secret;
  } 

  static function _http_get($url) {
    $file = @fopen($url, 'r');
    if (!$file) {
      return FALSE;
    }
    $r = '';
    while (!feof($file)) {
      $r .= fgets($file, 1024);
    }
    fclose($file);
    return $r;
  }

  function _construct_url($username, $chartType) {
    #return "/web/site/musicfeeds/martind.xml";
    return "http://ws.audioscrobbler.com/2.0/?method=user.gettopartists&period=${chartType}&user=${username}&api_key=" . $this->_key;
  }

  function _fetchTopArtistRecords($username, $chartType) {
    $result = array();
    $xml = LastFm::_http_get($this->_construct_url($username, $chartType));
    if (!$xml) {
      return null;
    }
    else {
      $dom = DOMDocument::loadXML($xml);
      $xpath = new DOMXPath($dom);

      $status = $xpath->evaluate('/lfm/@status')->item(0)->textContent;
      if ($status=="ok") {
        foreach ($xpath->evaluate('/lfm/topartists/artist') as $node) {
          $artist = array();
          $artist['rank'] = (int)($xpath->evaluate('@rank', $node)->item(0)->textContent);
          $artist['name'] = $xpath->evaluate('name', $node)->item(0)->textContent;
          $artist['playcount'] = (int)($xpath->evaluate('playcount', $node)->item(0)->textContent);
          $artist['mbid'] = $xpath->evaluate('mbid', $node)->item(0)->textContent;
          $artist['url'] = $xpath->evaluate('url', $node)->item(0)->textContent;
          $result[] = $artist;
        } 
      }
    }
    return $result;
  }

  function _storeTopArtistRecords($username, $chartType, $artists) {
    $id = $this->_db->getOne("SELECT nextval('musicfeeds_lastfm_usercharts_id_seq')");

    $this->_db->insert("INSERT INTO musicfeeds_lastfm_usercharts(id, date, name, chart_type) VALUES (" .
      "?, now(), ?, ?)",
      array($id, $username, $chartType));
    
    foreach ($artists as $artist) {
      $this->_db->insert("INSERT INTO musicfeeds_lastfm_userchart_artists " .
        "(lastfm_user_id, name, rank, playcount, mbid, url) " .
        "VALUES(?, ?, ?, ?, ?, ?)",
        array(
          $id,
          $artist['name'],
          $artist['rank'],
          $artist['playcount'],
          $artist['mbid'],
          $artist['url'],
        ));
    }
  }

  function _isCacheExpired($username, $chartType, $timeoutInSeconds) {
    $lastFetch = $this->_db->getOne("SELECT max(date) FROM musicfeeds_lastfm_usercharts " .
      "WHERE name=? AND chart_type=? AND date>now() - INTERVAL ?", 
      array($username, $chartType, "${timeoutInSeconds} seconds"));
    if ($lastFetch==null) {
      return TRUE;
    }
    return FALSE;
  }
  
  // Checks if the data is already cached locally, and the cache not stale.
  // If it isn't cache this will request the data from Last.fm and store it 
  // in the DB.
  function _prefetchTopArtists($username, $charttype, $limit) {
    if ($this->_isCacheExpired($username, $chartType, 60 * 60 * 24 * 7)) { // cache expires after 1 week
      $artists = $this->_fetchTopArtistRecords($username, $chartType);
      if (!is_array($artists)) {
        // failed to load -> return null/error
        return null;
      }
      if (count(!$artists) > 0) {
        $this->_storeTopArtistRecords($username, $chartType, $artists);
      }
    }
  }

  /**
   * Returns null in case of an error, or a map of artist names and playcounts.
   */
  function getTopArtistScores($username, $chartType=LastFm::CHARTTYPE_3MONTH, $limit=null) {
    $this->_prefetchTopArtists($username, $chartType, $limit);

    $query = "SELECT a.name AS name, a.playcount AS playcount " .
      "FROM musicfeeds_lastfm_usercharts u " .
      "INNER JOIN musicfeeds_lastfm_userchart_artists a ON u.id=a.lastfm_user_id " .
      "WHERE u.name=? AND u.chart_type=? AND u.date=(" .
        "SELECT max(date) FROM musicfeeds_lastfm_usercharts " .
        "WHERE name=? AND chart_type=?" .
      ") ORDER BY a.rank ASC";
    $parameters = array($username, $chartType, $username, $chartType);
    if (is_numeric($limit)) {
      $query .= " LIMIT ?";
      $parameters[] = $limit;
    }
    $select = $this->_db->query($query, $parameters);
    $result = array();
    foreach ($select as $row) {
      $result[$row['name']] = $row['playcount'];
    }
    return $result;
  }

  /**
   * Returns null in case of an error, or a list of artist names.
   */
  function getTopArtists($username, $chartType=LastFm::CHARTTYPE_3MONTH, $limit=null) {
    $this->_prefetchTopArtists($username, $chartType, $limit);

    $query = "SELECT a.name FROM musicfeeds_lastfm_usercharts u " .
      "INNER JOIN musicfeeds_lastfm_userchart_artists a ON u.id=a.lastfm_user_id " .
      "WHERE u.name=? AND u.chart_type=? AND u.date=(" .
        "SELECT max(date) FROM musicfeeds_lastfm_usercharts " .
        "WHERE name=? AND chart_type=?" .
      ") ORDER BY a.rank ASC";
    $parameters = array($username, $chartType, $username, $chartType);
    if (is_numeric($limit)) {
      $query .= " LIMIT ?";
      $parameters[] = $limit;
    }
    $result = $this->_db->getList($query, $parameters);
    return $result;
  }

}

/*
require_once('DB.class.php');

$key = "cc84abb177f541dfb3d43aff15f7166e";
$secret = "31f94812c778fde18c5742f1fe351d08";

$db = DB::connect("dbname=feedcache user=feedcache password=11FA94");
if (!$db) {
        die("Can't connect to the database.");
}

$lfm = new LastFm($db, $key, $secret);
print_r($lfm->getTopArtists('martind'));
*/
?>
