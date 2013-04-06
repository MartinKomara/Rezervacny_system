<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_sport');
$tpl->loadTemplateFile('podrobnosti_sportu.html');

$sport = new Sport($db);
$tmp = $sport->vrat_sport($_GET['sport_id']);


$tpl->setVariable("sport_id",$_GET['sport_id']);
$tpl->setVariable("nazov_sportu",$tmp['nazov_sportu']);
$tpl->setVariable("nazov_sportoviska",$tmp['nazov_sportoviska']);
$tpl->setVariable("pocet_sportovisk",$tmp['pocet_sportovisk']);


$jeden_interval = $sport->over_pocet_intervalov($_GET['sport_id'], 0);

if ($jeden_interval != false)
{
    $tpl->setVariable($jeden_interval['0'],"selected");
    $tpl->setVariable('jednotna_cena',$jeden_interval['1']);
}
        
// intervaly
$tyzden = array(0=>"Pondelok",1=>"Utorok",2=>"Streda",3=>"Štvrtok",4=>"Piatok",5=>"Sobota",6=>"Nedeľa");
foreach ($tyzden as $key=>$value)
{
    $interval_databaza = $sport->vrat_casovy_interval($_GET['sport_id'],$key, 0);
    
	$interval = 15;
	for ($i = 0; $i < 5; $i++)
	{
		$interval += 15;
		$tpl->setCurrentBlock("interval");
		$tpl->setVariable("hodnota",$interval);                
           if ($interval == $interval_databaza['dlzka_intervalu'])
               $tpl->setVariable("oznaceny_interval","selected");
		$tpl->parseCurrentBlock("interval");
	}
	$tpl->setCurrentBlock("den");
		$tpl->setVariable("nazov_dna",$value);
        $tpl->setVariable("cena",$interval_databaza['cena']);
	$tpl->parseCurrentBlock("den");
}



// otvaracie hodiny
$j = 0;
foreach($tyzden as $den)
{
        $otvaracie_hodiny = $sport->vrat_otvaracie_hodiny($_GET['sport_id'],$j, 0);
		
		if (!empty($otvaracie_hodiny['zaciatok']))
			$zaciatok = date('H:i',strtotime($otvaracie_hodiny['zaciatok']));
		else
			$zaciatok = "";
		
		if (!empty($otvaracie_hodiny['koniec']))
			$koniec = date('H:i',strtotime($otvaracie_hodiny['koniec']));  
        else
			$koniec = "";
		
		
	$tpl->setCurrentBlock("hod_den");
            $tpl->setVariable("nazov_dna",$den);
            $tpl->setVariable("tyzden_oznacenie",$j);
            $tpl->setVariable("zaciatok",$zaciatok);			
            $tpl->setVariable("koniec",$koniec);        
            $j++;
	$tpl->parseCurrentBlock("hod_den");
}


$fotky = $sport->vrat_fotky($_GET['sport_id']);

foreach($fotky as $fotka)
{
	
	$tpl->setCurrentBlock("fotka");
	$tpl->setVariable("riadok",$fotka['subor']);
	$tpl->setVariable("fotka_id",$fotka['fotky_id']);
	$tpl->parseCurrentBlock("fotka");
}


$zatvorenia = $sport->vrat_zatvorene_casy($_GET['sport_id']);

foreach($zatvorenia as $hodnota)
{
	
	$datum_zaciatok = new DateTime($hodnota['zaciatok']);
	$datum_koniec = new DateTime($hodnota['koniec']);
	$format_zaciatok = $datum_zaciatok->format("d.m.Y");
	$format_koniec = $datum_koniec->format("d.m.Y");
	
	$tpl->setCurrentBlock("zatvorenie");
	$tpl->setVariable("zaciatok",$format_zaciatok);
	$tpl->setVariable("koniec",$format_koniec);
	$tpl->setVariable("zatvorenie_id",$hodnota['id']);
	$tpl->parseCurrentBlock("zatvorenie");
}




	
$tpl->show();