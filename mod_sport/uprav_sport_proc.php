<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_sport');
$tpl->loadTemplateFile('uprav_sport_proc.html');

$sport = new Sport($db);

if (isset($_POST['vymazat']) && !empty($_POST['vymazat']))
{
		$sport->vymaz_sport($_GET['sport_id']);
		header ("Location: index.php?id=sport&cmd=prehlad_sportov");
        exit;
}


if (isset($_POST['ulozit']) && !empty($_POST['ulozit']))
{
		$sport_id = $_GET['sport_id'];

        $otvaracie_hodiny = array($_POST['hodiny_0'],
                                  $_POST['hodiny_1'], 
                                  $_POST['hodiny_2'], 
                                  $_POST['hodiny_3'], 
                                  $_POST['hodiny_4'], 
                                  $_POST['hodiny_5'], 
                                  $_POST['hodiny_6']);    

        if (empty($_POST['detail']))
        {
            $interval = $_POST['interval'];
            $cena = $_POST['cena'];
            $detail = 0;
        }
        else
        {
            $detail = 1;
            $interval = array($_POST['Pondelok'], $_POST['Utorok'], 
                              $_POST['Streda'], $_POST['Štvrtok'], 
                              $_POST['Piatok'], $_POST['Sobota'], $_POST['Nedeľa']);
            $cena = array($_POST['cena_Pondelok'], $_POST['cena_Utorok'], 
                              $_POST['cena_Streda'], $_POST['cena_Štvrtok'], 
                              $_POST['cena_Piatok'], $_POST['cena_Sobota'], $_POST['cena_Nedeľa']);
        }        
	   
	    
	   
        $idcko = $sport->zmen_sport($sport_id, $_POST['nazov_sportu'], $_POST['nazov_sportoviska'], $_POST['pocet_sportovisk'], $interval, $otvaracie_hodiny, $cena, $detail);
    
		
		if (isset($_POST['zatvorenie_zaciatok']))
		{
			$zatvorenie_zaciatok = $_POST['zatvorenie_zaciatok'];
			$zatvorenie_koniec = $_POST['zatvorenie_koniec'];  
		
			/*   zablokovanie sportu  */
			for($i = 0; $i < count($zatvorenie_zaciatok); $i++)
			{
				$datum_zaciatok = new DateTime($zatvorenie_zaciatok[$i]);
				$datum_koniec = new DateTime($zatvorenie_koniec[$i]);
				$format_zaciatok = $datum_zaciatok->format("Y-m-d");
				$format_koniec = $datum_koniec->format("Y-m-d");
				if (!empty($zatvorenie_zaciatok[$i]) && (!empty($zatvorenie_koniec[$i])))	
					$sport->nove_zatvorenie($idcko, $format_zaciatok, $format_koniec);			
			}
		}
  
  
  
		/*  nahravanie fotiek */ 
		if(isset($_FILES['file']) && !empty($_FILES['file']) && count(($_FILES['file']['error'])) == 0)
		{			
			$subory = array();
						
			foreach ($_FILES['file']['name'] as $value)
			{
				array_push($subory, $value);				
			}			
			
			$i = 0;
			foreach ($_FILES['file']['tmp_name'] as $key=>$value)
			{
				$nazov = time() . "_" . basename($subory[$i]);
				$temp_filename = getcwd() ."/images/sportovisko/" . $nazov;
				move_uploaded_file($value, $temp_filename);		
				$fotka['subor'] = $nazov;
				$fotka['sport_id'] = $idcko;
				$db->makeInsert('fotky',$fotka);		
				$i++;				
			}
			
			
		}
  
	
        header ("Location: index.php?id=sport&cmd=prehlad_sportov&sport_id=".$idcko);
        exit;
    	
}

?>