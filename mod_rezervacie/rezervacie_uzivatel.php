<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_rezervacie');
$tpl->loadTemplateFile('rezervacie_uzivatel.html');

$rezervacia = new Rezervacia($db);
$sport = new Sport($db);

$uzivatel_id = $_SESSION['uzivatel_id'];
$rezervacie = $rezervacia->vrat_rezervacie($uzivatel_id);

foreach($rezervacie as $hodnota)
{
	$datum_rez = new DateTime($hodnota['datum']);
	$datum = $datum_rez->format('d.m.Y'); 
	
	$zaciatok_rez = new DateTime($hodnota['zaciatok']);
	$zaciatok = $zaciatok_rez->format('H:i'); 

		
	$sportovisko = $sport->vrat_sport($hodnota['sport_id']);
	$nazov_sportoviska = $sportovisko['nazov_sportoviska']." ".$hodnota['sportovisko'];
	
	$tpl->setCurrentBlock('riadok');
		$tpl->setVariable('rezervacia_id',$hodnota['rezervacia_id']);
		$tpl->setVariable('datum',$datum);
		$tpl->setVariable('cas',$zaciatok);
		$tpl->setVariable('dlzka',$hodnota['dlzka']);
		$tpl->setVariable('cena',$hodnota['cena']);
		$tpl->setVariable('nazov_sportoviska',$nazov_sportoviska);
		$tpl->setVariable('sportovisko',$hodnota['sportovisko']);		
		if ($hodnota['potvrdena_rezervacia'] == 1)
			$tpl->setVariable('potvrdenie',"class = 'potvrdena' sorttable_customkey='1'");
		else
			$tpl->setVariable('potvrdenie',"class = 'nepotvrdena' sorttable_customkey='0'");		
	$tpl->parseCurrentBlock('riadok');


}

$tpl->show();