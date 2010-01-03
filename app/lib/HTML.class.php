<?

class HTML {

	static $whitelisted_tags = array(
		'a' => array(
			'href' => '/^([^ ]+|"[^"]+")$/',
		),
		'img' => array(
			'src' => '/^([^ ]+|"[^"]+")$/',
			'width' => '/^([^ ]+|"[^"]+")$/',
			'height' => '/^([^ ]+|"[^"]+")$/',
			'alt' => '/^([^ ]+|"[^"]+")$/',
			'title' => '/^([^ ]+|"[^"]+")$/',
		),
		'p' => array(),
		'div' => array(),
		'span' => array(),
		'br' => array(),
		'hr' => array(),
		
		'b' => array(),
		'i' => array(),
		'strong' => array(),
		'em' => array(),
		'small' => array(),
		'big' => array(),
		'sup' => array(),
		'sub' => array(),
		'strike' => array(),
		'u' => array(),
		
		'table' => array(),
		'tr' => array(),
		'th' => array(),
		'td' => array(),
		
		'ul' => array(),
		'ol' => array(),
		'li' => array(),
		
		'blockquote' => array(),
		'code' => array(),
		'pre' => array(),
		'tt' => array(),
	);

	// returns an empty string, or a string containing just whitelisted attributes prefixed with a space.
	static function _sanitise_attrs($tag, $attrs_str) {
		$result = '';
		if ($attrs_str=='' || preg_match('/^\s+$/', $attrs_str)) {
			return $result;
		}
		preg_match_all('/\s*([^= ]+)(?:=([^" ]+|"[^"]+"))?/', $attrs_str, $attrs, PREG_SET_ORDER);
		foreach($attrs as $attr) {
			$name = $attr[1];
			$value = $attr[2];
			if (array_key_exists($name, HTML::$whitelisted_tags[$tag])) { // whitelisted attribute?
				if (preg_match(HTML::$whitelisted_tags[$tag][$name], $value)) { // attribute value matches defined pattern?
					$result .= " $name=$value";
				}
			}
		}
		return $result;
	}

	// TODO: this should be a stack, not a hash. (Though the advantage of a hash is that 
	// we can easily keep count of open tags and make sure to close them all, even if not
	// in proper order.)
	static $open_tags = array();

	static function _close_open_tags() {
		$result = '';
		foreach (array_reverse(HTML::$open_tags, true) as $tag => $count) {
			for ($i=0; $i<$count; $i++) {
				$result .= "</$tag>";
			}
		}
		return $result;
	}

	static function _sanitise_preg_callback($matches) {
		$wtags = array_keys(HTML::$whitelisted_tags);
		$slash = $matches[1];
		$tag = $matches[2];
		$attrs_str = $matches[3];
		$slash2 = $matches[4];
		if (!in_array($tag, $wtags)) {
			return '';
		}
		if ($slash=='' and $slash2=='') {
			HTML::$open_tags[$tag]++;
		}
		else if ($slash=='/') {
			HTML::$open_tags[$tag]--;
		}
		return '<' . $slash . $tag . HTML::_sanitise_attrs($tag, $attrs_str) . $slash2 . '>';
	}

	// strip non-whitelisted tags and attributes
	public static function sanitise($html) {
		HTML::$open_tags = array();
		return preg_replace_callback('!<(/?)([^>]*?)( [^>]*?)?(?:(/?)>|$)!', array('HTML', '_sanitise_preg_callback'), $html) . HTML::_close_open_tags();
	}

	// trim to a number of non-tag (=="visible") characters. doesn't cut within tag boundaries, closes open tags.
	// the HTML returned is always sanitised.
	public static function excerpt($html, $maxlen) {
		// TODO: apply maxlen only to visible characters, not tags. don't cut within a tag.
		if (strlen($html) > $maxlen) {
                        $html = substr($html, 0, $maxlen);
			// remove trailing open tag
			$html = preg_replace('/ *<[^>]+$| *$/', '', $html);
			$html .= '&hellip;';
                }
		return HTML::sanitise($html);
	}
}

class TestAssertionFailedException extends Exception { }

function assert_sanitised_equals($expected, $html) {
	if (HTML::sanitise($html)!=$expected) {
		throw new TestAssertionFailedException(
			"Test assertion failed: Result of HTML::sanitise('$html') is '" . 
			HTML::sanitise($html) . "', expected result is '$expected'.");
	}
}

function test() {
	assert_sanitised_equals(null, null);
	assert_sanitised_equals('', '');
	assert_sanitised_equals('', '<');
	assert_sanitised_equals('<p>Another Fine For Contest Rules</p>', '<p>Another Fine For Contest Rules</p>');
	assert_sanitised_equals('<p>Another Fine For Contest Rules</p>', '<p>Another Fine For Contest Rules'); // add missing closing tags
	assert_sanitised_equals('abc', '<fdgsdg>abc</fdgsdg>'); // strip non-whitelisted tags
	assert_sanitised_equals('Another Fine For Contest Rules', 'Another Fine For Contest Rules<asd'); // strip unclosed trailing tags when not whitelisted
	assert_sanitised_equals('<p>Another Fine For Contest Rules</p>', '<p>Another Fine For Contest Rules</p'); // close unclosed trailing tags when whitelisted
	assert_sanitised_equals('<a href="http://dekstop.de/">dekstop.de</a>', '<a href="http://dekstop.de/" onclick="alert()">dekstop.de</a>'); // strip non-whitelisted attributes
	assert_sanitised_equals('<img/>', '<img width= />'); // strip incomplete attributes
	assert_sanitised_equals('<img width=10/>', '<img width=10/>'); // we want to support this oldschool HTML attribute style, even if invalid
	assert_sanitised_equals('<img src="test image.jpg"/>', '<img src="test image.jpg"/>'); // same with unescaped spaces in URLs
}

//test();
?>
