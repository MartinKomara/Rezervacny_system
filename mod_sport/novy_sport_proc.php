<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_sport');
$tpl->loadTemplateFile('novy_sport_proc.html');

$sport = new Sport($db);
if (isset($_POST['ulozit']) && !empty($_POST['ulozit']))
{

		
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
                              $_POST['Streda'], $_POST['Stvrtok'], 
                              $_POST['Piatok'], $_POST['Sobota'], $_POST['Nedela']);
            $cena = array($_POST['cena_Pondelok'], $_POST['cena_Utorok'], 
                              $_POST['cena_Streda'], $_POST['cena_Stvrtok'], 
                              $_POST['cena_Piatok'], $_POST['cena_Sobota'], $_POST['cena_Nedela']);
        }        
       
        $idcko = $sport->novy_sport($_POST['nazov_sportu'], $_POST['nazov_sportoviska'], $_POST['pocet_sportovisk'], $interval, $otvaracie_hodiny, $cena, $detail);
  
  
		$zatvorenie_zaciatok = $_POST['zatvorenie_zaciatok'];
		$zatvorenie_koniec = $_POST['zatvorenie_koniec'];
		
		
		for($i = 0; $i < count($zatvorenie_zaciatok); $i++)
		{
			$datum_zaciatok = new DateTime($zatvorenie_zaciatok[$i]);
			$datum_koniec = new DateTime($zatvorenie_koniec[$i]);
			$format_zaciatok = $datum_zaciatok->format("Y-m-d");
			$format_koniec = $datum_koniec->format("Y-m-d");		
			$sport->nove_zatvorenie($idcko, $format_zaciatok, $format_koniec);			
		}
		
		
				
			if(isset($_FILES['file']) && !empty($_FILES['file']) && count(($_FILES['file']['error'])) == 0)
			{		
				$subory = array();
				foreach($_FILES['file']['name'] as $value)
				{
					array_push($subory, $value);
					$fotka['subor'] = $value;
					$fotka['sport_id'] = $idcko;
					$db->makeInsert('fotky',$fotka);
					unset($fotka);
				}
				$i = 0;
				foreach($_FILES['file']['tmp_name'] as $value)
				{
					 move_uploaded_file($value,"images/sportovisko/" . $subory[$i]);
					 $i++;
				}
				
			}
		

  
  
	
        header ("Location: index.php?id=sport&cmd=prehlad_sportov&sport_id=".$idcko);
        exit;
    	
}

?>