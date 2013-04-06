<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_login');
$tpl->loadTemplateFile('registracia_proc.html');

$uzivatel = new Uzivatel($db);

if (isset($_POST['registrovat']) && (!empty($_POST['registrovat'])))
{

		
    
	if ($_SESSION['captcha']['code'] != $_POST['captcha'])
	{
		header ("Location: index.php?id=login&cmd=registracia&chyba=3");
        exit;	
	}
	else
	{
		$tmp = $uzivatel->novy_uzivatel($_POST['meno'],$_POST['priezvisko'],$_POST['nick'],$_POST['e_mail'],$_POST['tel_cislo'],$_POST['heslo']);
		header ("Location: index.php?id=home&cmd=home");
		exit;
	}
}

?>