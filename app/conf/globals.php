<?

// Global configuration.
// Entries in this map will be available as:
// - environment variables in Request class instances
// - smarty variables in templates constructed by View class instances
$GLOBALS = array(
	'app' => array(
		'name' => 'Music Feeds',
	),
	
	/* TODO
	'display' => array(
		'numHomepageItems' => 10, // default number of items on initial landing page
	  'numQueryItems' => 30, // default number of items per page
		'maxNumItems' => 100, // max threshold
	),
	*/
	
	'smarty' => array(
		'force_compile' => true,
		'debugging' => false,
		'caching' => false,
		'cache_lifetime' => 120,
	)
);

// TODO: move these into globals object above

// conf
$NUM_ENTRIES_WITHOUT_QUERY = 10;
$MAX_ENTRIES = 100;

$solr_url = 'http://127.0.0.1:8080/solr/';
$SOLR_NUM_ENTRIES = 500; // number of entries retrieved by solr. will be filtered down to only those entries owned by the ${feedcache_user} user

$lfm_key = "lfm_key";

$app_title = 'Music Feeds'; // TODO: find mechanism to make this available in all smarty templates (this probably does not belong in smarty conf)
$feedcache_user = 'musicblogs';

$db_name = 'db_name';
$db_user = 'db_user';
$db_pwd = 'db_password';

?>