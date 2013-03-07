<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_admin');
$tpl->loadTemplateFile('nova_polozka.html');


$tpl->show();
?>