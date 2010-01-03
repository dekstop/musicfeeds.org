<?

// Global app configuration.
// Entries in this map will be available as environment variables in Request class instances.
// The file smarty.conf contains additional configuration that is made available to templates 
// loaded by View instances.
$APP_ENV = array(

  'smarty.force_compile' => true,
  'smarty.debugging' => false,
  'smarty.caching' => false,
  'smarty.cache_lifetime' => 120,
  
  'solr.url' => 'http://127.0.0.1:8080/solr/',
  'solr.fetchSize' => '500',  // number of entries retrieved by solr. 
                              // will be filtered down to only those entries owned by the feedcache.user user
  
  'lastfm.key' => 'lastfm_key',

  'db.host' => 'localhost',
  'db.name' => 'db_name',
  'db.user' => 'db_user',
  'db.password' => 'db_password',

  'feedcache.user' => 'musicblogs',
  
  'display.numHomepageItems' => 10,     // default number of items on initial landing page
  'display.numQueryResultItems' => 30,  // default number of items per page
  'display.maxNumItems' => 100,         // max threshold
);

?>