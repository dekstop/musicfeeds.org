<?php

require_once('HTML.class.php');

/**
* Smarty HTML::excerpt modifier plugin
* 
* Type:     modifier<br>
* Name:     excerpt<br>
* Purpose:  trim an HTML string to a specific length while ensuring valid HTML.
*           This also calls HTML::sanitise.
* 
* @param string $string input string
* @param int $length maximum character length
*/
function smarty_modifier_excerpt($string, $length) {
	return HTML::excerpt($string, $length);
} 

?>
