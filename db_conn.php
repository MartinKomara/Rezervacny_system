<?php
$addr = $_SERVER["SERVER_ADDR"];

if ($addr == 'localhost' || $addr == '127.0.0.1' || $addr == '::1')
{
    define("SQL_HOST", "localhost");
    define("SQL_DBNAME", "event");
    define("SQL_USERNAME", "root");
    define("SQL_PASSWORD", "");
}
else
{
    define("SQL_HOST", "localhost");
    define("SQL_DBNAME", "cztournament");
    define("SQL_USERNAME", "cztournament");
    define("SQL_PASSWORD", "Tournament_125858");
}

$db_con = @mysql_connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD);

mysql_select_db(SQL_DBNAME, $db_con);

mysql_query("SET NAMES utf8");
?>