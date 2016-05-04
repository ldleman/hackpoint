<?php

/**
* Declare available resource types
* @author valentin carruesco
* @category Core
* @license copyright
*/


class Type{

	public static function get($uid){
		$t = Type::all();
		return $t[$uid];
	}

	public static function all(){
		$types = array();


		$types['readme'] = array(
			'label' => 'README',
			'extension' => 'md',
			'codemirror' => array(
				'smartIndent' => false,
				'readOnly' =>  false
			)
		);

		$types['arduino'] = array(
			'label' => 'Source Arduino',
			'extension' => 'ino',
			'codemirror' => array(
				'mode'=>'clike',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['php'] = array(
			'label' => 'Source PHP',
			'extension' => 'php',
			'codemirror' => array(
				'mode'=>'php',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['python'] = array(
			'label' => 'Source Python',
			'extension' => 'py',
			'codemirror' => array(
				'mode'=>'python',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['c'] = array(
			'label' => 'Source C++',
			'extension' => '.cpp',
			'codemirror' => array(
				'mode'=>'clike',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['java'] = array(
			'label' => 'Source JAVA',
			'extension' => '.java',
			'codemirror' => array(
				'mode'=>'java',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['image'] = array(
			'label' => 'Image',
			'upload' => array()
		);

		$types['part'] = array(
			'label' => 'Set de composants',
			'extension' => 'part'
		);

		
		return $types;
	}
	
	public static function toFileStream($resource){
		$type = self::get($resource->type);
		$file = (object) array('name'=>slugify($resource->label),'content'=>'');
		if(isset($type['extension'])) $file->name .= '.'.$type['extension'];
		switch($resource->type){
			case 'part':
				$file->content = '> '.strtoupper($resource->label).PHP_EOL;
				$file->content .= str_repeat('=',strlen($resource->label)+2).PHP_EOL;
				foreach(ResourcePart::loadAll(array('resource'=>$resource->id)) as $resourcePart):
					$part = $resourcePart->part_object;
					$file->content .= $part->label."\t";
					if(isset($part->link) && !empty($part->link)) $file->content .= $part->link."\t";
					if(isset($part->price) && !empty($part->price)) $file->content .= $part->price.'€'.PHP_EOL;
				endforeach;
			break;
			case 'image':
				$ext = getExt($resource->content);
				$file->name = slugify($resource->label).'.'.$ext;
				$file->content = file_get_contents(SKETCH_PATH.$resource->content);
			break;
			default:
				$file->content = html_entity_decode($resource->content);
			
			break;
		}
		return $file;
	}
	
}

?>