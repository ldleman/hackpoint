<?php 
class Css extends Readme {

	public static function manifest(){
		return array(
			'uid' => 'css',
			'label' => 'Css',
			'description' => 'Feuille de style CSS',
			'fromExtension' => array('css','scss'),
			'toExtension' => 'css'
		);
	}

	public static function toHtml($resource,$sketch){
		$infos = self::manifest();
		return array(
			'content'=>'<textarea>'.$resource->content.'</textarea>',
			'codemirror' => array(
				'mode'=>'css',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
	}
	
}
?>