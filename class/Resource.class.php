<?php

/**
 * Define a resource.
 *
 * @author valentin carruesco
 *
 * @category Core
 *
 * @license copyright
 */
class Resource extends Entity
{
    public $id,$label,$type,$content,$sketch,$sort;
    protected $fields =
    array(
        'id' => 'key',
        'label' => 'string',
        'sort' => 'int',
        'type' => 'string',
        'content' => 'longstring',
        'sketch' => 'int',
    );

    public function remove()
    {
        self::deleteById($this->id);
        if ($this->type == 'image' && file_exists(SKETCH_PATH.$this->content)) {
            unlink(SKETCH_PATH.$this->content);
        }
    }
}
