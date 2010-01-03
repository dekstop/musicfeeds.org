<?php

require_once('plugins/modifier.escape.php');

/**
* Smarty escape modifier plugin
* 
* Type:     modifier<br>
* Name:     e<br>
* Purpose:  an alias for <tt>|escape:'htmlall'</tt>
* 
* @param string $string input string
*/
function smarty_modifier_e($string) {
	return smarty_modifier_escape($string, 'htmlall');
} 

?>
