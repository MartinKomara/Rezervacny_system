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
		$this->spojenie->makeUpdate("menu",$menu,"menu_id = $id");	
	}
	
	public function zmaz_polozku($id)
	{
		$this->spojenie->zmazZaznam("menu","menu_id = {$id}");
	}
	
	public function vrat_polozku($id)
	{
		$result = $this->spojenie->getZaznam("select * from menu where menu_id = $id");
		return $result;
	}
	

}
?>
