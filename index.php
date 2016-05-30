<?php require_once __DIR__.DIRECTORY_SEPARATOR.'header.php'; ?>

	<?php if ($myUser->connected()): ?>

			<div class="jumbotron">
			<div class="jumbotron-contents">
				<h2>Mes sketch(s)</h2>
				<div class="btn-group">
				  <button class="btn btn-success" type="button" data-toggle="modal" data-target="#editSketch">Ajouter</button>
				  <button data-toggle="dropdown" class="btn btn-success dropdown-toggle" type="button">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul role="menu" class="dropdown-menu">
					<li><a data-toggle="modal" data-target="#editSketch">Créer</a></li>
					<li><a data-toggle="modal" data-target="#importSketch" href="#">Importer depuis un hackpoint</a></li>
					<li><a id="importJsonSketch">Importer depuis un fichier json</a></li>
				  </ul>
				</div>
				
				<table class="table table-striped table-hover" id="sketchs">
					<thead>
						<tr>
							<th>#</th>
							<th>Libellé</th>
							<th style="width:100px;">Options</th>
						</tr>
					</thead>
					<tbody>
						<tr data-id="{{id}}" style="display:none;">
							<td><a href="sketch.php?id={{id}}">{{id}}</a></td>
							<td><a href="sketch.php?id={{id}}">{{label}}</a></td>
							<td>
								<div onclick="delete_sketch(this);" class="btn btn-danger"><i class="fa fa-times"></i></div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
	<!-- Modal -->
	<div id="editSketch" class="modal fade" role="dialog" data-action="create_sketch">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Edition Sketch</h4>
	      </div>
	      <div class="modal-body">
	      	<label for="label">Comment vas t-on appeller ça ? :D</label>
	       <input class="form-control" type="text" id="label"/>
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" onclick="create_sketch();" data-dismiss="modal">Enregistrer</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
		
	  </div>
	</div>
	
	<!-- Modal -->
	<div id="importSketch" class="modal fade" role="dialog" data-action="import_sketch">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Edition Sketch</h4>
	      </div>
	      <div class="modal-body">
	      	<label for="url">Quelle est l'adresse du sketch ? :D</label>
	        <input class="form-control" type="text" id="url"/>
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" onclick="import_sketch();" data-dismiss="modal">Enregistrer</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
		
	  </div>
	</div>
			
	<?php else: ?>
		<div class="jumbotron">
			<div class="jumbotron-contents">
				<h2>Connexion</h2>
				<form action="action.php?action=login" method="post">
				<label for="login">Identifiant</label>
				<input type="text" class="form-control" name="login" placeholder="Identifiant">
				<label for="password">Mot de passe</label>
				<input type="password" class="form-control" name="password"  placeholder="Mot de passe">
				<br/>
				<input type="submit" class="btn btn-primary btn-block" value="Connexion"/>
				</form>
			</div>
		</div>
	<?php endif; ?>

<?php 



require_once __ROOT__.'footer.php' ?>
