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
	
$result = $db->getZaznamy("select * from menu join menu_to_groups on menu.id = menu_to_groups.menu_id where menu_to_groups.group_id = $group_id","id");

foreach($result as $value)
{
	$tpl->setCurrentBlock('riadok');
	$tpl->setVariable('id',$value['id']);
	$tpl->setVariable('nazov',$value['nazov']);
	if ($zalozka == $value['modul'].$value['subor'])
		$tpl->setVariable('oznacene',"oznacene");
	else
		$tpl->setVariable('oznacene',"neoznacene");
	$tpl->setVariable('modul',$value['modul']);
	$tpl->setVariable('subor',$value['subor']);
	$tpl->parseCurrentBlock('riadok');
	
}	
	
$tpl->show();