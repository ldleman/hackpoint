<?php 
class Json extends Readme {

	public static function manifest(){
		return array(
			'uid' => 'json',
			'label' => 'Json',
			'description' => 'Structure JSON (notation objet javascript)',
			'fromExtension' => array('json'),
			'toExtension' => 'json'
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