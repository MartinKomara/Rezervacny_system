<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_rezervacie');
$tpl->loadTemplateFile('nova_rezervacia_proc.html');


if (isset($_POST['rezervovat']) && !empty($_POST['rezervovat']))
{
	
	if (isset($_POST['uzivatel_id']) && !empty($_POST['uzivatel_id']))
		$uzivatel_id = $_POST['uzivatel_id'];
	else
		$uzivatel_id = $_SESSION['uzivatel_id'];
	$sport_id = $_POST['sport_id'];
	$sportovisko_id = $_POST['sportovisko_id'];
	$datum = $_POST['datum'];	
	$zaciatok = $_POST['zaciatok'];
	$dlzka = $_POST['dlzka'];	
	$cena = $_POST['cena'];
	
	$rezervacia = new Rezervacia($db);
	
	for ($i = 0; $i < count($sport_id); $i++)
	{
		$konvertovany_datum = date('Y-m-d', strtotime($datum[$i]));;
		$konvertovany_zaciatok = date('H:i', strtotime($zaciatok[$i]));
		$rezervacia->nova_rezervacia($sport_id[$i], $uzivatel_id, $sportovisko_id[$i], $konvertovany_datum, $konvertovany_zaciatok, $dlzka[$i], $cena[$i]);	
	}
	
	if (isset($_POST['uzivatel_id']) && !empty($_POST['uzivatel_id']))
		{
			header ("Location:/?id=uzivatelia&cmd=podrobnosti_uzivatela&uzivatel_id=".$_POST['uzivatel_id']); 
			exit;
		}
	else
		{
			header ("Location:/?id=rezervacie&cmd=rezervacie_uzivatel"); 
			exit;
		}
}





$tpl->show();