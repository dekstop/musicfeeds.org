<?php
/**
 * Search URL builder
 *
 * Type:     function<br>
 * Name:     searchURL<br>
 * Purpose:  build search URL<br>
 *
 * @param array $params URL parameters
 * @param object $smarty Smarty object
 * @param object $template template object
 * @return string
 */
function smarty_function_searchURL($params, $smarty, $template)
{
  if ($params['base']) {
    $url = $base;
    unset($params['base']);
  }
  else {
    $url = './';
  }
  
  $url .= '?q=' . urlencode($params['q']);
  if ($params['feed_id']) {
    $url .= '+feed_id:' . urlencode($params['feed_id']); // part of query string
  }
  if ($params['category']) {
    $url .= '+category:' . Solr::quoteTerm($category); // part of query string
  }
  
  unset($params['q']);
  if ($params['f']) {
    $url .= '&f=' . formatFacets($params['f']);
    unset($params['f']);
  }
  if ($params['lfmUser']) {
    $url .= '&lfm:user=' . urlencode($params['lfmUser']);
    unset($params['lfmUser']);
  }
  foreach ($params as $key=>$value) {
    $url .= '&' . $key . '=' . urlencode($value);
  }
  return $url;
}
?>
