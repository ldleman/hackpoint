<?php


//Cette fonction va generer un nouveau element dans le menu
function test_plugin_menu(&$menuItems){
	global $_;
	$menuItems[] = array(
	'sort'=>10,
	'url'=>'index.php?module=modele',
	'label'=>'Modele',
	'icon'=>'codepen'
	);
}

function test_plugin_menu_setting(&$menuItems){
	global $_;
	$menuItems[] = array(
	'sort'=>10,
	'url'=>'setting.php?module=modele',
	'label'=>'Modele',
	'icon'=>'codepen'
	);
}

function test_plugin_add_type(&$types){
	$types['readme'] = __DIR__.SLASH.'Readme.class.php';
	$types['readme'] = __DIR__.SLASH.'Arduino.class.php';
}


//Cette fonction va generer une page quand on clique sur Modele dans menu général
function test_plugin_page(){
	global $_;
	if(!isset($_['module']) || $_['module']!='modele') return;
	?>
	<h3>Mon plugin</h3>
	<h5>Plugins d'exemple</h5>
	
<?php
}

//Cette fonction va generer une page quand on clique sur Modele dans menu setting
function test_plugin_page_setting(){
	global $_;
	if(!isset($_['module']) || $_['module']!='modele') return;
	?>
	<h3>Réglages Mon plugin</h3>
	<h5>Plugins d'exemple</h5>
	
<?php
}

function test_plugin_install($id){
	if($id != 'fr.idleman.modele') return;
	// en cas d'erreur : throw new Exception('Mon erreur');
}
function test_plugin_uninstall($id){
	if($id != 'fr.idleman.modele') return;
	// en cas d'erreur : throw new Exception('Mon erreur');
}

function test_plugin_section(&$sections){
	$sections['modele'] = 'Gestion du plugin Modèle';
}


//cette fonction comprends toutes les actions du plugin qui ne nécessitent pas de vue html
function test_plugin_action(){
	require_once('action.php');
}


Plugin::addCss("/main.css"); 
Plugin::addJs("/main.js"); 

Plugin::addHook("install", "test_plugin_install");
Plugin::addHook("uninstall", "test_plugin_uninstall"); 
Plugin::addHook("section", "test_plugin_section");
Plugin::addHook("menu_main", "test_plugin_menu"); 
Plugin::addHook("page", "test_plugin_page");  
Plugin::addHook("menu_setting", "test_plugin_menu_setting"); 
Plugin::addHook("page_setting", "test_plugin_page_setting");  
Plugin::addHook("action", "test_plugin_action");    
Plugin::addHook("resource_type", "test_plugin_add_type");    

?>