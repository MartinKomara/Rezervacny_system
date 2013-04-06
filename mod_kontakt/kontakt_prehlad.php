<?php
$tpl = new HTML_Template_Sigma('./mod_kontakt');
$tpl->loadTemplateFile('kontakt_prehlad.html');


$sport = new Sport($db);

$kontakt = $sport->vrat_kontakt();

$tpl->setCurrentBlock('kontakt');
	$tpl->setVariable('adresa',$kontakt['adresa']);
	$tpl->setVariable('tel_cislo',$kontakt['tel_cislo']);
	$tpl->setVariable('e_mail',$kontakt['e_mail']);
	$tpl->setVariable('webova_stranka',$kontakt['webova_stranka']);
$tpl->parseCurrentBlock('kontakt');


$tpl->show();