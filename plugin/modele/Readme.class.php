<?php
class Readme {

	public static function manifest(){
		return array(
			'uid' => 'readme',
			'label' => 'README',
			'description' => 'Fichier texte de type "lisez moi", utilise du markdown pour la mise en forme',
			'fromExtension' => array('md'),
			'toExtension' => 'md',
			'codemirror' => array(
				'mode'=>'markdown',
				//'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
	}

	//Import depuis un glisser déposé du fichier
	public static function fromFile($resource){
		$enc = mb_detect_encoding($resource->content,"UTF-8, ISO-8859-1, GBK");
		if($enc!='UTF-8')
			$resource->content = iconv($enc,"utf-8",$resource->content); 
	
		return $resource;
	}

	//Import depuis un flux json compressé de la ressource
	public static function fromJson($resource){
		return $resource;
	}
	
	//export en fichier JSON compressé de la ressource
	public static function toJson($resource){
		$resource = $resource->toArray();
		$resource['content'] = htmlspecialchars($resource['content']);
		return $resource;
	}

	public static function toFile($resource){
		$infos = self::manifest();
		return array(
			'name'=> slugify($resource->label).'.'.$infos['toExtension'],
			'content' => html_entity_decode($resource->content)
		);
	}

	public static function toHtml($resource){
		$infos = self::manifest();
		return array(
			'content'=>'<textarea>'.$resource->content.'</textarea>',
			'codemirror' => $infos['codemirror']
		);
	}
}
?>