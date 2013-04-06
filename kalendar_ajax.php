<?php

session_start();
function hladaj_rezervaciu($obsadene_rezervacie,$cas)
{
	foreach($obsadene_rezervacie as $key=>$value)
	{
		if ($value['zaciatok'] == $cas)
			return $value['uzivatel_id'];			
	}
	return -1;
}



if ($_POST['datum'] == "dnes")
{
	$datum = date('d.m.Y', strtotime(' + 1 day') );
	$datum_den = date("N", strtotime($datum)) - 1;
}
else
{
	$datum = $_POST['datum'];
	$datum_den = date('N', strtotime($datum)) - 1;  
}



$sport_id = $_POST['sport'];
include 'classes/db.class.php';

require_once 'db_conn.php';
$db = new db($db_con);
include 'classes/Sport.class.php';
include 'classes/Rezervacia.class.php';


$sport = new Sport($db);
$rezervacia = new Rezervacia($db);

$datum_obj = new DateTime($datum);
$datum_format = $datum_obj->format("Y-m-d");


$sportovisko = $sport->vrat_sport($sport_id);
$pocet_sportovisk = $sportovisko['pocet_sportovisk'];
$casovy_interval = $sport->vrat_casovy_interval($sport_id, $datum_den, $datum_format);
$otvaracie_hodiny = $sport->vrat_otvaracie_hodiny($sport_id, $datum_den, $datum_format);
$zaciatok = $sport->vrat_otvorenie($sport_id, $datum_den, $datum_format);
$koniec = $sport->vrat_zatvorenie($sport_id, $datum_den, $datum_format);  
	
$zablokovanie = $sport->over_zablokovanie($sport_id, $datum_format);	

		
$posun = $casovy_interval['dlzka_intervalu'] / 60;  
  // vypocet casovej osi
  

  echo "<div class = 'clear'></div><br><div id = 'datum'>".$datum."</div>";
if (empty($otvaracie_hodiny) || $zablokovanie != 0)
{
	// sportovisko zatvorene
	echo "<table class = 'tabulka_rezervacie zavrete' id = 'tabulka_rezervacie'>";
	echo "<tr><td>Zavreté</td></tr>";
	echo "</table>";	
}
else
{
    
	echo "<table class = 'tabulka_rezervacie' id = 'tabulka_rezervacie'>";
    echo "<thead><tr>";
	echo "<td>&nbsp;</td>";
    for ($i = $zaciatok; $i < $koniec; $i += $posun )
        {
            $minuty = ($i - floor($i))*60;
            if ($minuty == 0)
               $minuty .= "0";
            $cas = floor($i).":".$minuty;
            echo "<td>";
                echo $cas;
            echo "</td>";
        }
		echo "</tr></thead><tbody>";
	
	  // vypis riadky tabulky pre sportovisko
    for ($j = 1; $j <= $pocet_sportovisk; $j++)
    {	
		echo "<tr>";
		echo "<td class = 'nazov_sportoviska'>";
		echo $sportovisko['nazov_sportoviska']." ".$j;
		echo "</td>";
		
		
        // vytvorenie bunky pre rezervaciu
        for ($i = $zaciatok; $i < $koniec; $i += $posun )
        {
			
		
			$minuty = ($i - floor($i))*60;
            if ($minuty == 0)
               $minuty .= "0";
            $cas = floor($i).":".$minuty;
			
			$cas_obj = new DateTime($cas);
			$cas_format = $cas_obj->format("H:i:s");
			$obsadene_rezervacie = $rezervacia->vrat_rezervacie_na_datum($datum_format, $sport_id, $j);
			$docasne_rezervacie = $rezervacia->vrat_docasne_rezervacie_na_datum($datum_format, $sport_id, $j);
			
			//id bunky v tvare : sport_id|sportovisko_id|datum|zaciatok|dlzka|cena
			$data = $sport_id.'|'.$j.'|'.$datum.'|'.$cas.'|'.$casovy_interval['dlzka_intervalu'].'|'.$casovy_interval['cena'];
			$cena_title = $casovy_interval['cena'];
			
			$idcko = $_POST['uzivatel'];
		   
		   
			if (hladaj_rezervaciu($obsadene_rezervacie,$cas_format) != -1)
				{
					
					if ($idcko == hladaj_rezervaciu($obsadene_rezervacie,$cas_format))
						{
							echo "<td id = ".$data." class = 'vlastna_rezervacia' title = 'Cena ".$cena_title." €, Čas : ".$cas."'>&nbsp;</td>";
						}
					else
						{
							echo "<td id = ".$data." class = 'obsadena_rezervacia'>&nbsp;</td>";
						}
				}
			else if (hladaj_rezervaciu($docasne_rezervacie,$cas_format) != -1)
				{
					echo "<td id = ".$data." class = 'vybrana' onclick = 'vymaz_rezervaciu(this.id,".$idcko.");' title = 'Cena ".$cena_title." €, Čas : ".$cas."'>&nbsp;</td>";
				}
			else
				{						
					echo "<td class = 'volna_rezervacia' onclick='pridaj_rezervaciu(this.id, this,".$idcko.");' id = ".$data." title = 'Cena ".$cena_title." €, Čas : ".$cas."'>&nbsp;</td>";
				}
			
            
        }
		
		
		echo "</tr></tbody>";
    }
	echo "</table>";
	
}






	
	
	


	
?>