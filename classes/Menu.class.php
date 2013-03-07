<?php
class Menu
{
	var $spojenie;
	private $nazov;
	private $modul;
	private $subor;
	
	public function __construct($db) {
        $this->spojenie = $db;
    }

	
	public function nova_polozka($nazov, $modul, $subor)
	{
		$menu['nazov'] = $nazov;
		$menu['modul'] = $modul;
		$menu['subor'] = $subor;		
		$this->spojenie->makeInsert("menu",$menu);			
	}
	
	public function uprav_polozku($id, $nazov, $modul, $subor)
	{
		$menu['nazov'] = $nazov;
		$menu['modul'] = $modul;
		$menu['subor'] = $subor;
		$this->spojenie->makeUpdate("menu",$menu,"id = $id");	
	}
	
	public function zmaz_polozku($id)
	{
		$this->spojenie->query("delete from menu where id = $id");
	}
	
	public function vrat_polozku($id)
	{
		$result = $this->spojenie->getZaznam("select * from menu where id = $id");
		return $result;
	}
	

}
?>
