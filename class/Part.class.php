<?php

/**
 * Define a part.
 *
 * @author valentin carruesco
 *
 * @category Core
 *
 * @license copyright
 */
class Part extends Entity
{
    public $id,$label,$price,$link,$image,$owner,$brand;

    protected $fields =
    array(
        'id' => 'key',
        'label' => 'string',
        'price' => 'string',
        'link' => 'longstring',
        'image' => 'longstring',
        'brand' => 'longstring',
        'owner' => 'int',
    );

    public function remove()
    {
        self::deleteById($this->id);
        if (file_exists(PART_PATH.$this->image)) {
            unlink(PART_PATH.$this->image);
        }
    }
}
