<?
require_once('HTML.class.php');

function urlencode_renderer($property, $encoder, $args) {
  if ($property->is_null()) return $property;
  return Sandbox::wrap(urlencode($property->raw()), $encoder);
}

?>