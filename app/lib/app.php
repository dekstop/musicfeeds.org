<?

// TODO: move all of these to separate classes

function getDb() {
  global $APP_ENV;
  $dsn = "dbname=${APP_ENV['db.name']} user=${APP_ENV['db.user']} password=${APP_ENV['db.password']}";
  if ($APP_ENV['db.host'] && $APP_ENV['db.host']!='localhost') {
    $dsn = "host=${APP_ENV['db.host']}" . $dsn;
  }
  return DB::connect($dsn);
}

function getSolr() {
  global $APP_ENV;
  return Solr::connect($APP_ENV['solr.url']);
}

?>
