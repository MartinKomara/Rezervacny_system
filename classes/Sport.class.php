<?php
class Sport
{
	var $spojenie;	
        
	public function __construct($db) {
        $this->spojenie = $db;
    }
    
    public function novy_sport($nazov_sportu, $nazov_sportoviska, $pocet_sportovisk, $interval, $otvaracie_hodiny, $cena, $detail)
    {     
       
        // insert do tabulky sport
        
        $sport['nazov_sportu'] = $nazov_sportu;
        $sport['nazov_sportoviska'] = $nazov_sportoviska;
        $sport['pocet_sportovisk'] = $pocet_sportovisk;
        $this->spojenie->makeInsert("sport",$sport);        
        $sport_id = $this->spojenie->insert_id();        
		$this->spojenie->query("UPDATE sport set tmp_id = $sport_id WHERE sport_id = $sport_id");
        
        $this->spojenie->query("UPDATE sport set platny_od = NOW() WHERE sport_id = $sport_id");
            
        // insert do tabulky otvaracie_hodiny
        
        foreach($otvaracie_hodiny as $den=>$hodiny)
         {
            if ($hodiny[0] != null && $hodiny[1]!= null)
            {
                $tmp['sport_id'] = $sport_id;
                $tmp['den_v_tyzdni'] = $den;
				if (!empty($hodiny[0]))
					$tmp['zaciatok'] = date('H:i',strtotime($hodiny[0]));
                if (!empty($hodiny[1]))
					$tmp['koniec'] = date('H:i',strtotime($hodiny[1]));
                $this->spojenie->makeInsert("otvaracie_hodiny",$tmp);
				$this->spojenie->query("UPDATE otvaracie_hodiny set platny_od = NOW() WHERE sport_id = $sport_id");
            }
         }       
        
         
         // insert do tabulky casove_intervaly
         
         if ($detail == 0)  // ak je danny jednotny interval
         {
             for ($i = 0; $i < 7; $i++)
             {
                 $casove_intervaly['sport_id'] = $sport_id;                 
                 $casove_intervaly['dlzka_intervalu'] = $interval;
                 $casove_intervaly['den'] = $i;
                 $casove_intervaly['cena'] = $cena;
                 $this->spojenie->makeInsert("casove_intervaly",$casove_intervaly);
				 $this->spojenie->query("UPDATE casove_intervaly set platny_od = NOW() WHERE sport_id = $sport_id");
                 
             }
         }
         else   // ak su zadane intervaly pre rozne dni
         {
             
             for($i = 0; $i < count($interval); $i++)
             {
                 $casove_intervaly['sport_id'] = $sport_id;                 
                 $casove_intervaly['dlzka_intervalu'] = $interval[$i];
                 $casove_intervaly['den'] = $i;
                 $casove_intervaly['cena'] = $cena[$i];
                 $this->spojenie->makeInsert("casove_intervaly",$casove_intervaly);
				 $this->spojenie->query("UPDATE casove_intervaly set platny_od = NOW() WHERE sport_id = $sport_id");
             }             
         }
         
         return $sport_id;
         
    }
	
	public function zmen_sport($sport_id, $nazov_sportu, $nazov_sportoviska, $pocet_sportovisk, $interval, $otvaracie_hodiny, $cena, $detail)
    {       
       
        // insert do tabulky sport
        
        $sport['nazov_sportu'] = $nazov_sportu;
        $sport['nazov_sportoviska'] = $nazov_sportoviska;
        $sport['pocet_sportovisk'] = $pocet_sportovisk;
        
		$pocet = $this->spojenie->num_rows("SELECT * FROM sport WHERE nazov_sportu = '{$nazov_sportu}' 
											AND nazov_sportoviska = '{$nazov_sportoviska}' AND pocet_sportovisk = '{$pocet_sportovisk}' AND tmp_id = '{$sport_id}'");
				
        if ($pocet == 0)
					{ 
						$this->spojenie->makeInsert("sport",$sport);
						$sport_nove_id = $this->spojenie->insert_id();
						$stare_id = $this->spojenie->getZaznam("SELECT * from sport where sport_id = $sport_id");
						$idcko = $stare_id['tmp_id'];
						$this->spojenie->query("UPDATE sport set tmp_id = $idcko, platny_od = NOW() + INTERVAL 15 DAY where sport_id = $sport_nove_id");
					}
           
        // insert do tabulky otvaracie_hodiny
        
        foreach($otvaracie_hodiny as $den=>$hodiny)
         {
            if ($hodiny[0] != null && $hodiny[1]!= null)
            {
                $tmp['sport_id'] = $sport_id;
                $tmp['den_v_tyzdni'] = $den;
                $tmp['zaciatok'] = date('H:i',strtotime($hodiny[0]));
                $tmp['koniec'] = date('H:i',strtotime($hodiny[1]));				
				
				$zaciatok =  $tmp['zaciatok'];
				$koniec = $tmp['koniec'];
				
				$pocet = $this->spojenie->num_rows("SELECT * FROM otvaracie_hodiny WHERE zaciatok = '{$zaciatok}' 
											AND koniec = '{$koniec}' AND den_v_tyzdni = '{$den}' AND sport_id = '{$sport_id}'");
				
				if ($pocet == 0)
					{
						$this->spojenie->makeInsert("otvaracie_hodiny",$tmp);
						$hodiny_id = $this->spojenie->insert_id();
						$this->spojenie->query("UPDATE otvaracie_hodiny set platny_od = NOW() + INTERVAL 15 DAY WHERE otvaracie_hodiny_id = $hodiny_id");
					}
            }
         }       
        
         
         // insert do tabulky casove_intervaly
         
         if ($detail == 0)  // ak je danny jednotny interval
         {
             for ($i = 0; $i < 7; $i++)
             {
                 $casove_intervaly['sport_id'] = $sport_id;                 
                 $casove_intervaly['dlzka_intervalu'] = $interval;
                 $casove_intervaly['den'] = $i;
                 $casove_intervaly['cena'] = $cena;
				 
				 
				 $pocet = $this->spojenie->num_rows("SELECT * FROM casove_intervaly WHERE dlzka_intervalu = '{$interval}' 
											AND den = '{$i}' AND cena = '{$cena}' AND sport_id = '{$sport_id}'");
				
				 if ($pocet == 0)
					{
						$this->spojenie->makeInsert("casove_intervaly",$casove_intervaly);
						$interval_id = $this->spojenie->insert_id();
						$this->spojenie->query("UPDATE casove_intervaly set platny_od = NOW() + INTERVAL 15 DAY WHERE casove_intervaly_id = $interval_id");
					}
                 
             }
         }
         else   // ak su zadane intervaly pre rozne dni
         {
             $ceny = array_values($cena);
             for($i = 0; $i < count($interval); $i++)
             {
                 $casove_intervaly['sport_id'] = $sport_id;                 
                 $casove_intervaly['dlzka_intervalu'] = $interval[$i];
				 $dlzka_intervalu = $casove_intervaly['dlzka_intervalu'];
                 $casove_intervaly['den'] = $i;
                 $casove_intervaly['cena'] = $ceny[$i];
				 $cena = $casove_intervaly['cena'];
                 
				 
				 $pocet = $this->spojenie->num_rows("SELECT * FROM casove_intervaly WHERE dlzka_intervalu = '{$dlzka_intervalu}' 
											AND den = '{$i}' AND cena = '{$cena}' AND sport_id = '{$sport_id}'");
					
				
				 if ($pocet == 0)
					{
						$this->spojenie->makeInsert("casove_intervaly",$casove_intervaly);
						$interval_id = $this->spojenie->insert_id();
						$this->spojenie->query("UPDATE casove_intervaly set platny_od = NOW() + INTERVAL 15 DAY WHERE casove_intervaly_id = $interval_id");
					}
				 
				 
             }             
         }
         
         return $sport_id;
         
    }
	
    
    public function vrat_sporty()
    {
        $sporty = $this->spojenie->getZaznamy("SELECT sport.tmp_id as id, sport.sport_id as sport_id, sport.nazov_sportu as nazov_sportu, sport.nazov_sportoviska as nazov_sportoviska,
                                                  sport.pocet_sportovisk as pocet_sportovisk, sport.platny_od as platny_od from sport WHERE datediff(NOW(),platny_od) >= 0 order by platny_od ASC","id");
        return $sporty;
                
    }
    
    public function vrat_sport($sport_id)
    {
        $sport = $this->spojenie->getZaznam("SELECT sport.tmp_id as id, sport.sport_id as sport_id, sport.nazov_sportu as nazov_sportu, sport.nazov_sportoviska as nazov_sportoviska,
                                                  sport.pocet_sportovisk as pocet_sportovisk, sport.platny_od as platny_od from sport WHERE datediff(NOW(),platny_od) >= 0 AND tmp_id = '{$sport_id}' order by platny_od DESC limit 1","id");
        return $sport;
                
    }
    
    public function vymaz_sport($sport_id)
    {         
		$sport = $this->spojenie->getZaznam("SELECT * FROM sport WHERE sport_id = $sport_id");
		$tmp_id = $sport['tmp_id'];
		
        $this->spojenie->zmazZaznam("sport","tmp_id = {$tmp_id}");  
		$this->spojenie->zmazZaznam("fotky","sport_id = {$tmp_id}"); 
		$this->spojenie->zmazZaznam("rezervacia","sport_id = {$tmp_id}");  
        $this->spojenie->zmazZaznam("casove_intervaly","sport_id = {$tmp_id}"); 
        $this->spojenie->zmazZaznam("otvaracie_hodiny","sport_id = {$tmp_id}"); 
		
    }
    
    public function vrat_casovy_interval($sport_id, $datum_den, $datum)
    {
		$sport = $this->spojenie->getZaznam("select sport_id as id, tmp_id from sport where sport_id = $sport_id");
		$id = $sport['tmp_id'];
	
		if ($datum == 0)
		{
			 $casovy_interval = $this->spojenie->getZaznam("SELECT casove_intervaly_id as id, sport_id, cena, dlzka_intervalu, den, platny_od, datediff(NOW(),platny_od) as rozdiel 
															FROM casove_intervaly WHERE sport_id = '{$id}' AND den = '{$datum_den}' AND datediff(NOW(),platny_od) >= 0 ORDER BY rozdiel ASC LIMIT 1");
		}
		else
		{
			$casovy_interval = $this->spojenie->getZaznam("SELECT casove_intervaly_id as id, sport_id, cena, dlzka_intervalu, den, platny_od, datediff('{$datum}',platny_od) as rozdiel 
                                                        FROM casove_intervaly WHERE sport_id = '{$id}' AND den = '{$datum_den}' AND datediff('{$datum}',platny_od) >= 0 ORDER BY rozdiel ASC LIMIT 1");
      
		}
		
		return $casovy_interval;
    }
    
    function over_pocet_intervalov($sport_id, $datum)
    {
		$sport = $this->spojenie->getZaznam("select sport_id as id, tmp_id from sport where sport_id = $sport_id");
		$id = $sport['tmp_id'];
	
        $casovy_interval = $this->vrat_casovy_interval($id, 0, $datum);
        $prva_hodnota = $casovy_interval['dlzka_intervalu'];
        for ($i = 1; $i < 7; $i++)
        {          
            $casovy_interval = $this->vrat_casovy_interval($id, $i, $datum);
            if ($prva_hodnota != $casovy_interval['dlzka_intervalu'])
                return false;
        }	
        return array($casovy_interval['dlzka_intervalu'],$casovy_interval['cena']);
    }
    
    public function vrat_otvaracie_hodiny($sport_id, $datum_den, $datum)
    {
		$sport = $this->spojenie->getZaznam("select sport_id as id, tmp_id from sport where sport_id = $sport_id");
		$id = $sport['tmp_id'];
	
		if ($datum == 0)
		{
			$otvaracie_hodiny = $this->spojenie->getZaznam("SELECT otvaracie_hodiny_id as id, sport_id, den_v_tyzdni, platny_od, datediff(NOW(),platny_od) as rozdiel,
                                                        zaciatok, koniec FROM otvaracie_hodiny WHERE sport_id = '{$id}' AND den_v_tyzdni = '{$datum_den}' AND datediff(NOW(),platny_od) >= 0 ORDER BY rozdiel ASC LIMIT 1");     
		}
		else
		{
			$otvaracie_hodiny = $this->spojenie->getZaznam("SELECT otvaracie_hodiny_id as id, sport_id, den_v_tyzdni, platny_od, datediff('{$datum}',platny_od) as rozdiel,
                                                        zaciatok, koniec FROM otvaracie_hodiny WHERE sport_id = '{$id}' AND den_v_tyzdni = '{$datum_den}' AND datediff('{$datum}',platny_od) >= 0 ORDER BY rozdiel ASC LIMIT 1");
        }
		return $otvaracie_hodiny;
		
    }
    
    public function vrat_otvorenie($sport_id, $den, $datum)
    {
	
		$sport = $this->spojenie->getZaznam("select sport_id as id, tmp_id from sport where sport_id = $sport_id");
		$id = $sport['tmp_id'];
	
		if ($datum == 0)
		{
			$otvorenie = $this->spojenie->getZaznam("SELECT otvaracie_hodiny_id as id, sport_id, zaciatok, platny_od, datediff(NOW(), platny_od) as rozdiel 
                                                FROM otvaracie_hodiny WHERE sport_id = '{$id}' AND den_v_tyzdni = '{$den}' AND datediff(NOW(),platny_od) >= 0 ORDER BY rozdiel ASC LIMIT 1");
        
		}
		else
		{
			$otvorenie = $this->spojenie->getZaznam("SELECT otvaracie_hodiny_id as id, sport_id, zaciatok, platny_od, datediff('{$datum}', platny_od) as rozdiel 
                                                FROM otvaracie_hodiny WHERE sport_id = '{$id}' AND den_v_tyzdni = '{$den}' AND datediff('{$datum}',platny_od) >= 0 ORDER BY rozdiel ASC LIMIT 1");
        
		}
		$cas = date('H:i:s', strtotime($otvorenie['zaciatok']));
        $hodiny = substr($cas,0,2);
        $minuty = substr($cas,3,2);
        $zaciatok = $hodiny + ($minuty / 60);
        return $zaciatok;
    }
    
    public function vrat_zatvorenie($sport_id, $den, $datum)
    {
		$sport = $this->spojenie->getZaznam("select sport_id as id, tmp_id from sport where sport_id = $sport_id");
		$id = $sport['tmp_id'];
	
		if ($datum == 0)
		{
			$otvorenie = $this->spojenie->getZaznam("SELECT otvaracie_hodiny_id as id, sport_id, koniec, platny_od, datediff(NOW(), platny_od) as rozdiel
                                                 FROM otvaracie_hodiny WHERE sport_id = '{$id}' and den_v_tyzdni = '{$den}' AND datediff(NOW(),platny_od) >= 0 ORDER BY rozdiel ASC LIMIT 1");
		}
		else
		{	
			$otvorenie = $this->spojenie->getZaznam("SELECT otvaracie_hodiny_id as id, sport_id, koniec, platny_od, datediff('{$datum}', platny_od) as rozdiel 
                                                 FROM otvaracie_hodiny WHERE sport_id = '{$id}' and den_v_tyzdni = '{$den}' AND datediff('{$datum}',platny_od) >= 0 ORDER BY rozdiel ASC LIMIT 1");
        }
		
		$cas = date('H:i:s', strtotime($otvorenie['koniec']));
        $hodiny = substr($cas,0,2);
        $minuty = substr($cas,3,2);
        $koniec = $hodiny + ($minuty / 60);
        return $koniec;
    }
	
	public function over_zablokovanie($sport_id, $datum)
	{
		$sport = $this->spojenie->getZaznam("select sport_id as id, tmp_id from sport where sport_id = $sport_id");
		$id = $sport['tmp_id'];
	
		$result = $this->spojenie->num_rows("SELECT * from zatvorene_casy WHERE sport_id = '{$id}' AND ('{$datum}' BETWEEN zaciatok AND koniec)");
		return $result;	
	}	
	
    
    public function vrat_kontakt()
    {
        $kontakt = $this->spojenie->getZaznam("SELECT id, adresa, tel_cislo, e_mail, webova_stranka 
                                                FROM kontakt","id");
        return $kontakt;
    }
    
	public function nastav_kontakt($adresa, $tel_cislo, $e_mail, $webova_stranka)
	{
		$this->spojenie->query("delete from kontakty");
		$kontakt['adresa'] = $adresa;
		$kontakt['tel_cislo'] = $tel_cislo;
		$kontakt['e_mail'] = $e_mail;
		$kontakt['webova_stranka'] = $webova_stranka;	
		$this->spojenie->makeInsert("kontakt",$kontakt);
		
	}
	
	public function nove_zatvorenie($sport_id, $zaciatok, $koniec)
	{
		$zatvorenie['sport_id'] = $sport_id;
		$zatvorenie['zaciatok'] = $zaciatok;
		$zatvorenie['koniec'] = $koniec;	
		$this->spojenie->makeInsert("zatvorene_casy",$zatvorenie);
	
	}
	
	public function vrat_zatvorene_casy($sport_id)
	{
		$sport = $this->spojenie->getZaznam("select sport_id as id, tmp_id from sport where sport_id = $sport_id");
		$id = $sport['tmp_id'];
	
		$result = $this->spojenie->getZaznamy("SELECT * FROM zatvorene_casy where sport_id = '{$id}' AND koniec > now()","id");
		return $result;
	}
	
	public function vymaz_zatvorenie($id)
	{
		$this->spojenie->query("DELETE FROM zatvorene_casy WHERE id = $id");
	}
	
	public function vrat_fotky($sport_id)
	{
		$fotky = $this->spojenie->getZaznamy("SELECT * FROM fotky where sport_id = $sport_id","fotky_id");
		return $fotky;	
	}
	
	public function vymaz_fotku($fotka_id)
	{
		$result = $this->spojenie->getZaznam("SELECT * FROM fotky WHERE fotky_id = $fotka_id");
		$nazov = "images/sportovisko/".$result['subor'];
	
		if ( file_exists ( $nazov )) {
			unlink($nazov);
		}  
		
		$this->spojenie->query("DELETE FROM fotky WHERE fotky_id = $fotka_id");	
		
	}
    
    
    
    
   
    


}
?>
