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
							<td><a href="{{link}}">{{label}}</a></td>
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
		

			
	<?php
	 else: 
		header('index.php');
	 endif; ?>

<?php 



require_once __ROOT__.'footer.php' ?>
