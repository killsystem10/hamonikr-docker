<?php

// 2020.09.10
$filepath = '/mnt/site-compatibility/site-compatibility-1.0.9.tar.gz';
$filesize = filesize($filepath);
$path_parts = pathinfo($filepath);
$filename = $path_parts['basename'];

header("Pragma: public");
header("Expires: 0");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Transfer-Encoding: binary");
header("Content-Length: $filesize");

ob_clean();
flush();
readfile($filepath);


$host = 'localhost';
$user = 'hamonitr';
$pw = 'gkahslzk!$(';
$dbName = 'hamonikrorg';
$mysqli = mysqli_connect($host, $user, $pw, $dbName);

$filename = str_replace('.', '_', $filename);

if($mysqli){
        $str_query = "insert into hamonika_download (name) values ('".$filename."')";

        if(mysqli_query($mysqli, $str_query)){
                echo "<script>console.log('sql success')</script>";
        }else{
                echo "<script>console.log('sql fail')</script>";
        }
        echo "<script>console.log('filepath')</script>";
}else{
        echo "<script>console.log('failed total count check')</script>";
}

