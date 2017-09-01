<?php 
class Javascript extends Readme {

	public static function manifest(){
		return array(
			'uid' => 'javascript',
			'label' => 'Javascript',
			'description' => 'Langage javascript client/serveur',
			'fromExtension' => array('js'),
			'toExtension' => 'js'
		);
	}

	public static function toHtml($resource,$sketch){
		$infos = self::manifest();
		return array(
			'content'=>'<textarea>'.$resource->content.'</textarea>',
			'codemirror' => array(
				'mode'=>'javascript',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
	}
	
}
?>