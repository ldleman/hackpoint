<?php 
class C extends Readme {

	public static function manifest(){
		return array(
			'uid' => 'clike',
			'label' => 'Source C++/C',
			'description' => 'Fichier contenant du langage c,c++ ou proche (clike)',
			'fromExtension' => array('c','cpp','h','hpp'),
			'toExtension' => 'cpp'
		);
	}

	public static function toHtml($resource,$sketch){
		$infos = self::manifest();
		return array(
			'content'=>'<textarea>'.$resource->content.'</textarea>',
			'codemirror' => array(
				'mode'=>'clike',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
	}
	
}
?>