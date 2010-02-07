<?
require_once('HTML.class.php');

function with_subtitle_renderer($property, $args) {
  if (count($args)!=1 && count($args)!=2) throw new Exception('Function requires one or two parameters. Provided: ' . count($args));
  
  $title = $property;
  $subtitle = SandBox::wrap($args[0]);  // allow strings and Properties as input
  if (count($args)==2) {
    $separator = SandBox::wrap($args[1]); // allow strings and Properties as input
  }
  else {
    $separator = SandBox::wrap(' – ');
  }

  // return raw values to prevent double-escaping:
  if ($subtitle->is_empty()) {
    return $title->raw();
  }
  return $title->raw() . $separator->raw() . $subtitle->raw();
}

?>