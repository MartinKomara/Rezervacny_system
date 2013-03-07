<?php
$uzivatel = new Uzivatel($db);

$meno = $_POST['prihlasovacie_meno'];
$heslo = $_POST['prihlasovacie_heslo'];

$pocet = $db->num_rows("select * from uzivatelia where nick = '{$meno}'");
if (!$uzivatel->over_nick($meno))
{
    header ("Location:/?id=home&cmd=home&chyba=1");
    exit;
}
else
{
	if (!$uzivatel->over_heslo($meno, $heslo))
    {
        header ("Location:/?id=home&cmd=home&chyba=2");
        exit;
    }
    else
    {
		$uzivatel->prihlas($meno);
		if ($_SESSION['group_id'] == 1)
			Header("Location: index.php?id=admin&cmd=admin");
		else
        	Header("Location: index.php?id=home&cmd=home");
			
    }
}
?>