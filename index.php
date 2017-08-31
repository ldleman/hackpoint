<?php require_once __DIR__.DIRECTORY_SEPARATOR.'header.php'; ?>

	

			<div class="jumbotron" id="sketchBlock">
			<div class="jumbotron-contents">
				<h2><i class="fa fa-inbox"></i> Sketch(s)</h2>
				
				<textarea>
					
					void setup() {
  Serial.begin(9600); 
  pinMode(PRESENCE_PIN, INPUT);    
  pinMode(LIGHT_PIN, INPUT);
  pinMode(SOUND_PIN, INPUT);
  pinMode(LED_PIN, OUTPUT);
  digitalWrite(LED_PIN,LOW);
  Serial.println("Demarrage de P.R.O.P.I.S.E");
  delay(3000);
  digitalWrite(LED_PIN,HIGH);
  Serial.println("Rruuullleez !");
}

				</textarea>
 <script>
      /*var cEditor = CodeMirror.fromTextArea(document.getElementById("c-code"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "text/x-csrc"
      });
     
      var ceylonEditor = CodeMirror.fromTextArea(document.getElementById("ceylon-code"), {
          lineNumbers: true,
          matchBrackets: true,
          mode: "text/x-ceylon"
      });
      var mac = CodeMirror.keyMap.default == CodeMirror.keyMap.macDefault;
      CodeMirror.keyMap.default[(mac ? "Cmd" : "Ctrl") + "-Space"] = "autocomplete";*/
    </script>
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
				<?php endif; ?>
				
				<table class="table table-striped table-hover" id="sketchs">
					<thead>
						<tr>
							<th>#</th>
							<th>Libellé</th>
							<th>Propriétaire</th>
							<th>Visibilité</th>
							<th style="width:100px;">Options</th>
						</tr>
					</thead>
					<tbody>
						<tr data-id="{{id}}" style="display:none;">
							<td><a href="sketch.php?id={{id}}">{{id}}</a></td>
							<td><a href="sketch.php?id={{id}}">{{label}}</a></td>
							<td>{{owner}}</td>
							<td>{{#public}}<i class="fa fa-eye"></i> Publique{{/public}}{{^public}}<i class="fa fa-eye-slash"></i> Privé{{/public}}</td>
							<td>
								<div onclick="delete_sketch(this);" class="btn btn-danger btn-mini btn-rounded pulse"><i class="fa fa-times"></i></div>
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
			


<?php 



require_once __ROOT__.'footer.php' ?>
