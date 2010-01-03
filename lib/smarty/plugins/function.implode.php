<?php
/**
 * Implode an array to a string
 *
 * Type:     function<br>
 * Name:     implode<br>
 * Purpose:  concatenate the elements of an array using an optional separator<br>
 *
 * @param array $params URL parameters
 * @param object $smarty Smarty object
 * @param object $template template object
 * @return string
 */
function smarty_function_implode($params, $smarty, $template)
{
	if ($params['separator']) {
		$s = $params['separator'];
	}
	else {
		$s = '';
	}
	$a = $params['from'];
	return implode($s, array_map('htmlspecialchars', $a));
}
?>
