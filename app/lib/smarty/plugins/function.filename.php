<?php
/**
 * Extract filename from a URL
 *
 * Type:     function<br>
 * Name:     filename<br>
 * Purpose:  extract the filename part from an URL<br>
 *
 * @param array $params URL parameters
 * @param object $smarty Smarty object
 * @param object $template template object
 * @return string
 */
function smarty_function_filename($params, $smarty, $template)
{
	$url = $params['url'];
	if ($url==null || $url=='' || strrpos($url, '/')===FALSE) {
		return $url;
	}
	return urldecode(substr($url, strrpos($url, '/') + 1));
}
?>
