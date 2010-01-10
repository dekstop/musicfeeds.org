<?php
/**
 * Search URL builder
 *
 * Type:     function<br>
 * Name:     searchURL<br>
 * Purpose:  build search URL, properly escaped for inclusion in both HTML and XML documents<br>
 *
 * @param array $params URL parameters
 * @param object $smarty Smarty object
 * @param object $template template object
 * @return string
 */
function smarty_function_searchURL($params, $smarty, $template)
{
  $url = _extract_param($params, 'base', './');
  
  // search query string:
  $url .= '?q=' . urlencode(_extract_param($params, 'q'));
  if ($feed_id = _extract_param($params, 'q')) {
    $url .= '+feed_id:' . urlencode($feed_id);
  }
  if ($category = _extract_param($params, 'category')) {
    $url .= '+category:' . urlencode(Solr::quoteTerm($category)); // part of search query string
  }
  
  // remaining search parameters:
  if ($f = _extract_param($params, 'f')) {
    $url .= '&f=' . formatFacets($f);
  }
  if ($lfmUser = _extract_param($params, 'lfmUser')) {
    $url .= '&lfm%3Auser=' . urlencode($lfmUser);
  }
  
  // and any additional parameters provided:
  foreach ($params as $key=>$value) {
    $url .= '&' . $key . '=' . urlencode($value);
  }
  return $url;
}

function _extract_param(&$params, $key, $default=null) {
  if (array_key_exists($key, $params)) {
    $v = $params[$key];
    unset($params[$key]);
    return $v;
  }
  return $default;
}
?>
