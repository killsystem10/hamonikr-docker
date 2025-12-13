<?php

// 2020.09.14
// LukeHan

header("Content-Type:text/html; charset=utf-8");

$type = $_GET['type'];      // type is file name
$hamonikruser = $_GET['user'];      // type is file name
$status = $_GET['status'];      // type is file name

//echo "<script>console.log('".$type." // ".$hamonikruser." // ".$status."')</script>";

$host = 'localhost';
$user = 'hamonitr';
$pw = 'gkahslzk!$(';
$dbName = 'jaycedb';
$mysqli = mysqli_connect($host, $user, $pw, $dbName);

if($type == 'get'){
	if($mysqli){
        	$sqlQuery="";
	        $sqlQuery = $sqlQuery."select * ";
	        $sqlQuery = $sqlQuery."from (";
	        $sqlQuery = $sqlQuery." select  'hamonikr-iwinv-01' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr-iwinv-01'  order by insert_dt desc limit 1) as conn_chk";
	        $sqlQuery = $sqlQuery." union ";
	        $sqlQuery = $sqlQuery." select  'hamonikr-iwinv-02' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr-iwinv-02'  order by insert_dt desc limit 1) as conn_chk";
	        $sqlQuery = $sqlQuery." union ";
	        $sqlQuery = $sqlQuery." select  'hamonikr-iwinv-03' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr-iwinv-03'  order by insert_dt desc limit 1) as conn_chk";
	        $sqlQuery = $sqlQuery." union ";
	        $sqlQuery = $sqlQuery." select  'hamonikr-iwinv-04' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr-iwinv-04'  order by insert_dt desc limit 1) as conn_chk";
	        $sqlQuery = $sqlQuery." union ";
	        $sqlQuery = $sqlQuery." select  'hamonikr-iwinv-05' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr-iwinv-05'  order by insert_dt desc limit 1) as conn_chk";
	        $sqlQuery = $sqlQuery." union ";
	        $sqlQuery = $sqlQuery." select  'hamonikr-iwinv-06' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr-iwinv-06'  order by insert_dt desc limit 1) as conn_chk";
	        $sqlQuery = $sqlQuery." union ";
	        $sqlQuery = $sqlQuery." select  'hamonikr-iwinv-07' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr-iwinv-07'  order by insert_dt desc limit 1) as conn_chk";
	        $sqlQuery = $sqlQuery." union ";
	        $sqlQuery = $sqlQuery." select  'hamonikr-iwinv-08' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr-iwinv-08'  order by insert_dt desc limit 1) as conn_chk";
	        $sqlQuery = $sqlQuery." union ";
	        $sqlQuery = $sqlQuery." select  'hamonikr-iwinv-09' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr-iwinv-09'  order by insert_dt desc limit 1) as conn_chk";
	        $sqlQuery = $sqlQuery." union ";
	        $sqlQuery = $sqlQuery." select  'hamonikr-iwinv-10' as login_id , (select status from tbl_vdi_conn_user where login_id = 'hamonikr-iwinv-10'  order by insert_dt desc limit 1) as conn_chk";
	        $sqlQuery = $sqlQuery.") ta ";

#       	echo "$sqlQuery";

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
}elseif($type == 'save'){
	if($mysqli){
		$str_query = "insert into tbl_vdi_conn_user (login_id, login_dt, logout_dt, status, insert_dt) values ('".$hamonikruser."', now(), now(), '".$status."',now())";

	        if(mysqli_query($mysqli, $str_query)){
        	        echo "<script>console.log('sql success')</script>";
	        }else{
        	        echo "<script>console.log('sql fail')</script>";
	        }
	}
}
