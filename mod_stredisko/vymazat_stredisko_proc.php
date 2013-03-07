<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_stredisko');
$tpl->loadTemplateFile('vymazat_stredisko_proc.html');

$stredisko = new Stredisko($db);
$stredisko->vymaz_stredisko($_GET['stredisko_id']);
header ("Location: index.php?id=stredisko&cmd=prehlad_stredisk");
exit;	
$tpl->show();