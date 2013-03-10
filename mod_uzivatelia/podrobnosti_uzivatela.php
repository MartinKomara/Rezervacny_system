<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_uzivatelia');
$tpl->loadTemplateFile('podrobnosti_uzivatela.html');

$uzivatel = new Uzivatel($db);
$meno = $uzivatel->uzivatel($_GET['uzivatel_id']);
$tpl->setVariable('uzivatel_meno',$meno['meno']." ".$meno['priezvisko']);


$tpl->show();