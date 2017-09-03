<?php

global $_,$conf;

switch($_['action']){
	case 'fritzing_upload_resource':
	
		Action::write(function($_,&$response){
			global $myUser;
			
			$resource = Resource::getByid($_['id']);
			$sketch = Sketch::getById($resource->sketch);
			$ext = getExt($_FILES['file']['name']);
			if($myUser->id != $sketch->owner) throw new Exception("Seul le propriétaire du sketch peux faire ça");
			if($ext!='fzz') throw new Exception('Extensions autorisées .fzz');
			if($_FILES['file']['size']>ALLOWED_RESOURCE_SIZE) throw new Exception('Taille maximum autorisée '.ALLOWED_RESOURCE_SIZE.' o');
			$name = $resource->id.'.'.$ext;
			$path = SKETCH_PATH.$name;	
			move_uploaded_file($_FILES['file']['tmp_name'], $path);
			
			$resource->content = $name;
			$resource->save();

			$response = array_merge(ResourceType::all($resource->type));
	
			
		});
	break;
}

?>