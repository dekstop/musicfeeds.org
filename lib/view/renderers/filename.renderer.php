<?

function filename_renderer($property, $args) {
  if ($property->is_null() || $property->is_empty_string() || strrpos($property, '/')===FALSE) {
    return $property;
  }
  return urldecode(substr($property, strrpos($property, '/') + 1));
}

?>