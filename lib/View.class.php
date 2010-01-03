<?

class View {
  
  var $_request;
  var $_smarty;
  
  function View($request) {
    $this->_request = $request;
    $this->_smarty = $this->_buildSmartyObj($request->getEnv());
  }
  
  static function _buildSmartyObj($conf) {
    global $APP_CONF, $APP_LIB, $APP_TEMPLATES, $PKG_LIB, $PKG_VAR;
    $smarty = new Smarty();
    $smarty->config_dir = $APP_CONF;
    $smarty->template_dir = $APP_TEMPLATES;
    $smarty->compile_dir = $PKG_VAR . '/templates_c';
    $smarty->cache_dir = $PKG_VAR . '/cache';
    $smarty->plugins_dir = array_merge(
      $smarty->plugins_dir, 
      array(
        $PKG_LIB . '/smarty/plugins',
        $APP_LIB . '/smarty/plugins'));
    if ($conf['smarty.force_compile']) $smarty->force_compile = $conf['smarty.force_compile'];
    if ($conf['smarty.debugging']) $smarty->debugging = $conf['smarty.debugging'];
    if ($conf['smarty.caching']) $smarty->caching = $conf['smarty.caching'];
    if ($conf['smarty.cache_lifetime']) $smarty->cache_lifetime = $conf['smarty.cache_lifetime'];
    return $smarty;
  }
  
  function setParam($key, $value) {
    $this->_smarty->assign($key, $value);     
  }
  
  function setParams($params) {
    foreach ($params as $key=>$value) {
      $this->setParam($key, $value);      
    }
  }
  
  function display($template) {
    $this->_smarty->display($template);
  }
}
?>