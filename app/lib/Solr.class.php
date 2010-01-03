<?

class Solr {
  
  public $_solr_url = null;
  
  public $_fl = '*,score';
  public $_sort = null;

  public $_facets = array();

  public function setFl($fields) {
    $this->_fl = $fields;
  }

  public function setSort($sortExpr) {
    $this->_sort = $sortExpr;
  }

  public function setFacet($field, $value) {
    $this->_facets[$field] = $value;
  }

  public function setFacets($facets) {
    $this->_facets = $facets;
  }

  public static function connect($solr_url) {
    // TODO: optionally test the connection with a ping
    $solr = new Solr();
    $solr->_solr_url = $solr_url;
    return $solr;
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

  public static function quoteQuery($q) {
    if ($q) {
      return $q;
    }
    return '*:*';
  }

  public static function quoteTerm($str) {
    if (0==preg_match('/^[a-zA-Z]+$/', $str)) { // not just roman alphabet?
      return '"' . preg_replace('/"/', ' ', $str) . '"';
    }
    return $str;
  }

  function _construct_url($query, $rows, $start) {
    $facets = array();
    foreach ($this->_facets as $field => $value) {
      $facets[] = urlencode($field) . ":" . urlencode(Solr::quoteTerm($value));
    }
    $fq = implode('+', $facets);
    return $this->_solr_url . 'select?indent=on&version=2.2' . 
      '&q=' . urlencode(Solr::quoteQuery($query)) . 
      '&start=' . urlencode($start) . 
      '&rows=' . urlencode($rows) . 
      '&fl=' . urlencode($this->_fl) .
      ($this->_sort == null ? '' : '&sort=' . urlencode($this->_sort)) . 
      '&fq=' . $fq . 
      '&qt=standard&wt=standard&explainOther=&hl.fl=';
  }

  // returns a map of properties mirroring the Solr result set
  public function select($query, $rows=10, $start=0) {
    $status = null;
    $QTime = null;
    $result = array();
  
    $xml = Solr::_http_get($this->_construct_url($query, $rows, $start));
    #print_r($this->_construct_url($query, $rows, $start));
    
    if (!$xml) {
      // HTTP error, etc
    }
    else {
      $dom = DOMDocument::loadXML($xml);
      $xpath = new DOMXPath($dom);

      $status = $xpath->evaluate('/response/lst[@name="responseHeader"]/int[@name="status"]')->item(0)->textContent;
      $qtime = $xpath->evaluate('/response/lst[@name="responseHeader"]/int[@name="QTime"]')->item(0)->textContent;
      $result = array();
      if ($status=='0') {
        foreach ($xpath->evaluate('/response/result/doc') as $node) {
          $doc = array();
          foreach ($xpath->evaluate('*', $node) as $field) {
            $field_name = $field->attributes->getNamedItem('name')->textContent;
            $arr = $xpath->evaluate('str', $field);
            // is it an array of string values?
            if ($arr->length > 0) {
              $field_values = array();
              foreach ($arr as $field_value) {
                $field_values[] = $field_value->textContent;
              }
              $doc[$field_name] = $field_values;
            }
            else {
              // just convert it to text
              $doc[$field_name] = $field->textContent;
            }
          }
          $result[] = $doc;
        }
      }
    }
    return array(
      'status' => $status,
      'QTime' => $qtime,
      'result' => $result,
    );
  }

  // Returns TRUE if the server sends a good response.
  public function ping() {
    $xml = Solr::_http_get($this->_solr_url . 'admin/ping');
    if (is_null($xml) || $xml=='') {
      return FALSE;
    }
    $dom = DOMDocument::loadXML($xml);
    $xpath = new DOMXPath($dom);
    $status = $xpath->evaluate('/response/lst[@name="responseHeader"]/int[@name="status"]')->item(0)->textContent;
    if ($status=='0') {
      return TRUE;
    }
    return FALSE;
  }
}

#$solr = Solr::connect('http://192.168.56.1:8080/solr/');
#print("Ping: " . $solr->ping());

//$solr->setFl('id,feed_id,date_added,score');
//$solr->setSort('date_added desc');
//$result = $solr->select('Radiohead');
//print_r($result);

?>
