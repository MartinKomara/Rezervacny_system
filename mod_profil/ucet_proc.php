<?php
require_once 'HTML/Template/Sigma.php';


$tpl = new HTML_Template_Sigma('./mod_profil');
$tpl->loadTemplateFile('ucet_proc.html');

if (isset($_POST['submit']) && !empty($_POST['submit']))
{

$uzivatel = new Uzivatel($db);

$overenie_hesla = $uzivatel->over_heslo_podla_id($_POST['uzivatel_id'],$_POST['stare_heslo']);
if ($overenie_hesla == false && !empty($_POST['stare_heslo']))
{
    header ("Location:/?id=profil&cmd=ucet&chyba_stare_heslo=1");
    exit;
}
if (!empty($_POST['nove_heslo']) && empty($_POST['stare_heslo']))
{
    header ("Location:/?id=profil&cmd=ucet&chyba_stare_heslo=1");
    exit;
}
if ($_POST['nove_heslo'] != $_POST['nove_heslo_znova'])
{
    header ("Location:/?id=profil&cmd=ucet&chyba_nove_heslo=1");
    exit;
}

$vysledok = $uzivatel->zmen_udaje($_POST['uzivatel_id'], $_POST['meno'], $_POST['priezvisko'], $_POST['nick'], $_POST['e_mail'], $_POST['tel_cislo'], $_POST['nove_heslo']);

if ($vysledok == false)
{
    header ("Location:/?id=profil&cmd=ucet&chyba_nick=1");
    exit;
}
else
{
    header ("Location:/?id=profil&cmd=ucet");
    exit;
}

}
$tpl->show();