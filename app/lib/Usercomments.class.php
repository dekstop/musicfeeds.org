<?

class Usercomments {

  var $_db;

  function Usercomments($db) {
    $this->_db = $db;
  }
  
  function post($name=null, $email=null, $text=null, $url=null) {
    return $this->_db->insert('INSERT INTO musicfeeds_usercomments(author_name, author_email, comments, url)' .
      'VALUES(?, ?, ?, ?)',
      array($name, $email, $text, $url));
  }
  
  function count() {
    return $this->_db->query('SELECT count(*) FROM musicfeeds_usercomments');
  }
  
  function get($num, $offset=0) {
    return $this->_db->query("SELECT * FROM musicfeeds_usercomments ORDER BY id DESC LIMIT ? OFFSET ?",
      array($num, $offset));
  }
}

?>
