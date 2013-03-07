<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_stredisko');
$tpl->loadTemplateFile('prehlad_stredisk.html');

$stredisko = new Stredisko($db);

$vsetky_strediska = $stredisko->vrat_strediska();

foreach($vsetky_strediska as $hodnota)
{
    $tpl->setCurrentBlock('riadok');
        $tpl->setVariable('id',$hodnota['id']);
        $tpl->setVariable('nazov_strediska',$hodnota['nazov_strediska']);
        $tpl->setVariable('otvorene',$hodnota['otvorene']);
    $tpl->parseCurrentBlock('riadok');
}

$tpl->show();