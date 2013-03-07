<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_admin');
$tpl->loadTemplateFile('uprav_polozku.html');

$menu = new Menu($db);
$result = $menu->vrat_polozku($_GET['idcko']);

$tpl->setCurrentBlock('form');
	$tpl->setVariable('nazov',$result['nazov']);
	$tpl->setVariable('modul',$result['modul']);
	$tpl->setVariable('subor',$result['subor']);
	$tpl->setVariable('id',$result['id']);
$tpl->parseCurrentBlock('form');


$tpl->show();