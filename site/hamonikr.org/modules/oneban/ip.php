<?
include('dbconn.php');
import_request_variables("gp", "form_");

$userid = $form_id;
$ip = $form_ip;

if($form_md5 == md5($ip.$md5key))  {
	$sql = "SELECT * FROM `xe_spamfilter_denied_ip` WHERE `ipaddress` = '$ip'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) == 0)  {
		print "<font color=\"red\">$ip  ($userid) 정지</font><br><br>";
		$time = date('YmdHis');
		$sql = "INSERT INTO `xe_spamfilter_denied_ip` ( `ipaddress` , `regdate` ) VALUES ('$ip', '$time') ";
		$result = mysql_query($sql);
		print $sql;
	}
	else  {
		print "<font color=\"blue\">$ip ($userid) 정지 해제</font><br><br>";
		$sql = "DELETE FROM `xe_spamfilter_denied_ip` WHERE ipaddress = '$ip'";
		$result = mysql_query($sql);
		print $sql;
	}
}
else  {
	print "MD5 Security error<br>";
}
?>