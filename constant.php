<?php

define('__ROOT__',dirname(__FILE__).DIRECTORY_SEPARATOR);
define('TIME_ZONE','Europe/Paris');
define('ENTITY_PREFIX', '');
define('DATABASE_PATH','database/.db');
define('BASE_CONNECTION_STRING','sqlite:'.DATABASE_PATH);
define('BASE_LOGIN',null);
define('BASE_PASSWORD',null);
define('UPLOAD_PATH','upload');
define('SKETCH_PATH',UPLOAD_PATH.'/sketch/');
define('PART_PATH',UPLOAD_PATH.'/part/');
define('ALLOWED_RESOURCE_IMAGE','jpg,png,jpeg,gif,bmp');
//Laisser vide si pas de vérification sur les resources de fichiers, sinon placez les extension
//autorisées sous la forme : jpg,png,jpeg,gif,bmp,txt,doc,docx,cmd,bat,exe,cpp,c,h,hpp,ino,php,js,css,ttf,woff,svg,pdf,xls,xlsx,sql,ico,doc,docx
define('ALLOWED_RESOURCE_FILE','');
define('ALLOWED_RESOURCE_SIZE',1000000);

define('PROGRAM_NAME','Hackpoint');
define('SOURCE_VERSION','1.0');
define('BASE_VERSION','1.0');
?>