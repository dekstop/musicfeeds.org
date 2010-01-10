<?

/**
 * Encapsulates a request including its environment.
 */
class Request {
  
  const DEFAULT_ACTION = 'index';
  
  var $_env = array();
  
  var $_namespace;
  var $_controller;
  var $_action;
  
  function Request() {
    $this->_parse($_GET);
  }
  
  /**
   * Only returns the input string if it starts with an alphanumeric character
   * and exclusively consists of alphanumeric characters or underscores. 
   * Otherwise returns an empty string.
   * 
   * This is mostly for security reasons, but also allows us to hide 
   * functions/classes/directories by prefixing their name with an underscore
   * character.
   */
  static function _cleanPathElement($pathElement) {
    if (is_null($pathElement)) {
      return '';
    }
    if (preg_match('/^[a-zA-z0-9][a-zA-z0-9_-]*$/', $pathElement)==0) {
      return '';
    }
    return $pathElement;
  }
  
  function _parse($array) {
    $this->_namespace = Request::_cleanPathElement($this->_get($array, 'namespace', null));
    $this->_controller = Request::_cleanPathElement($this->_get($array, 'controller'));
    $this->_action = Request::_cleanPathElement($this->_get($array, 'action', Request::DEFAULT_ACTION));
  }
  
  /**
   * Returns $default if the given key is not provided, or points to an empty string.
   */
  static function _get($array, $key, $default=null) {
    if (array_key_exists($key, $array) && $array[$key]!='') {
      return $array[$key];
    }
    return $default;
  }
  
  /**
   * Handles unescaping of magic_quotes as used for Get/Post/Cookie vars.
   */
  static function _unescape($v) {
    // cf http://www.php.net/manual/en/info.configuration.php#ini.magic-quotes-gpc
    return get_magic_quotes_gpc() ? stripslashes($v) : $v;
  }
  
  function getVar($key, $default=null) {
    return Request::_unescape(Request::_get($_GET, $key, $default));
  }
  
  function getString($key, $default=null) {
    return $this->getVar($key, $default);
  }
  
  function getInt($key, $default=null) {
    return (int)$this->getVar($key, $default);
  }
  
  function postVar($key, $default=null) {
    return Request::_unescape(Request::_get($_POST, $key, $default));
  }
  
  function postString($key, $default=null) {
    return $this->postVar($key, $default);
  }
  
  function postInt($key, $default=null) {
    return (int)$this->postVar($key, $default);
  }
  
  function setEnv($array) {
    $this->_env = $array;
  }
  
  function getEnv() {
    return $this->_env;
  }
  
  function envVar($key, $default=null) {
    return Request::_get($this->_env, $key, $default);
  }
  
  function envString($key, $default=null) {
    return $this->envVar($key, $default);
  }
  
  function envInt($key, $default=null) {
    return (int)$this->envVar($key, $default);
  }
  
  function getNamespace() {
    return $this->_namespace;
  }
  
  function getController() {
    return $this->_controller;
  }
  
  function getAction() {
    return $this->_action;
  }
}

?>