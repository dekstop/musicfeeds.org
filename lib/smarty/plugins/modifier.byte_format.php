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
function smarty_modifier_byte_format($bytes) {
	$format = '%.2f';
	$div = 1;
	$unit = '';
	if ($bytes < 1024) {
		$format = '%d';
		$unit = ' byte';
	}
	else if ($bytes < 1024 * 1024) {
		$div = 1024;
		$unit = ' KB';
	}
	else if ($bytes < 1024 * 1024 * 1024) {
		$div = 1024*1024;
		$unit = ' MB';
	}
	else {
		$div = 1024*1024*1024;
		$unit = ' GB';
	}
	return sprintf($format, ($bytes / $div)) . $unit;
} 

?>
