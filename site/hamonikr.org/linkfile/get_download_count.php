<?php
#header("Content-Type:application/json");
header("Content-Type:text/html; charset=utf-8");
Header("Access-Control-Allow-Origin: *");

$filepath = $_GET['name'];      // type is file name

$host = 'localhost';
$user = 'hamonitr';
$pw = 'gkahslzk!$(';
$dbName = 'hamonikrorg';
$mysqli = mysqli_connect($host, $user, $pw, $dbName);

if($mysqli){
        if($filepath == 'Total') $str_query = 'select count(*)+111683 as down_count from hamonika_download where name like "%iso%"';
        if($filepath == 'HamoniKR-ME') $str_query = 'select count(*) as down_count from hamonika_download where name like "HamoniKR-ME%iso";';

        if($filepath == 'Moordev_19_1_TESSA') $str_query = 'select count(*) as down_count from hamonika_download where name like "linuxmint-19_1_-moordev-mate-64bit_iso%";';
        if($filepath == 'Moordev_19_TARA_2_0') $str_query = 'select count(*) as down_count from hamonika_download where name like "linuxmint-19-mate-64bit-Moordev_iso%";';
        if($filepath == 'Moordev_18_TARA_1_0') $str_query = 'select count(*)+819+408 as down_count from hamonika_download where name like "LinuxMint-ko-amd64-18_3-HWE_iso%";';

        if($filepath == 'HamoniKR-RC') $str_query = 'select count(*)+34595+16035+510 as down_count from hamonika_download where name like "linuxmint17_3-mate-%bit_hamonikr-RC1-LiveCD%";';
        if($filepath == 'HamoniKR-RTM') $str_query = 'select count(*)+56661 as down_count from hamonika_download where name like "linuxmint17-mate%-%bit_hamonikr-RTM-LiveCD%";';

	if($result = mysqli_query($mysqli, $str_query)){
		for($i=0;$row=mysqli_fetch_array($result);$i++){
			$list[] = array("down_count"=>$row['down_count']);
		}
		echo json_encode($list);
	}else{
		echo 'not connected db';
	}
}

#echo(json_encode(array("mode" => '1111', "name" => '2222')));
#echo '1111';
?>
