<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_login');
$tpl->loadTemplateFile('registracia.html');

include("simple-php-captcha.php");
$_SESSION['captcha'] = captcha();



$tpl->setVariable('captcha',$_SESSION['captcha']['image_src']);

if (isset($_GET['chyba']) && $_GET['chyba'] == 2)
	$tpl->touchblock('chyba_mena');	
if (isset($_GET['chyba']) && $_GET['chyba'] == 3)
	$tpl->touchblock('chyba_captcha');	
	
$tpl->show();