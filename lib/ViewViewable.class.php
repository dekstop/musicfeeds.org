<?

require_once 'Viewable.class.php';

/**
 * A Viewable using the View.php template engine.
 * Excuse the horrible name.
 */
class ViewViewable implements Viewable {
  
  var $_request;
  var $_ctx;
  
  function setRequest($request) {
    $this->_request = $request;
    $this->_ctx = new Context();
  }
  
  function setParam($key, $value) {
    $this->_ctx[$key] = $value;
  }
  
  function setParams($params) {
    foreach ($params as $key=>$value) {
      $this->setParam($key, $value);
    }
  }
  
  function display($template) {
    global $APP_TEMPLATES;
    $view = new View($APP_TEMPLATES);
    $view->display($template, $this->_ctx);
  }
}
?>