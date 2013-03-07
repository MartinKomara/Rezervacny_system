<?php 
 
if (isset($_POST['ulozit'])  && !empty($_POST['ulozit']))
	{
		$menu = new Menu($db);
		$menu->nova_polozka($_POST['nazov'], $_POST['modul'], $_POST['subor']);		
		header ("Location: index.php?id=admin&cmd=admin_menu");
		exit;
	}
	
?>