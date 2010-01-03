<?php

require_once('HTML.class.php');

/**
* Smarty HTML::sanitise modifier plugin
* 
* Type:     modifier<br>
* Name:     sanitise<br>
* Purpose:  strip all but a whitelisted subset of HTML tags.
* 
* @param string $string input string
*/
function smarty_modifier_sanitise($string) {
  return HTML::sanitise($string);
} 

?>
