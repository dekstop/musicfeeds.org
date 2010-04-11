<?
require_once('HTML.class.php');

function sanitise_renderer($property, $encoder, $args) {
  if ($property->is_null()) return $property;
  return Sandbox::wrap(HTML::sanitise($property->raw()), $encoder);
}

?>