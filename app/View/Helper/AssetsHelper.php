<?php

App::uses('Helper', 'View');

class AssetsHelper extends AppHelper {
	
	public function js($files) {
		$out = '';
		foreach ($this->getUrls($files) as $js) {
			$out .= "<script src=\"$js\"></script>\n";
		}
		return $out;
	}
	
	public function css($files) {
		$out = '';
		foreach ($this->getUrls($files) as $css) {
			$out .= "<link rel=\"stylesheet\" href=\"$css\" />\n";
		}
		return $out;
	}
	
	public function getUrls($files) {
		if (Configure::read() > 0) {
			// write file to describe how to build
			// maybe app/Config/build.json
			return $files;
		}
		
	}
	
}
