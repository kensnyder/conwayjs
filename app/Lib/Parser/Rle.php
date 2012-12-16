<?php

class Parser_Rle {
	
	public function __construct() {
		
	}
	
	public function getData($rle) {
		$rle = preg_replace('/(\r\n|\r)/', "\n", $rle);
		$data = array(
			'format' => 'rle',
			'comments' => array(),
		);
		$lines = explode("\n", $rle);
		foreach ($lines as $i => $line) {
			if (preg_match('/^#N (.+)/', $line, $match)) {
				$data['name'] = $match[1];
			}
			elseif (preg_match('/^#O (.+)/', $line, $match)) {
				$data['found_by'] = $match[1];
			}
			elseif (preg_match('/^#C (.+)/', $line, $match)) {
				$data['comments'][] = $match[1];
			}
			elseif (preg_match('/^x\s*=\s*(\d+)\s*,\s*y\s*=\s*(\d+)(?:\s*,\s*rule\s*=\s*(\S+))?/', $line, $match)) {
				$data['size_x'] = $match[1];
				$data['size_y'] = $match[2];
				$data['rulestring'] = @$match[3] ? strtoupper($match[3]) : '23/3';
				$bo = join('', array_slice($lines, $i+1));
				@list ($spec, $comments) = explode('!', $bo);
				if (@$comments) {
					$data['comments'][] = $comments;
				}
				$data['spec'] = $spec;
				break;
			}
		}
		$data['comments'] = join("\n", $data['comments']);
		$data['comments'] = preg_replace_callback('~(http://|www\.)\S+~', function($match) use(&$data) {
			$link = $match[0];
			if (substr($link, 0, 3) == 'www') {
				$link = "http://$link";
			}
			$data['link'] = $link;
			return '';
		}, $data['comments']);
		$data['comments'] = preg_replace('/\n+/', '', $data['comments']);
		$data['comments'] = trim($data['comments']);
		return $data;
	}
	
}
