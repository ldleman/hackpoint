// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());


	
// Place any jQuery/helper plugins in here.
	
	$.page = function(element){
        	
		var path = window.location.href.split('/') ;
		path = path[path.length-1];
		path = path.split('.php');
		path = path[0];
        return path;
    }

	$.getForm= function(element){
        	var o = {};
			var obj = $(element);
			for(var key in obj.data()){
				if(key!="action" &&  key != "id") continue;
				o[key] = obj.attr('data-'+key);
			}
			
			$('input,select,textarea',obj).each(function(i,element){
					 if(element.id!=null && element.id!=""){
						if($(element).attr("type")=='checkbox' || $(element).attr("type")=='radio'){
							o[element.id] = ($(element).is(':checked')?1:0);
						}else{
							o[element.id] = $(element).val().replace("'","’");
						}
					 }
			});

           return o;
    }
	
	$.setForm= function(element,data){
        	var o = {};
			var obj = $(element);
			$('input,select,textarea',obj).each(function(i,element){
					
					 if(element.id!=null && element.id!=""){
						 
						if(data[element.id]!=null){
							if($(element).attr("type")=='checkbox' || $(element).attr("type")=='radio'){
								$(element).prop("checked",data[element.id]==1 || data[element.id]=='true' ?true:false);
							}else{
								
								$(element).val(data[element.id]);
							}
						}

					 }
			});
           return o;
    }
	
	
	$.action =  function(data,success,error) {
			$.ajax({
				dataType : 'json',
				method : 'POST',
				url : 'action.php',
				data : data,
				success: function(response){
					if(response.errors == null ) response.errors.push('Erreur indefinie, merci de contacter un administrateur');
					if(response.errors.length ==0 ){
						if(success!=null)success(response);
					}else{
						alert('ERREUR : '+"\n"+response.errors.join("\n"));
						if(error!=null) error(response);
					}
				},
				error : function(){
					alert('Erreur indefinie, merci de contacter un administrateur');
				}
			});
	}
	
	
	
	$.hashData = function(name){

			var page = window.location.hash.substring(1);

			page += "&"+window.location.search.substring(1);

			data = {};
			if(page!='' && page!= null){
				options = page.split('&');
				var data = {};
				for(var key in options){
					infos = options[key].split('=');
					data[infos[0]] = infos[1];
				}
			}
			if(name == null) return data;
			if(typeof name === "object"){
				data = name;
				hashstring = '';
				for(var key in data)
					hashstring+= "&"+key+"="+data[key];
				hashstring = hashstring.substring(1);
				window.location.hash = hashstring;
				return;
			}

			return typeof data[name] == "undefined" ? '':data[name];
	}


	$.urlParam = function(name){
			var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
			

			if (results==null){
				return null;
			}else{
				return results[1] || 0;
			}
	}
	
$.fn.extend({
	

	
	fill: function (option,callback){
		
            return this.each(function() {
				
				var obj = $(this);
				var model = null;
				var container = null;
				
				if(obj.prop("tagName") == 'UL'){
					container = obj;
					model = container.find('li:first-child');
					container.find('li:visible').remove();
				}else if(obj.prop("tagName") == 'TABLE'){
					container = obj.find('tbody');
					model = container.find('tr:first-child');
					container.find('tr:visible').remove();
				}else{
					container = obj;
					childName = container.children().get(0).nodeName;
					model = container.find(childName+':first-child');
					container.find(childName+':visible:not(.nofill)').remove();
				}
				var tpl = model.get(0).outerHTML;
				
				$.action(option,function(r){
					for(var key in r.rows){
						var line = $(Mustache.render(tpl,r.rows[key]));
						container.append(line);
						line.show();
					}
					
					if(callback!=null)callback(r);
				});
            });
	},
	
	enter: function (option){
            return this.each(function() {
				var obj = $(this);
				obj.keydown(function(event){
				if(event.keyCode == 13){
					option();
					return false;
				}
				});
				
            });
	},
	
	date: function (){
            return this.each(function() {

            	
				var obj = $(this);
				
				obj.datepicker({
					dateFormat: "dd/mm/yy",
					dayNames: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
					dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
					dayNamesShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
					monthNames: ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Décembre"],
					firstDay: 1,
					changeMonth: true,
					yearRange: "-100:+0",
					changeYear: true,
					onSelect: function(dateText, inst) { $( this).trigger( "blur" ); }
				});

            });
	}
});