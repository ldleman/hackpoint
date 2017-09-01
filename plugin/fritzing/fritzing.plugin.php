<?php






function fritzing_plugin_add_type(&$types){
	$types['fritzing'] = __DIR__.SLASH.'Fritzing.class.php';
}



function fritzing_plugin_install($id){
	if($id != 'fr.idleman.fritzing') return;
	// en cas d'erreur : throw new Exception('Mon erreur');
}
function fritzing_plugin_uninstall($id){
	if($id != 'fr.idleman.fritzing') return;
	// en cas d'erreur : throw new Exception('Mon erreur');
}


//cette fonction comprends toutes les actions du plugin qui ne nécessitent pas de vue html
function fritzing_plugin_action(){
	require_once('action.php');
}


Plugin::addCss("/main.css"); 
Plugin::addJs("/main.js"); 

Plugin::addHook("install", "fritzing_plugin_install");
Plugin::addHook("uninstall", "fritzing_plugin_uninstall"); 
Plugin::addHook("action", "fritzing_plugin_action");    
Plugin::addHook("resource_type", "fritzing_plugin_add_type");    

?>