<?php

// 2018.11.18

$filepath = $_GET['type'];      // type is file name
$serverpath = '/mnt/linkfile/';
//$goopath = "https://goo.gl/";
$goopath = "https://drive.google.com/";
$googlurl = "";

$gitpath = "https://github.com/ivsteam/Hamonikr_down/releases/download/";
$gitcheck_val = "git_link??";
$gitdown_url = "";

if(strpos($filepath, $goopath) !== false){
        // oogle drive url을  이용한 경우 설정
        $googlurl = explode($goopath, $filepath);
        $urlstr = $googlurl[1];

        // google drive url 제거
        $filepath = $googlurl[0];
        $path_parts = pathinfo($filepath);
        $filename = $path_parts['basename'];

	echo "<script>location.href='".$goopath.$urlstr."'</script>";
}elseif(strpos($filepath, $gitcheck_val) !== false){
	$gitdown_url = explode($gitcheck_val, $filepath);
	$filename = $gitdown_url[1];	// 파일명 - DB 저장
	
	$gitdown_url = $gitpath.$filename.'/'.$filename;

	echo "<script>location.href='".$gitdown_url."'</script>";
}else{
        // 파일 다운로드

        $filepath = $serverpath.$filepath;
        $filesize = filesize($filepath);
        $path_parts = pathinfo($filepath);
        $filename = $path_parts['basename'];

        header("Pragma: public");
        header("Expires: 0");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename='$filename'");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: $filesize");

        ob_clean();
        flush();
        readfile($filepath);
}


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

