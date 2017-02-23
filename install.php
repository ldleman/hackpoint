<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="css/bootflat.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
    

		<!-- menu -->
		<nav role="navigation" class="navbar navbar-inverse">
                  <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button data-target="#bs-example-navbar-collapse-5" data-toggle="collapse" class="navbar-toggle" type="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      <a href="index.php" class="navbar-brand">Hackpoint</a>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
					
                  </div><!-- /.container-fluid -->
                </nav>
		<!-- menu -->
		

		
		<!-- body -->
		<div class="container-fluid">
		

<?php
try {
    mb_internal_encoding('UTF-8');
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'constant.php');
    require_once(__ROOT__.'function.php');
    date_default_timezone_set(TIME_ZONE);
    set_error_handler('errorToException');
    spl_autoload_register('app_autoloader');
    
    $_ = array_map('secure_user_vars', array_merge($_POST, $_GET));
    
    if (isset($_SESSION['currentUser'])) {
        $myUser =unserialize($_SESSION['currentUser']);
    }

	
	if(!is_writable (__ROOT__.UPLOAD_PATH)) throw new Exception('Le dossier '.__ROOT__.UPLOAD_PATH.' doit être accessible en ecriture, merci de taper la commande linux <code>sudo chown -R www-data:www-data '.__ROOT__.UPLOAD_PATH.'</code> ou de régler le dossier en écriture via votre client ftp');
	if(!is_writable (dirname(__ROOT__.DATABASE_PATH))) throw new Exception('Le dossier '.dirname(__ROOT__.DATABASE_PATH).' doit être accessible en ecriture, merci de taper la commande linux <code>sudo chown -R www-data:www-data '.dirname(__ROOT__.DATABASE_PATH).'</code> ou de régler le dossier en écriture via votre client ftp');
	if(!file_exists(__ROOT__.SKETCH_PATH)) mkdir(__ROOT__.SKETCH_PATH);
	
    if (file_exists(__ROOT__.DATABASE_PATH) && filesize(__ROOT__.DATABASE_PATH)>0) throw new Exception('Base déjà installée, pour réinstaller la base, supprimez le fichier '.DATABASE_PATH.', puis rechargez cette page.');
	if(!extension_loaded('gd') || !function_exists('gd_info'))  throw new Exception('L\'extension php GD2  est requise, veuillez installer GD2 (sous linux : <code>sudo apt-get install php5-gd && service apache2 restart</code>)');
	if(!in_array('sqlite',PDO::getAvailableDrivers())) throw new Exception('Le driver SQLITE est requis, veuillez installer sqlite3 (sous linux : <code>sudo apt-get install php5-sqlite && service apache2 restart</code>)');
	
	if (!file_exists(__ROOT__.PART_PATH)) mkdir(__ROOT__.PART_PATH,0755,true);
	
	//Class entities
	Entity::install(__ROOT__.'class');

    $admin = new User();
    $admin->login = 'admin';
    $admin->password = User::password_encrypt('admin');
    $admin->rank = 'ADMIN';
    $admin->save();
    ?>

<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<strong>Succès!</strong> La base est bien installée, l'utilisateur par défaut est <code>admin:admin</code>, pensez à changer le mot de passe rapidemment. 
</div>
<?php

} catch (Exception $e) {
    ?>

<div class="alert alert-danger">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<strong>Oops!</strong> <?php echo $e->getMessage();
    ?> 
</div>
<?php 
} ?>
<a class="btn btn-primary" href="index.php">Revenir à l'index</a>
 </div>
		<!-- body -->
  
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="js/vendor/bootflat.min.js"></script>
        <script src="js/vendor/mustache.min.js"></script>
		<script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
