<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_uzivatelia');
$tpl->loadTemplateFile('prehlad_uzivatelov.html');

$uzivatel = new Uzivatel($db);

$vsetci_uzivatelia = $uzivatel->vrat_uzivatelov();

foreach($vsetci_uzivatelia as $hodnota)
{
    $tpl->setCurrentBlock('riadok');
        $tpl->setVariable('id',$hodnota['id']);
        $tpl->setVariable('meno',$hodnota['meno']);
        $tpl->setVariable('priezvisko',$hodnota['priezvisko']);
        $tpl->setVariable('nick',$hodnota['nick']);
        $tpl->setVariable('e_mail',$hodnota['e_mail']);
        $tpl->setVariable('tel_cislo',$hodnota['tel_cislo']);
    $tpl->parseCurrentBlock('riadok');
}

$tpl->show();