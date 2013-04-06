<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_sport');
$tpl->loadTemplateFile('prehlad_sportov.html');

$sport = new Sport($db);

$vsetky_sporty = $sport->vrat_sporty();


$i = 1;
foreach($vsetky_sporty as $hodnota)
{
    $tpl->setCurrentBlock('riadok');
		//$tpl->setVariable("nazov_strediska",$hodnota);		
	    $tpl->setVariable('id',$hodnota['id']);
       $tpl->setVariable('nazov_sportu',$hodnota['nazov_sportu']);
	if ($i % 5 == 0)
			$tpl->setVariable("medzera","<div class = 'clear'></div><br><br><br><br>");
    $tpl->parseCurrentBlock('riadok');
	$i++;
}

$tpl->show();