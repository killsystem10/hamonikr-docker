<?
session_start();
//foreach($_SERVER['HTTP_COOKIE'] as $key =>$val) setcookie($_COOKIE[$key], $val, "", "/",".hamonikr.org");
include './FN.php';
foreach($_REQUEST as $key => $val) $R[$key] = ${'k_'.$key} = trim(str_replace("( select| union| insert| update| delete| drop|\"|\'|#|\/\*|\*\/|\\\|\;)", "", $val));
if($k_P=='') {echo "false";exit;}
include './MI.php';
$DB= new MI;
$DB->Conn();

//$LOGIN = false;
//$COOKIE = explode(';', $_SERVER['HTTP_COOKIE']);
//foreach($_COOKIE as $key => $val) if(preg_match('/wordpress_logged_in_/', $key)==true) {$LOGIN = true;$COO = $val;}

if($_SESSION['member_srl']!=''){
	$DBX = new MI;
	$DBX->Conn('xedb','xedb','gkahslzk!$(');
	## 회원정보
	$sql = "select * from xe_member where member_srl='".$_SESSION['member_srl']."'";
	$member = $DBX->FAss($sql);
	$sql = "select group_srl from xe_member_group_member where member_srl='".$_SESSION['member_srl']."'";
	$levv = $DBX->KRow($sql);
	if($levv['Count'] > 0) for($i=0;$i<$levv['Count'];$i++) $lev[]=$levv[$i][0];
	if(in_array(148, $lev)!=false&&in_array(149,$lev)!=false) $_SESSION['group']='all';
	elseif(in_array(148, $lev)!=false&&in_array(149,$lev)==false) $_SESSION['group']='commiter';
	elseif(in_array(148, $lev)==false&&in_array(149,$lev)!=false) $_SESSION['group']='writer';
}

$sql = "select msgid from htrpass1 where ano='".$k_A."' and project='".$k_P."'";
$en = $DB->FRow($sql);

$sql = "
select
	a.msgid, t.*
from
	htrpass1 as a, transfer as t
where
	a.project='".$k_P."' and t.project=a.project and t.ano=a.ano and a.ano='".$k_A."' and t.page='K'
group by
	t.t_idx
order by
	t.status asc, t.vote desc, t.regdate desc
";
/*$sql = "
select
	a.msgid, t.*, v.uid
from
	htrpass1 as a, transfer as t
left join
	vote as v
on
	t.t_idx=v.t_idx and t.page='K'
where
	a.project='".$k_P."' and t.project=a.project and t.ano=a.ano and a.ano='".$k_A."' and t.page='K'
group by
	t.t_idx
order by
	t.status asc, t.vote desc, t.regdate desc
";*/
//echo $sql;exit;
//if($_SERVER['REMOTE_ADDR']=='211.58.153.75') {echo $sql;exit;}
$res = $DB->KAss($sql);


## 로그인 상태일때
/*if($LOGIN==true)
{
	$DBW = new MI;
	$DBW->Conn('wordpress');
	$uu = explode('|', $COO);
	$uid = $uu[0];
	$sql = "select meta_value from wp_usermeta where user_id=(select user_id from wp_usermeta where meta_key='nickname' and meta_value='".$uid."') and meta_key='wp_user_level'";
	$level2 = $DBW->FRow($sql);
	$level = $level2[0];
}*/
?>

<td colspan="5" align="right" style="background-color:transparent;font-family:'Malgun Gothic', Verdana;">

<form name="Cform_<?=$k_A;?>" method="post" action="proc.php">
<table class="table" style="width:90%;">
<input type="hidden" name="Kdiv" value="tran_con" />
<input type="hidden" name="A" value="<?=$k_A;?>" />
<input type="hidden" name="P" value="<?=$k_P;?>" />
<input type="hidden" name="T" value="" />
	<col width="" />
	<col width="100" />
	<col width="100" />
	<col width="60" />
	<col width="60" />
	<tr bgcolor="#f5f5f5" height="30" align="center" style="font-weight:bold;font-family:'Malgun Gothic', Verdana;">
		<td>번역내용</td>
		<td>작성자</td>
		<!--td>Commiter</td//-->
		<td>작성일</td>
		<td>추천수</td>
		<td>기능</td>
<?//if($_SESSION['group']=='commiter' || $_SESSION['group']=='all') echo '		<td width="70">Commit</td>'.chr(10);?>
	</tr>

<?
if($res['Count'] > 0)
{
	for($i=0;$i<$res['Count'];$i++)
	{
		$sql = "select uid from vote where t_idx='".$res[$i]['t_idx']."' and uid='".$member['user_id']."'";
//		if($_SERVER['REMOTE_ADDR']=='211.58.153.75') echo $sql.'<br />';
		$vo = $DB->FRow($sql);

?>

	<tr bgcolor="#ffffff" height="27" align="center" style="font-size:12px;font-family:'Malgun Gothic', Verdana;">
		<!-- 번역내용 -->
		<td align="left" style="padding:3px;color:#000000;"><?=htmlspecialchars(SLASH($res[$i]['msgstr'],1));?><?=($res[$i]['status']=='y')?' <span style="color:red;font-size:11px;">(최종 승인 번역)</span>':'';

//print_r($res[$i]);
?></td>
		<!-- 작성자 -->
		<td><?=$res[$i]['writer'];?></td>
		<!-- 커미터 -->
		<!--td><?=$res[$i]['commiter'];?></td//-->
		<!-- 작성일 -->
		<td style="font-size:11px;"><?=substr($res[$i]['regdate'],0,10);?><br /><?=substr($res[$i]['regdate'],10);?></td>
		<!-- 추천수 -->
		<td><?=number_format($res[$i]['vote']);?></td>
		<!--td><?if($level < 5  && $res[$i]['uid']=='') echo '<a href="javascript:;" onclick="javascript:VOTE(\''.$res[$i]['t_idx'].'\');"><img src="/images/icon/icon-vote.png" style="vertical-align:middle;" /></a>';
			else echo '';?></td//-->
		<!-- 추천 -->
		<td style="line-height:23px;">
<?
// hihoon: 데이터값 확인용. 추후 삭제예정 2014.11.14 00:26
//if ($res[$i]['writer'] == $member['user_id'])
//	echo $res[$i]['uid']."|".$res[$i]['writer']."|".$member['user_id'];
// TODO: 자기 자신인 경우 추천 불가 정책 구현 필요
//if($res[$i]['uid']!=$member['user_id']){
	// 추천 할 수 있는 그룹에 속한 사용자 이고, 자기자신이 아닌경우  여부 검사
//	if($_SESSION['group']=='writer') if($res[$i]['writer']!=$member['user_id']) {
	if($_SESSION['group']=='writer') if($res[$i]['writer']!=$member['user_id']) {if($vo[0]=='') {
		echo '<a class="btn-success btn btn-xs" style="vertical-align:middle;" onclick="javascript:VOTE(\''.$res[$i]['t_idx'].'\', \''.$k_A.'\');">추천</a>';
	}

//	}
	// 자기가 올린 번역후보글인 경우 삭제  가능
	}elseif($res[$i]['writer']==$member['user_id']) {
		// 삭제는 commit되어 확정된 번역항목이 아니고 추천자가 없는경우에 가능
		if($res[$i]['status']=='n' && $res[$i]['vote']=='0'){
			echo '<a href="javascript:Edit(\''.$res[$i]['t_idx'].'\');" class="btn btn-info btn-xs">수정</a><a href="javascript:Del(\''.$res[$i]['t_idx'].'\');" class="btn btn-danger btn-xs">삭제</a>';
//			echo '<a href="javascript:Del(\''.$res[$i]['t_idx'].'\');" class="btn btn-danger btn-xs">삭제</a>';
		}else {
			echo '<a class="jqtooltip" title="내가 쓴 글 또는 추천이 있거나|최종 승인된 글입니다"><u>X</u></a>';
		}
	}

?>
<?
	if($_SESSION['group']=='commiter' || $_SESSION['group']=='all' && $res[$i]['status']=='n')
		echo '		<a href="javascript:;" onclick="javascript:CONFIRM(\''.$res[$i]['t_idx'].'\', \''.$k_A.'\');" class="btn btn-danger btn-xs">Commit</a>'.chr(10);
//	else echo '<td></td>';
?>
		</td>
	</tr>
<?
	}
}else{
	// 번역 글이 하나도 없는경우
	$sql = "select * from htrpass1 where ano='".$k_A."' and project='".$k_P."'";
	$res = $DB->FAss($sql);
	if($res['ano']!='')
	{
?>

	<tr bgcolor="#ffffff" height="27" align="center" style="font-size:12px;font-family:'Malgun Gothic', Verdana;">
		<td align="left" style="padding:3px;color:#000000;"><?=htmlspecialchars(SLASH($res['msgstr'],1));?> <span style="color:red;font-size:11px;">(최종 승인 번역)</span></td>
		<td></td>
		<td style="font-size:11px;"><?=substr($res['wdate'],0,10);?><br /><?=substr($res['wdate'],10);?></td>
		<td>0</td>
		<td></td>
<?if($_SESSION['group']=='writer' || $_SESSION['group']=='all') echo '		<td></td>'.chr(10);?>
	</tr>

<?
	}else{
		echo '<tr><td colspan="5" height="150" align="center">작성된 내용이 없습니다</td></tr>';
	}
}
?>

</table>
</form>

</td>

<script>
$('.jqtooltip').tooltip({content: function(callback) {callback($(this).prop('title').replace('|', '<br />'));}});
</script>
