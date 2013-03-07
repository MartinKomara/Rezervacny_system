<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_login');
$tpl->loadTemplateFile('registracia.html');

if (isset($_GET['chyba']) && $_GET['chyba'] == 2)
	$tpl->touchblock('chyba_mena');	
	
$tpl->show();