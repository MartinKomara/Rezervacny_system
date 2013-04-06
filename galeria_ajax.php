<?php
include 'classes/db.class.php';
require_once 'db_conn.php';
$db = new db($db_con);
include 'classes/Sport.class.php';
$sport_id = $_POST['sport'];
$sport = new Sport($db);




$fotky = $sport->vrat_fotky($sport_id);
if (empty($fotky))
	exit;
echo "<fieldset>";
echo "<legend>Galéria</legend>";
echo "<ul>";
	
foreach($fotky as $fotka)
{
	echo "       <li>";
	echo "           <a href='images/sportovisko/".$fotka['subor']."'>";
	echo "               <img src='images/sportovisko/".$fotka['subor']."' width='72' height='72' alt='' />";
	echo "          </a>";
	echo "        </li>";
}

echo "   </ul>";
echo "</fieldset>";	
echo "<script>";
echo "$('#gallery a').lightBox();";
echo "</script>";	




	
	
	


	
?>