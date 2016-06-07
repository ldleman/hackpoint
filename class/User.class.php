<?php

/**
 * Define an application user.
 *
 * @author valentin carruesco
 *
 * @category Core
 *
 * @license copyright
 */
class User extends Entity
{
    public $id,$login,$password,$name,$firstname,$mail,$state,$rank,$rights;
    protected $fields =
    array(
        'id' => 'key',
        'login' => 'string',
        'password' => 'string',
        'name' => 'string',
        'firstname' => 'string',
        'mail' => 'string',
        'rank' => 'longstring',
        'state' => 'int',
    );

    public static function check($login, $password)
    {
        $user = self::load(array('login' => $login, 'password' => self::password_encrypt($password)));

        return is_object($user) ? $user : new self();
    }

    public function can($section, $selectedRight)
    {
        return !isset($this->rights[$section]) ? false : $this->rights[$section][$selectedRight];
    }

    public function fullName()
    {
        $fullName = ucfirst($this->firstname).' '.strtoupper($this->name);

        return trim($fullName) != '' ? $fullName : $this->login;
    }

    public static function password_encrypt($password)
    {
        return sha1(md5($password));
    }

    public function connected()
    {
        return $this->id != 0;
    }
}
