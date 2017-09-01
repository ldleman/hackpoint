<?php
class PartType {

	public static function manifest(){
		return array(
			'uid' => 'part',
			'label' => 'Set de composants',
			'description' => 'Ensemble de composants éléctroniques (ou autres)',
			'fromExtension' => array('part'),
			'toExtension' => 'txt'
		);
	}

	//Import depuis un glisser déposé du fichier
	public static function fromFile($resource){
		
		//TODO
		
		return $resource;
	}

	//Import depuis un flux json compressé de la ressource
	public static function fromJson($resource){
		global $myUser;
		$parts = $resource->content;
		$resource->content = '';
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

		return $resource;
	}
	
	//export en fichier JSON compressé de la ressource
	public static function toJson($resource){
		$resource = $resource->toArray();

		$resource['content'] = array();
		foreach(ResourcePart::loadAll(array('resource'=>$resource['id'])) as $resourcePart):
			$part = $resourcePart->part_object;
			$part = $part->toArray();
			$resourcePart = $resourcePart->toArray();
			if($part['image']!='') $part['image'] = base64_encode(file_get_contents(PART_PATH.$part['image']));
			$resource['content'][] = array('resourcePart'=>$resourcePart,'part'=>$part);
		endforeach;

		return $resource;
	}

	public static function toFile($resource){
		global $myUser;
		$infos = self::manifest();
		
		$content = '> '.strtoupper($resource->label).PHP_EOL;
		$content .= str_repeat('=',strlen($resource->label)+2).PHP_EOL;
		foreach(ResourcePart::loadAll(array('resource'=>$resource->id)) as $resourcePart):
			$part = $resourcePart->part_object;
			$content .= $part->label."\t";
			if(isset($part->link) && !empty($part->link)) $content .= $part->link."\t";
			if(isset($part->brand) && !empty($part->brand)) $content .= '('.$part->brand.")\t";
			if(isset($part->price) && !empty($part->price)) $content .= $part->price.'€';
			$content .= PHP_EOL;
		endforeach;
	
		
		return array(
			'name'=> slugify($resource->label).'.'.$infos['toExtension'],
			'content' => $content
		);
	}

	public static function toHtml($resource,$sketch){
		global $myUser;
		

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
		
		return $response;
	}
}
?>