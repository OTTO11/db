<?
/*
  * @Author: Alexander Nagnitchenko
  * @link: https://vk.com/otto_rocket
  * @special: KPHP-KDB
  * @version: 1
 */
$db = false;
$inId = $qNum = false;

#ifndef KittenPHP
function error($q = false){
  exit('DB Error NUM: <b>'.mysql_errno().'</b><br />Error: '.preg_replace("/'([^']+)'/i",'<b>$1</b>',mysql_error()).($q ? '<br />Query: '.$q : ''));
}
function new_db_decl(){
  $dbC = explode('\n', file_get_contents('db.conf'));
  if(!$db = mysql_connect($dbC[0], $dbC[1], $dbC[2])){
    error();
  }
  if(!mysql_select_db($dbC[3], $db)){
    error();
  }
  return true;
}
function dbQuery($q){
  global $inId;
  if($inId = mysql_query($q)){
	return $inId;
  }
  error($q);
}
function dbFetchRow($d){
  return mysql_fetch_assoc($d);
}
function dbNumRows(){
  global $inId;
  return mysql_num_rows($inId);
}
function dbInsertedId(){
  global $inId;
  return mysql_query('SELECT LAST_INSERT_ID()');
}
#endif
function query($q, $r = false){
  global $db;
  if(!$db){
	$db = new_db_decl();
  }
  $d = dbQuery($q);
  if(!$r){
    return $d;
  }
  return dbInsertedId();
}
function super_query($q, $m = false){
  $q = query($q);
  global $qNum;
  $qNum++;
  if(!$m){
    return dbFetchRow($q);
  }
  $r = array();
  while($e = dbFetchRow($q)){
    $r[] = $e;
  }
  return $r;
}
?>
