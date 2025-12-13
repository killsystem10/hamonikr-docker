<?php

// 2020.03.25
// LukeHan

header("Content-Type:text/html; charset=utf-8");

$host = 'localhost';
$user = 'hamonitr';
$pw = 'gkahslzk!$(';
$dbName = 'jaycedb';
$mysqli = mysqli_connect($host, $user, $pw, $dbName);

if($mysqli){
	$sqlQuery="";
	$sqlQuery = $sqlQuery."select * ";
	$sqlQuery = $sqlQuery."from (";
	$sqlQuery = $sqlQuery." select  'hamonikr1' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr1'  order by insert_dt desc limit 1) as conn_chk";
	$sqlQuery = $sqlQuery." union ";
	$sqlQuery = $sqlQuery." select  'hamonikr2' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr2'  order by insert_dt desc limit 1) as conn_chk";
	$sqlQuery = $sqlQuery." union ";
	$sqlQuery = $sqlQuery." select  'hamonikr3' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr3'  order by insert_dt desc limit 1) as conn_chk";
	$sqlQuery = $sqlQuery." union ";
	$sqlQuery = $sqlQuery." select  'hamonikr4' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr4'  order by insert_dt desc limit 1) as conn_chk";
	$sqlQuery = $sqlQuery." union ";
	$sqlQuery = $sqlQuery." select  'hamonikr5' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr5'  order by insert_dt desc limit 1) as conn_chk";
	$sqlQuery = $sqlQuery.") ta ";

#	echo "$sqlQuery";

	if($result = mysqli_query($mysqli, $sqlQuery)){
		for($i=0;$row=mysqli_fetch_array($result);$i++){
		        $list[] = array(
				"login_id"=>$row[login_id],
				"conn_chk"=>$row[conn_chk]);
		}
		echo json_encode($list);
	}else{
	        echo "<script>console.log('not connected db')</script>";
	}
}
