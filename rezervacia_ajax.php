<?php

include 'classes/db.class.php';
require_once 'db_conn.php';
$db = new db($db_con);
include 'classes/Rezervacia.class.php';
include 'classes/Uzivatel.class.php';

if (isset($_GET['rezervacia_id']))
{
	$rezervacia = new Rezervacia($db);
	$rezervacia->potvrd_rezervaciu($_GET['rezervacia_id']);
}

if (isset($_GET['vymazat_rezervaciu_id']))
{
	$rezervacia = new Rezervacia($db);
	$rezervacia->zmaz_rezervaciu($_GET['vymazat_rezervaciu_id']);
}

if (isset($_GET['vymazat_uzivatel_id']))
{
	$uzivatel = new Uzivatel($db);
	$uzivatel->zmaz_uzivatela($_GET['vymazat_uzivatel_id']);
}


?>