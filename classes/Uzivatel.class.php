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
		$pocet = $this->spojenie->num_rows("select * from uzivatelia where nick = '{$nick}'");
		if ($pocet > 0)
			return false;
		$tmp['meno'] = $meno;
		$tmp['priezvisko'] = $priezvisko;
		$tmp['nick'] = $nick;
		$tmp['e_mail'] = $e_mail;
		$tmp['tel_cislo'] = $tel_cislo;
		$tmp['group_id'] = 2;	
		$tmp['enc_password'] = $this->crypt->encrypt($heslo);
		$this->spojenie->makeInsert("uzivatelia",$tmp);
		
		return true;	
	}
	
	public function over_nick($nick)
	{
		$pocet = $this->spojenie->num_rows("select * from uzivatelia where nick = '{$nick}'");
		if ($pocet == 0)
			return false;
		else 
			return true;
	}
	
	public function over_heslo($meno, $heslo)
	{
		$this->crypt  = new Crypto();	
		$this->enc_password = $this->crypt->encrypt($heslo);
		$dat_heslo = $this->spojenie->getZaznam("select id, enc_password from uzivatelia where nick = '{$meno}'",'id');
		if ($this->enc_password != $dat_heslo['enc_password'])
			return false;
		else
			return true;
	
	}
	
	public function zmaz_uzivatela($id)
	{
		$this->spojenie->zmazZaznam("uzivatelia","id = $id");		
	}
	
	public function prihlas($meno)
	{
		$result = $this->spojenie->getZaznam("select id, group_id from uzivatelia where nick = '{$meno}'",'id');
        $_SESSION['group_id'] = $result['group_id'];
        $_SESSION['uzivatel_id'] = $result['id'];
	}
	
	public function odhlas()
	{
		unset($_SESSION['uzivatel_id']);
		unset($_SESSION['group_id']);
	}
	
	public function uzivatel($id)
	{
		$sql = "select * from uzivatelia where id = '{$id}'";
		$result = $this->spojenie->getZaznam($sql,"id");
		return $result;	
	}
        
        public function vrat_uzivatelov()
        {
            $uzivatelia = $this->spojenie->getZaznamy("SELECT uzivatelia.id as id, uzivatelia.meno as meno, uzivatelia.nick as nick,
                                                       uzivatelia.priezvisko as priezvisko, uzivatelia.tel_cislo as tel_cislo,
                                                       uzivatelia.e_mail as e_mail FROM uzivatelia","id");
            return $uzivatelia;
        }
        
        

}
?>
