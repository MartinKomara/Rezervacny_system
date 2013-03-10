<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_stredisko');
$tpl->loadTemplateFile('podrobnosti_strediska.html');

$stredisko = new Stredisko($db);
$tmp = $stredisko->vrat_stredisko($_GET['stredisko_id']);
$kontakt = $stredisko->vrat_kontakt($_GET['stredisko_id']);




$tpl->setVariable("nazov_strediska",$tmp['nazov_strediska']);
$tpl->setVariable("nazov_sportoviska",$tmp['nazov_sportoviska']);
$tpl->setVariable("pocet_sportovisk",$tmp['pocet_sportovisk']);
$tpl->setVariable("adresa",$kontakt['adresa']);
$tpl->setVariable("tel_cislo",$kontakt['tel_cislo']);
$tpl->setVariable("e_mail",$kontakt['e_mail']);
$tpl->setVariable("webova_stranka",$kontakt['webova_stranka']);


$jeden_interval = $stredisko->over_pocet_intervalov($_GET['stredisko_id']);

if ($jeden_interval != false)
{
    $tpl->setVariable($jeden_interval['0'],"selected");
    $tpl->setVariable('jednotna_cena',$jeden_interval['1']);
}
        
// intervaly
$tyzden = array(0=>"Pondelok",1=>"Utorok",2=>"Streda",3=>"Stvrtok",4=>"Piatok",5=>"Sobota",5=>"Nedela");
foreach ($tyzden as $key=>$value)
{
        $interval_databaza = $stredisko->vrat_casovy_interval($_GET['stredisko_id'],$key);
    
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
        $otvaracie_hodiny = $stredisko->vrat_otvaracie_hodiny($_GET['stredisko_id'],$j);
        $zaciatok = date('H:i',strtotime($otvaracie_hodiny['zaciatok']));
        $koniec = date('H:i',strtotime($otvaracie_hodiny['koniec']));  
        
	$tpl->setCurrentBlock("hod_den");
            $tpl->setVariable("nazov_dna",$den);
            $tpl->setVariable("tyzden_oznacenie",$j);
            $tpl->setVariable("zaciatok",$zaciatok);
            $tpl->setVariable("koniec",$koniec);        
            $j++;
	$tpl->parseCurrentBlock("hod_den");
}
	
$tpl->show();