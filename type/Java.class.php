<?php 
class Java extends Readme {

	public static function manifest(){
		return array(
			'uid' => 'java',
			'label' => 'Java',
			'description' => 'Langage JAVA (il faut de tout pour faire un monde...)',
			'fromExtension' => array('java','jsp','jar'),
			'toExtension' => 'java'
		);
	}

	public static function toHtml($resource,$sketch){
		$infos = self::manifest();
		return array(
			'content'=>'<textarea>'.$resource->content.'</textarea>',
			'codemirror' => array(
				'mode'=>'java',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
	}
	
}
?>