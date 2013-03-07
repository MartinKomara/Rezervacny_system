<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_stredisko');
$tpl->loadTemplateFile('nove_stredisko_proc.html');

$stredisko = new Stredisko($db);

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
       
         $idcko = $stredisko->nove_stredisko($_POST['nazov_strediska'], $_POST['nazov_sportoviska'], $_POST['pocet_sportovisk'], $_POST['adresa'], $_POST['tel_cislo'], $_POST['e_mail'], $_POST['webova_stranka'], $interval, $otvaracie_hodiny, $cena, $detail);
  
	
        header ("Location: index.php?id=stredisko&cmd=prehlad_stredisk&stredisko_id=".$idcko);
        exit;
    	
}

?>