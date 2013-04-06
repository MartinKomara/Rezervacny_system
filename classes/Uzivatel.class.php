<?php
class Uzivatel
{
	var $spojenie;	
	
	public function __construct($db) {
        $this->spojenie = $db;
    }

	
	public function novy_uzivatel($meno, $priezvisko, $nick, $e_mail, $tel_cislo, $heslo)
	{
		$this->crypt  = new Crypto();		
		$pocet = $this->spojenie->num_rows("SELECT * FROM uzivatel WHERE nick = '{$nick}'");
		if ($pocet > 0)
			return false;
		$tmp['meno'] = $meno;
		$tmp['priezvisko'] = $priezvisko;
		$tmp['nick'] = $nick;
		$tmp['e_mail'] = $e_mail;
		$tmp['tel_cislo'] = $tel_cislo;
		$tmp['skupiny_id'] = 2;	
		$tmp['enc_password'] = $this->crypt->encrypt($heslo);
		$this->spojenie->makeInsert("uzivatel",$tmp);		
		return true;	
	}
        
        public function zmen_udaje($uzivatel_id, $meno, $priezvisko, $nick, $e_mail, $tel_cislo, $heslo)
        {
                $this->crypt  = new Crypto();
                $pocet = $this->spojenie->num_rows("SELECT * FROM uzivatel WHERE nick = '{$nick}' AND uzivatel_id != '{$uzivatel_id}'");
		if ($pocet > 0)
			return false;
		$tmp['meno'] = $meno;
		$tmp['priezvisko'] = $priezvisko;
		$tmp['nick'] = $nick;
		$tmp['e_mail'] = $e_mail;
		$tmp['tel_cislo'] = $tel_cislo;
                if (!empty($heslo))
                    $tmp['enc_password'] = $this->crypt->encrypt($heslo);
		$this->spojenie->makeUpdate("uzivatel",$tmp,"uzivatel_id = $uzivatel_id");
                return true;
        }
	
	public function over_nick($nick)
	{
		$pocet = $this->spojenie->num_rows("select * from uzivatel where nick = '{$nick}'");
		if ($pocet == 0)
			return false;
		else 
			return true;
	}
	
	public function over_heslo($nick, $heslo)
	{
		$this->crypt  = new Crypto();	
		$enc_password = $this->crypt->encrypt($heslo);
		$dat_heslo = $this->spojenie->getZaznam("select uzivatel_id, enc_password from uzivatel where nick = '{$nick}'",'uzivatel_id');
		if ($enc_password != $dat_heslo['enc_password'])
			return false;
		else
			return true;
	
	}
        
        public function over_heslo_podla_id($id, $heslo)
	{
		$this->crypt  = new Crypto();	
		$enc_password = $this->crypt->encrypt($heslo);
		$dat_heslo = $this->spojenie->getZaznam("select uzivatel_id, enc_password from uzivatel where uzivatel_id = '{$id}'",'uzivatel_id');
		if ($enc_password != $dat_heslo['enc_password'])
			return false;
		else
			return true;
	
	}
	
	public function zmaz_uzivatela($id)
	{
		$this->spojenie->zmazZaznam("uzivatel","uzivatel_id = $id");		
	}
	
	public function prihlas($meno)
	{
		$result = $this->spojenie->getZaznam("select uzivatel_id, skupiny_id from uzivatel where nick = '{$meno}'",'uzivatel_id');
        $_SESSION['group_id'] = $result['skupiny_id'];
        $_SESSION['uzivatel_id'] = $result['uzivatel_id'];
	}
	
	public function odhlas()
	{
		unset($_SESSION['uzivatel_id']);
		unset($_SESSION['group_id']);
	}
	
	public function uzivatel($id)
	{
        $this->crypt  = new Crypto();	
		$result = $this->spojenie->getZaznam("select uzivatel.uzivatel_id as id, nick, meno, priezvisko, tel_cislo, e_mail from uzivatel where uzivatel_id = '{$id}'","id");
        return $result;	
	}
        
    public function vrat_uzivatelov()
    {
        $uzivatelia = $this->spojenie->getZaznamy("SELECT uzivatel.uzivatel_id as id, uzivatel.meno as meno, uzivatel.nick as nick,
                                                       uzivatel.priezvisko as priezvisko, uzivatel.tel_cislo as tel_cislo,
                                                       uzivatel.e_mail as e_mail FROM uzivatel","id");
        return $uzivatelia;
    }
        
        

}
?>
