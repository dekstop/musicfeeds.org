<?

function _buildSearchContext() {
  global $APP_ENV;
  $request = new Request();
  $request->setEnv($APP_ENV);
  $sc = new SearchContext($request);
  return $sc;
}

function _makeBaseUrl($base) {
  global $appUrl;
  $base = Sandbox::unwrap($base);
  if (is_null($base)) $base = $appUrl;
  return $base;
}

function buildCategorySearchUrl($category, $base=null) {
  $sc = _buildSearchContext();
  $sc->setQ(array('category' => Sandbox::unwrap($category)));
  return $sc->buildUrl(_makeBaseUrl($base));
}

function buildFeedSearchUrl($feed_id, $base=null) {
  $sc = _buildSearchContext();
  $sc->setQ(array('feed_id' => Sandbox::unwrap($feed_id)));
  $sc->setM($sc->getN());
  return $sc->buildUrl(_makeBaseUrl($base));
}

function buildSearchUrl($searchContext, $base=null) {
  $sc = Sandbox::unwrap($searchContext);
  return $sc->buildUrl(_makeBaseUrl($base));
}

?>