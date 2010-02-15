<?

require_once('LastFm.class.php');

/**
 * This is the implementation of our Last.fm API.
 */
class LastfmService {
  
  private $_lfm;
  
  public function LastfmService($db, $apiKey) {
    $this->_lfm = new LastFm($db, $apiKey);
  }
  
  /**
   * Returns a list of artist names, or null if the lookup failed.
   * TODO: throw a meaningful exception on error instead of simply returning null.
   */
  public function getArtists($username, $numArtists) {

    $artistScores = $this->_lfm->getTopArtistScores($username);
    if(is_null($artistScores)) {
      return null;
    }
    if (count($artistScores)==0) {
      return array();
    }
    $corpus = new Corpus();
    $corpus->add($artistScores);
    $artists = $corpus->getRandom($numArtists);
    return $artists;
  }
}

?>