<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_admin');
$tpl->loadTemplateFile('admin_menu.html');

//$result = $db->getZaznamy("select menu.id as id, menu.nazov as nazov, menu.modul as modul,menu.subor as subor from menu join menu_to_groups on menu.id = menu_to_groups.menu_id","id");

$result = $db->getZaznamy("select * from menu","id");
foreach($result as $value)
{
	$menu_id = $value['id'];
	
		$grupy = $db->getZaznamy("select * from groups","id");
		foreach($grupy as $grupa)
		{
			$idcko = $grupa['id'];
			$pocet = $db->num_rows("select * from menu_to_groups where menu_id = $menu_id and group_id = $idcko");
		    if ($pocet != 0)
				$tpl->setVariable('oznacit',"checked");
			$tpl->setCurrentBlock('grupa');
			$tpl->setVariable('pristup',$grupa['nazov']);
			$tpl->setVariable('idcko',$value['id']);
			$tpl->parseCurrentBlock('grupa');
		}
	$tpl->setCurrentBlock('riadok');
	$tpl->setVariable('nazov',$value['nazov']);
	$tpl->setVariable('modul',$value['modul']);
	$tpl->setVariable('subor',$value['subor']);
	$tpl->setVariable('id',$value['id']);
	$tpl->parseCurrentBlock('riadok');
}

$tpl->show();