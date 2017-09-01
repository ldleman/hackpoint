<?php 
class Python extends Readme {

	public static function manifest(){
		return array(
			'uid' => 'python',
			'label' => 'Python',
			'description' => 'Fichier python',
			'fromExtension' => array('py'),
			'toExtension' => 'py'
		);
	}

	public static function toHtml($resource,$sketch){
		$infos = self::manifest();
		return array(
			'content'=>'<textarea>'.$resource->content.'</textarea>',
			'codemirror' => array(
				'mode'=>'python',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
	}
	
}
?>