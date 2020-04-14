<?php
//Подключение к БД
$hostname="localhost"; 
$username="root";
$password=""; 
$db = "diplom";
$connect=mysql_connect($hostname, $username, $password) or die ("Error connect MySQL");
mysql_query('SET NAMES utf8');
$dbb=mysql_select_db($db, $connect) or die ("Error connect DB");
function resCreate($sql){
	$i = 0;
	$sql_err['response'] = array('error_code'=>'1','error'=>'Incorrect parameter.');
	$w = mysql_query($sql) or die (json_encode($sql_err));
	if(mysql_num_rows($w) != 0){
		while ($row = mysql_fetch_assoc($w)){
			foreach($row as $key => $value){
				$res['response'][$i][$key] = $value;
			}
			$i++;
		}
		$res['count'] = mysql_num_rows($w);
	} else {
		$res['response'] = array('error_code'=>'301','error'=>'No data');
	}
	return $res;
}
//Функции формирования JSON-объекта
function json_safe_encode($var){
return json_encode(json_fix_cyr($var));
}
function json_fix_cyr($var){
if (is_array($var)) {
$new = array();
foreach ($var as $k => $v) {
$new[json_fix_cyr($k)] = json_fix_cyr($v);
}
$var = $new;
} elseif (is_object($var)) {
$vars = get_object_vars($var);
foreach ($vars as $m => $v) {
$var->$m = json_fix_cyr($v);
}
}
return $var;
}
//Функция для административной части, совершает MySQL-запрос, в случае ошибки - записывает параметры ошибки в БД
function que($q,$ip,$browser) {
if(!mysql_query($q))
	{
	$err = mysql_errno().'|'.mysql_error();
	$err = explode("|",$err);
	$err_no = $err[0];
	$err_code = mysql_real_escape_string(strip_tags($err[1]));
	$q = mysql_real_escape_string(strip_tags($q));
	mysql_query("INSERT INTO `mysql_errs` (code, text, query, ip, browser) VALUES('$err_no', '$err_code', '$q', '$ip', '$browser')");
	return "no";
	}
else
	return "ok";
}
//Функция, проверяющая любые данные строчного типа, которые учавствуют в запросах к БД. 
function smart($value)
{
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    if (!is_numeric($value)) {
        $value = mysql_real_escape_string($value);
    }
    return $value;
}
?>