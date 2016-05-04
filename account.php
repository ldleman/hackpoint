<?php require_once __DIR__.DIRECTORY_SEPARATOR.'header.php'; ?>

	<?php if(!$myUser->connected()) header('location: index.php'); ?>

		<div class="jumbotron">
			<div class="jumbotron-contents">
				<form method="POST" action="action.php?action=save_user&amp;id=<?php echo $myUser->id;?>">
					<h2>Mon profil</h2>
					<label for="password">Nouveau mot de passe</label>
	      			<input type="password" id="password" name="password" class="form-control"/>
	      			<label for="confirmPassword">Nouveau mot de passe (Confirmation)</label>
	      			<input type="password" id="confirmPassword" name="confirmPassword" class="form-control"/>
	      			<br/>
	      			<input class="btn btn-primary" type="submit" value="Enregistrer">
				</form>
			</div>
		</div>

<?php require_once __ROOT__.'footer.php' ?>
