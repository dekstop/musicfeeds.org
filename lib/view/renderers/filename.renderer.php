<?

function filename_renderer($property, $encoder, $args) {
  if ($property->is_null() || $property->is_empty_string() || strrpos($property->raw(), '/')===FALSE) {
    return $property;
  }
  $p = Sandbox::unwrap($property);
  return Sandbox::wrap(urldecode(substr($p, strrpos($p, '/') + 1)), $encoder);
}

?>