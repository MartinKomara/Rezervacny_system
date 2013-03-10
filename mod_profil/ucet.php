<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_profil');
$tpl->loadTemplateFile('ucet.html');

$uzivatel = new Uzivatel($db);
$id = $_SESSION['uzivatel_id'];
$value = $uzivatel->uzivatel($id);

if (isset($_GET['chyba_nick']) && !empty($_GET['chyba_nick']))
    $tpl->touchBlock('chyba_nick');
if (isset($_GET['chyba_stare_heslo']) && !empty($_GET['chyba_stare_heslo']))
    $tpl->touchBlock('chyba_stare_heslo');
if (isset($_GET['chyba_nove_heslo']) && !empty($_GET['chyba_nove_heslo']))
    $tpl->touchBlock('chyba_nove_heslo');

$tpl->setCurrentBlock('ucet');
        $tpl->setVariable('uzivatel_id',$id);
	$tpl->setVariable('meno',$value['meno']);
	$tpl->setVariable('priezvisko',$value['priezvisko']);
	$tpl->setVariable('nick',$value['nick']);
	$tpl->setVariable('e_mail',$value['e_mail']);
	$tpl->setVariable('tel_cislo',$value['tel_cislo']);
$tpl->parseCurrentBlock('ucet');

$tpl->show();