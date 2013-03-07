<?php
	require_once 'HTML/Template/Sigma.php';
	$tpl = new HTML_Template_Sigma('./mod_login');
	$tpl->loadTemplateFile('odhlasenie_proc.html');
	
	$uzivatel = new Uzivatel($db);	
	
	if (isset($_POST['odhlasit']) && !empty($_POST['odhlasit']))
	{
		$uzivatel->odhlas();
		header ("Location: index.php?id=home&cmd=home");
	}

?>