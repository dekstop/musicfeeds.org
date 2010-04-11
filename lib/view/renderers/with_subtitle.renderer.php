<?
require_once('HTML.class.php');

function with_subtitle_renderer($property, $encoder, $args) {
  if (count($args)!=1 && count($args)!=2) throw new Exception('Function requires one or two parameters. Provided: ' . count($args));
  
  $title = SandBox::unwrap($property);
  $subtitle = SandBox::unwrap($args[0]);  // allow strings and Properties as input
  if (count($args)==2) {
    $separator = SandBox::unwrap($args[1]); // allow strings and Properties as input
  }
  else {
    $separator = ' – ';
  }

  // return raw values to prevent double-escaping:
  if (is_null($subtitle) || $subtitle=='') {
    return Sandbox::wrap($title, $encoder);
  }
  return Sandbox::wrap($title . $separator . $subtitle, $encoder);
}

?>