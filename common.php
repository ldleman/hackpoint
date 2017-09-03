<?php 
    session_start('hackpoint-session');
    mb_internal_encoding('UTF-8');
    
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'constant.php');
    require_once(__ROOT__.'function.php');
    date_default_timezone_set(TIME_ZONE);

    set_error_handler('errorToException');
    spl_autoload_register('app_autoloader');
    
    if (!file_exists(__ROOT__.DATABASE_PATH)) {
        header('location:install.php');
    }

    global $myUser,$conf,$_,$success;
    
    $_ = array_map('secure_user_vars', array_merge($_POST, $_GET));
    $myUser = new User();
    $conf = new Configuration();
    $conf->getAll();
    $page = basename($_SERVER['PHP_SELF']).(isset($_SERVER['QUERY_STRING'])?'?'.$_SERVER['QUERY_STRING']:'');

  

    if (isset($_SESSION['currentUser'])) {
        $myUser =unserialize($_SESSION['currentUser']);
    }
    Plugin::includeAll();

    Plugin::addHook('resource_type',function(&$types){
        $types['readme'] = __DIR__.SLASH.'type'.SLASH.'Readme.class.php';
        $types['arduino'] = __DIR__.SLASH.'type'.SLASH.'Arduino.class.php';
        $types['c'] = __DIR__.SLASH.'type'.SLASH.'C.class.php';
        $types['shell'] = __DIR__.SLASH.'type'.SLASH.'Shell.class.php';
        $types['php'] = __DIR__.SLASH.'type'.SLASH.'Php.class.php';
        $types['xml'] = __DIR__.SLASH.'type'.SLASH.'Xml.class.php';
        $types['python'] = __DIR__.SLASH.'type'.SLASH.'Python.class.php';
        $types['java'] = __DIR__.SLASH.'type'.SLASH.'Java.class.php';
        $types['css'] = __DIR__.SLASH.'type'.SLASH.'Css.class.php';
        $types['javascript'] = __DIR__.SLASH.'type'.SLASH.'Javascript.class.php';
        $types['json'] = __DIR__.SLASH.'type'.SLASH.'Json.class.php';
        $types['image'] = __DIR__.SLASH.'type'.SLASH.'Image.class.php';
        $types['files'] = __DIR__.SLASH.'type'.SLASH.'File.class.php';
        $types['part'] = __DIR__.SLASH.'type'.SLASH.'PartType.class.php';
    });
    

?>