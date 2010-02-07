<?

function searchUrl($params) {
  $url = _extract_searchUrl_param($params, 'base', './');
  
  // search query string:
  $url .= '?q=' . urlencode(_extract_searchUrl_param($params, 'q'));
  if ($feed_id = _extract_searchUrl_param($params, 'feed_id')) {
    $url .= '+feed_id:' . urlencode($feed_id);
  }
  if ($category = _extract_searchUrl_param($params, 'category')) {
    $url .= '+category:' . urlencode(Solr::quoteTerm($category)); // part of search query string
  }
  
  // remaining search parameters:
  if ($f = _extract_searchUrl_param($params, 'f')) {
    $url .= '&f=' . formatFacets($f);
  }
  if ($lfmUser = _extract_searchUrl_param($params, 'lfmUser')) {
    $url .= '&lfm%3Auser=' . urlencode($lfmUser);
  }
  
  // and any additional parameters provided:
  foreach ($params as $key=>$value) {
    $url .= '&' . $key . '=' . urlencode(Sandbox::unwrap($value));
  }
  return Sandbox::wrap($url);
}

function _extract_searchUrl_param(&$params, $key, $default=null) {
  if (array_key_exists($key, $params)) {
    $v = $params[$key];
    unset($params[$key]);
    return Sandbox::unwrap($v);
  }
  return $default;
}


?>