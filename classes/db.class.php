<?php

	$pocet_spojeni = 0;
    $pocet_query = 0;
	

class db {
    var $_dbresult;
    var $_spojenie;
    var $_recent_sql;
    var $_vypisuj_sql = 0;
    public $_dlzka_vykonania_queries = 0;
    public $_pocet_query = 0;
    public $queries = array();
    public $queries_vykonanie = array();
    public $queries_data = array();
    public $celkovo_prenesene = 0;

    public function __construct($db_con) {
        $this->_spojenie = $db_con;
    }

   // this will be called automatically at the end of scope
    public function __destruct() {
        mysql_close($this->_spojenie);
    }
   
   
   function select($stmt){
        $this->_dbresult = mysql_query($stmt, $this->_spojenie);
        return($this->_dbresult);
    }

    function fetch_object(){
        return mysql_fetch_object($this->_dbresult);
    }

    function fetch_array(){
        $x = mysql_fetch_array($this->_dbresult, MYSQL_ASSOC);
        $stmt = $this->_recent_sql;

        if (!isset($this->queries_data[$stmt]))
            $this->queries_data[$stmt] = strlen(serialize($x));
        else
            $this->queries_data[$stmt] += strlen(serialize($x));

        $this->celkovo_prenesene += strlen(serialize($x));
        return $x;
    }

    function num_rows($stmt = ''){
        if(isset($stmt) && !empty($stmt)){
            $this->query($stmt);
        }
        return  mysql_num_rows($this->_dbresult);
    }

    function num_rows2()
    {
        // TODO: dynamically add COUNT(*)
    }

    function query($stmt){

        global $pocet_query;
        $pocet_query++;

        if (!isset($this->queries[$stmt]))
            $this->queries[$stmt] = 1;
        else
            $this->queries[$stmt]++;

        $this->_recent_sql = $stmt;
        if ($this->_vypisuj_sql == 1)
            echo "SQL: ".(date("G.i:s"))." - {$stmt}<br />";
        $this->_dbresult = mysql_query($stmt, $this->_spojenie);

        $this->_pocet_query++;

        return $this->_dbresult;
    }

    function insert_id(){
        return mysql_insert_id($this->_spojenie);
    }
        
    function setKodovanie($codename)
    {
        return mysql_query("SET CHARACTER SET {$codename}");
    }
        
    public function getZaznamy($qry, $primary = 'id')
    {
        $ret = $this->query($qry);
        $data = array();
        while ($meta = $this->fetch_array())
        {
            foreach ($meta as $key=>$value)
                $data[$meta[$primary]][$key] = stripslashes($value);
        }
        return $data;
    }

    public function makeUpdate($table, $pole, $where='')
    {
        $qry = "update `{$table}` set ";
        foreach ($pole as $key=>$value)
        {
            $qry .= "{$key}='{$value}', ";
        }
        $qry = substr($qry, 0, -2);
        if (!empty($where))
            $qry .= " where {$where}";
        return $this->query($qry);
    }
    	
    public function zmazZaznam($table, $where = '')
    {
        $qry = "delete from `{$table}` where {$where};";
        return $this->query($qry);
    }
    
    public function makeInsert($table, $pole)
    {
        $qry = "insert into `{$table}` ";
        $keys = array();
        $values = array();
        foreach ($pole as $key=>$value)
        {
            $keys[] = $key;
            $values[] = "'".addslashes($value)."'";
        }
        $qry .= "(".implode(', ', $keys).") values (".implode(", ",$values).");";
        return $this->query($qry);
    }
        
    public function getZaznam($qry)
    {
        $d = $this->getZaznamy($qry);
        return @$d[@key($d)];
    }
}


// and you don't need to close the link manually
?>