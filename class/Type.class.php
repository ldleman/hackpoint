<?php

/**
* Declare available resource types
* @author valentin carruesco
* @category Core
* @license copyright
*/


class Type{

	public static function get($uid){
		$t = Type::all();
		return $t[$uid];
	}

	public static function all(){
		$types = array();


		$types['readme'] = array(
			'label' => 'README',
			'extension' => array('md'),
			'codemirror' => array(
				'smartIndent' => false,
				'readOnly' =>  false
			)
		);

		$types['arduino'] = array(
			'label' => 'Source Arduino',
			'extension' => array('ino'),
			'codemirror' => array(
				'mode'=>'text/x-carduino',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['clike'] = array(
			'label' => 'Source C++/C',
			'extension' => array('cpp'),
			'codemirror' => array(
				'mode'=>'clike',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['shell'] = array(
			'label' => 'Shell',
			'extension' => array('sh'),
			'codemirror' => array(
				'mode'=>'shell',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['php'] = array(
			'label' => 'Source PHP',
			'extension' => array('php'),
			'codemirror' => array(
				'mode'=>'php',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['xml'] = array(
			'label' => 'Source XML',
			'extension' => array('xml'),
			'codemirror' => array(
				'mode'=>'htmlmixed',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);


		$types['python'] = array(
			'label' => 'Source Python',
			'extension' => array('py'),
			'codemirror' => array(
				'mode'=>'python',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['c'] = array(
			'label' => 'Source C++',
			'extension' => array('cpp'),
			'codemirror' => array(
				'mode'=>'clike',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['java'] = array(
			'label' => 'Source JAVA',
			'extension' => array('java'),
			'codemirror' => array(
				'mode'=>'java',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['css'] = array(
			'label' => 'Feuille CSS',
			'extension' => array('css'),
			'codemirror' => array(
				'mode'=>'css',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);
		$types['javascript'] = array(
			'label' => 'Source Javascript',
			'extension' => array('js'),
			'codemirror' => array(
				'mode'=>'javascript',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['json'] = array(
			'label' => 'Source JSON',
			'extension' => array('json'),
			'codemirror' => array(
				'mode'=>'javascript',
				'theme'=>'monokai',
				'lineNumbers' => true,
				'readOnly' =>  false
			)
		);

		$types['image'] = array(
			'label' => 'Image',
			'upload' => array(
				'url'     => 'action.php?action=upload_resource',
				'element' => '#resource p img:eq(0)',
				'callback' => '$(\'#resource img:eq(0)\').attr(\'src\',r.url);'
			),
			'extension' => array('jpg','jpeg','png','bmp','gif','svg')
		);
		
		$types['files'] = array(
			'label' => 'Set de fichiers',
			'upload' => array(
				'url'=>'action.php?action=upload_resource_file',
				'element' => '#dropZoneFiles',
				'callback' => "search_file();",
			),
			'extension' => array('zip')
		);

		$types['part'] = array(
			'label' => 'Set de composants',
			'extension' => array('part')
		);

		
		return $types;
	}
	
	public static function fromFileImport($file,$sketch,$type){
		$resource = new Resource();
		$resource->sketch = $sketch->id;
		$stream = file_get_contents($file['tmp_name']);
		$resource->label = $file['name'];
		$resource->type = $type;
		switch($resource->type){
			case 'arduino':
			case 'php':
			case 'shell':
			case 'python':
			case 'c':
			case 'css':
			case 'javascript':
			case 'java':
			case 'readme':
				$resource->content = file_get_contents($file['tmp_name']);
				$enc = mb_detect_encoding($resource->content,"UTF-8, ISO-8859-1, GBK");
				if($enc!='UTF-8')
					$resource->content = iconv($enc,"utf-8",$resource->content); 
				
				
				$resource->save();
			break;
			case 'files':
				$resource->save();
				$path = SKETCH_PATH.'/'.$resource->id;
				if(!file_exists($path)) mkdir($path);
				$zip = new ZipArchive;
				$res = $zip->open($file['tmp_name']);
				if ($res === TRUE) {
				  $zip->extractTo($path);
				  $zip->close();
				}
			break;
			case 'image':
				$resource->save();
				$ext = getExt($resource->label);
				$name = $resource->id.'.'.$ext;
				file_put_contents(SKETCH_PATH.$name,$stream);
				$resource->content = $name;
				$resource->save();
			break;
			default:
			break;
		}
	}
	
	public static function fromImport($res,$sketch){
		global $myUser;
		$resource = new Resource();
		$resource->fromArray($res);
		$resource->id = null;
		$resource->sketch = $sketch->id;
		$stream = '';
		
		switch($resource->type){
			case 'image':
				$stream = base64_decode($resource->content);
				$resource->content = '';
			break;
			case 'part':
				$parts = $resource->content;
				$resource->content = '';
			break;
			case 'files':
				$files = $resource->content;
				$resource->content = '';
			break;
			default:
			break;
		}


		if(is_string($resource->content))
			$resource->content = htmlspecialchars_decode($resource->content);
	
		$resource->save();
		
		switch($resource->type){
			case 'image':
				$resource->content = $resource->id.'.png';
				file_put_contents(SKETCH_PATH.$resource->content,$stream);
				$resource->save();
			break;
			case 'part':
				foreach($parts as $p):
				
					$part = new Part();
					$part->fromArray($p['part']);
					$part->id = null;
					$stream = base64_decode($part->image);
					$part->owner = $myUser->id;
					$part->save();
					$name = $part->id.'.png';
					file_put_contents(PART_PATH.$name,$stream);
					$part->image = $name;
					$part->save();
					
							
					$resourcePart = new ResourcePart();
					$resourcePart->fromArray($p['resourcePart']);
					$resourcePart->id = null;
					$resourcePart->part = $part->id;
					$resourcePart->resource = $resource->id;
					$resourcePart->save();
					
				endforeach;
				$resource->content = '';
				$resource->save();
			break;
			case 'files':
				$folder = SKETCH_PATH.'/'.$resource->id;
				if(!file_exists($folder)) mkdir($folder);
				foreach($files as $file):
					
					$stream = base64_decode($file['stream']);
					file_put_contents($folder.'/'.$file['label'],$stream);

				endforeach;
				$resource->content = '';
				$resource->save();
			break;
			
			default:
			break;
		}
	}
	
	
	public static function toExport($resource){
		$resource = $resource->toArray();
		
		switch($resource['type']){
			case 'image':
				$resource['content'] = base64_encode(file_get_contents(SKETCH_PATH.$resource['content']));
			break;
			case 'part':
				$resource['content'] = array();
				foreach(ResourcePart::loadAll(array('resource'=>$resource['id'])) as $resourcePart):
					$part = $resourcePart->part_object;
					$part = $part->toArray();
					$resourcePart = $resourcePart->toArray();
					if($part['image']!='') $part['image'] = base64_encode(file_get_contents(PART_PATH.$part['image']));
					$resource['content'][] = array('resourcePart'=>$resourcePart,'part'=>$part);
				endforeach;
			break;
			case 'files':
				$resource['content'] = array();
				$folder = SKETCH_PATH.'/'.$resource['id'];
				foreach(glob($folder.'/*') as $file):
					$resource['content'][] = array('label'=>basename($file),'stream'=>base64_encode(file_get_contents($file)));
				endforeach;
			break;
			default:
				$resource['content'] = htmlspecialchars(SKETCH_PATH.$resource['content']);
			break;
		}
	
		return $resource;
	}
	
	public static function toHtml($resource,$sketch){
		global $myUser;
		$response = array();
		$response = $resource->toArray();
		$type = self::get($resource->type);
		switch($resource->type){
				
				case 'image':
					
					
						$image = $response['content']==''?'img/default_image.png':'action.php?action=get_resource_image&id='.$response['id'];
						$response['content'] = '<img style="width:100%;height:auto;" class="dropzone" src="'.$image.'" />';
					if($myUser->id == $sketch->owner){
						$response['upload'] = $type['upload'];
					}
				break;
				
				case 'files':

					if($myUser->id == $sketch->owner){
						$response['upload'] = $type['upload'];
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
				break;
				case 'part':
					$response['callback'] = 'init_part();';
					$response['content'] = '<table class="table table-stripped table-bordered" id="parts"><thead>
						<tr>
							<th>Libellé</th>
							<!--<th>Lien</th>
							<th>Prix</th>-->';
					if($myUser->id == $sketch->owner)
							$response['content'] .= '<th></th>';
							
					$response['content'] .= '</tr>';
						
					if($myUser->id == $sketch->owner){
						$response['content'] .= '<tr id="partForm" data-action="save_part" data-id="">
							<td><input type="text" id="label" class="form-control"></td>
							<!--<td><input type="url" id="link"  class="form-control"></td>
							<td><input type="text" id="price"  class="form-control input-mini"></td>-->
							<td><div class="btn btn-success" onclick="save_part();"><i class="fa fa-plus"></i></div></td>
						</tr>';
					}
						
					$response['content'] .= '</thead><tbody>';
					
					$response['content'] .= '<tr style="display:none" data-id="{{id}}">
							<td ><a href="{{link}}"><div class="componentImage"><img src="{{image}}"/></div> {{label}}</a> {{#price}}<code>{{price}} €</code>{{/price}}{{#brand}} <small>{{brand}}</small>{{/brand}}</td>';
					
					if($myUser->id == $sketch->owner)
						$response['content'] .= '<td><div class="btn btn-danger btn-mini btn-rounded pulse" onclick="delete_part(this);"><i class="fa fa-times" ></i></div></td>';
					
					$response['content'] .= '</tr>';
					$response['content'] .='</tbody></table>';
				break;
			}
			//for sources
			if(isset($type['codemirror'])){
				$response['content'] = '<textarea>'.$response['content'].'</textarea>';
				$response['code'] = $type['codemirror'];
				if($myUser->id != $sketch->owner) $response['code']['readOnly'] = true;
			}
			
			return $response;
	}
	
	public static function toFileStream($resource){
		$type = self::get($resource->type);
		$file = (object) array('name'=>slugify($resource->label),'content'=>'');
		if(isset($type['extension'])) $file->name .= '.'.$type['extension'][0];
		switch($resource->type){
			case 'part':
				$file->content = '> '.strtoupper($resource->label).PHP_EOL;
				$file->content .= str_repeat('=',strlen($resource->label)+2).PHP_EOL;
				foreach(ResourcePart::loadAll(array('resource'=>$resource->id)) as $resourcePart):
					$part = $resourcePart->part_object;
					$file->content .= $part->label."\t";
					if(isset($part->link) && !empty($part->link)) $file->content .= $part->link."\t";
					if(isset($part->price) && !empty($part->price)) $file->content .= $part->price.'€';
					$file->content .= PHP_EOL;
				endforeach;
			break;
			case 'files':

				global $myUser;
				$path = SKETCH_PATH.'/'.$resource->id;

				$filename = $resource->label.'-'.time().'.zip';
				$filepath = sys_get_temp_dir().DIRECTORY_SEPARATOR.$filename;
				$zip = new ZipArchive;
				if(file_exists($filepath))unlink($filepath); 
				$res = $zip->open($filepath, ZipArchive::CREATE);
				if ($res === TRUE) {
					foreach(glob($path.'/*') as $f)
						$zip->addFile($f,basename($f));
					
					$zip->close();
				}
			
				$file->name = slugify($resource->label).'.zip';
				$file->content = file_get_contents($filepath);

			break;
			case 'image':
				$ext = getExt($resource->content);
				$file->name = slugify($resource->label).'.'.$ext;
				if(file_exists(SKETCH_PATH.$resource->content))
				$file->content = file_get_contents(SKETCH_PATH.$resource->content);
			break;
			default:
				$file->content = html_entity_decode($resource->content);
			break;
		}
		return $file;
	}
	
}

?>