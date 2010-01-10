<?php
/**
 * Convert a PHP variable to a JSON string
 *
 * Type:     function<br>
 * Name:     json_encode<br>
 * Purpose:  convert PHP data structures to a JSON string representation<br>
 *
 * @param array $params URL parameters
 * @param object $smarty Smarty object
 * @param object $template template object
 * @return string
 */
function smarty_function_json_encode($params, $smarty, $template)
{
  return json_encode($params['data']);
}
?>
