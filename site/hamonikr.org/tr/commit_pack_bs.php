<?php
/**
 * commit_pack_bs.php
 * This file acts as the "backend controller" to your application. You can
 * get git clone source using this.
 * PHP error_reporting level may also be changed.
 *
 * @see http://hamonikr.org
 *
 * The MIT License (MIT)
 *
 * Copyright (c) <hamonikr@gmail.com> <HamoniKR.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

include 'hmnk_header.php';

if($k_P=='') {Alert("잘못된 접근입니다");Hist();exit;}
?>

    <!--BREADCRUMBS WRAPPER STARTS-->
    <div id="breadcrumbs-wrapper">
    	<!--BREADCRUMBS CONTAINER STARTS-->
        <div id="breadcrumbs-container" class="container">
                <!--BREADCRUMBS STARTS-->
            <p class="breadcrumbs"><a href="#"><img src="/layouts/TM_Tiva/images/home.png" /></a><span class="breadcrumb-arrow"></span>
                            <a  href="<?=_DIR_;?>/commiter.php">커미터</a>
                        </p>
            <!--BREADCRUMBS ENDS-->
        </div>
        <!--BREADCRUMBS CONTAINER ENDS-->
    </div>    <!--BREADCRUMBS WRAPPER ENDS-->



    <div id="inner-page-wrapper">

        <!--INNER PAGE CONTAINER STARTS-->
    	<div id="inner-page-container" class="container ">

             <!-- s : rQuick Menu layer -->
                    <!-- e : rQuick Menu layer -->

          <!-- s : lQuick Menu layer -->
                    <!-- e : lQuick Menu layer -->

 <!-- ///////////// SIDEBAR CONTENT //////////// -->
            <!--SIDEBAR STARTS-->

                        <!--SIDEBAR ENDS-->


<style>
.F_00 {color:#000000;}
.bold {font-weight:bold;}
</style>



<?php
## 페이징 계산
define(_PS, ($_GET['_PS'])?$_GET['_PS']:30);
define(_PB, ($_GET['_PB'])?$_GET['_PB']:5);
## 현재 페이지
define(_N, ($_GET['_N'] > 1) ? $_GET['_N'] : 1);
## 현재 페이지 내 첫번째 레코드 구하기
define(_F, (_N) ? (_N - 1)*_PS : 0);
## 전달값
define(_L, '_PB='._PB.'&_PS='._PS.'&c='.$k_c.'&Colm='.$k_Colm.'&Query='.$k_Query.'&P='.$k_P.'&Type='.$k_Type);

$where ='';
//if($k_Query!='' && $k_Colm!='') $where = " and h.".$k_Colm." like '%".$k_Query."%'";
//elseif($k_Query!='' && $k_Colm=='') $where = " and (h.project like '%".$k_Query."%' or h.msgid like '%".$k_Query."%' or h.msgcommit like '%".$k_Query."%')";
//$where = " and (h.project like '%".$k_Query."%' or h.msgid like '%".$k_Query."%' or h.msgstr like '%".$k_Query."%' or h.msgcommit like '%".$k_Query."%')";
if($k_Type!='') $Twhere.=" and h.msgcommit='' and h.msgstr=''";

if($k_D=='t') {
	$where .= " and left(t.regdate,10)='".date("Y-m-d")."'";
}elseif($k_D=='w'){
	$where .= " and t.regdate  > date_add(now(),interval -7 day)";
}

$sql = "select h.ano from htrpass1 as h, transfer as t where 1=1 and h.msgid<>'' and h.ano=t.ano and h.project=t.project and t.page='K' and h.project='".$k_P."'".$Twhere.$where." and t.status='n' group by h.ano";
$r1 = $DB->NRow($sql);
$cnt = $r1;
$Number=($cnt - (_N - 1) * _PS);
$sql = "
select
	h.*, t.commiter, t.writer
from
	htrpass1 as h, transfer as t
where
	h.project='".$k_P."'".$Twhere." and h.msgid<>'' and 	h.ano=t.ano and h.project=t.project and t.status='n' and t.page='K'".$where."
group by
	h.ano
order by
	h.ano asc, h.wdate desc
 limit
 "._F.", "._PS;
if($_SERVER['REMOTE_ADDR']=='211.58.153.75') echo $sql;
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
			$srcfilename_and_pos = str_replace('../', '/', $val);
			$source .= '<a href="javascript:;" target="_blank">'.$srcfilename_and_pos.'</a>&nbsp;&nbsp;&nbsp;';
			$ii++;
//			$source .= '<a href="javascript:;" target="_blank">'.$val.'</a>&nbsp;&nbsp;&nbsp;';
		}

		$arSRCpos = explode(":",$srcfilename_and_pos);

		$HTML .= '
	<tr align="center" style="border-top:2px solid #666666;">
		<td class="bs3-wrap"><button onclick="javascript:WRT('.$res[$i]['ano'].',\''.$k_P.'\');" id="B_'.$res[$i]['ano'].'" class="btn btn-info BVIEW">펼치기</button></td>
		<td align="left" style="line-height:20px;">
			<div style=""><ul style="border:0px solid;float:left;height:100%;">원 문 :&nbsp;</ul><ul class="bold F_00" style="float:left;width:90%;border:0px solid;word-break:break-all;">'.(($res[$i]['msgid']!='')?RE($res[$i]['msgid'],$k_Query,1):'&nbsp;').'</ul></div>
			<!--div style=""><ul style="border:0px solid;float:left;height:100%;">번 역 :&nbsp;</ul><ul class="bold F_00" style="float:left;width:90%;border:0px solid;word-break:break-all;color:#ff6600;height:100%;">'.RE(SLASH($res[$i]['msgstr'],1),$k_Query,1).'</ul></div//-->
			<div style=""><ul style="border:0px solid;float:left;height:100%;">번 역 :&nbsp;</ul><ul class="bold F_00" style="float:left;width:90%;border:0px solid;word-break:break-all;color:#ff6600;height:100%;">'.RE(SLASH(($res[$i]['msgcommit']!='')?$res[$i]['msgcommit']:$res[$i]['msgstr'],1),$k_Query,1).'</ul></div>
		</td>
		<td align="right" style="line-height:20px;"><span class="F_00">'.strlen($res[$i]['msgid']).' Bytes</span><br /><span class="F_00">'.strlen(SLASH(($res[$i]['msgcommit']!='')?$res[$i]['msgcommit']:$res[$i]['msgstr'],1)).' Bytes</span></td>
		<td style="line-height:20px;">'.substr($res[$i]['wdate'],0,10).'</td>
		<td style="line-height:20px;"><br>'.$res[$i]['writer'].'</td>
	</tr>
	<tr>
		<td></td>
		<td colspan="4" style="">
			<div style="float:left;width:40px;">소 스 : </div><div style="width:95%;word-break:break-all;font-size:12px;font-family:verdana;float:left;">'.$source.' |
			<a href=JavaScript:newPopup("http://git.hamonikr.org:3000/redmine/projects/'.$k_P.'/repository/revisions/master/entry/'.$arSRCpos[0].'#L'.$arSRCpos[1].'")>소스보기</a> </div>
		</td>
	</tr>
		';
		if($_SESSION['member_srl']!=''){
			$HTML .= '
<!--form name="Iform_'.$res[$i]['ano'].'" method="post" action="proc.php" onsubmit="javascript:return SBM('.$res[$i]['ano'].');"//-->
<form name="Iform_'.$res[$i]['ano'].'" id="Iform_'.$res[$i]['ano'].'" method="post" action="javascript:;">
<input type="hidden" name="Kdiv" value="tran_write" />
<input type="hidden" name="A" value="'.$res[$i]['ano'].'" />
<input type="hidden" name="P" value="'.$k_P.'" />
	<tr>
		<td colspan="5" style="vertical-align:middle;padding-left:70px;"><span style="float:left;padding-top:6px;">번역등록 :&nbsp;</span><span style="vertical-align:middle;float:left;" class="btn-lg-3"><input type="text" id="msg_'.$res[$i]['ano'].'" name="msg_'.$res[$i]['ano'].'" class="form-control btn-lg-3" style="vertical-align:middle;width:'.(strlen($res[$i]['msgid'])*10).'px;height:28px;max-width:600px;" onkeyup="javascript:Byte(this, '.(strlen($res[$i]['msgid'])*2).', \'ano_'.$res[$i]['ano'].'\');" /></span><span style="vertical-align:middle;padding-left:10px;float:left;"><a href="javascript:;" onclick="javascript:Inst(\''.$res[$i]['ano'].'\', \''.$k_P.'\');" class="btn-success btn btn-sm" style="vertical-align:middle;">등록</a></span><span id="ano_'.$res[$i]['ano'].'" style="padding-top:6px;padding-left:10px;float:left;width:50px;text-align:right;">0</span> <span style="padding-top:6px;padding-left:10px;float:left;">bytes</span></td>
	</tr>
</form>
			';
		}

		$HTML .= '
	<tr id="DV_'.$res[$i]['ano'].'" style="display:none;" class="DVVIEW">
	</tr>
<script>WRT('.$res[$i]['ano'].',\''.$k_P.'\');</script>
		';
#		$cnt2 += $res[$i]['cnt'];
	}
}else{
	$HTML = '<tr><td colspan="5" height="100" align="center">등록된 내용이 없습니다</td></tr>';
}
/*
# 총 참여자
$sql = "select count(distinct(writer)) from transfer where project='".$k_P."' and page='K'";
$man = $DB->FRow($sql);

#진척율
$sql = "select count(h.ano) from htrpass1 as h, transfer as t where h.msgid<>'' and h.ano=t.ano and h.project=t.project and t.page='K' and h.project='".$k_P."'".$Twhere.$where;
$tot = $DB->FRow($sql);
$sql = "select count(h.ano) from htrpass1 as h, transfer as t where h.project='".$k_P."' and h.ano=t.ano and h.project=t.project and t.page='K' and (h.msgstr<>'' or h.msgcommit is not null) and h.msgid<>''".$Twhere.$where;
$ing = $DB->FRow($sql);

$sql = "select count(distinct(h.project)) as cntP, count(h.ano) as cntA from htrpass1 as h, transfer as t where left(t.regdate,10)='".date("Y-m-d")."' and h.ano=t.ano and h.project=t.project and t.page='K' and h.project='".$k_P."'";
$tod = $DB->FRow($sql);*/

$SEARCH = '
<form name="Sform" method="get" action="">
<input type="hidden" name="A" value="'.$res[$i]['ano'].'" />
<input type="hidden" name="P" value="'.$k_P.'" />
<input type="hidden" name="Type" value="'.$k_Type.'" />
<table class="table">
	<tr height="28">
		<td align="left">
			<span class="btn-lg-3" style="float:left;padding-right:10px;">
				<a class="btn btn-'.(($k_D==''&&$k_Type=='')?'info':'warning').'" href="'.$_SERVER['PHP_SELF'].'?P='.$k_P.'">전체 리스트</a>
				<a class="btn btn-'.(($k_D=='w')?'info':'warning').'" href="'.$_SERVER['PHP_SELF'].'?P='.$k_P.'&D=w">금주 번역등록</a>
				<a class="btn btn-'.(($k_D=='t')?'info':'warning').'" href="'.$_SERVER['PHP_SELF'].'?P='.$k_P.'&D=t">오늘 번역등록</a>
				<a class="btn btn-'.(($k_Type=='no')?'info':'warning').'" href="'.$_SERVER['PHP_SELF'].'?Type=no&P='.$k_P.'">미번역</a>
			</span>
			<!--span class="btn-lg-3" style="float:left;padding-right:10px;">
				<select name="Colm" class="form-control" style="vertical-align:middle;">
					<option value="">전체</option>
					<option value="project"'.Sele($k_Colm, "project",1).'>패키지명</option>
					<option value="msgid"'.Sele($k_Colm, "msgid",1).'>원문내용</option>
					<option value="msgcommit"'.Sele($k_Colm, "msgcommit",1).'>번역내용</option>
				</select>
			</span>
			<span class="btn-lg-3" style="float:left;padding-right:10px;"><input type="text" class="form-control" size="20" name="Query" value="'.URLDecode($k_Query).'" style="vertical-align:middle;" /></span>
			<span class="btn-lg-3" style="float:left;padding-right:10px;"><select name="_PS" class="form-control" style="vertical-align:middle;">
				<option value="10"'.Sele(_PS,'10',1).'>10개 보기</option>
				<option value="20"'.Sele(_PS,'20',1).'>20개 보기</option>
				<option value="30"'.Sele(_PS,'30',1).'>30개 보기</option>
				<option value="50"'.Sele(_PS,'50',1).'>50개 보기</option>
				<option value="100"'.Sele(_PS,'100',1).'>100개 보기</option>
				<option value="200"'.Sele(_PS,'200',1).'>200개 보기</option>
			</select></span//-->
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
function WRT(AA, PP){var xetime = new Date();if($("#DV_"+AA).css("display")=='none') {var URL = "get_data<?=_LINK_;?>.php?A="+AA+"&P="+PP+"&time="+(Math.random()*xetime.getTime());var xmlhttp = null;if(window.XMLHttpRequest) {xmlhttp = new XMLHttpRequest();} else {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}xmlhttp.open('GET', URL, true);xmlhttp.onreadystatechange = function() {if(xmlhttp.readyState==4 && xmlhttp.status == 200 && xmlhttp.statusText=='OK') {var ret = xmlhttp.responseText;$("#DV_"+AA).html(ret);$("#DV_"+AA).show(700);}}
xmlhttp.send('');
$("#B_"+AA).html('접&nbsp;&nbsp;기');$("#B_"+AA).removeClass('btn-info');$("#B_"+AA).addClass('btn-warning');}else{$("#B_"+AA).html('펼치기');$("#B_"+AA).addClass("btn-info");$("#DV_"+AA).hide();}}
function Inst(A, P){if(DFAlert("msg_"+A, "Iform_"+A)==false) return;var Fdata = "A="+A+"&P="+P+"&C="+$("#msg_"+A).val()+"&Kdiv=tran_write";
$.ajax({url : "proc.php",type : "post",	data : Fdata,success : function(data){alert("등록되었습니다");var URL = "get_data<?=_LINK_;?>.php?A="+A+"&P="+P;var xmlhttp = null;if(window.XMLHttpRequest) {xmlhttp = new XMLHttpRequest();} else {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}xmlhttp.open('GET', URL, true);xmlhttp.onreadystatechange = function() {if(xmlhttp.readyState==4 && xmlhttp.status == 200 && xmlhttp.statusText=='OK') {var ret = xmlhttp.responseText;$("#DV_"+A).html(ret);$("#DV_"+A).show(100);}$("#msg_"+A).val("");}
xmlhttp.send('');
},error : function(){alert("등록중 오류가 발생하였습니다.\n\n다시 시도해 주세요");}});}
<?php
/*function WRT(AA, PP){if($("#DV_"+AA).css("display")=='none') {$(".DVVIEW").hide();var URL = "get_data<?=_LINK_;?>.php?A="+AA+"&P="+PP;var xmlhttp = null;if(window.XMLHttpRequest) {xmlhttp = new XMLHttpRequest();} else {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}xmlhttp.open('GET', URL, true);xmlhttp.onreadystatechange = function() {if(xmlhttp.readyState==4 && xmlhttp.status == 200 && xmlhttp.statusText=='OK') {var ret = xmlhttp.responseText;$("#DV_"+AA).html(ret);$("#DV_"+AA).show(700);}}
xmlhttp.send('');
$(".BVIEW").removeClass("btn-warning");$(".BVIEW").html('펼치기');$(".BVIEW").addClass("btn-info");$("#B_"+AA).html('접&nbsp;&nbsp;기');$("#B_"+AA).removeClass('btn-info');$("#B_"+AA).addClass('btn-warning');}else{$(".BVIEW").removeClass("btn-warning");$("#B_"+AA).html('펼치기');$("#B_"+AA).addClass("btn-info");$("#DV_"+AA).hide();}}
*/
?>
//-->

// Popup window code
function newPopup(url) {
popupWindow = window.open(
url,'popUpWindow','height=640,width=800,left=100,top=10,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes')
}
</script>

<div id="wrap" class="bs3-wrap" style="padding-top:30px">
	<div id="left_menu">
		<ul id="menu">

				<ul>
					<p>
					<span style="padding-bottom:5px;"><a href="korean<?=_LINK_;?>.php" class="btn btn-success">패키지별 한글화</a></span>
					<span><a href="desktop<?=_LINK_;?>.php" class="btn btn-default bgn-lg-3">데스크탑 한글화</a></span>
<?//if($_SESSION['group']=='commiter') echo '<span style="float:right;"><a class="btn btn-danger" href="javascript:;" id="dialog-commiter">Commiter</a></span>';?>
					</p>
				</ul>
				<ul>
<!--table width="100%">
	<tr>
		<td style="padding-left:10px;">- 개별 프로젝트명 : Linux-Mint-17-Quana-Mate-64bit &gt; <span class="bold" style="color:green;"><?=RE($k_P,$k_Query);?></span></td>
<?if($member['is_admin']=='Y'){?>
		<td rowspan="10" valign="top">
			<table class="table">
				<tr>
					<td>참여자 활동 :</td>
					<td>
						<table class="table table-striped table-hover">
<?php
$sql = "
select
	distinct(count(t_idx)) as cnt, writer
from
	transfer
where
	msgstr<>'' and page='K'
group by
	writer
 order by
	cnt desc
limit 5
";
$rank = $DB->KAss($sql);
if($rank['Count'] > 0)
{
	for($i=0;$i<$rank['Count'];$i++)
	{
?>
	<tr>
		<td><?=$rank[$i]['writer'];?> (<?=$rank[$i]['cnt'];?>) 건</td>
	</tr>
<?php
	}
}
?>
						</table>
					</td>
				</tr>
			</table>
		</td>
<?}?>
	</tr>
	<tr><td style="padding-left:15px;">&nbsp;한글화 진척율 : <?=number_format($ing[0]/$tot[0]*100, 2);?>%  (총 <?=number_format($tot[0]);?> 건 중 <?=number_format($ing[0]);?> 건 완료)</td></tr>
	<tr><td style="padding-left:20px;"><div style="width:100%;background-color:#a5a5a5;height:17px;border:1px solid #000000;"><span style="float:left;width:<?=round($ing[0]/$tot[0]*100,1);?>%;height:15px;background-color:#9966ff;color:white;padding-left:10px;"><small><?=round($ing[0]/$tot[0]*100,2);?> %</small></span></div></td></tr>
	<tr><td style="padding-left:15px;">&nbsp;총 참여자 : <span style="color:#ff0000;" class="bold"><?=number_format($man[0]);?></span> 명</td></tr>
	<tr><td style="padding-left:15px;">&nbsp;금일 수정 항목 : <?=number_format($tod[0]);?>개 프로젝트 총 <?=number_format($tod[1]);?>건</td></tr>
</table//-->
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

<div id="dialog" title="내가 쓴 글" style="display:none;">
<iframe width="100%" height="100%" border="5" frameborder="0" scrolling="yes" id="FRM"></iframe>
</div>

<script>
$( "#dialog" ).dialog({autoOpen: false,width: $(window).width()-200,height:$(window).height()-200,buttons: [{text: "창닫기",click: function() {$("#FRM").attr('src','about:blank');$( this ).dialog( "close" );}}]});
</script>



<?php
include 'hmnk_footer.php';
?>
