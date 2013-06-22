<?php

App::uses('AppController', 'Controller');

class ImportController extends AppController {
	
	public $uses = array('GameRule', 'GameShape');
	
	public function test() {
		$rules = $this->GameRule->find('all');
		pprd($rules);
	}
	
	public function files() {
		$dir = APP . '/Writeable/tmp/jslife';
		$parser = new Parser_Rle();
		$i = 0;
		$dupes = 0;
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $shape) {
			$name = $shape->getFilename();
			$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
			if (!$shape->isFile() || $ext !== 'lif') {
				continue;
			}
			$data = array();
			$data['name'] = 'jslife ' . preg_replace('~.+?/([^/]+)/([^./]+)\.lif$~i', '$1 $2', $shape->getPathName());
			$data['created_by'] = 'Jason Summers';
			$data['game_shape_category_id'] = 11;
			$data = $data + $parser->getData(file_get_contents($shape->getPathname()));
			$dupe = $this->GameShape->find('first', array(
				'fields' => array('id'),
				'conditions' => array(
					'spec' => $data['spec']
				)
			));
			if ($dupe) {
				echo 'duplicate pattern ' . $data['name'] . '<br />';
				$dupes++;
				continue;
			}
			$this->GameShape->create();
			$this->GameShape->save(array('GameShape'=>$data));
			$i++;
		}
		die("Imported $i shapes; ignored $dupes duplicates.");
	}
	
	public function zip() {
		$zip = new ZipArchive();
		$res = $zip->open(APP . '/Writeable/tmp/jslife.zip');
		if ($res !== true) {
			die('Error ' . $res);
		}
		$ok = $zip->extractTo(APP . '/Writeable/tmp');
		$zip->close();
		die('Result: ' . $ok);
	}
	
}