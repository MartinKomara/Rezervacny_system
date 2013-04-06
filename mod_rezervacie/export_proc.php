<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_rezervacie');
$tpl->loadTemplateFile('export_proc.html');


if (isset($_POST['exportovat']) && !empty($_POST['exportovat']))
{


$export = $_POST['export'];
$rezervacia = new Rezervacia($db);
$sport = new Sport($db);
$kontakt = $sport->vrat_kontakt();
$adresa = $kontakt['adresa'];

           
$config = array( "unique_id" => "web_sport");        
$v      = new vcalendar( $config );

$v->setProperty( "method", "PUBLISH" );
$v->setProperty( "x-wr-calname", "Calendar Sample" );
$v->setProperty( "X-WR-CALDESC", "Calendar Description" );



foreach($export as $key => $value)
{
	$polozka = $rezervacia->vrat_rezervaciu($key);
	$podrobnosti = $sport->vrat_sport($polozka['sport_id']);
	
	$popis = "Rezervácia športoviska na ".$podrobnosti['nazov_sportoviska']." ".$polozka['sportovisko'].". Cena : ".$polozka['cena']." €";
	
	$datum = new DateTime($polozka['datum']);
	$zaciatok = new DateTime($polozka['zaciatok']);
	
	$rok = $datum->format('Y'); 
	$mesiac = $datum->format('m'); 
	$den = $datum->format('d'); 
	
	
	$hodiny = strval(intval($zaciatok->format('H')));
	$minuty = strval(intval($zaciatok->format('i')));
	
	$posun = "+".$polozka['dlzka']." minutes";
	
	$zaciatok->modify($posun);
	$hodiny_koniec = strval(intval($zaciatok->format('H')));
	$minuty_koniec = strval(intval($zaciatok->format('i')));

	

	$vevent = & $v->newComponent( "vevent" );
	  
	$start = array("year"=>$rok,"month"=>$mesiac,"day"=>$den,"hour"=>$hodiny,"min"=>$minuty, "sec"=>0);
	$vevent->setProperty( "dtstart", $start );
	$end   = array("year"=>$rok,"month"=>$mesiac,"day"=>$den,"hour"=>$hodiny_koniec,"min"=>$minuty_koniec,"sec"=>0);
	$vevent->setProperty( "dtend",   $end );
	$vevent->setProperty( "LOCATION", $adresa );
	$vevent->setProperty( "summary", "Rezervácia športoviska" );
	$vevent->setProperty( "description", $popis );
	
	
}

	$v->parse();

	$v->setConfig( "directory", "" );
	$v->setConfig( "filename",  "icalmerge.ics" );
	$v->saveCalendar();
	
	
	$zip_filename = "icalmegre.ics";
	$user_filename = "export.ics";
	$temp_filename = "icalmerge.ics";
	$name = "export";
	
	
	$zip = new ZipArchive;
        $zip->open($zip_filename, ZIPARCHIVE::CREATE);
        if ($zip->addFile($temp_filename, $user_filename) == false)
        {
                echo "error while adding file: $temp_filename<br>";
        }
        $zip->close();
	
	
    header("Pragma: public"); 
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=\"" . basename($name) . ".zip\";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . filesize($zip_filename));
    ob_clean();
    flush();
    readfile($zip_filename);
	unlink($zip_filename);
    unlink($temp_filename);
    exit;
}

$tpl->show();