<?php 
$menu = new Menu($db);
	
if (isset($_POST['ulozit'])  && !empty($_POST['ulozit']))
	{
		$menu->uprav_polozku($_POST['id'],$_POST['nazov'],$_POST['modul'],$_POST['subor']);
		header ("Location: index.php?id=admin&cmd=admin_menu");
		exit;
	}
	
if (isset($_POST['zmazat'])  && !empty($_POST['zmazat']))
	{
		$menu->zmaz_polozku($_POST['id']);
		header ("Location: index.php?id=admin&cmd=admin_menu");
		exit;
	}

?>