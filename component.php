<?php require_once __DIR__.DIRECTORY_SEPARATOR.'header.php'; ?>

	

			<div class="jumbotron">
			<div class="jumbotron-contents">
				<h2>Mes composants</h2>
				<?php if ($myUser->connected()): ?>
					<a  onclick="edit_component();" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter</a>
				<?php endif; ?>
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
							<td>{{label}}<br/><small class="text-muted"><a href="{{link}}" target="_blank">{{link}}</a></small></td>
							<td>{{brand}}</td>
							<td>{{price}}</td>
							<td>
								<?php if ($myUser->connected()): ?>
									<div onclick="edit_component(this);" class="btn btn-primary"><i class="fa fa-pencil"></i></div>
									<div onclick="delete_component(this);" class="btn btn-danger"><i class="fa fa-times"></i></div>
								<?php endif; ?>
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
                <div class="form-group">
	          <label for="label">Libellé</label>
	          <input class="form-control" type="text" id="label"/>
	        </div>
                <div class="form-group">
	          <label for="label">Marque</label>
	          <input class="form-control" type="text" id="brand"/>
	        </div>
                <div class="form-group">
	          <label for="label">Lien</label>
	          <input class="form-control" type="text" id="link"/>
	        </div>
                <div class="form-group">
	          <label for="label">Prix</label>
	          <input class="form-control" type="text" id="price"/>
	        </div>
                <div class="form-group">
	          <label for="imageUpload">Image (fichier)</label><br/>
	          <img src="img/default_image.png" style="width:100px;height:100px;" id="imageUpload"/>
	        </div>
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" onclick="save_component();" data-dismiss="modal">Enregistrer</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>

	  </div>
	</div>			



<?php require_once __ROOT__.'footer.php' ?>
