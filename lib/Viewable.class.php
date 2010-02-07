<?

/**
 * Adapter interface that allows plugging in different template engines.
 * Viewables are classes that receive a set of named variables, and then
 * compile and display a named template document.
 */
interface Viewable {
  
  /**
   * Implementations always receive a Request instance after construction. Implementation can use this 
   * as a signal to reset their state; though at the moment we don't actually reuse Viewable instances 
   * across multiple requests.
   * TODO: can PHP interfaces be used to mandate constructor method signatures? would be nicer than this.
   */
  function setRequest($request);
  
  /**
   * Set a named variable used during display.
   */
  function setParam($key, $value);
  
  /**
   * Set a map of named variables.
   */
  function setParams($params);
  
  /**
   *
   * $template is the name of the template to display. The naming convention should be
   * template engine-independent, and converted to a template engine-specific form
   * by the Viewable implementation.
   */
  function display($template);
}
?>