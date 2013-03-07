<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_top');
$tpl->loadTemplateFile('top.html');



if (isset($_GET['chyba']) && $_GET['chyba'] == 1)
	$tpl->touchblock('neznamy_uzivatel');

if (isset($_GET['chyba']) && $_GET['chyba'] == 2)
	$tpl->touchblock('nezname_heslo');



if (isset($_SESSION['uzivatel_id']))
	{
		$tpl->hideBlock('prihlasenie');
		$tpl->touchBlock('odhlasenie');
	}
else 
	{
		$tpl->touchBlock('prihlasenie');
		$tpl->hideBlock('odhlasenie');
	}




$tpl->show();