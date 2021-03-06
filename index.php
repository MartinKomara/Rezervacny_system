<?php

require_once 'HTML/Template/Sigma.php';
require_once 'util.php';
session_start();

$tpl_main = new HTML_Template_Sigma('.');
$tpl_main->loadTemplateFile('index.html');

include 'classes/db.class.php';

require_once 'db_conn.php';
$db = new db($db_con);


include 'classes/Crypto.class.php';
include 'classes/Uzivatel.class.php';
include 'classes/Menu.class.php';
include 'classes/Sport.class.php';
include 'classes/Rezervacia.class.php';
include 'classes/iCalcreator.class.php';




if (isset($_GET['id']))
{	
    $id = $_GET['id'];
    $cmd = $_GET['cmd'];
}
else
{
    $id = "home";
    $cmd = "home";
}
$page = 'mod_'.$id.'/'.$cmd.'.php';
ob_start();
$subor = $cmd;

include('mod_top/top.php');
include('mod_menu/menu.php');
if (!file_exists($page))
{
	die('error page not found');
}
$pristup = 0;

if (isset($_SESSION['group_id']) && !empty($_SESSION['group_id']))
{
	$prava = $db->getZaznamy("select prava_skupiny.prava_skupiny_id as id, prava_skupiny.skupiny_id as prava from prava_skupiny join pristupove_prava on
    (prava_skupiny.pristupove_prava_id = pristupove_prava.pristupove_prava_id) and pristupove_prava.nazov = '$subor'",'id');
    
	foreach ($prava as $value)
	{
        if ($_SESSION['group_id'] == $value['prava'])
		{
            $pristup = 1;
            break;
		}
	}
}
else
{
    $prava = $db->getZaznamy("select prava_skupiny.prava_skupiny_id as id, prava_skupiny.skupiny_id as prava from prava_skupiny join pristupove_prava on
    (prava_skupiny.pristupove_prava_id = pristupove_prava.pristupove_prava_id) and pristupove_prava.nazov = '$subor'",'id');
	
	foreach ($prava as $value)
	{
        if (3 == $value['prava'])
        {
            $pristup = 1;
            break;
		}
	}
}


$pos = strpos($subor, "_proc");


if (($pristup == 1) || ($pos == true))
{
	include($page);
    $tpl_main->setCurrentBlock('page_content');
    $tpl_main->setVariable('content', ob_get_contents());
    $tpl_main->parseCurrentBlock('page_content');
    $tpl_main->hideBlock('access_denied');
}
else
{
    $tpl_main->setCurrentBlock('page_content');
    $tpl_main->setVariable('content', ob_get_contents());
    $tpl_main->parseCurrentBlock('page_content');
    $tpl_main->touchBlock('access_denied');
}
ob_end_clean();
$tpl_main->show();

?>