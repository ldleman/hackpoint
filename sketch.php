<?php require_once __DIR__.DIRECTORY_SEPARATOR.'header.php'; 
try{
if(!isset($_['id']) || !is_numeric($_['id'])) throw new Exception("Sketch non spécifié");
$sketch = Sketch::getById($_['id']);
if(!$sketch || $sketch->id == 0) throw new Exception("Sketch inexistant");

if(!$sketch->public && $myUser->id != $sketch->owner) throw new Exception("Sketch privé, demandez à son propriétaire de le rendre publique");

?>
<div class="row" id="sketch" data-id="<?php echo $sketch->id; ?>">
	<div class="col-md-4">
	
		<h5 id="sketchTitle"><input onblur="save_sketch_title(this);" type="text" value="<?php echo htmlentities(html_entity_decode($sketch->label)); ?>"/></h5>
	</div>
	<div class="col-md-8">
		<ul class="sketchOptions">
			<li onclick="window.location='action.php?action=export_sketch&amp;id=<?php echo $sketch->id; ?>';" title="Exporter"><i class="fa fa-share-square-o"></i></li>
			<li id="importResource" title="Envoyer des fichiers"><i class="fa fa-files-o"></i></li>
			<li onclick="toggle_embed_sketch();" title="Partager"><i class="fa fa-code"></i></li>
			<li onclick="toggle_share_sketch(this);" title="Rendre <?php echo $sketch->public==0?'Public':'Privé'; ?>"><i class="fa <?php echo $sketch->public==0?'fa-eye-slash':'fa-eye'; ?>"></i></li>
			<li id="download" onclick="window.location='action.php?action=download_sketch&amp;id=<?php echo $sketch->id; ?>'" title="Télécharger"><i class="fa fa-arrow-circle-o-down"></i></li>
		</ul>
	</div>
</div>
<div class="row">
	
	<div class="col-md-3">

		<?php if($myUser->id == $sketch->owner) : ?>
			   <div class="btn-group" style="margin:10px auto;">
				  <button onclick="add_resource();" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Ajouter une ressource</button>
				  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
					<?php foreach(Type::all() as $uid=>$type): ?>
					<li><a onclick="add_resource('<?php echo $uid; ?>');"><?php echo $type['label']; ?></a></li>
					<?php endforeach; ?>
				  </ul>
			    </div>
		<?php endif; ?>
		
		<div class="list-group resources" id="resources">
					  
			<a class="list-group-item" style="display:none;" data-id="{{id}}" onclick="select_resource($(this).attr('data-id'));"> 
				<h4 class="list-group-item-heading">{{label}}</h4>
				<p class="list-group-item-text">Type : <code>{{type}}</code></p>
				<div class="resources-options"><i class="fa fa-pencil" onclick="edit_resource(this,event);"></i> <i  onclick="delete_resource(this,event);" class="fa fa-times"></i></div>
			</a>

			
					 
		</div>
	</div>
	<div class="col-md-9">
		<div class="jumbotron">
			<div class="jumbotron-contents" id="resource">
				<h2 style="margin:0 0 5px 0;">Aucune ressource sélectionnée</h2>
				<div class="preloader"></div>
				<p>Ce tableau est tristement vide... :(</p>
			</div>
		</div>
	</div>
</div>





<!-- Resource edition -->
<div id="editResourceMeta" class="modal fade" role="dialog" data-action="save_resource" data-id="">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edition ressource</h4>
      </div>
      <div class="modal-body">
	  <label for="type">Type</label>
       <select class="form-control" id="type">
       	<?php foreach(Type::all() as $uid=>$type): ?>
       	<option value="<?php echo $uid; ?>"><?php echo $type['label']; ?></option>
       <?php endforeach; ?>
       </select>
       <label for="label">Libellé</label>
       <input class="form-control" type="text" id="label"/>
       
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-primary" onclick="save_resource();" data-dismiss="modal">Enregistrer</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Resource edition -->
<div id="embedModal" class="modal fade" role="dialog" data-action="save_resource" data-id="">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copier coller le code suivant</h4>
      </div>
      <div class="modal-body">
      <label for="code">Code</label>
      <textarea class="form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<?php 
}catch(Exception $e){
	$_SESSION['error'] = $e->getMessage();
	header('location: index.php');
}
require_once __ROOT__.'footer.php' ?>