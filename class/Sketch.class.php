<?php

/**
* Define a Sketch
* @author valentin carruesco
* @category Core
* @license copyright
*/


class Sketch extends Entity{

	public $id,$label,$owner,$state,$public,$slug;
	
	protected $fields = 
	array(
		'id'=>'key',
		'label' => 'string',
		'owner' => 'int',
		'state' => 'int',
		'public' => 'int',
		'slug' => 'string'
	);

	public  function save(){
		if($this->id==0)
			$this->slug = slugify($this->label);
		parent::save();
	}
	
}

?>