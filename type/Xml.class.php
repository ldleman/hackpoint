<?php 
class Xml extends Readme {

	public static function manifest(){
		return array(
			'uid' => 'xml',
			'label' => 'Html & XML',
			'description' => 'Fichier contenant du html',
			'fromExtension' => array('html','xml'),
			'toExtension' => 'html'
		);
	}

	public static function toHtml($resource,$sketch){
		$infos = self::manifest();
		return array(
			'content'=>'<textarea>'.$resource->content.'</textarea>',
			'codemirror' => array(
				'mode'=>'htmlmixed',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
	}
	
}
?>