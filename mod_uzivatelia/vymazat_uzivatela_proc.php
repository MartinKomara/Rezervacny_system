<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_uzivatelia');
$tpl->loadTemplateFile('vymazat_uzivatela_proc.html');

$uzivatel = new Uzivatel($db);

$vsetci_uzivatelia = $uzivatel->zmaz_uzivatela($_GET['uzivatel_id']);
header ("Location: index.php?id=uzivatelia&cmd=prehlad_uzivatelov");
exit;	

$tpl->show();