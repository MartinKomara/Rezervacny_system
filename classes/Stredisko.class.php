<?php
class Stredisko
{
	var $spojenie;	
        
	public function __construct($db) {
        $this->spojenie = $db;
    }
    
    public function nove_stredisko($nazov_strediska,$nazov_sportoviska, $pocet_sportovisk, $adresa, $tel_cislo,$e_mail, $webova_stranka, $interval, $otvaracie_hodiny, $cena, $detail)
    {       
       
        // insert do tabulky stredisko
        
        $stredisko['nazov_strediska'] = $nazov_strediska;
        $stredisko['nazov_sportoviska'] = $nazov_sportoviska;
        $stredisko['pocet_sportovisk'] = $pocet_sportovisk;
        $stredisko['otvorene'] = 1;        
        $this->spojenie->makeInsert("stredisko",$stredisko);        
        
        $stredisko_id = $this->spojenie->insert_id();        
        
        // insert do tabulky kontakty
        
        $kontakty['stredisko_id'] = $stredisko_id;
        $kontakty['adresa'] = $adresa;
        $kontakty['tel_cislo'] = $tel_cislo;
        $kontakty['e_mail'] = $e_mail;
        $kontakty['webova_stranka'] = $webova_stranka;
        $this->spojenie->makeInsert("kontakty",$kontakty);
        
        // insert do tabulky otvaracie_hodiny
        
        foreach($otvaracie_hodiny as $den=>$hodiny)
         {
            if ($hodiny[0] != null && $hodiny[1]!= null)
            {
                $tmp['stredisko_id'] = $stredisko_id;
                $tmp['den_v_tyzdni'] = $den;
                $tmp['zaciatok'] = date('H:i',strtotime($hodiny[0]));
                $tmp['koniec'] = date('H:i',strtotime($hodiny[1]));
                $this->spojenie->makeInsert("otvaracie_hodiny",$tmp);
            }
         }       
        
         
         // insert do tabulky casove_intervaly
         
         if ($detail == 0)  // ak je danny jednotny interval
         {
             for ($i = 0; $i < 7; $i++)
             {
                 $casove_intervaly['stredisko_id'] = $stredisko_id;                 
                 $casove_intervaly['dlzka_intervalu'] = $interval;
                 $casove_intervaly['den'] = $i;
                 $casove_intervaly['cena'] = $cena;
                 $this->spojenie->makeInsert("casove_intervaly",$casove_intervaly);
                 
             }
         }
         else   // ak su zadane intervaly pre rozne dni
         {
             
             for($i = 0; $i < count($interval); $i++)
             {
                 $casove_intervaly['stredisko_id'] = $stredisko_id;                 
                 $casove_intervaly['dlzka_intervalu'] = $interval[$i];
                 $casove_intervaly['den'] = $i;
                 $casove_intervaly['cena'] = $cena[$i];
                 $this->spojenie->makeInsert("casove_intervaly",$casove_intervaly);
             }             
         }
         
         return $stredisko_id;
         
    }
    
    public function vrat_strediska()
    {
        $strediska = $this->spojenie->getZaznamy("SELECT stredisko.stredisko_id as id, stredisko.nazov_strediska as nazov_strediska, stredisko.nazov_sportoviska as nazov_sportoviska,
                                                  stredisko.pocet_sportovisk as pocet_sportovisk, stredisko.otvorene as otvorene from stredisko","id");
        return $strediska;
                
    }
    
    public function vrat_stredisko($stredisko_id)
    {
        $stredisko = $this->spojenie->getZaznam("SELECT stredisko.stredisko_id as id, stredisko.nazov_strediska as nazov_strediska, stredisko.nazov_sportoviska as nazov_sportoviska,
                                                  stredisko.pocet_sportovisk as pocet_sportovisk, stredisko.otvorene as otvorene from stredisko where stredisko_id = '{$stredisko_id}'","id");
        return $stredisko;
                
    }
    
    public function vymaz_stredisko($stredisko_id)
    {         
        $this->spojenie->zmazZaznam("kontakty","stredisko_id = {$stredisko_id}"); 
        $this->spojenie->zmazZaznam("casove_intervaly","stredisko_id = {$stredisko_id}"); 
        $this->spojenie->zmazZaznam("otvaracie_hodiny","stredisko_id = {$stredisko_id}"); 
        $this->spojenie->zmazZaznam("fotky","stredisko_id = {$stredisko_id}"); 
        $this->spojenie->zmazZaznam("stredisko","stredisko_id = {$stredisko_id}");  
    }
    
    public function vrat_casovy_interval($stredisko_id, $datum_den)
    {
        $casovy_interval = $this->spojenie->getZaznam("SELECT casove_intervaly_id as id, stredisko_id, cena, dlzka_intervalu, den 
                                                        FROM casove_intervaly WHERE stredisko_id = '{$stredisko_id}' AND den = '{$datum_den}'","id");
        return $casovy_interval;
    }
    
    function over_pocet_intervalov($stredisko_id)
    {
        $casovy_interval = $this->vrat_casovy_interval($stredisko_id, 0);
        $prva_hodnota = $casovy_interval['dlzka_intervalu'];
        for ($i = 1; $i < 7; $i++)
        {          
            $casovy_interval = $this->vrat_casovy_interval($stredisko_id, $i);
            if ($prva_hodnota != $casovy_interval['dlzka_intervalu'])
                return false;
        }	
        return array($casovy_interval['dlzka_intervalu'],$casovy_interval['cena']);
    }
    
    public function vrat_otvaracie_hodiny($stredisko_id, $datum_den)
    {
        $otvaracie_hodiny = $this->spojenie->getZaznam("SELECT otvaracie_hodiny_id as id, stredisko_id, den_v_tyzdni,
                                                        zaciatok, koniec FROM otvaracie_hodiny WHERE stredisko_id = '{$stredisko_id}' AND den_v_tyzdni = '{$datum_den}'","id");
        return $otvaracie_hodiny;
    }
    
    public function vrat_otvorenie($stredisko_id, $den)
    {
        $otvorenie = $this->spojenie->getZaznam("SELECT otvaracie_hodiny_id as id, stredisko_id, zaciatok 
                                                FROM otvaracie_hodiny WHERE stredisko_id = '{$stredisko_id}' AND den_v_tyzdni = '{$den}'");
        $cas = date('H:i:s', strtotime($otvorenie['zaciatok']));
        $hodiny = substr($cas,0,2);
        $minuty = substr($cas,3,2);
        $zaciatok = $hodiny + ($minuty / 60);
        return $zaciatok;
    }
    
    public function vrat_zatvorenie($stredisko_id, $den)
    {
        $otvorenie = $this->spojenie->getZaznam("SELECT otvaracie_hodiny_id as id, stredisko_id, koniec 
                                                 FROM otvaracie_hodiny WHERE stredisko_id = '{$stredisko_id}' and den_v_tyzdni = '{$den}'");
        $cas = date('H:i:s', strtotime($otvorenie['koniec']));
        $hodiny = substr($cas,0,2);
        $minuty = substr($cas,3,2);
        $koniec = $hodiny + ($minuty / 60);
        return $koniec;
    }
    
    public function vrat_kontakt($stredisko_id)
    {
        $kontakt = $this->spojenie->getZaznam("SELECT stredisko_id as id, adresa, tel_cislo, e_mail, webova_stranka 
                                                FROM kontakty WHERE stredisko_id = '{$stredisko_id}'","id");
        return $kontakt;
    }
    
    
    
    
    
   
    


}
?>
