<?php
class Rezervacia
{
	var $spojenie;	
        
	public function __construct($db) {
        $this->spojenie = $db;
    }
	
	public function nova_rezervacia($sport_id, $uzivatel_id, $sportovisko, $datum, $zaciatok, $dlzka, $cena)
	{
		$rezervacia['sport_id'] = $sport_id;
		$rezervacia['uzivatel_id'] = $uzivatel_id;
		$rezervacia['sportovisko'] = $sportovisko;
		$rezervacia['datum'] = $datum;
		$rezervacia['zaciatok'] = $zaciatok;
		$rezervacia['dlzka'] = $dlzka;
		$rezervacia['cena'] = $cena;			
		$this->spojenie->makeInsert("rezervacia",$rezervacia);
	}
	
	public function nova_docasna_rezervacia($sport_id, $uzivatel_id, $sportovisko, $datum, $zaciatok, $dlzka, $cena)
	{
		$rezervacia['sport_id'] = $sport_id;
		$rezervacia['uzivatel_id'] = $uzivatel_id;
		$rezervacia['sportovisko'] = $sportovisko;
		$rezervacia['datum'] = $datum;
		$rezervacia['zaciatok'] = $zaciatok;
		$rezervacia['dlzka'] = $dlzka;
		$rezervacia['cena'] = $cena;			
		$this->spojenie->makeInsert("docasna_rezervacia",$rezervacia);
	}
	
		
	public function vrat_rezervacie_na_datum($datum, $sport_id, $sportovisko_id)
	{
		$rezervacie = $this->spojenie->getZaznamy("SELECT * FROM rezervacia WHERE datum = '{$datum}' AND sport_id = '{$sport_id}' AND sportovisko = '{$sportovisko_id}'","rezervacia_id");
		return $rezervacie;	
	}
	
	public function vrat_docasne_rezervacie_na_datum($datum, $sport_id, $sportovisko_id)
	{
		$rezervacie = $this->spojenie->getZaznamy("SELECT * FROM docasna_rezervacia WHERE datum = '{$datum}' AND sport_id = '{$sport_id}' AND sportovisko = '{$sportovisko_id}'","rezervacia_id");
		return $rezervacie;	
	}	
	
	public function vrat_rezervacie($uzivatel_id)
	{
		$rezervacie = $this->spojenie->getZaznamy("SELECT * FROM rezervacia WHERE uzivatel_id = '{$uzivatel_id}' order by datum_pridania desc","rezervacia_id");
		return $rezervacie;	
	}
	
	public function vrat_rezervaciu($rezervacia_id)
	{
		$rezervacia = $this->spojenie->getZaznam("SELECT rezervacia_id as id, sport_id, uzivatel_id, sportovisko, datum, zaciatok, dlzka, cena FROM rezervacia WHERE rezervacia_id = $rezervacia_id");
		return $rezervacia;	
	}
	
	public function vrat_admin_rezervacie()
	{
		$rezervacie = $this->spojenie->getZaznamy("SELECT * FROM rezervacia","rezervacia_id");
		return $rezervacie;	
	}
	
	public function zmaz_rezervaciu($rezervacia_id)
	{
		$this->spojenie->zmazZaznam("rezervacia","rezervacia_id = $rezervacia_id");	
	}
	
	
	//vymaze jendu konkretnu docasnu rezervaciu
	public function vymaz_docasnu_rezervaciu($sport_id, $uzivatel_id, $sportovisko_id, $datum, $zaciatok)
    {

		$rezervacia = $this->spojenie->getZaznam("SELECT rezervacia_id as id FROM docasna_rezervacia WHERE sport_id = '{$sport_id}' AND
									uzivatel_id = '{$uzivatel_id}' AND sportovisko = '{$sportovisko_id}' AND
									datum = '{$datum}' AND zaciatok = '{$zaciatok}'","rezervacia_id");
		$rezervacia_id = $rezervacia['id'];
		$this->spojenie->zmazZaznam("docasna_rezervacia","rezervacia_id = $rezervacia_id");
		
	}
	
	// vymaze vsetky docasne rezervacie uzivatela
	public function zmaz_docasne_rezervacie($uzivatel_id)
    {
		$this->spojenie->zmazZaznam("docasna_rezervacia","uzivatel_id = $uzivatel_id");		
	}
   

}
?>
