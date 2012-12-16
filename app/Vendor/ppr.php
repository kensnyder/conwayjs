<?php

// DEFAULT OPTIONS
$GLOBALS['ppr_mode'] = 'screen'; // mode = screen | popup | email | log | off
$GLOBALS['ppr_char_limit'] = 200;//1024 * 500;
$GLOBALS['ppr_str_limit'] = 1024 * 4;
$GLOBALS['ppr_backtrace_str_limit'] = 100;
$GLOBALS['ppr_backtrace_array_limit'] = 6;
$GLOBALS['ppr_backtrace_arg_limit'] = 5;
$GLOBALS['ppr_mailto'] = @$_SERVER['SERVER_ADMIN'];





function ppr() {
	list ($file, $line) = _ppr_get_file_line(debug_backtrace());
	$args = _ppr_format_args(func_get_args());
	ppr_display_in_mode('ppr', $file, $line, $args);
}

function pprd() {
	list ($file, $line) = _ppr_get_file_line(debug_backtrace());
	$args = _ppr_format_args(func_get_args());
	ppr_display_in_mode('ppr', $file, $line, $args);
	die;
}

function pprs() {
	list ($file, $line) = _ppr_get_file_line(debug_backtrace());
	$args = _ppr_format_args(func_get_args());
	ob_start();
	ppr_display_in_mode('ppr', $file, $line, $args);
	return ob_get_clean();
}

function ppr_table($rows) {
	list ($file, $line) = _ppr_get_file_line(debug_backtrace());
	ppr_display_in_mode('ppr_table', $file, $line, $rows);
}

function ppr_backtrace() {
	$bt = debug_backtrace();
	list ($file, $line) = _ppr_get_file_line($bt);
	array_shift($bt);
	ppr_display_in_mode('ppr_backtrace', $file, $line, $bt);
}

function ppr_db_log() {
	$sources = ConnectionManager::sourceList();
	foreach ($sources as $source):
		$db =& ConnectionManager::getDataSource($source);
		if (!$db->isInterfaceSupported('getLog')):
			continue;
		endif;
		ppr($db->getLog());
	endforeach;
}

function ppr_count() {
	static $count = 0;
	return ++$count;
}

function ppr_mode_screen_ppr($file, $line, $value) {
	echo _ppr_get_style();
	echo '<pre class="ppr">';
	echo "ppr() from $file($line)\n";
	echo _ppr_get_html($value);
	echo '</pre>' . "\n";
	echo _ppr_get_js();
}

function ppr_mode_screen_ppr_backtrace($file, $line, $bt) {
	$lines = '';
	$count = count($bt);
	foreach ($bt as $i => $t) {
		$lines .= '<span class="ppr-bt-no">#' . ($count - $i - 1) . '</span> ';
		if (isset($t['file'])) {
			$lines .= '<span class="ppr-bt-path">' . _ppr_backtrace_normalize_path($t['file']) . '</span>';
		}
		else {
			$lines .= '<span class="ppr-bt-path">{unknown} </span>';
		}
		if (isset($t['line'])) {
			$lines .= '<span class="ppr-bt-line">(' . $t['line'] . ')</span> ';
		}
		if (isset($t['class'])) {
			$lines .= '<span class="ppr-bt-class">' . $t['class'] . $t['type'] . '</span>';
		}
		$lines .= '<span class="ppr-bt-fn">' . $t['function'] . '(</span>';
		$args = array();
		foreach ($t['args'] as $i => $arg) {
			if ($i > $GLOBALS['ppr_backtrace_arg_limit'] - 1) {
				break;
			}
			if ($arg === false || $arg === true) {
				$args[] = _ppr_get_html_bool($arg);
			}
			elseif ($arg === null) {
				$args[] = _ppr_get_html_null();
			}
			elseif (is_resource($arg)) {
				$args[] = _ppr_get_html_resource($arg);
			}
			elseif (is_array($arg)) {
				$args[] = '<span class="ppr-array">array(</span>' . _ppr_backtrace_array($arg) . '<span class="ppr-array">)</span>';
			}
			elseif (is_object($arg)) {
				$class = get_class($arg);
				$args[] = '<span class="ppr-object">' . $class . '{</span>'
					. _ppr_backtrace_array(get_object_vars($arg))
					. '<span class="ppr-object">}</span>';
			}
			elseif (is_float($arg)) {
				$args[] = _ppr_get_html_float($arg);
			}
			elseif (is_int($arg)) {
				$args[] = _ppr_get_html_int($arg);
			}
			else {
				$args[] = '<span class="ppr-string"><span class="ppr-lquot">&ldquo;</span>' . _ppr_backtrace_string($arg) . '<span class="ppr-rquot">&rdquo;</span></span>';
			}
		}
		if (count($t['args']) > $GLOBALS['ppr_backtrace_arg_limit']) {
			$diff = count($t['args']) - $i;
			$args[] = '<span class="ppr-max">' . $diff . ' more args...</span>';
		}
		$lines .= join(', ', $args);
		$lines .= '<span class="ppr-bt-fn">)</span>';
		$lines .= "\n";
	}

	echo _ppr_get_style();
	echo '<pre class="ppr">';
	echo "ppr_backtrace() from $file($line)\n";
	echo $lines;
	echo '</pre>' . "\n";
}

function ppr_mode_screen_ppr_table($file, $line, $rows) {
	echo _ppr_get_style();
	echo '<pre class="ppr">';
	echo "ppr_table() from $file($line)\n";
	if (count($rows) == 0) {
		echo '[Empty Table]</pre>' . "\n";
	}
	else {
		echo '<pre class="ppr"><table class="ppr-rs" border="1"><tr>';
		foreach ($rows[0] as $th => $v) {
			echo "<th>$th</th>";
		}
		echo '</tr>';
		foreach ($rows as $row) {
			echo '<tr>';
			foreach ($row as $value) {
				echo '<td>' . _ppr_get_html($value, 1) . '</td>';
			}
			echo '</tr>';
		}
		echo '</table></pre>' . "\n";
		echo _ppr_get_js();
	}
}

function ppr_mode_file($method, $file, $line, $value) {
	$fn = "ppr_mode_screen_$method";
	ob_start();
	$fn($file, $line, $value);
	$html = ob_get_clean();

	error_log(_ppr_html_to_plain($html));
}

function ppr_mode_email($method, $file, $line, $value) {
	$fn = "ppr_mode_screen_$method";
	ob_start();
	$fn($file, $line, $value);
	$html = ob_get_clean();

	$domain = $_SERVER['HTTP_HOST'];
	ppr_mail("$domain: $method from $file($line)", _ppr_html_to_plain($html));
}

function ppr_mode_popup($method, $file, $line, $value) {
	$fn = "ppr_mode_screen_$method";
	ob_start();
	$fn($file, $line, $value);
	$html = ob_get_clean();

	echo '
<script type="text/javascript">
(function() {
var popup = window.open();
popup.document.write(\'' . _ppr_escape_js($html) . '\');
popup.document.close();
})();
</script>
';
}






function _ppr_get_file_line($bt) {
	return array(
		str_replace($_SERVER['DOCUMENT_ROOT'].'/','',$bt[0]['file']),
		$bt[0]['line'],
	);
}

function ppr_display_in_mode($method, $file, $line, $values) {
	$fnSpecific = "ppr_mode_" . $GLOBALS['ppr_mode'] . '_' . $method;
	if (function_exists($fnSpecific)) {
		$fnSpecific($file, $line, $values);
	}
	$fnGeneral = "ppr_mode_" . $GLOBALS['ppr_mode'];
	if (function_exists($fnGeneral)) {
		$fnGeneral($method, $file, $line, $values);
	}
}

function _ppr_backtrace_string($s) {
	$len = strlen($s);
	if ($len > $GLOBALS['ppr_backtrace_str_limit']) {
		$s = substr($s, 0, $GLOBALS['ppr_backtrace_str_limit'] - 5) . '...+' . ($len - $GLOBALS['ppr_backtrace_str_limit']);
	}
	$s = str_replace("\r", '\\r', $s);
	$s = str_replace("\n", '\\n', $s);
	$s = _ppr_escape_string($s);
	return $s;
}

function _ppr_escape_string_nonprintable($match) {
	if ($match[0] == ' ') {
		return '<span class="ppr-space"> </span>';
	}
	$hex = dechex( ord($match[0]) );
	if (strlen($hex) == 0) {
		return $match[0];
	}
	$hex = strtoupper($hex);
	if ($hex == '9') {
		// little arrow to represent tab
		return '<span class="ppr-tab">    </span>';
	}
	elseif ($hex == 'A') {
		// newline
		$hex = "\\n\n";
	}
	elseif ($hex == 'D') {
		// carriage return
		$hex = "\\r\r";
	}
	elseif (strlen($hex) == 1) {
		// x08 or less
		$hex = '\\x0' . $hex;
	}
	else {
		// x0B up until space
		$hex = '\\x' . $hex;
	}
	return '<span class="ppr-unprintable">' . $hex . '</span>';
}

function _ppr_escape_string($s) {
	$s = htmlspecialchars((string) $s, ENT_NOQUOTES);
	$s = preg_replace_callback('/[\x00-\x20\x7F]/', '_ppr_escape_string_nonprintable', $s);
	// Replace \r linebreak \n linebreak with \r\n linebreak
	$s = str_replace("<span class=\"ppr-unprintable\">\\r\r</span><span class=\"ppr-unprintable\">\\n\n</span>", "<span class=\"ppr-unprintable\">\\r\\n\n</span>", $s);
	return $s;
}

function _ppr_backtrace_array($array) {
	$members = array();
	$i = 0;
	foreach ($array as $key => $item) {
		if ($i >= $GLOBALS['ppr_backtrace_array_limit'] - 1) {
			break;
		}
		$member = '';
		if ($key !== $i) {
			$member .= '<span class="ppr-bracket">[<span class="ppr-array-key">' . _ppr_backtrace_string($key) . '</span>]</span>';
			$member .= '<span class="ppr-arrow ppr-double-arrow">=&gt;</span>';
		}
		$i++;
		if ($item === false || $item === true) {
			$member .= _ppr_get_html_bool($item);
		}
		elseif ($item === null) {
			$member .= _ppr_get_html_null();
		}
		elseif (is_resource($item)) {
			$member .= _ppr_get_html_resource($item);
		}
		elseif (is_array($item)) {
			$member .= '<span class="ppr-array">array</span>';
		}
		elseif (is_object($item)) {
			$class = get_class($item);
			$member .= '<span class="ppr-array">object ' . $class . '</span>';
		}
		elseif (is_float($item)) {
			$member .= _ppr_get_html_float($item);
		}
		elseif (is_int($item)) {
			$member .= _ppr_get_html_int($item);
		}
		else {
			$member .= '<span class="ppr-string"><span class="ppr-lquot">&ldquo;</span>' . _ppr_backtrace_string($item) . '<span class="ppr-rquot">&rdquo;</span></span>';
		}
		$members[] = $member;
	}
	if (count($array) > $GLOBALS['ppr_backtrace_array_limit']) {
		$diff = count($array) - $i;
		$members[] = '<span class="ppr-max">' . $diff . ' more items...</span>';
	}
	return join(',', $members);
}

function _ppr_backtrace_normalize_path($path) {
	static $pwd;
	if (!$pwd) {
		$pwd = getcwd();
	}
	return str_replace($pwd, '', $path);
}

function _ppr_escape_js($s) {
	$s = preg_replace('/\r\n|\r|\n/', '\\n', $s);
	$s = str_replace("'", "\\'", $s);
	$s = str_replace('</', '<\\/', $s);
	return $s;
}

function _ppr_get_object_vars($obj, $dump = '') {
	if (method_exists($obj, 'pprGetObjectVars')) {
		return $obj->pprGetObjectVars($dump);
	}
	return get_object_vars($obj);
}

function _ppr_format_args($args) {
	if (count($args) == 1) {
		$args = $args[0];
	}
	else {
		$newArgs = array();
		foreach ($args as $i => $a) {
			$newArgs["@arg$i"] = $a;
		}
		$args = $newArgs;
	}
	return $args;
}

$GLOBALS['ppr_char_count'] = 0;
function _ppr_get_html($val, $_maxDepth=16, $_level=0, $_isInObject=false, &$_objIdCache=array()) {
	$out = '';
	if ($val === false || $val === true) {
		$out .= _ppr_get_html_bool($val);
	}
	elseif ($val === null) {
		$out .= _ppr_get_html_null();
	}
	elseif (is_resource($val)) {
		$out .= _ppr_get_html_resource($val);
	}
	elseif (is_array($val)) {
		$out .= _ppr_get_html_array($val, $_maxDepth, $_level, $_isInObject, $_objIdCache);
	}
//	elseif ($val instanceof DOMNodeList) {
//		//$out .= _ppr_get_html_nodelist($val, $_maxDepth, $_level, $_isInObject, $_objIdCache);
//		$nodes = array();
//		foreach ($val as $node) {
//			$nodes[] = '&lt;' . $node->nodeName . '&gt;';
//		}
//		if (count(array_unique($nodes)) == 1) {
//			$list = $nodes[0] . 'x' . $val->length;
//		}
//		else {
//			$list = join(',', $nodes);
//		}
//		$out .= "DOMNodeList[$val->length] { $list }";
//	}
//	elseif ($val instanceof DOMNode) {
//		//$out .= _ppr_get_html_node($val, $_maxDepth, $_level, $_isInObject, $_objIdCache);
//	}	
	elseif (is_object($val)) {
		$out .= _ppr_get_html_object($val, $_maxDepth, $_level, $_isInObject, $_objIdCache);
	}
	elseif (is_float($val)) {
		$out .= _ppr_get_html_float($val);
	}
	elseif (is_int($val)) {
		$out .= _ppr_get_html_int($val);
	}
	else {
		$out .= _ppr_get_html_string($val);
	}
	if (!$_isInObject) {
		$out .= "\n";
	}
	$GLOBALS['ppr_char_count'] = 0;
	return $out;
}

function _ppr_get_html_bool($val) {
	return '<span class="ppr-bool">' . ($val ? 'true' : 'false') . '</span>';
}

function _ppr_get_html_null() {
	return '<span class="ppr-null">null</span>';
}

function _ppr_get_html_resource($val) {
	ob_start();
	var_dump($val);
	$dump = trim(strip_tags(ob_get_clean()));
	if (preg_match('/^.+\((\d+)\)( of type \(([\w_ ]+)\))?$/',$dump,$match)) {
		if ($match[2]) {
			return '<span class="ppr-resource">Resource #' . $match[1] . ' <span class="ppr-resource-type">'.$match[3].'</span></span>';
		}
		else {
			return '<span class="ppr-resource">Resource #' . $match[1] . '</span>';
		}
	}
	return '<span class="ppr-resource">Resource</span>';
}

function _ppr_get_html_array($val, $_maxDepth=16, $_level=0, $_isInObject=false, &$_objIdCache=array()) {
	if ($GLOBALS['ppr_char_limit'] < $GLOBALS['ppr_char_count']) {
		return '<span class="ppr-brace">{</span> <span class="ppr-max">Max Debug Length</span> <span class="ppr-brace">}</span>';
	}
	$indent = str_repeat('    ',$_level);
	$indentNext = str_repeat('    ',$_level+1);

	$out = '';
	if ($_maxDepth == 0) {
		$out .= '<span class="ppr-array">Array <span class="ppr-brace">{</span> <span class="ppr-max">Max Depth</span> <span class="ppr-brace">}</span></span>';
	}
	else {
		if ($_isInObject) {
			foreach ($val as $k => $v) {
				$out .= $indentNext . '<span class="ppr-array-key ppr-object-prop">' . _ppr_escape_string($k) . '</span>';
				$out .= ' <span class="ppr-arrow ppr-single-arrow">-&gt;</span> ' .  _ppr_get_html($v, $_maxDepth-1, $_level+1, false, $_objIdCache);
			}
		}
		else {
			$out .= '<span class="ppr-collapser">  </span><span class="ppr-array">Array <span class="ppr-parens">(</span></span><span class="ppr-collapsible">' . "\n";
			foreach ($val as $k => $v) {
				$out .= $indentNext . '<span class="ppr-bracket">[<span class="ppr-array-key">' . _ppr_escape_string($k) . '</span>]</span>';
				$out .= ' <span class="ppr-arrow ppr-double-arrow">=&gt;</span> ' .  _ppr_get_html($v, $_maxDepth-1, $_level+1, false, $_objIdCache);
			}
			$count = count($val);
			if ($count > 0) {
				$elipses = $count . ' item' . ($count == 1 ? '' : 's');
				$out .= $indent . '</span><span class="ppr-elipses" style="display:none"> '.$elipses.' </span><span class="ppr-parens">)</span>';
			}
			else {
				$out .= $indent . '</span><span class="ppr-parens">)</span>';
			}
		}
	}
	$GLOBALS['ppr_char_count'] += strlen($out);
	return $out;
}

function _ppr_get_html_object($val, $_maxDepth=16, $_level=0, $_isInObject=false, &$_objIdCache=array()) {
	if ($GLOBALS['ppr_char_limit'] < $GLOBALS['ppr_char_count']) {
		return '<span class="ppr-brace">{</span> <span class="ppr-max">Max Debug Length</span> <span class="ppr-brace">}</span>';
	}
	$indent = str_repeat('    ',$_level);

	$out = '';
	ob_start();
	var_dump($val);
	$dump = trim(strip_tags(ob_get_clean())); // xdebug adds in html tags
	if (preg_match('/^\w+\(([\w_]+)\)(#\d+|\[\d+\])/',$dump,$match)) {
		$class = $match[1];
		$id = ' ' . $match[2];
	}
	else {	
		// casting with (object)
		$class = 'stdClass';
		$id = '';
	}

	if ($_maxDepth <= 1 || isset($_objIdCache[$id])) {
		$text = (isset($_objIdCache[$id]) ? 'Recursion' : 'Max Depth');
		$out .= '<span class="ppr-object">Object' . $id .' <span class="ppr-class">'.$class.'</span> <span class="ppr-brace">{</span> <span class="ppr-max">'.$text.'</span> <span class="ppr-brace">}</span></span>';
	}
	else {
		if ($id) {
			$_objIdCache[$id] = 1;
		}
		$props = _ppr_get_object_vars($val, $dump);
		$out .= '<span class="ppr-collapser">  </span><span class="ppr-object">Object' . $id .' <span class="ppr-class">'.$class.'</span> <span class="ppr-brace">{</span></span><span>';
		if (is_array($props) || is_object($props)) {
			$out .= "\n";
			$out .= _ppr_get_html($props,$_maxDepth-1,$_level,true,$_objIdCache);
			$out .= $indent;
			$count = count($props);
			$elipses = $count . ' propert' . ($count == 1 ? 'y' : 'ies');
		}
		else {
			$out .= _ppr_get_html($props,$_maxDepth-1,$_level,true,$_objIdCache);
			$elipses = "value";
			$count = 1;
		}
		if ($count > 0) {
			$out .= '</span><span class="ppr-elipses" style="display:none"> '.$elipses.' </span><span class="ppr-brace">}</span>';
		}
		else {
			$out .= '</span><span class="ppr-brace">}</span>';
		}
	}
	$GLOBALS['ppr_char_count'] += strlen($out);
	return $out;
}

function _ppr_get_html_float($val) {
	return '<span class="ppr-float">' . $val . '</span>';
}

function _ppr_get_html_int($val) {
	return '<span class="ppr-int">' . $val . '</span>';
}

function _ppr_get_html_string($val) {
	$val = (string) $val;
	if (strlen($val) > $GLOBALS['ppr_str_limit'] + 5) {
		$first = _ppr_escape_string(substr($val, 0, $GLOBALS['ppr_str_limit']));
		$last = _ppr_escape_string(substr($val, $GLOBALS['ppr_str_limit']));
		$content =  $first . '<span class="ppr-elipses">...</span><span class="ppr-more" title="Click to show full text">  </span><span style="display:none">' . $last . '</span><span class="ppr-less" style="display:none">  </span>';
	}
	else {
		$content = _ppr_escape_string($val);
	}

	return '<span class="ppr-string"><span class="ppr-lquot">&ldquo;</span>' . $content . '<span class="ppr-rquot">&rdquo;</span></span>';
}

function _ppr_get_style() {
	static $style_printed = false;
	if ($style_printed) {
		return '';
	}
	$style_printed = true;
//	return '<style>' . file_get_contents(dirname(__FILE__) . '/ppr.css') . '</style>';	
	$css = <<<CSS
<style type="text/css">.ppr{font-family:Monaco,"Bitstream Vera Sans Mono",Consolas,"Courier New",monospace;font-size:12px;line-height:130%;word-wrap:break-word;white-space:pre-wrap;background-color:rgb(255,255,255);background-color:rgba(255,255,255,0.92);border:1px solid #888;padding:5px;text-align:left;clear:both}.ppr-array,.ppr-object,.ppr-resource{font-style:italic}.ppr-brace,.ppr-parens{font-style:normal}.ppr-string{color:#080;font-weight:normal}.ppr-unprintable{color:#C500FF}.ppr-space{background:#FFFCD2 url('data:image/gif;base64,R0lGODlhAgACAIAAANzeeAAAACH5BAQAAAAALAAAAAACAAIAAAIChFEAOw==') no-repeat 50% 50%}.ppr-tab{background:#F8B9F3 url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAALCAYAAACgR9dcAAAAZElEQVQokZ2QQQrAIAwEJ8VPVQ/26fagPiu9VCpU0LinsGR2YUVVsaoKUSEdZrLTElyEvA0DfhTgqhAtAV4JzZDMxmKtWeCaPSmk9yxeCW1tdyr3DC7ygb2/OtgPXIZHoKV5qAcnDh/cIfxNfwAAAABJRU5ErkJggg==') no-repeat 50% 50%}.ppr-lquot{color:#777;font-family:"Times New Roman",Times,serif;font-weight:bold;font-size:10px;margin-right:2px}.ppr-rquot{color:#777;font-family:"Times New Roman",Times,serif;font-weight:bold;font-size:10px;margin-left:2px}.ppr-arrow{color:#656DA9}.ppr-array-key{color:#303030}.ppr-class,.ppr-resource-type{color:#05e;font-weight:bold;font-style:normal}.ppr-null{color:#00e;font-style:italic}.ppr-bool{color:#d70;font-style:italic}.ppr-bracket{color:#bbb}.ppr-float{color:#20e;font-weight:bold}.ppr-int{color:#c00;font-weight:bold}.ppr-max{text-transform:uppercase;font-style:italic;color:#b66;font-weight:bold}.ppr-collapser{cursor:pointer;width:9px;background:url('data:image/gif;base64,R0lGODlhCQAJAOMMADFKY5SlvZylvZytxqW11qm92r3GxrnK5MbW69jh8efv9+vz/////////////////yH5BAEKAA8ALAAAAAAJAAkAAAQrMI0pRAhmsL2XAhrnHaCoJAUgMEDbpgHjJQlCAMFcH3egIIcCYQgwuI6GCAA7') no-repeat 0 2px}.ppr-collapsed{background:url('data:image/gif;base64,R0lGODlhCQAJAOMMADFKY5SlvZylvZytxqW11qm92r3GxrXI48bS59Te8efv9+vz/////////////////yH5BAEKAA8ALAAAAAAJAAkAAAQuMI0pRAhmsL2XAtoGeAjIAUpSAAIDvO8aMB6QIAQQeAmAHLmA4lcgGAEGmNIQAQA7') no-repeat 0 2px}.ppr-more{cursor:pointer;width:9px;background:url('data:image/gif;base64,R0lGODlhCQAJAIQPADFKY2l9k3CDmHeHmJSlvZylvZytxqW11qm92r3GxrnK5MbW69jh8efv9+vz/////////////////////////////////////////////////////////////////////yH5BAEKABAALAAAAAAJAAkAAAUyIGOMRUEQifGsq9MA6jMArgKzAsAgQNE2AQCP8HAFFwcAwQhYKJKEhhNxqAISgKz2GgIAOw%3D%3D') no-repeat 100% 2px}.ppr-less{cursor:pointer;width:9px;background:url('data:image/gif;base64,R0lGODlhCQAJAIQPADFKY2N5kWl9k3CDmJSlvZylvZytxqW11qm92r3GxrnK5MbW69jh8efv9+vz/////////////////////////////////////////////////////////////////////yH5BAEKABAALAAAAAAJAAkAAAU0IGOMRUEQifGsq9MA6goMjQLLNIMAxTM3DB2A8HAABIzFYehiAAKKJaGxUCAOWEACwO1qQwA7') no-repeat 100% 100%}</style>
CSS;
	return $css;
}

function _ppr_get_js() {
	static $js_printed = false;
	if ($js_printed) {
		return '<script>pprDecorate()</script>';
	}
	$js_printed = true;
//	return '<script>' . file_get_contents(dirname(__FILE__) . '/ppr.js') . '</script>';
	$js = <<<JS
<script>(function(a){var b=document.querySelectorAll?function(a,b,c){var d=a.querySelectorAll("."+b);for(var e=0,f=d.length;e<f;e++)d[e].onclick=c}:function(a,b,c){var d=a.getElementsByTagName("*"),e=new RegExp("\\b"+b+"\\b");for(var f=0,g=d.length;f<g;f++)e.test(d[f].className)&&(d[f].onclick=c)},c=function(a){a.style.display="none"},d=function(a){a.style.display=""},e=function(){var a=this.nextSibling.nextSibling;a.style.display=="none"?(this.className=this.className.replace(" ppr-collapsed",""),d(a),c(a.nextSibling)):(this.className+=" ppr-collapsed",c(a),d(a.nextSibling))},f=function(){c(this),c(this.previousSibling),d(this.nextSibling),d(this.nextSibling.nextSibling)},g=function(){var a;c(this),c(a=this.previousSibling),d(a=a.previousSibling),d(a.previousSibling)};a.pprDecorate=function(){var a=document.getElementsByTagName("pre"),c=a[a.length-1];b(c,"ppr-collapser",e),b(c,"ppr-more",f),b(c,"ppr-less",g)},a.pprDecorate()})(window)</script>
JS;
	return $js;
}

function _ppr_html_to_plain($html) {
	$html = str_replace('&ldquo;','"', $html);
	$html = str_replace('&rdquo;','"', $html);
	$html = str_replace('&nbsp;'," ", $html);

	$str = strip_tags($html);
	$str = html_entity_decode($str);
	$str = trim($str);
	return $str;
}

function ppr_mail($subject, $body) {
	mail($GLOBALS['ppr_mailto'], $subject, $body);
}
