<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_sportovisko');
$tpl->loadTemplateFile('nove_sportovisko_proc.html');

sportovisko = new Sportovisko($db);

if (isset($_POST['submit']) && !empty($_POST['submit']))
{

	$tmp = $uzivatel->novy_uzivatel($_POST['meno'],$_POST['priezvisko'],$_POST['nick'],$_POST['e_mail'],$_POST['tel_cislo'],$_POST['heslo']);	
    
	
	if ($tmp == false)
    {
        header ("Location: index.php?id=login&cmd=registracia&chyba=2");
        exit;
    }
	else
	{
		header ("Location: index.php?id=home&cmd=home");
		exit;
	}
	
}

?>