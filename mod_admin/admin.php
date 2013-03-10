<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_admin');
$tpl->loadTemplateFile('admin.html');

$moduly = array();

$groups = $db->getZaznamy("select * from skupiny","skupiny_id");

function vytried($str)
{
	$pos = strpos($str, "_proc");
	if ($pos == true)
			return true;
	$pos = strpos($str, ".html");
	if ($pos == true)	
		return true;
	return false;
	
}


foreach($groups as $group)
	{
		$tpl->setCurrentBlock('hlavicka');
		$tpl->setVariable('nazov_skupiny',$group['nazov']);
		$tpl->parseCurrentBlock('hlavicka');
	
	}

if ($handle = opendir('.')) {
    while (false !== ($modul = readdir($handle))) {
        if (preg_match("/^mod_/", $modul))
			array_push($moduly,$modul);
    }
	
	
	
	foreach($moduly as $modul)
	{
	$subory = scandir($modul);
		
	foreach($subory as $subor)	
	{				
			 if ((!in_array($subor,array(".",".."))) && (vytried($subor) == false))
			 {
				$nazov_suboru = substr($subor,0,-4);
				
				$pocet = $db->num_rows("select * from pristupove_prava where nazov = '{$nazov_suboru}'");
				if ($pocet == 0)
				{
					$prava = array();
					$prava['nazov'] = $nazov_suboru;
					$db->makeInsert("pristupove_prava",$prava);
					$id = $db->insert_id();
					
					for ($i = 1; $i < 4; $i++)
					{
						$pole['pristupove_prava_id'] = $id;
						$pole['skupiny_id'] = $i;
						$db->makeInsert('prava_skupiny',$pole);
					}
				}
				
				foreach($groups as $group)
				{
					$tpl->setCurrentBlock('check');
					$riadok = $db->getZaznamy("select prava_skupiny.prava_skupiny_id as id, prava_skupiny.skupiny_id as group_id from prava_skupiny join pristupove_prava on (prava_skupiny.pristupove_prava_id = pristupove_prava.pristupove_prava_id) where pristupove_prava.nazov = '$nazov_suboru'",'id');

					$tmp = $db->getZaznam("select pristupove_prava.pristupove_prava_id as id, nazov from pristupove_prava where nazov = '{$nazov_suboru}'","nazov");
					$idcko = $tmp['id'];
					$checkbox_nazov = $group['nazov'];
					$tpl->setVariable('checkbox_nazov',$checkbox_nazov."[".$idcko."]");

					foreach($riadok as $value)
					{
						if ($value['group_id'] == 1 && $group['skupiny_id'] == 1)					
							$tpl->setVariable("oznacene","checked");
						if ($value['group_id'] == 2 && $group['skupiny_id'] == 2)					
							$tpl->setVariable("oznacene","checked");
						if ($value['group_id'] == 3 && $group['skupiny_id'] == 3)					
							$tpl->setVariable("oznacene","checked");
					}
					$tpl->parseCurrentBlock('check'); 
					
					
				}
				
				$tpl->setCurrentBlock('subor');
				$tpl->setVariable('nazov_suboru',$nazov_suboru);
				$tpl->parseCurrentBlock('subor');
				
			 } 
		}
		
	
		
		
		$tpl->setCurrentBlock('modul');
		$tpl->setVariable('nazov_modulu',$modul);
		$tpl->parseCurrentBlock('modul');
		
		
	}

    
    closedir($handle);
}


	
$tpl->show();