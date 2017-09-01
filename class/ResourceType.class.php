<?php

class ResourceType{
	
	public $user,$type;
	protected $fields =
    array(
        'id' => 'key',
        'user' => 'string',
        'type' => 'string'
    );

	public static function all($target=null){
		$rawtypes = array();
		Plugin::callHook('resource_type',array(&$rawtypes));
		$types = array();
		foreach($rawtypes as $uid=>$file){
			if(isset($target) && $uid!=$target) continue;
			$class = str_replace('.class.php','',basename($file));
			require_once($file);
			if(!method_exists ( $class , 'toHtml' )) continue;
			if(!method_exists ( $class , 'toJson' )) continue;
			if(!method_exists ( $class , 'toFile' )) continue;
			if(!method_exists ( $class , 'fromJson' )) continue;
			if(!method_exists ( $class , 'fromFile' )) continue;
        	$types[$uid] = $class::manifest();
        	$types[$uid]['file'] =$file;
        	$types[$uid]['class'] = $class;
    	}
		return isset($target) ? $types[$target] : $types;
	}

}

?>