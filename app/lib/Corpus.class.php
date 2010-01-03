<?

// A mapping of items and scores, with random item selection.
class Corpus {
  
  // map of item -> score, where score in [0..1] and sum(scores)=1
  private $_map = array();
  
  // normalises the score of each item so that the entire set has an aggregated 
  // score of 1.0
  function _normaliseScores($map) {
    $sum = 0;
    foreach ($map as $k => $v) {
      $sum += $v;
    }
    $result = array();
    foreach ($map as $k => $v) {
      $result[$k] = $v / $sum;
    }
    return $result;
  }

  // Add a set of items to the corpus. Expects a map of {object => score} that 
  // contains items and their numeric score. 
  // Individual item scores will get normalised within this set so that the 
  // aggregated score for the entire set is 1.0
  // Optionally accepts a score of this set relative to other submitted sets.
  // If the same item gets submitted several times (by multiple consecutive 
  // calls) its scores accumulate.
  function add($map, $score=1.0) {
    $normalisedMap = Corpus::_normaliseScores($map);
    foreach ($normalisedMap as $k => $v) {
      if (!in_array($k, $this->_map)) {
        $this->_map[$k] = 0;
      }
      $this->_map[$k] += $v * $score;
    }
    arsort($this->_map);
  }
  
  // Returns the number of items.
  function size() {
    return sizeof(array_keys($this->_map));
  }
  
  // Returns the item at position $pos, where item position is the sum of the 
  // scores of all preceding items.
  // Takes a map (item->score), and a position
  // Returns an item
  function _getItemAt($map, $pos) {
    $items = array_keys($map);
    $curpos = 0;
    $idx = 0;
    while ($curpos<=$pos) {
      $curpos += $map[$items[$idx]];
      $idx++;
    }
    return $items[$idx-1];
  }

  // Returns a sample of items, using a normal distributed selection process 
  // based on accumulated item score.
  function getRandom($numitems) {
    $map = $this->_map;
    $size = 1.0;
    $result = array();
    $numitems = min($numitems, $this->size());
    while (sizeof($result)<$numitems) {
      $pos = (float)rand()/(float)getrandmax() * $size;
      $item = $this->_getItemAt($map, $pos);
      $result[] = $item;
      $size -= $map[$item];
      unset($map[$item]);
    }
    return $result;
  }
}

/*
$c = new Corpus();
$c->add(array(
  "a" => 10,
  "b" => 10,
  "c" => 1,
  "d" => 1,
  "e" => 1
  ));
$s = $c->getRandom(2);
print_r($s);
*/
?>
