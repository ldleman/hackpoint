<?php require_once __DIR__.DIRECTORY_SEPARATOR.'header.php'; ?>



<div class="jumbotron">
	<div class="jumbotron-contents">
		<?php $myUser->connected() ? "<h2>Mes sketch(s)</h2>" : "<h2>Sketch(s) publique</h2>"; ?>
		<?php if ($myUser->connected()): ?>
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
		<?php endif ?>

		<table class="table table-striped table-hover" id="sketchs">
			<thead>
				<tr>
					<th>#</th>
					<th>Libellé</th>
					<th>Public</th>
					<th style="width:100px;">Options</th>
				</tr>
			</thead>
			<tbody>
				<tr data-id="{{id}}" style="display:none;">
					<td><a href="sketch.php?id={{id}}">{{id}}</a></td>
					<td><a href="sketch.php?id={{id}}">{{label}}</a></td>
					<td><a href="sketch.php?id={{id}}">{{public}}</a></td>
					<td>
						<?php if ($myUser->connected()): ?>
							<div onclick="delete_sketch(this);" class="btn btn-danger"><i class="fa fa-times"></i></div>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>






<!-- Modal Edit Sketch -->
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

<!-- Modal Import Sketch -->
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

<?php  require_once __ROOT__.'footer.php'; ?>
