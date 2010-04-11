<?
require_once('HTML.class.php');

function excerpt_renderer($property, $encoder, $args) {
  if ($property->is_null()) return $property;
  if (count($args)!=1) throw new Exception('Function requires one parameter. Provided: ' . count($args));
  $html = $property->raw(); // get raw to prevent double-escaping when treating as string
  $len = $args[0]->raw(); // get raw to prevent conversion from int to string
  return Sandbox::wrap(HTML::excerpt($html, $len), $encoder);
}

?>