<?php

include 'classes/db.class.php';
require_once 'db_conn.php';
$db = new db($db_con);
include 'classes/Sport.class.php';

$sport = new Sport($db);

if (isset($_GET['vymazat_sport_id']))
{	
	$sport->vymaz_sport($_GET['vymazat_sport_id']);
}

if (isset($_GET['vymazat_fotku_id']))
{
	$sport->vymaz_fotku($_GET['vymazat_fotku_id']);	
}

if (isset($_GET['vymazat_zablokovanie']))
{
	$sport->vymaz_zatvorenie($_GET['vymazat_zablokovanie']);
}


?>