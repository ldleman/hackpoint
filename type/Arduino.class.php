<?php 
class Arduino extends Readme {

	public static function manifest(){
		return array(
			'uid' => 'arduino',
			'label' => 'Arduino',
			'description' => 'Fichier contenant du langage arduino (proche du c++)',
			'fromExtension' => array('ino'),
			'toExtension' => 'ino'
		);
	}

	public static function toHtml($resource,$sketch){
		$infos = self::manifest();
		return array(
			'content'=>'<textarea>'.$resource->content.'</textarea>',
			'codemirror' => array(
				'mode'=>'text/x-carduino',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
	}
	
}
?>