<?php
class File {

	public static function manifest(){
		return array(
			'uid' => 'files',
			'label' => 'Set de fichiers',
			'description' => 'Ensemble de fichiers attachés',
			'fromExtension' => array('zip','7z'),
			'toExtension' => 'zip',
			'upload' => array(
				'url'=>'action.php?action=upload_resource_file',
				'element' => '#dropZoneFiles',
				'callback' => "search_file();",
			)
		);
	}

	//Import depuis un glisser déposé du fichier
	public static function fromFile($resource){
		$resource->save();
		
		$path = SKETCH_PATH.'/'.$resource->id;
		if(!file_exists($path)) mkdir($path);


		$filepath = sys_get_temp_dir().DIRECTORY_SEPARATOR.$resource->label.'-'.time();
		if(file_exists($filepath))unlink($filepath); 
		file_put_contents($filepath, $resource->content);

		$zip = new ZipArchive;
		$res = $zip->open($filepath);
		if ($res === TRUE) {
		  $zip->extractTo($path);
		  $zip->close();
		}
		$resource->content = '';

		return $resource;
	}

	//Import depuis un flux json compressé de la ressource
	public static function fromJson($resource){
		
		$files = $resource->content;
		$resource->content = '';
		$resource->save();
		$folder = SKETCH_PATH.'/'.$resource->id;
		if(!file_exists($folder)) mkdir($folder);
		foreach($files as $file):
			$stream = base64_decode($file['stream']);
			file_put_contents($folder.'/'.$file['label'],$stream);
		endforeach;

		return $resource;
	}
	
	//export en fichier JSON compressé de la ressource
	public static function toJson($resource){
		$resource = $resource->toArray();



		$resource->content = array();
		$folder = SKETCH_PATH.'/'.$resource->id;
		foreach(glob($folder.'/*') as $file):
			$resource->content[] = array('label'=>basename($file),'stream'=>base64_encode(file_get_contents($file)));
		endforeach;

		return $resource;
	}

	public static function toFile($resource){
		global $myUser;
		$infos = self::manifest();
		$path = SKETCH_PATH.'/'.$resource->id;

		$filename = $resource->label.'-'.time().'.'.$infos['toExtension'];
		$filepath = sys_get_temp_dir().DIRECTORY_SEPARATOR.$filename;
		$zip = new ZipArchive;
		if(file_exists($filepath))unlink($filepath); 
		$res = $zip->open($filepath, ZipArchive::CREATE);
		if ($res === TRUE) {
			foreach(glob($path.'/*') as $f)
				$zip->addFile($f,basename($f));
			
			$zip->close();
		}
	
		
		return array(
			'name'=> slugify($resource->label).'.'.$infos['toExtension'],
			'content' => file_get_contents($filepath)
		);
	}

	public static function toHtml($resource,$sketch){
		global $myUser;
		$infos = self::manifest();

		if($myUser->id == $sketch->owner){
			$response['upload'] = $infos['upload'];
			$response['content'] = '<div id="dropZoneFiles"><i class="fa fa-file-text-o"></i> Faites glisser vos fichiers ici</div>';
}
		$response['callback'] = 'init_file();';
		$response['content'] .= '<table class="table table-stripped table-bordered" id="files"><thead>
			<tr>
				<th>Fichiers disponibles <a class="btn btn-primary" href="action.php?action=download_file&resource='.$resource->id.'" style="float:right;" title="Télécharger les fichiers"><i class="fa fa-file-zip-o"></i> Télécharger</a></th>';
		
		$response['content'] .= '<th style="width:50px;"></th>';
				
		$response['content'] .= '</tr></thead><tbody>';
		
		$response['content'] .= '<tr style="display:none" data-id="{{id}}">
				<td ><a href="action.php?action=get_resource_file&id={{resource}}&file={{label}}"><i class="fa {{icon}}"></i> {{label}}</a></td>';
		
		
			$response['content'] .= '<td>';
			if($myUser->id == $sketch->owner)
			$response['content'] .= '<div class="btn btn-danger btn-mini btn-rounded pulse" onclick="delete_file(this);"><i class="fa fa-times" ></i></div>';
			$response['content'] .= '</td>';
		
		$response['content'] .= '</tr>';
		$response['content'] .='</tbody></table>';
		
		return $response;
	}
}
?>