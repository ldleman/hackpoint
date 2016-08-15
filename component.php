<?php require_once __DIR__.DIRECTORY_SEPARATOR.'header.php'; ?>

	<?php if($myUser->connected()): ?>

			<div class="jumbotron">
			<div class="jumbotron-contents">
				<h2>Mes composants</h2>
				<a  onclick="edit_component();" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter</a>
				<table class="table table-striped table-hover" id="components">
					<thead>
						<tr>
							<th>#</th>
							<th>Image</th>
							<th>Libellé</th>
							<th>Marque</th>
							<th>Prix moyen</th>
							<th style="width:100px;">Options</th>
						</tr>
					</thead>
					<tbody>
						<tr data-id="{{id}}" style="display:none;">
							<td>{{id}}</td>
							<td><div class="componentImage"><img src="{{image}}"/></div></td>
							<td>{{label}}</td>
							<td>{{brand}}</td>
							<td>{{price}}</td>
							<td>
								<div onclick="edit_component(this);" class="btn btn-primary"><i class="fa fa-pencil"></i></div>
								<div onclick="delete_component(this);" class="btn btn-danger"><i class="fa fa-times"></i></div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
	<!-- Modal -->
	<div id="editComponent" class="modal fade" role="dialog" data-id="" data-action="save_component">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Edition composant</h4>
	      </div>
	      <div class="modal-body">
	      	<label for="label">Libellé</label>
	        <input class="form-control" type="text" id="label"/>
	        <label for="label">Marque</label>
	        <input class="form-control" type="text" id="brand"/>
	        <label for="label">Prix</label>
	        <input class="form-control" type="text" id="price"/>
			<label for="imageUpload">Image (fichier) (taille max : <?php echo max_upload_size(array(ALLOWED_RESOURCE_SIZE)); ?> )</label><br/>
			<img src="img/default_image.png" style="width:100px;height:100px;" id="imageUpload"/>
			
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" onclick="save_component();" data-dismiss="modal">Enregistrer</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>

	  </div>
	</div>
			
	<?php
	 else: 
		header('index.php');
	 endif; ?>

<?php 



require_once __ROOT__.'footer.php' ?>
