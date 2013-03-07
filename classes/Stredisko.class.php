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
                 $casove_intervaly['den'] = $den;
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
        $strediska = $this->spojenie->getZaznamy("SELECT stredisko.id as id, stredisko.nazov_strediska as nazov_strediska,
                                                  stredisko.otvorene as otvorene from stredisko","id");
        return $strediska;
                
    }
    
    public function vymaz_stredisko($stredisko_id)
    {
        $this->spojenie->zmazZaznam("stredisko","id = {$stredisko_id}");        
    }
    
    
    
   
    


}
?>
