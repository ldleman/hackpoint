<?php

/**
* Define a ResourcePart
* @author valentin carruesco
* @category Core
* @license copyright
*/


class ResourcePart extends Entity{

	public $id,$resource,$part,$sort;
	
	protected $fields = 
	array(
		'id'=>'key',
		'resource' => 'int',
		'part' => 'int',
		'sort' => 'int'
	);



}

?>