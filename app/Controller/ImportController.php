<?php

App::uses('AppController', 'Controller');

class ImportController extends AppController {
	
	public $uses = array('GameRule', 'GameShape');
	
	public function test() {
		$rules = $this->GameRule->find('all');
		pprd($rules);
	}
	
	public function files() {
		$dir = '/Users/ksnyder/Sites/retreat/library';
		$parser = new Parser_Rle();
		$i = 0;
		foreach (new DirectoryIterator($dir) as $cat) {
			if ($cat->isDot() || $cat->isFile()) {
				continue;
			}
			$catName = $cat->getBaseName();
			foreach (new DirectoryIterator($cat->getPathname()) as $shape) {
				if ($shape->isDot()) {
					continue;
				}
				$data = array();
				$data['name'] = $shape->getBaseName();
				$data['category'] = $catName;
				$img = false;
				foreach (new DirectoryIterator($shape->getPathName()) as $file) {
					if ($file->isDot()) {
						continue;
					}						
					$name = $file->getFilename();
					$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
					if ($ext == 'png') {
						$imgData = getimagesize($file->getPathname());
						$data = $data + array(
							'image_path' => "/img/shapes/" . $file->getFilename(),
							'image_width' => $imgData[0],
							'image_height' => $imgData[1],
						);
						copy($file->getPathname(), ROOT . '/app/webroot/img/shapes/' . $file->getFilename());
					}
					elseif ($ext == 'rle') {
						$data = $data + $parser->getData(file_get_contents($file->getPathname()));
					}
				}
				$this->GameShape->create();
				$this->GameShape->save(array('GameShape'=>$data));
				$i++;
			}
		}
		die('imported ' . $i . ' shapes');
	}
	
	
}