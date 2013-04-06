<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_uzivatelia');
$tpl->loadTemplateFile('podrobnosti_uzivatela.html');
$rezervacia = new Rezervacia($db);
$sport = new Sport($db);
$uzivatel = new Uzivatel($db);

$uzivatel_id = $_GET['uzivatel_id'];

$uzivatel_info = $uzivatel->uzivatel($uzivatel_id);
$tpl->setVariable("meno_uzivatela",$uzivatel_info['meno']." ".$uzivatel_info['priezvisko']); 

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
		$tpl->setVariable('cena',$hodnota['cena']);
		$tpl->setVariable('dlzka',$hodnota['dlzka']);
		$tpl->setVariable('nazov_sportoviska',$nazov_sportoviska);
		$tpl->setVariable('sportovisko',$hodnota['sportovisko']);
		$datum_obj = new DateTime($hodnota['datum_pridania']);
		$datum_pridania = $datum_obj->format("d.m.Y");		
		$tpl->setVariable('datum_pridania',$datum_pridania);	
		if ($hodnota['potvrdena_rezervacia'] == 1)
			$tpl->setVariable('potvrdenie',"class = 'potvrdena' sorttable_customkey='1'");
		else
			$tpl->setVariable('potvrdenie',"class = 'nepotvrdena' sorttable_customkey='0'");
	$tpl->parseCurrentBlock('riadok');
}

$tpl->setVariable('uzivatel_id',$uzivatel_id);	


$tpl->show();