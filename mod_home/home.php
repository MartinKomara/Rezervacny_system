<?php
$tpl = new HTML_Template_Sigma('./mod_home');
$tpl->loadTemplateFile('home.html');

$tpl->show();