$(document).ready(function(){

	if($.hashData('embeded') =="1"){
		$('.navbar,.resources-options,#sketch').hide();
		$('.container-fluid,.col-md-9,.col-md-3').css('padding','0');
		$('.list-group-item,#resource').css('padding','3px').css('border-radius','0px');
		$('.jumbotron').css('border-radius','0');
		$('.row').css('margin','0');
		$('#resources').before('<div  style="width:100%;border-radius:0px;" onclick="'+$('#download').attr('onclick')+'" class="btn btn-success" ><i class="fa fa-arrow-circle-o-down"></i> Télécharger</div>');
		$('.col-md-3,.col-md-9').attr('style','width:20%;float:left;padding:0;');
		$('.col-md-9').attr('style','width:70%;float:left;padding:0;');
	}

	var init = 'init_'+$.page();

	if(window[init]!=null) window[init]();
	if($.page()=='' ) init_index();
		
	Dropzone.autoDiscover = false;

});



function init_index(){
	search_sketch();
		$('#label').enter(function(){
			create_sketch();
	});
	
	$('#importJsonSketch').dropzone({
			url : 'action.php?action=import_sketch',
			complete : function(useless,server){
				if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
        			search_sketch();
      			}
			},
			sending : function(file, xhr, formData){
				formData.append('from','file');
			}
	});
}



//COMPONENT

function init_component(){

	search_component();

	$('#imageUpload').dropzone({
			url : 'action.php?action=upload_component_image',
			success : function(useless,server){
					
        			$('#imageUpload').attr('src',server);
      			
			},
			sending : function(file, xhr, formData){
				//formData.append('id', $('#sketch').attr('data-id'));
			}
	});

	
	$('#label').autocomplete({
		source : 'http://hack.idleman.fr/action.php?action=find_component',
		minLength: 4,
		select: function( event, ui ) {
			
			$.setForm('#editComponent',ui.item.value);
			$('#imageUpload').attr('src',ui.item.value.image);
			return false;
		}
	}).data("uiAutocomplete")._renderItem = function (ul, item) {
		
        return $("<li />")
            .data("item.autocomplete", item)
            .append("<a><img style='height:50px;width:auto;' src='" + item.value.image + "' /> " + item.label + "</a>")
            .appendTo(ul);
	};
	
	
};

function save_component(){
	var data = $.getForm('#editComponent');
	
	if($('#imageUpload').attr('src').substring(0,10)=='data:image')
		data.image = $('#imageUpload').attr('src');
	
	$.action(data,function(r){
		search_component();
	});
}

function search_component(){
	$('#components').fill({action:'search_component'});
}

function edit_component(element){
	var data = {action : 'edit_component'};
	$('#editComponent input').val('');
	$('#imageUpload').attr('src','img/default_image.png');
	if(element!=null){
		var line = $(element).closest('tr');
		data.id = line.attr('data-id');
	}
	
	$.action(data,function(r){
		$('#editComponent').modal('show');
		$.setForm('#editComponent',r);
		$('#imageUpload').attr('src',r.image);
		$('#editComponent').attr('data-id',r.id);
	});
}


function delete_component(element){
	if(!confirm('Êtes vous sûr de vouloir supprimer ça?')) return;
	var line = $(element).closest('tr');
	$.action({action : 'delete_component',id : line.attr('data-id')},function(r){
		line.remove();
	});
}
//SKETCH

function init_sketch(){
	search_resources(function(){
			var resource = $.hashData('resource');
			resource = resource == '' ? $('#resources a:visible():eq(1)').attr('data-id') : resource;
			select_resource(resource);
	});
	
	
	
	$('#importResource i').dropzone({
			url : 'action.php?action=import_resource',
			complete : function(useless,server){
				if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
        			search_resources();
      			}
			},
			sending : function(file, xhr, formData){
				formData.append('id', $('#sketch').attr('data-id'));
			}
	});
}

function search_sketch(){
	$('#sketchs').fill({action:'search_sketch'});
}

function create_sketch(){
	$.action($.getForm('#editSketch'),function(r){
		window.location = 'sketch.php?id='+r.id;
	});
}

function import_sketch(){
	var data = $.getForm('#importSketch');
	data.from = 'url';
	$.action(data,function(){
		search_sketch();
	});
}

function delete_sketch(element){
	if(!confirm('Êtes vous sûr de vouloir supprimer ça?')) return;
	var line = $(element).closest('tr');
	$.action({action : 'delete_sketch',id : line.attr('data-id')},function(r){
		line.remove();
	});
}

function save_sketch_title(element){
	$.action({action : 'save_sketch_title',label : $(element).val(),id:$('#sketch').attr('data-id')});
}

function toggle_share_sketch(element){
	var button = $(element).find('i');
	var nextState = !button.hasClass('fa-eye');
	$.action({action:'toggle_share_sketch',state:(nextState?1:0),id:$('#sketch').attr('data-id')},function(){
		button.removeClass('fa-eye').removeClass('fa-eye-slash');
		button.addClass((nextState?'fa-eye':'fa-eye-slash'));
		$(element).attr('title','Rendre '+(nextState?'Privé':'Public'));
	});
}

function toggle_embed_sketch(){
	$('#embedModal').modal('show');
	$('#embedModal textarea').val('<a href="'+window.location+'"><small>Voir en taille réelle<small></a><br/><iframe frameborder="0" width="100%" align="center" height="400px" src="'+window.location+'&embeded=1"></iframe>');

	$("#embedModal textarea").focus(function() {
	    var $this = $(this);
	    $this.select();
	    $this.mouseup(function() {
	        $this.unbind("mouseup");
	        return false;
	    });
	});

}

//RESOURCE

function add_resource(selected){
	$('#resources a').removeClass('active');
	$('#editResourceMeta').modal('show');
	if(selected!=null) $('#editResourceMeta select').val(selected);
	$('#label').val('').focus();
	$('#resource').attr('data-id','');
}

function save_resource(){
	var data = $.getForm('#editResourceMeta');
	data.sketch = $('#sketch').attr('data-id');
	
	$.action(data,function(r){
		$('#editResourceMeta input').val('');
		$('#editResourceMeta').attr('data-id','');
		search_resources(select_resource(r.id));
	});
}

function search_resources(callback){
	var id = $('#sketch').attr('data-id');
	$('#resources').fill({id:id,action:'search_resources'},function(){
		if(callback!=null) callback();
	});
}

function edit_resource(element,event){
	event.stopPropagation();
	var line = $(element).closest('a');
	
	$.action({action:'edit_resource',id:line.attr('data-id')},function(r){
		$('#editResourceMeta').modal('show');
		$.setForm('#editResourceMeta',r);
		
		$('#editResourceMeta').attr('data-id',r.id);
	});
}

function delete_resource(element,event){
	event.stopPropagation();
	if(!confirm('Êtes vous sûr de vouloir supprimer ça?')) return;
	var line = $(element).closest('a');
	$.action({action : 'delete_resource',id : line.attr('data-id')},function(r){
		line.remove();
	});
}

function select_resource(id){
	$.hashData({resource:id});
	load_resource();
}

function load_resource(){
	var id = $.hashData('resource');
	
	var line = $('[data-id="'+id+'"]');
	if(line.attr('data-id')==null) return;
	$('.preloader').show();
	$('#resource p').html('');
	
	
	$.action({action:'edit_resource',id:line.attr('data-id')},function(r){
		$('.preloader').hide();
		$('#resources a').removeClass('active');
		line.addClass('active');
		
		$('#resource').attr('data-id',r.id);
		$('#resource h2').html(r.label);
		$('#resource p').html(r.content);
		$('#resource textarea:eq(0)').focus();

		if(r.upload !=null){
				var data = {};
				data.url = 'action.php?action=upload_resource';
				data.success = function(useless,r){
					if(r.errors.length!=0){
						alert('Erreur : '+r.errors.join(','));
					}else{
						$('#resource img:eq(0)').attr('src',r.url);
					}
				}
				data.sending = function(file, xhr, formData){
					formData.append('id', $('#resource').attr('data-id'));
				}
				data.createImageThumbnails = false;
				$('#resource p img:eq(0)').dropzone(data);
		}

		if(r.code != null){
			var editor = CodeMirror.fromTextArea($('#resource p textarea').get(0),r.code);


			editor.on("change", function() { 
				var data ={height:800};
				var wrap = editor.getWrapperElement();      
				var approp = editor.getScrollInfo().height > data.height ? data.height+"px" : "auto";
				if (wrap.style.height != approp) {
					wrap.style.height = approp;
					editor.refresh();
				}
			});
				
				  
			editor.on("blur", function(cm,obj){
				var data = {};
				data.content = cm.getValue();
				data.action='save_resource_content';
				data.id = $('#resource').attr('data-id');
				
				$.action(data,function(r){
					
				});
			});
		}
	
		if(r.callback !=null){
			eval(r.callback);
		}
		
	});
}

function init_part(){
	$('#label').autocomplete({
		source : 'action.php?action=autocomplete_part',
		minLength: 2,
		select: function( event, ui ) {
			save_part(ui.item.value);
			return false;
		}
	}).data("uiAutocomplete")._renderItem = function (ul, item) {
		
        return $("<li />")
            .data("item.autocomplete", item)
            .append("<a><img style='height:50px;width:auto; float:left;' src='" + item.value.image + "' /> " + item.label + " <br/></a><small>"+item.value.brand+"</small>")
            .appendTo(ul);
	};
	search_part();
};

function search_part(){
	$('#parts').fill({action:'search_part',id:$('#resource').attr('data-id')});
}

function save_part(model){
	var data = $.getForm('#partForm');
	if (model!=null) data.model = model.id;
	data.resource = $('#resource').attr('data-id');

	$.action(data,function(r){
		search_part();
		clear_part();
	});
}
function clear_part(){
	$('#partForm input').val('');
	$('#partForm').attr('data-id','');
}
/*
function edit_part(element){
	var line = $(element).closest('tr');
	$.action({action:'edit_part',id:line.attr('data-id')},function(r){
		$.setForm('#partForm',r);
		$('#partForm').attr('data-id',r.id);
	});
}
*/
function delete_part(element){
	if(!confirm('Êtes vous sûr de vouloir supprimer ça?')) return;
	var line = $(element).closest('tr');
	$.action({action : 'delete_part',id : line.attr('data-id')},function(r){
		line.remove();
	});
}