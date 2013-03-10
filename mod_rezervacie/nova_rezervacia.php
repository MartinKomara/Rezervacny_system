<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_rezervacie');
$tpl->loadTemplateFile('nova_rezervacia.html');

$stredisko = new Stredisko($db);

$strediska = $stredisko->vrat_strediska();
foreach($strediska as $hodnota)
{
    $tpl->setCurrentBlock('riadok');
        $tpl->setVariable('stredisko_id',$hodnota['id']);
        $tpl->setVariable('nazov_strediska',$hodnota['nazov_strediska']);
    $tpl->parseCurrentBlock('riadok');
}


if (isset($_GET['stredisko_id']) && !empty($_GET['stredisko_id']))
{
    $tpl->touchBlock("stranka");
    
    $datum_den = 4;
    
    $sportovisko = $stredisko->vrat_stredisko($_GET['stredisko_id']);
    $pocet_sportovisk = $sportovisko['pocet_sportovisk'];
    $casovy_interval = $stredisko->vrat_casovy_interval($_GET['stredisko_id'], $datum_den);
    $otvaracie_hodiny = $stredisko->vrat_otvaracie_hodiny($_GET['stredisko_id'], $datum_den);
    $zaciatok = $stredisko->vrat_otvorenie($_GET['stredisko_id'], $datum_den);
    $koniec = $stredisko->vrat_zatvorenie($_GET['stredisko_id'], $datum_den);   
    
    // vypocet posunu casu
    $posun = $casovy_interval['dlzka_intervalu'] / 60;    
    
    
    // vypocet a vygenerovanie casovej osi
    for ($i = $zaciatok; $i < $koniec; $i += $posun )
        {
            $minuty = ($i - floor($i))*60;
            if ($minuty == 0)
               $minuty .= "0";
            $cas = floor($i).":".$minuty;
            $tpl->setCurrentBlock("casova_os");
                $tpl->setVariable('cas',$cas);
            $tpl->parseCurrentBlock("casova_os");
        }
    
    // vypis riadku tabulky pre sportovisko
    for ($j = 1; $j <= $pocet_sportovisk; $j++)
    {
        // vytvorenie bunky pre rezervaciu
        for ($i = $zaciatok; $i < $koniec; $i += $posun )
        {
            $tpl->setCurrentBlock("bunka");
                $tpl->setVariable('hodnota',"x");
            $tpl->parseCurrentBlock("bunka");
            
        }
    $tpl->setCurrentBlock('sportovisko');
        $tpl->setVariable('nazov_sportoviska',$sportovisko['nazov_sportoviska']." ".$j);
    $tpl->parseCurrentBlock('sportovisko');
    }
    
}

$tpl->show();