<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


$filepath = $_GET['type'];      // type is file name


echo "<script>console.log('{$filepath}')</script>";

$bucket = "hamonikr_community_storagy";
//$key = "hamonikr-taebaek-arm64.tar.xz";
$key = $filepath;

try {
    $s3Client = new S3Client([
        //'region' => 'kr-standard',
        'region' => 'default',
        'version' => 'latest',
        'credentials' => [
            'key' => '391YIT6XHS1DKT9V76Y2',
            'secret' => 'kUENS23u4QfkqxeqWTMHdxd7ASaizI5qyOjsyTRP',
        ]
        ,'endpoint' => 'https://kr.object.iwinv.kr'
    ]);


//case 1.
    // 사전 서명된 요청 생성
//    $cmd = $s3Client->getCommand('GetObject', [
//        'Bucket' => $bucket,
//        'Key'    => $key
//    ]);
//    
//    // 사전 서명된 URL 생성
//    $request = $s3Client->createPresignedRequest($cmd, '+20 minutes'); // 20분 후 만료
//    $presignedUrl = (string) $request->getUri();
//    
//    echo "사전 서명된 URL: " . $presignedUrl;
//    header('Location: '.$presignedUrl);


//case 2.

    $result = $s3Client->getObject([
        'Bucket' => $bucket,
        'Key' => $key
     //   ,'ResponseContentDisposition' => 'attachment; filename="'.basename($key).'"'
    ]);




    // 파일 다운로드를 위한 헤더 설정
    header("Content-Type: {$result['ContentType']}");
    header('Content-Disposition: attachment; filename="' . basename($key) . '"');
    header('Content-Length: ' . $result['ContentLength']);

    header('Pragma: public');     // required
    header('Expires: 0');     // no cache
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);

    header('Accept-Ranges: bytes');

    // 파일 콘텐츠 스트리밍
    $stream = $result['Body'];
    while (!$stream->eof()) {
        echo $stream->read(1024 * 1024); // 1MB씩 데이터를 읽어서 출력
    }





} catch (S3Exception $e) {
    echo $e->getMessage() . "\n";
}


?>
