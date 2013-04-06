<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_menu');
$tpl->loadTemplateFile('menu.html');

if (isset($_SESSION['uzivatel_id']) && !empty($_SESSION['uzivatel_id']))
	$tpl->touchBlock('profil');
else 	
	$tpl->hideBlock('profil');

if (isset($_GET['cmd']) && isset($_GET['id']))
	$zalozka = $_GET['id'].$_GET['cmd'];
else
	$zalozka = '';



if (!isset($_SESSION['group_id']))
	$group_id = 3;
else 
	$group_id = $_SESSION['group_id'];
	
$result = $db->getZaznamy("select * from menu join menu_skupiny on menu.menu_id = menu_skupiny.menu_id where menu_skupiny.skupiny_id = $group_id","menu_id");

foreach($result as $value)
{
	$tpl->setCurrentBlock('riadok');
	$tpl->setVariable('id',$value['menu_id']);
	$tpl->setVariable('nazov',$value['nazov']);
	
	if ($zalozka == $value['modul'].$value['subor'])
		$tpl->setVariable('oznacene',"class = 'active'");
	else
		$tpl->setVariable('oznacene',"");
	$tpl->setVariable('modul',$value['modul']);
	$tpl->setVariable('subor',$value['subor']);
	$tpl->parseCurrentBlock('riadok');
	
}	
	
$tpl->show();