<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_sportovisko');
$tpl->loadTemplateFile('nove_sportovisko.html');


$tyzden = array("Pondelok","Utorok","Streda","Stvrtok","Piatok","Sobota","Nedela");
foreach ($tyzden as $den)
{
	$interval = 15;
	for ($i = 0; $i < 5; $i++)
	{
		$interval += 15;
		$tpl->setCurrentBlock("interval");
		$tpl->setVariable("hodnota",$interval);
		$tpl->parseCurrentBlock("interval");
	}
	$tpl->setCurrentBlock("den");
		$tpl->setVariable("nazov_dna",$den);
	$tpl->parseCurrentBlock("den");
}

foreach($tyzden as $den)
{
	$tpl->setCurrentBlock("hod_den");
	$tpl->setVariable("nazov_dna",$den);
	$tpl->parseCurrentBlock("hod_den");
}
	
$tpl->show();