<?
require_once('HTML.class.php');

function if_equals_renderer($property, $args) {
  if (count($args)!=2 && count($args)!=3) throw new Exception('Function requires two or three parameters. Provided: ' . count($args));
  
  $value = $property;
  $cmp = $args[0];
  $val1 = SandBox::wrap($args[1]); // allow strings and Properties as input
  if (count($args)==3) {
    $val2 = SandBox::wrap($args[2]); // allow strings and Properties as input
  }
  else {
    $val2 = SandBox::wrap('');
  }

  // return raw values to prevent double-escaping:
  if ($value->equals($cmp)) {
    return $val1->raw();
  }
  return $val2->raw();
}

?>