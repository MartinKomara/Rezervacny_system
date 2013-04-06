<?php
	session_start();

	include 'classes/db.class.php';
	require_once 'db_conn.php';
	$db = new db($db_con);
	include 'classes/Rezervacia.class.php';

	$rezervacia = new Rezervacia($db);
	
	$datum_obj = new DateTime($_GET['datum']);
	$datum = $datum_obj->format("Y-m-d");
	
	$zaciatok_obj = new DateTime($_GET['zaciatok']);
	$zaciatok = $zaciatok_obj->format("H:i");

	//if ($_SESSION['group_id'] == 1)
		$idcko = $_GET['uzivatel_id'];
	//else
	//	$idcko = $_SESSION['uzivatel_id'];
	
	
	if (isset($_GET['pridaj'])  && !empty($_GET['pridaj']))
	{
		$rezervacia->nova_docasna_rezervacia($_GET['sport'], $idcko, $_GET['sportovisko_id'], $datum, $zaciatok, $_GET['dlzka'], $_GET['cena']);
	}	
	else if (isset($_GET['vymaz'])  && !empty($_GET['vymaz']))
	{
		$rezervacia->vymaz_docasnu_rezervaciu($_GET['sport'], $idcko, $_GET['sportovisko_id'], $datum, $zaciatok);
	}


?>