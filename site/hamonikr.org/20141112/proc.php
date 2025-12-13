<?
session_start();
header('Content-Type: text/html; charset=utf-8');		# 페이지 속성 utf-8
include './FN.php';
include './MI.php';
$DB = new MI;
$DB->Conn();

## 전달변수 관련
foreach($_REQUEST as $key => $val) $R[$key] = ${'k_'.$key} = trim(str_replace("( select| union| insert| update| delete| drop|\"|\'|#|\/\*|\*\/|\\\|\;)", "", $val));

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
	$uid = $member['user_id'];
}else{
	Alert("회원정보가 없습니다");
	Hist();
	exit;
}

Switch($k_Kdiv)
{
	Case 'tran_write':
		$sql = "
insert into transfer (
	ano, project, msgstr, writer, status, regdate, page
) values (
	'".$k_A."', '".$k_P."', '".SLASH($k_C)."', '".$uid."', 'n', '".date("Y-m-d H:i:s")."', 'K'
)
		";
		$DB->Query($sql);
//		Alert("등록되었습니다");
//		Loca('pack_bs.php?A='.$k_A.'&P='.$k_P);
		exit;
	break;

	Case 'tran_con';
		$sql = "update transfer set status='n' where ano='".$k_A."' and project='".$k_P."' and status='y' and page='K'";
		$DB->Query($sql);
		$sql = "update transfer set status='y', commiter='".$uid."' where ano='".$k_A."' and project='".$k_P."' and t_idx='".$k_T."' and page='K'";
		$DB->Query($sql);
		$sql = "select * from transfer where t_idx='".$k_T."' and page='K'";
		$Tran = $DB->FAss($sql);
#		$sql = "update htrpass1 set msgstr='".$Tran['msgstr']."', wdate='".date("Y-m-d H:i:s")."' where ano='".$k_A."' and project='".$k_P."'";
		$sql = "update htrpass1 set msgcommit='".SLASH($Tran['msgstr'])."', msgcommitwdate='".date("Y-m-d H:i:s")."' where ano='".$k_A."' and project='".$k_P."'";
		$DB->Query($sql);
		Alert("해당 내용이 수정, 공개되었습니다");
//		echo '<script>opener.document.location.reload();</script>';
		Loca('pack_bs.php?A='.$k_A.'&P='.$k_P);
		exit;
	break;

	Case 'vote':
		$sql = "insert into vote (t_idx, uid) values ('".$k_T."', '".$uid."');";
		$DB->Query($sql);
		$sql = "update transfer set vote=vote+1 where t_idx='".$k_T."' and page='K'";
		$DB->Query($sql);
		Loca('pack_bs.php?P='.$k_P);
		exit;
	break;

	Case 'tran_del':
		$sql = "select * from transfer where t_idx='".$k_tid."' and writer='".$member['user_id']."' and page='K'";
		$res = $DB->KRow($sql);
		if($res['Count'] > 0)
		{
			$sql = "delete from transfer where t_idx='".$k_tid."' and writer='".$member['user_id']."' and page='K'";
			$DB->Query($sql);
			Alert("삭제되었습니다");
			Loca($_SERVER['HTTP_REFERER']);
			exit;
		}
		else
		{
			Alert("작성한 글이 아닙니다");
			Hist();
			exit;
		}
	break;

	Case 'my_edit':
		$sql = "select t_idx, ano, project from transfer where t_idx='".$k_tid."' and writer='".$member['user_id']."' and page='K'";
		$wr = $DB->FRow($sql);
		if($wr[0]!='')
		{
			$sql = "update transfer set msgstr='".SLASH($k_msg)."' where t_idx='".$k_tid."' and writer='".$member['user_id']."' and page='K'";
			$DB->Query($sql);
			Alert("수정되었습니다");
			if($k_ref=='my') {
				echo '<script>parent.document.location.reload();</script>';
			}elseif($k_ref=='pack'){
				echo '<script>parent.WRT('.$wr[1].',\''.$wr[2].'\');</script>';
				echo '<script>parent.WRT('.$wr[1].',\''.$wr[2].'\');</script>';
				echo '<script>parent.$("#dialog").dialog("close");</script>';
			}
			exit;
		}
		else
		{
			Alert("작성자 본인이 아닙니다");
			echo '<script>parent.document.location.reload();</script>';
			exit;
		}
	break;



### 아래 데스크탑


	Case 'desk_write':
		$sql = "
insert into transfer (
	ano, project, msgstr, writer, status, regdate, page
) values (
	'".$k_A."', '".$k_P."', '".SLASH($k_C)."', '".$uid."', 'n', '".date("Y-m-d H:i:s")."', 'D'
)
		";
		$DB->Query($sql);
//		Alert("등록되었습니다");
//		Loca('pack_bs.php?A='.$k_A.'&P='.$k_P);
		exit;
	break;

	Case 'desk_con';
		$sql = "update transfer set status='n' where ano='".$k_A."' and project='".$k_P."' and status='y' and page='D'";
		$DB->Query($sql);
		$sql = "update transfer set status='y', commiter='".$uid."' where ano='".$k_A."' and project='".$k_P."' and t_idx='".$k_T."' and page='D'";
		$DB->Query($sql);
		$sql = "select * from transfer where t_idx='".$k_T."' and page='D'";
		$Tran = $DB->FAss($sql);
#		$sql = "update htrpass1 set msgstr='".$Tran['msgstr']."', wdate='".date("Y-m-d H:i:s")."' where ano='".$k_A."' and project='".$k_P."'";
		$sql = "update htrpass3 set msgcommit='".SLASH($Tran['msgstr'])."', msgcommitwdate='".date("Y-m-d H:i:s")."' where ano='".$k_A."' and project='".$k_P."'";
		$DB->Query($sql);
		Alert("해당 내용이 수정, 공개되었습니다");
//		echo '<script>opener.document.location.reload();</script>';
		Loca('desk_pack_bs.php?A='.$k_A.'&P='.$k_P);
		exit;
	break;

	Case 'desk_vote':
		$sql = "insert into vote (t_idx, uid) values ('".$k_T."', '".$uid."');";
		$DB->Query($sql);
		$sql = "update transfer set vote=vote+1 where t_idx='".$k_T."' and page='D'";
		$DB->Query($sql);
		Loca('desk_pack_bs.php?P='.$k_P);
		exit;
	break;

	Case 'desk_del':
		$sql = "select * from transfer where t_idx='".$k_tid."' and writer='".$member['user_id']."' and page='D'";
		$res = $DB->KRow($sql);
		if($res['Count'] > 0)
		{
			$sql = "delete from transfer where t_idx='".$k_tid."' and writer='".$member['user_id']."' and page='D'";
			$DB->Query($sql);
			Alert("삭제되었습니다");
			Loca($_SERVER['HTTP_REFERER']);
			exit;
		}
		else
		{
			Alert("작성한 글이 아닙니다");
			Hist();
			exit;
		}
	break;

	Case 'desk_edit':
		$sql = "select t_idx, ano, project from transfer where t_idx='".$k_tid."' and writer='".$member['user_id']."' and page='D'";
		$wr = $DB->FRow($sql);
		if($wr[0]!='')
		{
			$sql = "update transfer set msgstr='".SLASH($k_msg)."' where t_idx='".$k_tid."' and writer='".$member['user_id']."' and page='D'";
			$DB->Query($sql);
			Alert("수정되었습니다");
			if($k_ref=='my') {
				echo '<script>parent.document.location.reload();</script>';
			}elseif($k_ref=='pack'){
				echo '<script>parent.WRT('.$wr[1].',\''.$wr[2].'\');</script>';
				echo '<script>parent.WRT('.$wr[1].',\''.$wr[2].'\');</script>';
				echo '<script>parent.$("#dialog").dialog("close");</script>';
			}
			exit;
		}
		else
		{
			Alert("작성자 본인이 아닙니다ㅇ");
			echo '<script>parent.document.location.reload();</script>';
			exit;
		}
	break;

}
?>
