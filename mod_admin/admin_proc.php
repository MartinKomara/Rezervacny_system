 <?php 
if (isset($_POST['ulozit'])  && !empty($_POST['ulozit']))
	{
		if (isset($_POST['admin']) && !empty($_POST['admin']))
			$admin = $_POST['admin'];
		if (isset($_POST['uzivatel']) && !empty($_POST['uzivatel']))
			$uzivatel = $_POST['uzivatel'];
		if (isset($_POST['neprihlaseny']) && !empty($_POST['neprihlaseny']))
			$neprihlaseny = $_POST['neprihlaseny'];
		
		
		$db->query("delete from prava_skupiny");
		
		
		
		foreach($admin as $key => $value)
		{
			$prava1['skupiny_id'] = 1;
			$prava1['pristupove_prava_id'] = $key;
			$db->makeInsert('prava_skupiny',$prava1);
			
		
		}
		foreach($uzivatel as $key => $value)
		{
			$prava2['skupiny_id'] = 2;
			$prava2['pristupove_prava_id'] = $key;
			$db->makeInsert('prava_skupiny',$prava2);
		
		}
		
		foreach($neprihlaseny as $key => $value)
		{
			$prava3['skupiny_id'] = 3;
			$prava3['pristupove_prava_id'] = $key;
			$db->makeInsert('prava_skupiny',$prava3);
		
		}
		header ("Location: index.php?id=admin&cmd=admin");
		exit;
	}

?>