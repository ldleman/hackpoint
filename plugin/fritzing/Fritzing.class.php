<?php
class Fritzing {

	public static function manifest(){
		return array(
			'uid' => 'fritzing',
			'label' => 'Schéma fritzing',
			'description' => 'Fichier pour le logiciel fritzing',
			'fromExtension' => array('fzz'),
			'toExtension' => 'fzz',
			'upload' => array(
				'url'     => 'action.php?action=upload_resource_file',
				'element' => '#dropZoneFiles',
				'callback' => "refresh_fritzing();",
			)
		);
	}

	//Import depuis un glisser déposé du fichier
	public static function fromFile($resource){
		$resource->save();
		$ext = getExt($resource->label);
		$name = $resource->id.'.'.$ext;
		file_put_contents(SKETCH_PATH.$name,$resource->content);
		$resource->content = $name;
		return $resource;
	}

	//Import depuis un flux json compressé de la ressource
	public static function fromJson($resource){
		$resource->save();
		$stream = base64_decode($resource->content);
		$resource->content = $resource->id.'.png';
		file_put_contents(SKETCH_PATH.$resource->content,$stream);
		return $resource;
	}
	
	//export en fichier JSON compressé de la ressource
	public static function toJson($resource){
		$resource = $resource->toArray();
		$resource['content'] = base64_encode(file_get_contents(SKETCH_PATH.$resource['content']));
		return $resource;
	}

	public static function toFile($resource){

		$ext = getExt($resource->content);
		$infos = self::manifest();
		return array(
			'name'=> slugify($resource->label).'.'.$ext,
			'content' => file_exists(SKETCH_PATH.$resource->content) ? file_get_contents(SKETCH_PATH.$resource->content) : ''
		);
	}

	public static function toHtml($resource,$sketch){
		global $myUser;
		$infos = self::manifest();

		$content = $resource->content ==''?'Envoyer un fichier':'<a href="action.php?action=get_resource&id='.$resource->id.'">Télécharger</a>';
		$response['content'] = '<div id="dropZoneFiles" style="width:100%;height:auto;" class="dropzone">'.$content.'</div>';
		if($myUser->id == $sketch->owner)
			$response['upload'] = $infos['upload'];
		
		return $response;
	}
}
?>