<?
include 'kka_header.php';
?>


<style>
.F_00 {color:#000000;}
.bold {font-weight:bold;}
</style>



<?
define(_V_, '00000001');
define(_LINK_, '_bs');
include './FN.php';
foreach($_REQUEST as $key => $val) $R[$key] = ${'k_'.$key} = trim(str_replace("( select| union| insert| update| delete| drop|\"|\'|#|\/\*|\*\/|\\\|\;)", "", $val));
if($k_P=='') {Alert("잘못된 접근입니다");Hist();exit;}
include './MI.php';
$DB= new MI;
$DB->Conn();

if($_SESSION['member_srl']!=''){
	$DBX = new MI;
	$DBX->Conn('xedb','xedb','gkahslzk!$(');
	## 회원정보
	$sql = "select group_srl from xe_member_group_member where member_srl='".$_SESSION['member_srl']."'";
	$levv = $DBX->KRow($sql);
	if($levv['Count'] > 0) for($i=0;$i<$levv['Count'];$i++) $lev[]=$levv[$i][0];
#print_r($lev);
	$writer = array_search(149, $lev);
	$commiter = array_search(148, $lev);
	$_SESSION['writer']=$writer;
	$_SESSION['commiter']=$commiter;
}

## 페이징 계산
define(_PS, ($_GET['_PS'])?$_GET['_PS']:30);
define(_PB, ($_GET['_PB'])?$_GET['_PB']:5);
## 현재 페이지
define(_N, ($_GET['_N'] > 1) ? $_GET['_N'] : 1);
## 현재 페이지 내 첫번째 레코드 구하기
define(_F, (_N) ? (_N - 1)*_PS : 0);
## 전달값
define(_L, '_PB='._PB.'&_PS='._PS.'&c='.$k_c.'&_N='._N.'&Colm='.$k_Colm.'&Query='.$k_Query.'&P='.$k_P);

if($k_Query!='' && $k_Colm!='') $where = " and h.".$k_Colm." like '%".$k_Query."%'";
if($k_Type!='') $where.=" and h.msgstr=''";

$sql = "select h.ano from htrpass1 as h where 1=1 and h.msgid<>'' and h.project='".$k_P."'".$where."";
$r1 = $DB->NRow($sql);
$cnt = $r1;
$Number=($cnt - (_N - 1) * _PS);
$sql = "
select
	h.*, t.commiter, t.writer
from
	htrpass1 as h
left join
	transfer as t
on
	h.ano=t.ano and h.project=t.project and t.status='y'
where
	h.project='".$k_P."'".$where." and h.msgid<>''
group by
	h.ano
order by
	h.ano asc, h.wdate desc
 limit
 "._F.", "._PS;
$res = $DB->KAss($sql);

if($res['Count'] > 0)
{
	for($i=0;$i<$res['Count'];$i++)
	{
		$source = $S = $S1 = $S2 = '';
		$S2 = str_replace(chr(10), ' ', $res[$i]['msgline']);
		$S1 = explode(' ', $S2);

		$ii=0;
		foreach($S1 as $key => $val){
			if($ii>4) break;
			$source .= '<a href="javascript:;" target="_blank">'.str_replace('../', '/', $val).'</a>&nbsp;&nbsp;&nbsp;';
			$ii++;
		}

		$HTML .= '
	<tr align="center" style="border-top:2px solid #666666;">
		<td class="bs3-wrap"><button onclick="javascript:WRT('.$res[$i]['ano'].',\''.$k_P.'\');" id="B_'.$res[$i]['ano'].'" class="btn btn-info BVIEW">펼치기</button></td>
		<td align="left" style="line-height:20px;">
			<div style=""><ul style="border:0px solid;float:left;height:100%;">원 문 :&nbsp;</ul><ul class="bold F_00" style="float:left;width:90%;border:0px solid;word-break:break-all;">'.(($res[$i]['msgid']!='')?RE($res[$i]['msgid'],$k_Query,1):'&nbsp;').'</ul></div>
			<div style=""><ul style="border:0px solid;float:left;height:100%;">번 역 :&nbsp;</ul><ul class="bold F_00" style="float:left;width:90%;border:0px solid;word-break:break-all;color:#ff6600;height:100%;">'.RE(SLASH($res[$i]['msgstr'],1),$k_Query,1).'</ul></div>
		</td>
		<td align="right" style="line-height:20px;"><span class="F_00">'.strlen($res[$i]['msgid']).' Bytes</span><br /><span class="F_00">'.strlen($res[$i]['msgstr']).' Bytes</span></td>
		<td style="line-height:20px;">'.substr($res[$i]['wdate'],0,10).'</td>
		<td style="line-height:20px;">'.$res[$i]['writer'].'</td>
	</tr>
	<tr>
		<td></td>
		<td colspan="4" style="">
			<div style="float:left;width:40px;">소 스 : </div><div style="width:95%;word-break:break-all;font-size:12px;font-family:verdana;float:left;">'.$source.'</div>
		</td>
	</tr>
		';
		if($_SESSION['member_srl']!=''){
			$HTML .= '
<form name="Iform_'.$res[$i]['ano'].'" method="post" action="proc.php" onsubmit="javascript:return SBM('.$res[$i]['ano'].');">
<input type="hidden" name="Kdiv" value="tran_write" />
<input type="hidden" name="A" value="'.$res[$i]['ano'].'" />
<input type="hidden" name="P" value="'.$k_P.'" />
	<tr>
		<td colspan="5" style="vertical-align:middle;padding-left:70px;"><span style="float:left;padding-top:6px;">번역등록 :&nbsp;</span><span style="vertical-align:middle;float:left;" class="btn-lg-3"><input type="text" name="msg" class="form-control btn-lg-3" style="vertical-align:middle;width:'.(strlen($res[$i]['msgid'])*10).'px;height:28px;max-width:600px;" onkeyup="javascript:Byte(this, '.(strlen($res[$i]['msgid'])*2).', \'ano_'.$res[$i]['ano'].'\');" /></span><span style="vertical-align:middle;padding-left:10px;float:left;"><button class="btn-success btn btn-sm" style="vertical-align:middle;">등록</button></span><span id="ano_'.$res[$i]['ano'].'" style="padding-top:6px;padding-left:10px;float:left;width:50px;text-align:right;">0</span> <span style="padding-top:6px;padding-left:10px;float:left;">bytes</span></td>
	</tr>
</form>
			';
		}

		$HTML .= '
	<tr id="DV_'.$res[$i]['ano'].'" style="display:none;" class="DVVIEW">
	</tr>
		';
#		$cnt2 += $res[$i]['cnt'];
	}
}

# 총 참여자
$sql = "select count(distinct(writer)) from transfer where project='".$k_P."'";
$man = $DB->FRow($sql);

#진척율
$sql = "select count(h.ano) from htrpass1 as h where h.msgid<>'' and h.project='".$k_P."'";
$tot = $DB->FRow($sql);
$sql = "select count(h.ano) from htrpass1 as h where h.project='".$k_P."' and h.msgid<>'' and h.msgstr<>''";
$ing = $DB->FRow($sql);

$sql = "select count(distinct(project)) as cntP, count(ano) as cntA from htrpass1 where left(wdate,10)='".date("Y-m-d")."' and project='".$k_P."'";
$tod = $DB->FRow($sql);

$SEARCH = '
<form name="Sform" method="get" action="">
<table class="table">
	<tr height="28">
		<td align="left">
			<span class="btn-lg-3" style="float:left;padding-right:10px;">
				<a class="btn btn-info" href="pack_bs.php?P='.$k_P.'">전체 리스트</a>
				<a class="btn btn-warning" href="pack_bs.php?Type=no&P='.$k_P.'">미번역</a>
			</span>
			<span class="btn-lg-3" style="float:left;padding-right:10px;">
				<select name="Colm" class="form-control" style="vertical-align:middle;">
					<option value="">== 검색옵션</option>
					<option value="project"'.Sele($k_Colm, "project",1).'>패키지명</option>
					<option value="msgid"'.Sele($k_Colm, "msgid",1).'>원문내용</option>
					<option value="msgstr"'.Sele($k_Colm, "msgstr",1).'>번역내용</option>
				</select>
			</span>
			<span class="btn-lg-3" style="float:left;padding-right:10px;"><input type="text" class="form-control" size="20" name="Query" value="'.URLDecode($k_Query).'" style="vertical-align:middle;" /></span>
			<span class="btn-lg-3" style="float:left;padding-right:10px;"><select name="_PS" class="form-control" onchange="javascript:document.Sform.submit();" style="vertical-align:middle;">
				<option value="10"'.Sele(_PS,'10',1).'>10개 보기</option>
				<option value="20"'.Sele(_PS,'20',1).'>20개 보기</option>
				<option value="30"'.Sele(_PS,'30',1).'>30개 보기</option>
				<option value="50"'.Sele(_PS,'50',1).'>50개 보기</option>
				<option value="100"'.Sele(_PS,'100',1).'>100개 보기</option>
				<option value="200"'.Sele(_PS,'200',1).'>200개 보기</option>
			</select></span>
			<input type="image" src="/images/icon/icon-search.png" style="vertical-align:middle;margin:0px;" title="검색" />
		</td>
		<td align="right">'.Paging(_N, $cnt, _L, _PS, _PB).'</td>
	</tr></table>
</form>
';
?>
<script>
var $ = jQuery;
</script>
<script language="javascript">
<!--
function Byte(O, M, I){var B=B2=0;var V=$(O).val();var L="";for(i=0;i<V.length;i++){B2=(escape(V.charAt(i)).length>4)?2:1;B+=B2;}if(B>M){alert(M+" bytes를 넘을 수 없습니다");$(O).val($(O).val().substr(0,V.length-B2));B=M-B2;}$("#"+I).html(B);}
function DFAlert(Colm,Frm){if (eval("document."+Frm+"."+Colm).value==""){eval("document."+Frm+"."+Colm).style.backgroundColor="#e8e8e8";eval("document."+Frm+"."+Colm).value="";eval("document."+Frm+"."+Colm).focus();return false;}}
function SBM(A){if(DFAlert("msg", "Iform_"+A)==false) return false;}
function CONFIRM(T){if(confirm("선택하신 번역내용을 최종 번역으로 승인하시겠습니까?")){document.Cform.T.value=T;document.Cform.submit();}}
function VOTE(T){if(confirm("이 번역내용을 좋은 번역으로 추천합니다.\n\n한번 추천하신 번역내용은 더 이상 추천할 수 없습니다.")){document.Cform.Kdiv.value="vote";document.Cform.T.value=T;document.Cform.submit();}}
function WRT(AA, PP){if($("#DV_"+AA).css("display")=='none') {$(".DVVIEW").hide();var URL = "get_data<?=_LINK_;?>.php?A="+AA+"&P="+PP;var xmlhttp = null;if(window.XMLHttpRequest) {xmlhttp = new XMLHttpRequest();} else {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}xmlhttp.open('GET', URL, true);xmlhttp.onreadystatechange = function() {if(xmlhttp.readyState==4 && xmlhttp.status == 200 && xmlhttp.statusText=='OK') {var ret = xmlhttp.responseText;$("#DV_"+AA).html(ret);$("#DV_"+AA).show(700);}}
xmlhttp.send('');
$(".BVIEW").removeClass("btn-warning");$(".BVIEW").html('펼쳐기');$(".BVIEW").addClass("btn-info");$("#B_"+AA).html('접&nbsp;&nbsp;기');$("#B_"+AA).removeClass('btn-info');$("#B_"+AA).addClass('btn-warning');}else{$(".BVIEW").removeClass("btn-warning");$("#B_"+AA).html('펼쳐기');$("#B_"+AA).addClass("btn-info");$("#DV_"+AA).hide();}}
//-->
</script>


<div id="wrap" class="bs3-wrap">
	<div id="left_menu">
		<ul id="menu">

				<ul>
					<p>
					<span style="padding-bottom:5px;"><a href="korean<?=_LINK_;?>.php" class="btn btn-success">패키지별 한글화</a></span>
					<span><a href="javascript:;" class="btn btn-default bgn-lg-3">데스크탑 한글화</a></span>
					</p>
				</ul>
				<ul>
<table width="100%">
	<tr><td style="padding-left:10px;">- 개별 프로젝트명 : Linux-Mint-17-Quana-Mate-64bit &gt; <span class="bold" style="color:green;"><?=RE($k_P,$k_Query);?></span></td></tr>
	<tr><td style="padding-left:15px;">&nbsp;한글화 진척율 : <?=number_format($ing[0]/$tot[0]*100, 2);?>%  (총 <?=number_format($tot[0]);?> 건 중 <?=number_format($ing[0]);?> 건 완료)</td></tr>
	<tr><td style="padding-left:20px;"><div style="width:100%;background-color:#a5a5a5;height:17px;border:1px solid #000000;"><span style="float:left;width:<?=round($ing[0]/$tot[0]*100,1);?>%;height:15px;background-color:#9966ff;color:white;padding-left:10px;"><small><?=round($ing[0]/$tot[0]*100,2);?> %</small></span></div></td></tr>
	<tr><td style="padding-left:15px;">&nbsp;총 참여자 : <span style="color:#ff0000;" class="bold"><?=number_format($man[0]);?></span> 명</td></tr>
	<tr><td style="padding-left:15px;">&nbsp;금일 수정 항목 : <?=number_format($tod[0]);?>개 프로젝트 총 <?=number_format($tod[1]);?>건</td></tr>
</table>
<h5 style="padding-top:30px;">ㅇ 패키지 "<?=$k_P;?>"의 총 항목 수 : <?=number_format($cnt);?> 개</h5>

<?=$SEARCH;?>

<table class="table table-striped table-hover bs3-wrap" style="width:100%;table-layout:fixed;">
	<col width="80" />
	<col width="" />
	<col width="100" />
	<col width="100" />
	<col width="100" />
	<tbody height="40" align="center" style="font-weight:bold;vertical-align:middle;" valign="center">
		<td colspan="2">원문 / 번역(최종)</td>
		<td>길이</td>
		<td>최종번역일</td>
		<td>작성자</td>
	</tbody>
<?=$HTML;?>
</table>

<?=$SEARCH;?>

				</ul>
		</ul>
	</div>

</div>













<?
include 'kka_footer.php';
?>
