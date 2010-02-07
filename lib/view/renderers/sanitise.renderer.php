<?
require_once('HTML.class.php');

function sanitise_renderer($property, $args) {
  if ($property->is_null()) return null;
  return HTML::sanitise($property);
}

?>