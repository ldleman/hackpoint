<?php 
class Shell extends Readme {

	public static function manifest(){
		return array(
			'uid' => 'shell',
			'label' => 'Shell',
			'description' => 'Script shell (linux, windows...)',
			'fromExtension' => array('sh','bat'),
			'toExtension' => 'sh'
		);
	}

	public static function toHtml($resource,$sketch){
		$infos = self::manifest();
		return array(
			'content'=>'<textarea>'.$resource->content.'</textarea>',
			'codemirror' => array(
				'mode'=>'shell',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
	}
	
}
?>