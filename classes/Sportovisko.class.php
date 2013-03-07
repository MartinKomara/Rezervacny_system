<?php
class Uzivatel
{
	var $spojenie;
	private $nazov_strediska;
	private $nazov_sportoviska;
	private $pocet_sportovisk;
	private $adresa;
	private $tel_cislo;
	private $casovy_interval;
        private $otvaracie_hodiny;
	private $cena;
	
        
	public function __construct($db) {
        $this->spojenie = $db;
    }


}
?>
