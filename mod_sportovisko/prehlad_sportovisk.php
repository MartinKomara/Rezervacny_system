<?php
require_once 'HTML/Template/Sigma.php';
$tpl = new HTML_Template_Sigma('./mod_sportovisko');
$tpl->loadTemplateFile('prehlad_sportovisk.html');

	
$tpl->show();