<?
/*
  * @Author: Alexander Nagnitchenko
  * @link: https://vk.com/otto_rocket
  * @special: KPHP-KDB
  * @version: 1
 */
$db = $inId = $qNum = 0;
function error($q = false){
  exit('DB Error NUM: <b>'.mysql_errno().'</b><br />Error: '.preg_replace("/'([^']+)'/i",'<b>$1</b>',mysql_error()).($q ? '<br />Query: '.$q : ''));
}
#ifndef KittenPHP
function new_db_decl(){
  $dbC = explode('\n', file_get_contents('db.connect'));
  if(!$db = mysql_connect($dbC[0], $dbC[1], $dbC[2])){
    return false;
  }
  if(!mysql_select_db($dbC[3], $db)){
    return false;
  }
  return true;
}
function dbQuery($q){
  global $inId;
  $inId = mysql_query($q);
  return $inId;
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
  if(!$db && !($db = new_db_decl())){
    error();
  }
  if(!($d = dbQuery($q))){
    error($q);
  }
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
