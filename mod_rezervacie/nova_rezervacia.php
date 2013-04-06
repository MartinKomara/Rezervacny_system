<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_rezervacie');
$tpl->loadTemplateFile('nova_rezervacia.html');


$rezervacia = new Rezervacia($db);

if (isset($_GET['uzivatel_id']) && !empty($_GET['uzivatel_id']))
	$rezervacia->zmaz_docasne_rezervacie($_GET['uzivatel_id']);
else
	$rezervacia->zmaz_docasne_rezervacie($_SESSION['uzivatel_id']);


$sport = new Sport($db);

$sporty = $sport->vrat_sporty();

$i = 1;
foreach($sporty as $hodnota)
{
    $tpl->setCurrentBlock('riadok');
		$tpl->setVariable("nazov_sportu",$hodnota);		
		if (isset($_GET['uzivatel_id']) && !empty($_GET['uzivatel_id']))
			$tpl->setVariable("uzivatel_id", $_GET['uzivatel_id']);
		else
			$tpl->setVariable("uzivatel_id", $_SESSION['uzivatel_id']);
	    $tpl->setVariable('sport_id',$hodnota['sport_id']);
       $tpl->setVariable('nazov_sportu',$hodnota['nazov_sportu']);
	if ($i % 5 == 0)
			$tpl->setVariable("medzera","<div class = 'clear'></div><br><br><br><br>");
    $tpl->parseCurrentBlock('riadok');
	$i++;
}

if (isset($_GET['uzivatel_id']) && !empty($_GET['uzivatel_id']))
	$tpl->setVariable("uzivatel", $_GET['uzivatel_id']);


$tpl->show();