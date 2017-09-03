<?php
class FritzingType {

	public static function manifest(){
		return array(
			'uid' => 'fritzing',
			'label' => 'Schéma fritzing',
			'description' => 'Fichier pour le logiciel fritzing',
			'fromExtension' => array('fzz'),
			'toExtension' => 'fzz',
			'upload' => array(
				'url'     => 'action.php?action=fritzing_upload_resource',
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
		require_once(__DIR__.SLASH."Fritzing.class.php");

		$file =SKETCH_PATH.$resource->id.'.fzz';
		if(!file_exists($file)){
			$content =  '<div id="dropZoneFiles" style="width:100%;height:auto;" class="dropzone"> <img src="plugin/fritzing/img/fritzing.png" /><br>Envoyer un fritzing</div>';
		}else{

			$breadboard = new Fritzing($file);
			
			$content =  '<div id="dropZoneFiles" style="width:100%;height:auto;" class="dropzone"></div>';
			$content .= '<h1>Schéma</h1>'.$breadboard->toHtml();
		
			if($breadboard->ino!=null){
				$content .= '<h1>Code associé</h1><textarea>'.$breadboard->ino.'</textarea>';
				$response['codemirror'] = array(
					'mode'=>'text/x-carduino',
					'theme'=>'monokai',
					'lineNumbers' => true,
					'readOnly' =>  true
				);
			}
			$content .= '<h1>Composants</h1><ul class="list-group">';
			foreach ($breadboard->parts as $part) {
				if($part['type'] == 'component' && isset($part['component']['name'])){
					
					$content .= '<li  class="list-group-item"><strong>'.$part['sigle'].' :</strong> '. $part['component']['name'].' ('. $part['component']['description'].')</li>';
					
				}
			}
			$content .= '</ul>';
		}
		

	
		$response['content'] = $content;

		

		if($myUser->id == $sketch->owner)
			$response['upload'] = $infos['upload'];
		
		return $response;
	}
}
?>