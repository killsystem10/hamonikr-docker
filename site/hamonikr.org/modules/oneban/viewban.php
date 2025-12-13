<?
include('dbconn.php');

$logged_info = Context::get('logged_info');
$userid= mysql_escape_string($logged_info->user_id);
$sql = "SELECT * FROM `xe_member` WHERE `user_id` = '$userid'";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);

if($row['is_admin'] == "Y")  {
//--------------------------------------------------------------------
print "<br>";
$oDocumentModelz = &getModel('document');
$document_srlz = Context::get('document_srl');
$oDocumentz = $oDocumentModelz->getDocument($document_srlz);
$userid = $oDocumentz->get('user_id');
$sql = "SELECT * FROM `xe_member` WHERE `user_id` = '$userid'";
$result = mysql_query($sql);
if(mysql_num_rows($result) == 0)  {
	print "삭제된 아이디 ";
}
else  {
	$row = mysql_fetch_assoc($result);
	$link = $linkStart . "id.php?id=" . URLencode($userid) . "&md5=" . md5($userid.$md5key);
	if($row['denied'] === "Y")  {
		print "<a href=\"$link\" target=\"_blank\"><font color=\"green\">정지 해제</font></a>";
		$mark1 = 1;
	}
	else  {
		print "<a href=\"$link\" target=\"_blank\">정지</a>";
		$mark1 = 0;
	}
}

print " - ";

$ip = $oDocumentz->getIpaddress();
$sql = "SELECT * FROM `xe_spamfilter_denied_ip` WHERE `ipaddress` = '$ip'";
$result = mysql_query($sql);
$link = $linkStart . "ip.php?ip=" . $ip . "&md5=" . md5($ip.$md5key) . "&id=" . URLencode($userid);;
if(mysql_num_rows($result) == 0)  {
		print "<a href=\"$link\" target=\"_blank\">IP 정지</a>";
		$mark2 = 0;
}
else  {
		print "<a href=\"$link\" target=\"_blank\"><font color=\"green\">IP 정지 해제</font></a>";
		$mark2 = 1;
}

if($mark1 == 0 || $mark2 == 0)  {
	print " - ";
	$link = $linkStart . "onekill.php?ip=" . $ip . "&md5=" . md5($ip.$md5key) . "&id=" . URLencode($userid);;
	print "<a href=\"$link\" target=\"_blank\">원 클릭 차단</a>";
}

print " - ";
$link = $linkStart2 . $oDocumentz->get('member_srl');
print "<a href=\"$link\" target=\"_blank\">글 추적</a>";
//--------------------------------------------------------------------
}

?>