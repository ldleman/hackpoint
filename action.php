<?php

require_once __DIR__.DIRECTORY_SEPARATOR."common.php";


if(!isset($_['action'])) throw new Exception('Action inexistante');

//Execution du code en fonction de l'action
switch ($_['action']){
	
	
	case 'login':
		global $myUser;
		try{
			$myUser = User::check($_['login'],$_['password']);
			
			if(!$myUser->connected()) throw new Exception('Utilisateur inexistant');
			$_SESSION['currentUser'] = serialize($myUser);
			
		}catch(Exception $e){
			$_SESSION['error'] = $e->getMessage();
		}
		header('location: index.php');
	break;
	
	case 'logout':
		unset($_SESSION['currentUser']);
		session_destroy();
		header('location: index.php');
	break;

	case 'save_user':
		try{
			global $myUser;
			if(!$myUser->connected()) throw new Exception("Permission refusée, seul un connecté peux faire ça");
			if($myUser->id!=$_['id']) throw new Exception("Permission refusée, seul le propriétaire du compte peux faire ça");

			if(!empty($_['password']) && $_['password']!=$_['confirmPassword']) throw new Exception("Les deux mot de passe ne correspondent pas");
			if(!empty($_['password'])) $myUser->password = User::password_encrypt($_['password']);
			$myUser->login = $_['login']; 

			$myUser->save();
			$_SESSION['currentUser'] = serialize($myUser);
			$_SESSION['success'] = "Compte modifié avec succès";
		}catch(Exception $e){
			$_SESSION['error'] = $e->getMessage();
		}
		header('location: account.php');
	break;

	

	// SKETCH
	case 'create_sketch':
		Action::write(function($_,&$response){
			global $myUser;

			if(!$myUser->connected()) throw new Exception("Permission refusée, seul un connecté peux faire ça");
			$sketch = new Sketch();
			$sketch->fromArray($_);
			$sketch->owner = $myUser->id;
			$sketch->save();
			$resource = new Resource();
			$resource->label = 'README';
			$resource->type = 'readme';
			$resource->sketch = $sketch->id;
			$resource->content = 'Décrivez votre projet ici...';
			$resource->sort = 0;
			$resource->save();
			$response = $sketch->toArray();
		});
	break;
	
	case 'export_sketch':
	
		global $myUser;
		$response = array();
		try{
		
			$sketch = Sketch::getById($_['id']);
			
			if(!$sketch->public && $myUser->id!=$sketch->owner)throw new Exception('Ce sketch est privé');
			
			$response['sketch'] = $sketch->toArray();
			$response['resources'] = array();
			
			foreach(Resource::loadAll(array('sketch'=>$_['id'])) as $resource)
				$response['resources'][] = Type::toExport($resource);

		
		}catch(Exception $e){
			$response['error'] = $e->getMessage();
		}
	
		$response = gzdeflate(json_encode($response));
		
		if(!isset($_['api'])){
			$filename = slugify($sketch->label).('.export.').date('d-m-Y_H-i').'.json';
			header("Content-Type: application/json");
			header("Content-Length: " . strlen($response));
			header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header('Cache-Control: post-check=0, pre-check=0', FALSE);
			header('Pragma: no-cache');
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
		}

		echo $response;
	break;
	
	case 'import_sketch':
		Action::write(function($_,&$response){
			global $myUser;

			if(!$myUser->connected()) throw new Exception("Permission refusée, seul un connecté peux faire ça");
			
			if($_['from'] == 'url'){
				if(!isset($_['url']) || empty($_['url'])) throw new Exception("Adresse du sketch invalide");
				$url = parse_url($_['url']);
				parse_str($url['query'], $parameters);
				if(!isset($parameters['id']) || empty($parameters['id']) || !is_numeric($parameters['id'])) throw new Exception("ID du sketch invalide");
				$contentPath = $url['scheme'].'://'.$url['host'].'/'.substr($url['path'],0,-11).'/action.php?action=export_sketch&id='.$parameters['id'].'&api=true';
			}else{
				$ext = getExt($_FILES['file']['name']);
				if($ext!='json') throw new Exception('Extension JSON autorisée uniquement');
				$contentPath = $_FILES['file']['tmp_name'];
			}
			
			$stream = false;
			try{ $stream = @file_get_contents($contentPath); }catch(Exception $a){}
			if($stream === false) throw new Exception("Impossible d'atteindre le contenu hackpoint : $contentPath ");
			$stream = gzinflate($stream);
			if($stream === false) throw new Exception('Impossible de décompresser le sketch...');
			$json = json_decode($stream,true);
			if($json == false) throw new Exception('Impossible de parser la réponse du hackpoint, json invalide :'.$stream);
			
			
			
			if(isset($json['error'])) throw new Exception($json['error']);
			$sketch = new Sketch();
			$sketch->fromArray($json['sketch']);
			$sketch->id = null;
			$sketch->owner = $myUser->id;
			$sketch->public = 0;
			$sketch->label = $sketch->label .='-import-'.date('d/m/Y H:i');
			$sketch->save();
			
			
			
			foreach($json['resources'] as $res)
				Type::fromImport($res,$sketch);

		});
	break;
	case 'search_sketch':
		Action::write(function($_,&$response){
			global $myUser;
			
			$filters = array('public'=>1);
			if($myUser->connected()){
				$filters = array('owner'=>$myUser->id);
			}
			
			$sketchs = Sketch::loadAll($filters);
			foreach($sketchs as $sketch){
				$sketch->label = html_entity_decode($sketch->label);
				$sketch->owner = User::getById($sketch->owner)->login;
				$sketch->public = $sketch->public==1?true:false;
				$response['rows'][] = $sketch->toArray();
			}
		});
	break;
	case 'delete_sketch':
		Action::write(function($_,&$response){
			global $myUser;
			$sketch = Sketch::getById($_['id']);
			if($myUser->id != $sketch->owner) throw new Exception("Permission refusée, seul l'auteur du sketch peux faire ça");
			Part::staticQuery("delete FROM ".ResourcePart::tableName()." WHERE resource IN(SELECT id FROM ".Resource::tableName()." WHERE sketch=".$_['id'].") ");
			foreach(Resource::loadAll(array('sketch'=>$_['id'])) as $resource):
				$resource->remove();
			endforeach;
			Sketch::deleteById($_['id']);
		});
	break;

	case 'save_sketch_title':
		Action::write(function($_,&$response){
			global $myUser;
			$sketch = Sketch::getById($_['id']);
			if($myUser->id != $sketch->owner) return;
			$sketch->fromArray($_);
			$sketch->save();
		});
	break;

	case 'toggle_share_sketch':
		Action::write(function($_,&$response){
			global $myUser;
			$sketch = Sketch::getById($_['id']);
			if($myUser->id != $sketch->owner) throw new Exception("Permission refusée, seul l'auteur du sketch peux faire ça");
			$sketch->public = $_['state'];
			$sketch->save();
		});
	break;

	case 'download_sketch':

		$sketch = Sketch::getByid($_['id']);
		$resources = Resource::loadAll(array('sketch'=>$sketch->id),'sort');
		
		$filename = $sketch->slug.'-'.time().'.zip';
		$filepath = sys_get_temp_dir().DIRECTORY_SEPARATOR.$filename;
		$zip = new ZipArchive;
		$res = $zip->open($filepath, ZipArchive::CREATE);
		if ($res === TRUE) {
			foreach($resources as $resource){
				$type = Type::get($resource->type);
				$file = Type::toFileStream($resource);
				$zip->addFromString($file->name, $file->content);
			}
			$zip->close();
		}
		header("Content-Type: application/zip");
		header("Content-Length: " . filesize($filepath));
		header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header('Cache-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');
		header("Content-Disposition: attachment; filename=\"".$filename."\"");
		readfile($filepath);
		unlink($filepath); 
	break;
	
	// RESOURCES

	case 'upload_resource':
		Action::write(function($_,&$response){
			global $myUser;
			$resource = Resource::getByid($_['id']);
			$sketch = Sketch::getById($resource->sketch);
			$ext = getExt($_FILES['file']['name']);
			if($myUser->id != $sketch->owner) throw new Exception("Seul le propriétaire du sketch peux faire ça");
			if(!in_array($ext,explode(',',ALLOWED_RESOURCE_IMAGE))) throw new Exception('Extensions autorisées '.ALLOWED_RESOURCE_IMAGE);
			if($_FILES['file']['size']>ALLOWED_RESOURCE_SIZE) throw new Exception('Taille maximum autorisée '.ALLOWED_RESOURCE_SIZE.' o');
			$name = $resource->id.'.'.$ext;
			$path = SKETCH_PATH.$name;	
			move_uploaded_file($_FILES['file']['tmp_name'], $path);
			
			$resource->content = $name;
			$resource->save();
			$response = array_merge(Type::get($resource->type));
			//$response['url'] = $path.'?v='.time();
			
			$response['url'] ="action.php?action=get_resource_image&id=".$resource->id."&v=".time();
			
			
		});
	break;
	
	case 'upload_resource_file':
		Action::write(function($_,&$response){
			global $myUser;
			$resource = Resource::getByid($_['id']);
			$sketch = Sketch::getById($resource->sketch);
			$ext = getExt($_FILES['file']['name']);
			if($myUser->id != $sketch->owner) throw new Exception("Seul le propriétaire du sketch peux faire ça");
			if(ALLOWED_RESOURCE_FILE!='' && !in_array($ext,explode(',',ALLOWED_RESOURCE_FILE))) throw new Exception('Extensions autorisées '.ALLOWED_RESOURCE_FILE);
			if($_FILES['file']['size']>ALLOWED_RESOURCE_SIZE) throw new Exception('Taille maximum autorisée '.ALLOWED_RESOURCE_SIZE.' o');
			$name = $resource->id;
			$folder = SKETCH_PATH.$name;	
			if(!file_exists($folder)) mkdir($folder);
			
			$path = $folder.'/'.$_FILES['file']['name'];
			
			move_uploaded_file($_FILES['file']['tmp_name'], $path);
			$response = array_merge(Type::get($resource->type));
			$response['url'] = $path.'?v='.time();
		});
	break;
	
	case 'search_resources':
	    
		
		Action::write(function($_,&$response){
			
			if(!isset($_['id']) || !is_numeric($_['id'])) throw new Exception("Sketch non spécifié");
			$resources = Resource::loadAll(array('sketch'=>$_['id']),'sort');
			
			foreach($resources as $resource){
				$resource->label = html_entity_decode($resource->label);
				$resource->content = null;
				$resource = $resource->toArray();
				$response['rows'][] = $resource;
			}
				
		});
	break;
	
	case 'import_resource':
		global $myUser;
		$sketch = Sketch::getByid($_['id']);
		if($myUser->id != $sketch->owner) return;
		$ext = explode('.',$_FILES['file']['name']);
		$ext = strtolower(array_pop($ext));
		$types = Type::all();
		$type = 'readme';
		foreach($types as $uid=>$tp)
			if(isset($tp['extension']) && $ext == $tp['extension']) $type = $uid;
		
		Type::fromFileImport($_FILES['file'],$sketch,$type);
		
	break;
	
	//COMPONENT

	case 'upload_component_image':
		global $myUser;
		$response = array();
		try{
			if(!isset($_FILES['file'])) throw new Exception("Le fichier est trop gros pour votre configuration php (php.ini), taille max :".max_upload_size(array(ALLOWED_RESOURCE_SIZE)));
			$ext = explode('.',$_FILES['file']['name']);
			$ext = strtolower(array_pop($ext));
			if(!in_array($ext,explode(',',ALLOWED_RESOURCE_IMAGE))) throw new Exception("Format d'image interdit, autorisé : ".ALLOWED_RESOURCE_IMAGE);
			if($_FILES['file']['size']>ALLOWED_RESOURCE_SIZE) throw new Exception("Le fichier est trop gros pour votre configuration programme, taille max: ".max_upload_size(array(ALLOWED_RESOURCE_SIZE)));
		

			imageResize($_FILES['file']['tmp_name'],100,100);
			$response['thumb']  = 'data:image/png;base64,'.base64_encode(file_get_contents($_FILES['file']['tmp_name']));
		}catch(Exception $e){
			$response['error'] = $e->getMessage();
		}
		header('Content-Type:application/json');
		echo json_encode($response);
	break;
	
	
	
	case 'search_component':
		Action::write(function($_,&$response){
			global $myUser;
			$parts = Part::populate();
			foreach($parts as $part){
				$part->label = html_entity_decode($part->label);
				$part->link = html_entity_decode($part->link);
				if($part->image==''){
					$part->image = 'img/default_image.png';
				}else{
					$part->image = PART_PATH.html_entity_decode($part->image).'?t='.time();
				}
				$response['rows'][] = $part->toArray();
			}
		});
	break;

	

	case 'delete_component':
		Action::write(function($_,&$response){
			global $myUser;
			$part = Part::getById($_['id']);
			if($myUser->id!=$part->owner) throw new Exception('Seul le propriétaire du composant peux faire ça');
			$part->remove();
		});
	break;

	case 'save_component':
		Action::write(function($_,&$response){
			global $myUser;

			if(!$myUser->connected()) throw new Exception("Permission refusée, seul un connecté peux faire ça");
			$part = isset($_['id']) && is_numeric($_['id'])?Part::getById($_['id']):new Part();
			$part->fromArray($_);
			$part->owner = $myUser->id;
			$part->save();
			if(substr($part->image,0,10) == 'data:image'){
				$imageName = $part->id.'.png';
				base64_to_image($part->image,PART_PATH.$imageName);
				$part->image = $imageName;
				$part->save();
			}
			$response = $part->toArray();
		});
	break;

	case 'edit_component':
		Action::write(function($_,&$response){
			$part = isset($_['id'])? Part::getById($_['id']):new Part;
			$part->label = html_entity_decode($part->label);
			$part->link = html_entity_decode($part->link);
			$part->brand = html_entity_decode($part->brand);
			if($part->image==''){
				$part->image = 'img/default_image.png';
			}else{
				$part->image = PART_PATH.html_entity_decode($part->image);
			}
			$part->image.='?t='.time();
			$response = $part->toArray();
		});
	break;

	//RESOURCE

	case 'save_resource':
		Action::write(function($_,&$response){
			global $myUser;
			$sketch = Sketch::getById($_['sketch']);
			if($myUser->id != $sketch->owner) throw new Exception("Permission refusée, seul l'auteur du sketch peux faire ça");
			$resource = isset($_['id']) && is_numeric($_['id']) && !empty($_['id']) ? Resource::getByid($_['id']) : new Resource();
			$resource->fromArray($_);
			$resource->sort = 10;
			if($resource->type=='readme' && $resource->id==0) $resource->sort = 0;
			$resource->label = $resource->label == ''?'Sans titre '.date('d-m-Y H:i'):$resource->label;
			$resource->save();
	
			$response = $resource->toArray();
		});
	break;
	case 'save_resource_content':
		Action::write(function($_,&$response){
			global $myUser;
			
			$resource = Resource::getByid($_['id']);
			$sketch = Sketch::getById($resource->sketch);
			if($myUser->id != $sketch->owner) return;
			$resource->fromArray($_);
			$resource->save();
		});
	break;
	
	case 'get_resource_file':
		global $myUser;
		$resource = Resource::getById($_['id']);
		$sketch =$resource->sketch_object;
		if($myUser->id != $sketch->owner && !$sketch->public){
			echo 'Désolé, vous n\'avez pas d\'accès à cette ressource...';
			return;
		}
		$filepath = SKETCH_PATH.$resource->id.'/'.$_['file'];
		$finfo = finfo_open(FILEINFO_MIME_TYPE); 
		
		$mime = finfo_file($finfo, $filepath);
		
		header('Content-Type: '.$mime);
		header("Content-Length: " . filesize($filepath));
		header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header('Cache-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');
		header("Content-Disposition: attachment; filename=\"".basename($filepath)."\"");
		
		echo file_get_contents($filepath);
		finfo_close($finfo);
	break;
	
	case 'get_resource_image':
		global $myUser;
		
		$resource = Resource::getById($_['id']);
		$sketch =$resource->sketch_object;
		if($myUser->id != $sketch->owner && !$sketch->public){
			readfile('img/default_image.png');
			return;
		}
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$filepath = SKETCH_PATH.$resource->content;
		$mime = finfo_file($finfo, $filepath);
		header('Content-Type: '.$mime);
		readfile($filepath);
	break;
	
	case 'edit_resource':
		Action::write(function($_,&$response){
		
			$resource = Resource::getById($_['id']);
			global $myUser;
			$sketch = Sketch::getById($resource->sketch);
			$resource->label = html_entity_decode($resource->label);
			$response = Type::toHtml($resource,$sketch);
		});
	break;
	case 'delete_resource':
		Action::write(function($_,&$response){
			global $myUser;
			$resource = Resource::getById($_['id']);
			$sketch = Sketch::getById($resource->sketch);
			if($myUser->id != $sketch->owner) throw new Exception("Permission refusée, seul l'auteur du sketch peux faire ça");
			Resource::getById($_['id']);
			$resource->remove();
		});
	break;
	
	/*FILES*/
	case 'search_file':
	
	Action::write(function($_,&$response){
			global $myUser;
			if(!isset($_['id']) || !is_numeric($_['id'])) throw new Exception("Ressource non spécifiée");
			
			$resource = Resource::getById($_['id']);
			$sketch = $resource->sketch_object;
			
			if($myUser->id != $sketch->owner && !$sketch->public) throw new Exception("Désolé, le sketch est privé");
			
			foreach(glob(SKETCH_PATH.'/'.$_['id'].'/*') as $file):
				$icon = getExtIcon(getExt($file));
				
				$response['rows'][] = array('id'=>basename($file),'icon'=>$icon,'label'=>basename($file),'resource'=>$resource->id);
			endforeach;
		});
	break;
	
	case 'download_file':
			
			global $myUser;
			$path = SKETCH_PATH.'/'.$_['resource'];
			
			
			$resource = Resource::getById($_['resource']);
			$sketch = $resource->sketch_object;
			if($myUser->id != $sketch->owner && !$sketch->public) throw new Exception("Permission refusée, le sketch est privé");
			
		
			
			$filename = $resource->label.'-'.time().'.zip';
			$filepath = sys_get_temp_dir().DIRECTORY_SEPARATOR.$filename;
			$zip = new ZipArchive;
			if(file_exists($filepath))unlink($filepath); 
			$res = $zip->open($filepath, ZipArchive::CREATE);
			if ($res === TRUE) {
				foreach(glob($path.'/*') as $file)
					$zip->addFile($file,basename($file));
				
				
				$zip->close();
			}
		
			header("Content-Type: application/zip");
			header("Content-Length: " . filesize($filepath));
			header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header('Cache-Control: post-check=0, pre-check=0', FALSE);
			header('Pragma: no-cache');
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			readfile($filepath);
			unlink($filepath); 

		
	break;
	
	case 'delete_file':
		Action::write(function($_,&$response){
			global $myUser;
			$path = SKETCH_PATH.'/'.$_['resource'].'/'.$_['id'];
			$resource = Resource::getById($_['resource']);
			$sketch = $resource->sketch_object;
			if($myUser->id != $sketch->owner) throw new Exception("Permission refusée, seul l'auteur du sketch peux faire ça");
			if(file_exists($path)) unlink($path);
		});
	break;
	
	/*PART*/
	
	case 'search_part':
	
	Action::write(function($_,&$response){
			if(!isset($_['id']) || !is_numeric($_['id'])) throw new Exception("Ressource non spécifiée");
			$resourceParts = ResourcePart::loadAll(array('resource'=>$_['id']),'sort');
			foreach($resourceParts as $resourcePart):
				$part = $resourcePart->part_object;
				$part->label = html_entity_decode($part->label);
				$part->link = html_entity_decode($part->link);
				if($part->image == '') { 
					$part->image = 'img/default_image.png';
				}else{
					$part->image = PART_PATH.$part->image;
				}
				$line = $part->toArray();
				$line['id'] = $resourcePart->id;
				$response['rows'][] = $line;
			endforeach;
		});
	break;
	
	case 'delete_part':
		Action::write(function($_,&$response){
			global $myUser;
			$part = ResourcePart::getById($_['id']);
			
			$resource = $part->resource_object;
			$sketch = $resource->sketch_object;
			if($myUser->id != $sketch->owner) throw new Exception("Permission refusée, seul l'auteur du sketch peux faire ça");
			ResourcePart::deleteById($_['id']);
		});
	break;
	case 'save_part':
		Action::write(function($_,&$response){
			global $myUser;
			$model = isset($_['model']) && is_numeric($_['model']) && !empty($_['model']) ? Part::getByid($_['model']) : new Part();
			if($model->id==0){
				$model->fromArray($_);
				$model->save();
			}
			
			$resourcePart = new ResourcePart();
			$resourcePart->fromArray($_);
			$resourcePart->part = $model->id;
			$resourcePart->save();
			
		});
	break;
	
	case 'autocomplete_part':
		global $myUser;
		$parts = Part::staticQuery("SELECT DISTINCT label,* FROM ".Part::tableName()." WHERE label LIKE ? GROUP BY label ORDER BY price ASC ",array('%'.$_['term'].'%'),true);
		$rows = array();
		levenshtein_deduplication($parts);
		foreach($parts as $part):
			$part->image = PART_PATH.$part->image;
			$rows[] = array('label'=>$part->label.' ('.$part->price.'€)','value'=>$part->toArray());
		endforeach;
		echo json_encode($rows);
	break;
	
	default:
		
	break;
}


function levenshtein_deduplication(&$objs){
	foreach($objs as $obj1):
		foreach($objs as $u=>$obj2):
			if($obj1->id==$obj2->id) continue;
			if(levenshtein($obj1->label,$obj2->label) < 5 )
			unset($objs[$u]);
		endforeach;
	endforeach;
}
?>