</div>
</div>



		<!-- Modal component -->
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
				<label for="brand">Marque</label>
				<input class="form-control" type="text" id="brand"/>
				<label for="price">Prix</label>
				<input class="form-control" type="text" id="price"/>
				<label for="link">Lien</label>
				<input class="form-control" type="text" id="link"/>
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

		<!-- body -->
  
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
        
        <script src="js/vendor/bootflat.min.js"></script>
        <script src="js/vendor/mustache.min.js"></script>
        <script src="js/vendor/dropzone.js"></script>
        <script src="js/vendor/codemirror.js"></script>
         <?php 
        echo PHP_EOL;
        foreach (glob('js/vendor/codemirror/*.js') as $js) {
            echo "\t\t<script type='text/javascript' src='$js'></script>".PHP_EOL;
        }
        ?>
		<script src="js/plugins.js"></script>
        <script src="js/vendor/jquery-ui.min.js"></script>
        <script src="js/main.js"></script>
		<div class="footer"><?php echo PROGRAM_NAME.' V'.SOURCE_VERSION.'.'.BASE_VERSION.' by <a href="http://idleman.fr">@idleman</a>'; ?></div>
    </body>
</html>
