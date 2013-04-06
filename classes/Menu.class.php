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
		$id = $this->spojenie->insert_id();
		$menu_skupiny['skupiny_id'] = 0;		
		$menu_skupiny['menu_id'] = $id;
		$this->spojenie->makeInsert("menu_skupiny",$menu_skupiny);		
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
		$result = $this->spojenie->getZaznam("select menu_id as id, nazov, modul, subor from menu where menu_id = '{$id}'","menu_id");
		return $result;
	}
	

}
?>
