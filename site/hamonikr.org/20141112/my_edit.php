<?
session_start();
define('__ZBXE__', true);
define('__XE__', true);
require_once('../config/config.inc.php');
$oContext = &Context::getInstance();
$oContext->init();

define(_V_, '00000001');
define(_LINK_, '_bs');
define(_DIR_, '/20141112');
include './FN.php';
foreach($_REQUEST as $key => $val) $R[$key] = ${'k_'.$key} = trim(str_replace("( select| union| insert| update| delete| drop|\"|\'|#|\/\*|\*\/|\\\|\;)", "", $val));

include './MI.php';
$DB= new MI;
$DB->Conn();

//$DBX = new MI;
//$DBX->Conn('xedb','xedb','gkahslzk!$(');
## 회원정보
//$sql = "select group_srl from xe_member_group_member where member_srl='".$_SESSION['member_srl']."'";
//$levv = $DBX->KRow($sql);
//if($levv['Count'] > 0) for($i=0;$i<$levv['Count'];$i++) $lev[]=$levv[$i][0];

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
//	if($levv[0]==148) $_SESSION['group']='commiter';
//	elseif($levv[0]==149) $_SESSION['group']='writer';
//	else $_SESSION['group']='user';
}


$sql = "select * from transfer where t_idx='".$k_T."' and writer='".$member['user_id']."'";
$res = $DB->FAss($sql);
if($res['t_idx']!='')
{

	if(preg_match('/my_list.php/', $_SERVER['HTTP_REFERER'])==true) $ref = "my";
	elseif(preg_match('/pack_bs.php/', $_SERVER['HTTP_REFERER'])==true) $ref = "pack";

?>

<!DOCTYPE html>
<html lang="ko">
<head>
<!-- META -->
<meta charset="utf-8">
<meta name="Generator" content="XpressEngine">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- TITLE -->
<title></title>
<!-- CSS -->
<link rel="stylesheet" href="/common/css/xe.min.css?20141102212543" />
<link rel="stylesheet" href="/common/js/plugins/ui/jquery-ui.min.css?20141102212544" />
<link rel="stylesheet" href="/modules/editor/styles/dreditor/style.css?20141102212612" />
<link rel="stylesheet" href="/addons/bootstrap3_css/bootstrap.min.css?20141103234159" />
<!-- JS -->
<!--[if lt IE 9]><script src="/common/js/jquery-1.x.min.js?20141102212543"></script>
<![endif]--><!--[if gte IE 9]><!--><script src="/common/js/jquery.min.js?20141102212543"></script>
<![endif]-->
<!--[if lt IE 9]><script src="/common/js/html5.js"></script><![endif]-->
<script language="javascript">
<!--
function DFAlert(Colm,Frm){if (eval("document."+Frm+"."+Colm).value==""){eval("document."+Frm+"."+Colm).style.backgroundColor="#e8e8e8";eval("document."+Frm+"."+Colm).value="";eval("document."+Frm+"."+Colm).focus();return false;}}
function SBM(){if(DFAlert("msg","Eform")==false) return false;}
//-->
</script>
</head>

<body>

<style>
* {margin:0px;padding:0px;}
body {margin:0px;padding:0px;width:100%;background-color:#ffffff;}
</style>

<div class="bs3-wrap" style="padding-top:30px;">

<form name="Eform" method="post" action="proc.php" onsubmit="javascript:return SBM();">
<input type="hidden" name="Kdiv" value="my_edit" />
<input type="hidden" name="tid" value="<?=$k_T;?>" />
<input type="hidden" name="ref" value="<?=$ref;?>" />
<table class="table table-striped" style="width:100%;">

	<col width="100" />
	<col />
	<tr>
		<td style="padding:10px;">작성한 내용 : </td>
		<td style="padding:10px;"><?=nl2br(SLASH($res['msgstr'],1));?></td>
	</tr>
	<tr>
		<td style="padding:10px;">수정할 내용 : </td>
		<td style="padding:10px;"><span class="btn-sm-3"><textarea name="msg" class="form-control" rows="5"></textarea></span></td>
	</tr>
</table>

<div style="width:100%;text-align:center;">
	<span><button class="btn btn-success">수 정</button></span>
	<span><a href="javascript:;" class="btn btn-default" onclick="javascript:parent.$('#dialog').dialog('close');parent.$('#FRM').attr('src','about:blank');">취 소</a></span>
</div>
</form>

</div>
</body>
</html>




<?
}
else
{
	Alert("작성자 본인이 아닙니다");
	echo '<script>parent.document.location.reload();</script>';
	exit;
}
?>