<?php
//require '/opt/hamoniRenewal/linkfile/vendor/autoload.php';
//require '/opt/hamoniRenewal/linkfile/aws.phar';
//require 'aws.phar';
//require 'vendor/autoload.php';
require 'aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$bucket= "aws-hamonikr-jin";
$filetodownload = "hamonikr-jin-4.0-amd64.iso";
$resultbool = $s3->doesObjectExist($bucket, $filetodownload );
if ($resultbool) {
    $result = $client->getObject([ 
    'Bucket' => $bucket,
    'Key' => $filetodownload 
]);
}
else
{
    echo "file not found";die;
}
header ( "Content-Type: {$result['ContentType']}" );
header ( "Content-Disposition: attachment; filename=" . $filetodownload );
header ('Pragma: public');
echo $result ['Body'];
die ();
?>
