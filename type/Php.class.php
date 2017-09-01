<?php 
class Php extends Readme {

	public static function manifest(){
		return array(
			'uid' => 'php',
			'label' => 'PHP',
			'description' => 'Fichier PHP',
			'fromExtension' => array('php','phar'),
			'toExtension' => 'php'
		);
	}

	public static function toHtml($resource,$sketch){
		$infos = self::manifest();
		return array(
			'content'=>'<textarea>'.$resource->content.'</textarea>',
			'codemirror' => array(
				'mode'=>'php',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
	}
	
}
?>