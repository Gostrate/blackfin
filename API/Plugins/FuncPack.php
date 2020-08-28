<?php
/*
	DBLink V1.5	2008/12/23
	
	V1.5 2009/1/8		Can define log which script
	
	v1.4 2008/12/23	OpenAPI can use curl (default=fopen) config.php  OpenAPIMode="fopen/curl"
							can accept Fopen(GET) sockopen(POST) curl(POST/GET)
							相容於舊環境
							使用方式 OpenAPI($key,$post_data=""){		//ex:$post_data=  SRV=xxx&job=xxxx&value=YYYY  沒有POSTDATA 對 fopen = fopenGET 友的話 = sockopen POST
							

	v1.3 2008/07/10  OpenAPI 紀錄返回時間  V1.3


	2008/07/07	PHP5 相容修改  V1.2
		>> if (isset($req_field)){
		>> for ($i=0;$i<(count($site)-1);$i++){

*/

namespace Plugins;

use Repository;
use PDO;

class FuncPack
{
	public $PlayerId;
	public $GameCore;
	private $GD;

	public function __construct()
	{
		$this->GD = new Repository\GlobalData;
	}
	function setGameCore($gameCore)
	{
		$this->GameCore = $gameCore;
	}
	function convert2DArrayToJSON($arr)
	{
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				$arr["$key"] = json_encode($value);
			}
		}
		return json_encode($arr);
	}

	public function postData($sql)
  {
	$dbms = 'mysql';
   
    $host = $this->getProperty("host");
    $user = $this->getProperty("dbuser");
    $pass  = $this->getProperty("pwd");
    $dbName = $this->getProperty("dbname");
	$dsn = "$dbms:host=$host;dbname=$dbName";
	// echo $host.$user.$pass.$dbName;
    $main_array = array();
    try {
      $dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象
      $dbh->exec("SET CHARACTER SET utf8");
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sth = $dbh->prepare($sql);
    //   echo $sql;
      $sth->execute();
    //   $main_array = $sth->fetchAll(PDO::FETCH_ASSOC);
      // foreach ($dbh->query($sql) as $row) {
      //   array_push($main_array, $row); //你可以用 echo($GLOBAL); 来看到这些值
      // }
      $dbh = null;
    } catch (PDOException $e) {
      die("Error!: " . $e->getMessage() . "<br/>");
    }
    // return $main_array;
  }

  public function getData($sql)
  {
	$dbms = 'mysql';
   
    $host = $this->getProperty("host");
    $user = $this->getProperty("dbuser");
    $pass  = $this->getProperty("pwd");
    $dbName = $this->getProperty("dbname");
	$dsn = "$dbms:host=$host;dbname=$dbName";
	// echo $host.$user.$pass.$dbName;
    $main_array = array();
    try {
      $dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象
      $dbh->exec("SET CHARACTER SET utf8");
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sth = $dbh->prepare($sql);
    //   echo $sql;
      $sth->execute();
      $main_array = $sth->fetchAll(PDO::FETCH_ASSOC);
      // foreach ($dbh->query($sql) as $row) {
      //   array_push($main_array, $row); //你可以用 echo($GLOBAL); 来看到这些值
      // }
      $dbh = null;
    } catch (PDOException $e) {
      die("Error!: " . $e->getMessage() . "<br/>");
    }
    return $main_array;
  }

	//定义错误回报信息
	//********************************************************************
	function showErr($type, $Detail, $PayLoad = "")
	{

		$tmp = '{"Error":{"Type":"' . $type . '",';
		$tmp .= '"Detail":"' . $Detail . '"';
		if ($PayLoad <> "") {
			$tmp .= ',"PayLoad":' . $PayLoad . '}}';
		} else {
			$tmp .= '}}';
		}
		echo $tmp;

		die();
	}

	function showErrSet($serialErr,$errorcode="None")
	{
		switch ($serialErr) {
			
			case 1:
				return $this->showErr('Out Of Actions', 'there is no more action');
				break;
			case 2:
				return $this->showErr('Undefined DebugMode', 'there is no such debug mode');
				break;
			case 3:
				return $this->showErr('Wrong Type', 'you shold change type as Actions declared');
				break;
			case 4:
				return $this->showErr('No Such Data', 'please contact to math core designer, error message:'.$errorcode);
			case 5:
				return $this->showErr('No Such Action/Type', 'please check the  Action/Type name  you input');
				case 6:
					return $this->showErr('Wrong Lines', 'please check Initial-SpinConstraint-Lines');
			default:
				return $this->showErr('Undefine Error', 'there is undefine error, code_'.$serialErr);
				break;
		}
	}
	//********************************************************************


	function show($arr)
	{
		print("<pre>" . print_r($arr, true) . "</pre>");
	}
	function convertClassToString($obj)
	{
		return json_decode(json_encode($obj));
	}
	function ccgetfrominput()
	{
		return file_get_contents("php://input");
	}

	function createRoundID()
	{
		return substr($this->GameCore, 0, 3) . substr(md5($this->PlayerId), 0, 5) . str_replace(".", "", microtime(true));
	}
	function CCGetFromArray($parameter_name, $parameter_array, $default_value = "error 105: Can't get value form array")
	{
		return array_key_exists($parameter_name, $parameter_array) ? $parameter_array[$parameter_name] : $default_value;
	}
	function getProperty($name)
	{


		return $this->GD->__get($name);
	}

	function traslateSecond($s)
	{
		return str_pad(floor(($s % 86400) / 3600), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor((($s % 86400) % 3600) / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor((($s % 86400) % 3600) % 60), 2, '0', STR_PAD_LEFT);
	}

	

	function printWord($paremeter, $printName = "")
	{
		if (true) {//統一關閉顯示
			if ($printName != "") {
				echo ($printName . ": ");
			}
			print_r($paremeter);
			echo "<br />";
		}

		return;
	}

	function Trace($var)
	{
		global $site_no;
		global $site;
		global $db_dmode;
		global $SCRIPTname;
		if ($db_dmode) {
			write_err_temp("Log_" . $site[$site_no]["name"] . "_" . $SCRIPTname . "_DBug_" . date("YmdH"), "    [Trace]" . $var);
		}
	}





	function GetTlog($name, $sql1, $link)
	{
		$sql = "show tables like '$name'";
		$result = SearchBySQL($sql, $link);
		if ($result) {
			if (mysql_num_rows($result) <= 0) {
				return executesql(str_replace("%name%", $name, $sql1), $link);
			} else {
				return true;
			}
		}
	}


	//WriteNeedToDoSQL("api name","errcode","sql","memo",$link);
	function WriteNeedToDoSQL($api, $errcode, $sql, $memo, $link)
	{
		$sql = "show tables like 'Err_Needtodo_sql'";
		$result = mysql_query($sql, $link);
		if ($result) {
			if (mysql_num_rows($result) <= 0) {
				$sql = 'CREATE TABLE `Err_Needtodo_sql` ('
					. ' `sn` int(11) NOT NULL auto_increment,'
					. ' `api` varchar(20) NOT NULL default \'\','
					. ' `errcode` varchar(20) NOT NULL default \'\','
					. ' `sql` varchar(255) NOT NULL default \'\','
					. ' `memo` varchar(255) NOT NULL default \'\','
					. ' `add_date` datetime default NULL,'
					. ' PRIMARY KEY (`sn`)'
					. ' ) ENGINE=MyISAM AUTO_INCREMENT=1 ;';
				executesql($sql, $link);
			}
		}
		$sql = "INSERT INTO `Err_Needtodo_sql` (`api` , `errcode` , `sql` , `memo` , `add_date` ) VALUES (" .
			"'$api','$errcode','$sql','$memo',now())";
		executesql($sql, $link);
	}



	function write_db_debug($sql, $result, $link)
	{
		global $site_no;
		global $site;
		global $db_dmode;
		global $SCRIPTname;
		if ($db_dmode) {
			if ($result) {
				write_err_temp("Log_" . $site[$site_no]["name"] . "_" . $SCRIPTname . "_DBug_" . date("YmdH"), $sql . chr(10) . chr(9) . "[" . mysql_num_rows($result) . " rows] " . mysql_error($link));
			} else {
				write_err_temp("Log_" . $site[$site_no]["name"] . "_" . $SCRIPTname . "_DBug_" . date("YmdH"), $sql . chr(10) . chr(9) . "[0 rows] " . mysql_error($link));
			}
		}
	}

	function write_db_debug_value($value)
	{
		global $site_no;
		global $site;
		global $db_dmode_value;
		global $SCRIPTname;
		if ($db_dmode_value) {
			write_err_temp("Log_" . $site[$site_no]["name"] . "_" . $SCRIPTname . "_DBug_" . date("YmdH"), $value);
		}
	}


	function OpenAPI($key, $post_data = "")
	{		//ex:$post_data=  SRV=xxx&job=xxxx&value=YYYY
		global $OpenAPIMode;
		$start_time = caclutime();
		write_db_debug_value(chr(9) . "[" . $OpenAPIMode . " OpenAPI] " . $key);
		write_db_debug_value(chr(9) . chr(9) . "+Post_DATA= " . $post_data);
		if ($OpenAPIMode == "curl") {
			$post_result = CURL_Post($key, $post_data);
		} else {
			if ($post_data == "") {
				$post_result = HTTP_Get($key);
			} else {
				$post_result = HTTP_Post($key, $post_data);
			}
		}
		write_db_debug_value(chr(9) . ">>$start_time ~ " . caclutime() . " = " . (caclutime() - $start_time) . ">> " . $post_result);
		return $post_result;
	}

	function ReturnCode($name, $value)
	{
		global $site_no;
		global $site;
		global $db_dmode;
		global $SCRIPTname;

		$ret = "ret=" . date("His") . "_" . $site_no . "&";
		switch ($site[$site_no]["outformat"]) {
			case "norm":
				$result = date("His") . "_" . $site_no . ",";;
				foreach ($value as $v) $result .= $v . ",";
				if ($db_dmode) {
					write_err_temp("Log_" . $site[$site_no]["name"] . "_" . $SCRIPTname . "_DBug_" . date("YmdH"), chr(9) . "RetCode>>> $result" . chr(10));
				}
				die($result);
				break;

			default:
				for ($i = 0; $i < count($name); $i++) {
					$ret .= $name[$i] . "=" . $value[$i] . "&";
				}
				if ($db_dmode) {
					write_err_temp("Log_" . $site[$site_no]["name"] . "_" . $SCRIPTname . "_DBug_" . date("YmdH"), chr(9) . "RetCode>>> $ret" . chr(10));
				}
				die($ret);
				break;
		}
	}

	function ReturnCode02($name, $value)
	{
		$result = "";
		$result .= "ret=" . date("His") . "&";
		for ($i = 0; $i < count($name); $i++) {
			$result .= $name[$i] . "=" . $value[$i] . "&";
		}
		return $result;
	}

	function write_err($filename, $newdata)
	{
		global $SCRIPTname;
		global $log_script;
		if (strpos("~" . $log_script, $SCRIPTname, 1) > 0) {

			$f = fopen($filename . ".txt", "a");
			fwrite($f, $newdata . chr(10));
			fclose($f);
		}
	}

	function write_err_temp($filename, $newdata)
	{
		global $SCRIPTname;
		global $log_script;
		global $write_err_temp;
		if (strpos("~" . $log_script, $SCRIPTname, 1) > 0) {
			if (isset($write_err_temp[$filename])) {
				$write_err_temp[$filename] = $write_err_temp[$filename] . $newdata . chr(10);
			} else {
				$write_err_temp[$filename] = $newdata . chr(10);
			}
		}
	}
	function die_out($msg)
	{
		die($msg);
	}


	function write_errtemp()
	{
		global $write_err_temp;
		foreach ($write_err_temp as $key => $val) {
			$f = fopen($key . ".txt", "a");
			fwrite($f, $val);
			fclose($f);
		}
	}

	function write_list($filename, $newdata)
	{
		$f = fopen($filename . ".txt", "a+");
		fwrite($f, $newdata . chr(10));
		fclose($f);
	}

	function write_file($filename, $newdata)
	{
		$f = fopen($filename, "w");
		fwrite($f, $newdata);
		fclose($f);
	}

	function ExecuteSQL($sql, $link)
	{
		global $site_no;
		global $site;
		global $db_dmode;
		global $SCRIPTname;
		$result = mysql_query($sql, $link);
		if ($db_dmode) {
			write_err_temp("Log_" . $site[$site_no]["name"] . "_" . $SCRIPTname . "_DBug_" . date("YmdH"), chr(9) . "[ExecuteSQL] " . $sql . chr(10) . chr(9) . chr(9) . chr(9) . "[" . mysql_affected_rows($link) . " rows] " . mysql_error($link));
		}
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	function SearchBySql($sql, $link)
	{
		$result = mysql_query($sql, $link);
		write_db_debug(chr(9) . "[SearchSQL] " . $sql, $result, $link);
		return $result;
	}


	function CCDLookUp($field_name, $table_name, $where_condition, $link, $echosql = 0, $lineno = "")
	{
		$sql = "SELECT " . $field_name . ($table_name ? " FROM " . $table_name : "") . ($where_condition ? " WHERE " . $where_condition : "");
		if ($echosql) {
			echo $sql;
		}
		$result = mysql_query($sql, $link);
		write_db_debug(chr(9) . "[CCDLookUp_$lineno] " . $sql, $result, $link);
		if ($result) {
			if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_row($result);
				$dbvalue = $row[0];
				write_db_debug_value(chr(9) . chr(9) . chr(9) . "Value=$dbvalue");
			} else {
				$dbvalue = "";
			}
		} else {
			$dbvalue = "";
		}
		return $dbvalue;
	}

	function GetConnect($tmp)
	{
		/*$link=mysql_connect($tmp["ip"],$tmp["uid"],$tmp["pwd"]) or die("mysql_connect() failed.");
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db($tmp["tbname"],$link) or die("mysql_select_db() failed.");*/
		$link = mysql_connect($tmp["ip"], $tmp["uid"], $tmp["pwd"]);
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db($tmp["tbname"], $link);
		return $link;
	}

	function CCToSQL($Value)
	{
		return str_replace("'", "''", $Value);
	}

	function CCGetFromPost($parameter_name, $default_value = "")
	{
		return isset($_POST[$parameter_name]) ? CCStrip($_POST[$parameter_name]) : $default_value;
	}

	function CCGetFromGet($parameter_name, $default_value = "")
	{
		return isset($_GET[$parameter_name]) ? CCStrip($_GET[$parameter_name]) : $default_value;
	}

	function CCGetFromRequest($parameter_name, $default_value = "")
	{
		return isset($_REQUEST[$parameter_name]) ? CCStrip($_REQUEST[$parameter_name]) : $default_value;
	}

	function CCGetSession($parameter_name)
	{
		return isset($_SESSION[$parameter_name]) ? $_SESSION[$parameter_name] : "";
	}

	function CCSetSession($param_name, $param_value)
	{
		$_SESSION[$param_name] = $param_value;
	}

	function CCStrip($value)
	{
		if (get_magic_quotes_gpc() != 0) {
			if (is_array($value))
				foreach ($value as $key => $val)
					$value[$key] = stripslashes($val);
			else
				$value = stripslashes($value);
		}
		return $value;
	}

	function LoadRow($field_title, $field, $table, $where, $link, $echosql = 0)
	{
		if (trim($where) == "") {
			$sql = "select $field from $table";
		} else {
			$sql = "select $field from $table where $where";
		}
		if ($echosql) {
			echo $sql;
		}
		$result = mysql_query($sql, $link);
		write_db_debug(chr(9) . "[LoadRow] " . $sql, $result, $link);
		if ($result) {
			if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_row($result);
				write_db_debug_value(chr(9) . chr(9) . chr(9) . "Value(" . count($row) . ")=" . join("^", $row));
				$tmp1 = explode(",", $field);
				for ($j = 0; $j < count($tmp1); $j++) {
					$tmp1[$j] = $field_title . $tmp1[$j];
					echo $tmp1[$j];
				}
				for ($j = 0; $j < count($tmp1); $j++) {
					//global $$tmp1[$j];
					$$tmp1[$j] = $row[$j];
				}
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function U2B($text)
	{
		//return iconv("Big5","UTF-8",$text);
		return iconv("UTF-8", "Big5", $text);
	}

	function B2U($text)
	{
		return iconv("Big5", "UTF-8", $text);
	}

	function RefillZero($var, $strlen)
	{
		if (strlen($var) < $strlen) {
			for ($i = 0; $i < ($strlen - strlen($var)); $i++) {
				$tmp .= "0";
			}
			return $tmp . $var;
		} else {
			return $var;
		}
	}


	function Redirect($url)
	{
		if (strpos($url, "?") === false) {
			$url .= "?t=" . strtotime(date("Y-m-d H:i:s"));
		} else {
			$url .= "&t=" . strtotime(date("Y-m-d H:i:s"));
		}
		write_db_debug_value(chr(10) . chr(9) . "[Redirect]" . $url);
		header("Location: " . $url);
		die("");
	}

	//add at 080710
	function caclutime()
	{
		$time = explode(" ", microtime());
		$usec = (float) $time[0];
		$sec = (float) $time[1];
		return $sec + $usec;
	}


	function write_caclutime($st)
	{
		global $site_no;
		global $site;
		global $db_dmode;
		global $SCRIPTname;
		if ($db_dmode) {
			$totl = (caclutime() - $st);
			write_err_temp("Log_" . $site[$site_no]["name"] . "_" . $SCRIPTname . "_DBug_" . date("YmdH"), chr(9) . "caclutime>>> $totl" . chr(10));
		}
	}




	//add at 081223
	function HTTP_Get($key)
	{
		$handle = fopen($key, "rb");
		$contents = "";
		do {
			$data = fread($handle, 8192);
			if (strlen($data) == 0) {
				break;
			}
			$contents .= $data;
		} while (true);
		fclose($handle);
		return $contents;
	}

	function HTTP_Post($URL, $post_data, $referrer = "")
	{
		// parsing the given URL
		$URL_Info = parse_url($URL);

		// Building referrer
		if ($referrer == "") // if not given use this script as referrer
			$referrer = $_SERVER["SCRIPT_URI"];

		// Find out which port is needed - if not given use standard (=80)
		if (!isset($URL_Info["port"])) {
			$URL_Info["port"] = 80;
		}

		// building POST-request:
		$request .= "POST " . $URL_Info["path"] . " HTTP/1.1\n";
		$request .= "Host: " . $URL_Info["host"] . "\n";
		$request .= "Referer: $referer\n";
		$request .= "Content-type: application/x-www-form-urlencoded\n";
		$request .= "Content-length: " . strlen($post_data) . "\n";
		$request .= "Connection: close\n";
		$request .= "\n";
		$request .= $post_data . "\n";
		//
		$fp = fsockopen($URL_Info["host"], $URL_Info["port"]);
		fputs($fp, $request);
		while (!feof($fp)) {
			$result .=  fgets($fp, 128);
		}
		fclose($fp);

		$out = $result;
		$pos = strpos($out, "\r\n\r\n");
		$head = substr($out, 0, $pos);    //http head   
		$status = substr($head, 0, strpos($head, "\r\n"));    //http status line   
		$body = substr($out, $pos + 4, strlen($out) - ($pos + 4)); //page body   
		if (preg_match("/^HTTP\/\d\.\d\s([\d]+)\s.*$/", $status, $matches)) {
			if (intval($matches[1]) / 100 == 2) {
				return ($body);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/*****************************************************************************
	 * @purpose: do http post via CURL extension
	 * @author: Ken Lo
	 * @date: 2007/03/10
	 * @last modified: 2007/03/14
	 * @usage: String CURL_Post(String $url, String $post_data);
	 *****************************************************************************/
	function CURL_Post($url, $post_data)
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$post_result = curl_exec($ch);

		if (curl_errno($ch)) {
			print curl_error($ch);
		}

		curl_close($ch);

		return $post_result;
	}
}
