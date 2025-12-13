<?
include('dbconn.php');
import_request_variables("gp", "form_");

$userid = $form_id;
$ip = $form_ip;

print "원 클릭 차단<br>";

if($form_md5 == md5($ip.$md5key))  {
	$sql = "SELECT * FROM `xe_member` WHERE `user_id` LIKE '$userid'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) == 0)  {
		print "삭제된 아이디<br>";
		print $sql."<br><br>";
	}
	else  {
		$row = mysql_fetch_assoc($result);
		if($row['denied'] === "Y")  {
			print "$userid 이미 정지됨<br><br>";
		}
		else  {
			print "<font color=\"red\">$userid 정지</font><br>";
			print $sql."<br><br>";
			$sql = "UPDATE `xe_member` SET denied = 'Y' WHERE `user_id` = '$userid'";
			$result = mysql_query($sql);
		}
	}


	$sql = "SELECT * FROM `xe_spamfilter_denied_ip` WHERE `ipaddress` = '$ip'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) == 0)  {
		print "<font color=\"red\">$userid $ip 정지</font><br>";
		print $sql."<br><br>";
		$time = date('YmdHis');
		$sql = "INSERT INTO `xe_spamfilter_denied_ip` ( `ipaddress` , `regdate` ) VALUES ('$ip', '$time') ";
		$result = mysql_query($sql);
	}
	else  {
		print "$userid $ip 이미 정지됨<br><br>";
	}
}
else  {
	print "MD5 Security error<br>";
}
?>