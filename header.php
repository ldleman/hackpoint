<?php 
require_once __DIR__.DIRECTORY_SEPARATOR.'common.php';
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo PROGRAM_NAME.' V'.SOURCE_VERSION.'.'.BASE_VERSION ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
  		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic" rel="stylesheet" type="text/css">
  		<link rel="stylesheet" href="css/bootflat.min.css">
      <link rel="stylesheet" href="css/font-awesome.min.css">
      <link rel="stylesheet" href="css/codemirror.css">
      <link rel="stylesheet" href="css/monokai.css">
      <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <div id="wrap">
    


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
                    
					<?php if ($myUser->connected()): ?>
					<div id="bs-example-navbar-collapse-5" class="collapse navbar-collapse">
                      <ul class="nav navbar-nav">
                        <li <?php echo $page=='index.php'?'class="active"':''; ?>><a href="index.php">Sketch</a></li>
                        <li <?php echo $page=='component.php'?'class="active"':''; ?>><a href="component.php">Composants</a></li>
                        <!--
                        <li class="dropdown">
                          <a data-toggle="dropdown" class="dropdown-toggle" href="#">Réglages <b class="caret"></b></a>
                          <ul role="menu" class="dropdown-menu">
                            <li class="dropdown-header">Setting</li>
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li class="active"><a href="#">Separated link</a></li>
                            <li class="divider"></li>
                            <li class="disabled"><a href="#">One more separated link</a></li>
                          </ul>
                        </li>
                        -->
                      </ul>
                      <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown <?php echo $page=='account.php'?'active':''; ?>" >
                          <a data-toggle="dropdown" class="dropdown-toggle" href="#"> Connecté en tant que <?php echo $myUser->login; ?> <b class="caret"></b></a>
                          <ul role="menu" class="dropdown-menu">
                            <li class="dropdown-header">Profil</li>
                            <li ><a href="account.php">Modifier</a></li>
                            <li class="divider"></li>
                            <li><a href="action.php?action=logout">Déconnexion</a></li>
                          </ul>
                        </li>
                      </ul>
					 

            

					           <!--<button class="btn btn-danger navbar-btn" onclick="window.location='action.php?action=logout';" type="button">MAJ</button>-->
					  
                    </div><!-- /.navbar-collapse -->
					<?php endif; ?>
					
					
                  </div><!-- /.container-fluid -->
                </nav>
		<!-- menu -->
		

		
		<!-- body -->
		<div class="container-fluid">
		
		
		<!-- messages -->
			<?php if (isset($_SESSION['error'])): ?>
			<div class="alert alert-danger">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <strong>Oops!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
			<?php endif; ?>
			<?php if (isset($_SESSION['success'])): ?>
			<div class="alert alert-success alert-dismissable">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <strong>Succès !</strong> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
			<?php endif; ?>
			<!-- messages -->
