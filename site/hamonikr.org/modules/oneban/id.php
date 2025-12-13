<?
include('dbconn.php');
import_request_variables("gp", "form_");

$userid = $form_id;

if($form_md5 == md5($userid.$md5key))  {
	$sql = "SELECT * FROM `xe_member` WHERE `user_id` LIKE '$userid'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) == 0)  {
		print "삭제된 아이디 ";
	}
	else  {
		$row = mysql_fetch_assoc($result);
		if($row['denied'] === "Y")  {
			print "<font color=\"blue\">$userid 정지 해제</font><br><br>";
			$sql = "UPDATE `xe_member` SET denied = 'N' WHERE `user_id` = '$userid'";
			$result = mysql_query($sql);
			print $sql;
		}
		else  {
			print "<font color=\"red\">$userid 정지</font><br><br>";
			$sql = "UPDATE `xe_member` SET denied = 'Y' WHERE `user_id` = '$userid'";
			$result = mysql_query($sql);
			print $sql;
		}
	}
}
else  {
	print "MD5 Security error<br>";
}
?>