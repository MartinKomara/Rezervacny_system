<?php
	if (isset($_POST['ulozit']) && !empty($_POST['ulozit']))
	{
	$sport = new Sport($db);
	
	$sport->nastav_kontakt($_POST['adresa'], $_POST['tel_cislo'], $_POST['e_mail'], $_POST['webova_stranka']);
	
	header ("Location:/?id=kontakt&cmd=novy_kontakt");        
	exit;
}

?>