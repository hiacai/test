<?php
/*
 * Todo: 数据库
 * Author: Linwencai
 */

class mysql{
    private $host;
    private $name;
    private $pass;
    private $table;
    private $ut;

    function __construct($host,$name,$pass,$table,$ut){
        $this->host=$host;
     	$this->name=$name;
     	$this->pass=$pass;
     	$this->table=$table;
     	$this->ut=$ut;
     	$this->connect();
     }

    function connect(){
        $link=mysql_connect($this->host,$this->name,$this->pass) or die ($this->error());
        mysql_select_db($this->table,$link) or die("NoDatabase: ".$this->table);
        mysql_query("SET NAMES '$this->ut'");
     }

	function query($sql, $type = '') {
	    if(!($query = mysql_query($sql))) return $query;//$this->show('Say:', $sql);
	    return $query;
	}

    function show($message = '', $sql = '') {
		if(!$sql) echo $message;
		else echo $message.'<br>'.$sql;
	}
    
    // 之前操作影响行
    function affected_rows() {
		return mysql_affected_rows();
	}

    //取得结果数据
	function result($query, $row) {
		return mysql_result($query, $row);
	}
	
	//取得结果集中行的数目
	function num_rows($query) {
		return @mysql_num_rows($query);
	}
	
	//取得结果集中字段的数目
	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		return mysql_insert_id();
	}

	function fetch_row($query) {
		return mysql_fetch_row($query);
	}

	function version() {
		return mysql_get_server_info();
	}

	function close() {
		return mysql_close();
	}

    function fn_insert($table, $name, $value){
        $this->query("insert into $table ($name) value ($value)");
    }
    function fn_select($table, $key, $value){
        $result = $this->query("select * from $table where $key = '$value'");
		if ($result)
			return mysql_fetch_array($result);
		else
			return null;
    }
    function fn_getUser($table, $uuid){
        $result = mysql_fetch_array($this->query("select * from $table where uuid = '$uuid'"));
		if (!$result)
			$this->fn_insert("tb_user", "uuid", $uuid);
			$result = mysql_fetch_array($this->query("select * from $table where uuid = '$uuid'"));
		return  $result;
    }
	function fn_getValue($table, $uuid, $key){
        $result = mysql_fetch_array($this->query("select $key from $table where uuid = '$uuid'"));
		if ($result)
			return (int)$result[$key];
		else
			return -1;
	}
    function fn_costValue($table, $uuid, $key, $value){
        $result = $this->query("update $table set $key=$key-$value where uuid = '$uuid'");
		if ($result)
			return $this->fn_getValue($table, $uuid, $key);
		else
			return -1;
    }
    function fn_addValue($table, $uuid, $key, $value){
        $result = $this->query("update $table set $key=$key+$value where uuid = $uuid");
		
		if ($result)
			return $this->fn_getValue($table, $uuid, $key);
		else
			return -1;
    }
}

include 'config.php';
$host = $config_array["db"]["host"];
$user = $config_array["db"]["user"];
$passwd = $config_array["db"]["passwd"];
$dbName = $config_array["db"]["db"];
$charset = $config_array["db"]["charset"];
$db = new mysql($host, $user, $passwd, $dbName, $charset);

//$db->fn_insert('test','id,title,dates',"'','我插入的信息',now()");

//print_r($db->fn_select("tb_user", "uuid", 1));
//echo json_encode($db->fn_select("tb_user", "uuid", 1));
//$db->close();
?>
