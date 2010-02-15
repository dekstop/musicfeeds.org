<?

require_once('Solr.class.php'); // We use solr-specific parameter quoting when building URLs.

/**
 * Encapsulates all parameters of a search query. Can be constructed from a 
 * Request instance, and can build search URLs.
 */
class SearchContext {
  
  private $_request;
  
  private $_q; // query, can be a string or a map of fields->values. In the latter case
               // values will be quoted.
  private $_f; // facet
  private $_c; // number of characters per post
  private $_n; // number of posts per page
  private $_lastfmUser; // Last.fm user name
  private $_m; // max number of consecutive posts by the same blog
  private $_a; // max number of Last.fm artists to load
  
  const DEFAULT_C = 1000;
  const DEFAULT_N = 20;
  const DEFAULT_M = 2;
  const DEFAULT_A = 35;
  
  /**
   * This requires a Request instance to:
   * * initialise state based on form query parameters (optional)
   * * get access to global environment variables encapsulated by Request (mandatory)
   */
  public function SearchContext($request) {
    $this->_request = $request;
    $this->_q = $request->getString('q');
    $this->_f = $request->getString('f');
    $this->_c = $request->getInt('c', SearchContext::DEFAULT_C);
    $this->_n = min(
      $request->envVar('display.maxNumItems'), 
      $request->getInt('n', 
        $request->envVar('display.numQueryResultItems',
          SearchContext::DEFAULT_N)));
    $this->_lastfmUser = $request->getString('lfm:user');
    $this->_m = $request->getInt('m', SearchContext::DEFAULT_M);
    $this->_a = $request->getInt('a', SearchContext::DEFAULT_A);
  }
  
  public function isComplete() {
    return $this->hasQuery() || $this->hasLastfmUser();
  }
  
  public function hasQuery() {
    return !empty($this->_q);
  }
  
  public function hasLastfmUser() {
    return !empty($this->_lastfmUser);
  }
  
  /**
   * This assumes that $base will always end in a trailing slash.
   */
  function buildUrl($base='') {
    $url = $base . '?';
    $tokens = array();

    if ($this->getQ()) {
      if (is_array($this->getQ())) {
        $tokens[] = 'q=' . SearchContext::_formatKeyValueMap($this->getQ());
      }
      else {
        $tokens[] = 'q=' . urlencode($this->getQ());
      }
    }
    if ($this->getF()) {
      $tokens[] = 'f=' . SearchContext::_formatKeyValueMap($f);
    }
    if ($this->getLastfmUser()) {
      $tokens[] = 'lfm%3Auser=' . urlencode($this->getLastfmUser());
    }
    if ($this->getC() && $this->getC()!=SearchContext::DEFAULT_C) {
      $tokens[] = 'c=' . urlencode($this->getC());
    }
    if ($this->getN() && 
      $this->getN()!=$this->_request->envVar('display.numQueryResultItems', SearchContext::DEFAULT_N)) {
      $tokens[] = 'n=' . urlencode($this->getN());
    }
    if ($this->getM() && $this->getM()!=SearchContext::DEFAULT_M) {
      $tokens[] = 'm=' . urlencode($this->getM());
    }
    if ($this->getA() && $this->getA()!=SearchContext::DEFAULT_A) {
      $tokens[] = 'a=' . urlencode($this->getA());
    }
    return $url . implode('&', $tokens);
  }
  
  private static function _quoteTerm($str) {
    return Solr::quoteTerm($str);
  }

  private static function _formatKeyValueMap($facets) {
    $strings = array();                
    foreach ($facets as $field => $value) {
      $key = $field;

      # TODO: do we need to do this for facets?
      #preg_match('/^(.*)(_facet)?$/', $facet, $matches);
      #print_r($matches);
      #if (strpos($field, '_facetstr...

      $strings[] = urlencode($key) . ":" . urlencode(SearchContext::_quoteTerm($value));
    }       
    return implode('+', $strings);
  }
  
  public function getQ() {
    return $this->_q;
  }
  
  public function setQ($value) {
    $this->_q = $value;
  }
  
  public function getF() {
    return $this->_f;
  }
  
  public function setF($value) {
    $this->_f = $value;
  }
  
  public function getC() {
    return $this->_c;
  }
  
  public function setC($value) {
    $this->_c = $value;
  }
  
  public function getN() {
    return $this->_n;
  }
  
  public function setN($value) {
    $this->_n = $value;
  }
  
  public function getLastfmUser() {
    return $this->_lastfmUser;
  }
  
  public function setLastfmUser($value) {
    $this->_lastfmUser = $value;
  }
  
  public function getM() {
    return $this->_m;
  }
  
  public function setM($value) {
    $this->_m = $value;
  }
  
  public function getA() {
    return $this->_a;
  }
  
  public function setA($value) {
    $this->_a = $value;
  }
  
}

?>