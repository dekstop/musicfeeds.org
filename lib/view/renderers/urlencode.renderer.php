<?
require_once('HTML.class.php');

function urlencode_renderer($property, $args) {
  if ($property->is_null()) return null;
  return urlencode($property->raw());
}

?>