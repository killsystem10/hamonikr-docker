<?
include 'hmnk_header.php';
?>


    <!--BREADCRUMBS WRAPPER STARTS-->
    <div id="breadcrumbs-wrapper">
    	<!--BREADCRUMBS CONTAINER STARTS-->
        <div id="breadcrumbs-container" class="container">
                <!--BREADCRUMBS STARTS-->
            <p class="breadcrumbs"><a href="#"><img src="/layouts/TM_Tiva/images/home.png" /></a><span class="breadcrumb-arrow"></span>
                            <a  href="<?=_DIR_;?>/korean_bs.php">한글화 서비스</a>  <span class="breadcrumb-arrow"></span>
                    <span>
                    <a href="<?=_DIR_;?>/desktop_bs.php"> 데스크탑 한글화</span>                 </a>
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



<?
## 페이징 계산
define(_PS, ($_GET['_PS'])?$_GET['_PS']:30);
define(_PB, ($_GET['_PB'])?$_GET['_PB']:5);
## 현재 페이지
define(_N, ($_GET['_N'] > 1) ? $_GET['_N'] : 1);
## 현재 페이지 내 첫번째 레코드 구하기
define(_F, (_N) ? (_N - 1)*_PS : 0);
## 전달값
define(_L, '_PB='._PB.'&_PS='._PS.'&c='.$k_c.'&Colm='.$k_Colm.'&Query='.$k_Query.'&Type='.$k_Type);
## 정렬
define(_Ord, ($_GET['_Ord']!='')?$_GET['_Ord']:'h.project');
define(_Asc, ($_GET['_Asc']!='')?$_GET['_Asc']:'asc');

$where ='';
//if($k_Query!='' && $k_Colm!='') $where = " and h.".$k_Colm." like '%".$k_Query."%'";
//elseif($k_Query!='' && $k_Colm=='') $where = " and (h.project like '%".$k_Query."%' or h.msgid like '%".$k_Query."%' or h.msgstr like '%".$k_Query."%' or h.msgcommit like '%".$k_Query."%')";
$where = " and (h.project like '%".$k_Query."%' or h.msgid like '%".$k_Query."%' or h.msgstr like '%".$k_Query."%' or h.msgcommit like '%".$k_Query."%')";
if($k_Type!='') $Twhere=" and h.msgcommit is null and h.msgstr=''";

$sql = "select count(h.ano) from htrpass3 as h where 1=1 and h.msgid<>''".$where.$Twhere." group by h.project";
$r1 = $DB->NRow($sql);
$cnt = $r1;
$Number=($cnt - (_N - 1) * _PS);
//select h.*, count(h.project) as cnt1, if(h.msgid='', (select count(h2.ano) from htrpass3 as h2 where h2.project=h.project and h.msgid!='' and h2.ano=h.ano), '') as cnt2 from htrpass3 as h group by h.project order by h.wdate desc
$sql = "
select
	h.*, count(h1.project) as cnt1, count(h2.project) as cnt2, t.commiter, count(t2.t_idx) as tcnt
from
	htrpass3 as h
left join
	htrpass3 as h1
on
	h.ano=h1.ano and h.project=h1.project and h1.msgid<>''
left join
	htrpass3 as h2
on
	h.project=h2.project and h.ano=h2.ano and if(h2.msgstr='', h2.msgcommit<>'', 1=1) and h2.msgid<>''
left join
	transfer as t
on
	h.project=t.project and t.status='y' and t.ano=h.ano and t.page='D'
left join
	transfer as t2
on
	h.project=t.project and t.status='n' and t.ano=h.ano and t.page='D'
where
	1=1 and h.msgid<>''".$where.$Twhere."
group by
	h.project
order by
	"._Ord." "._Asc."
 limit
 "._F.", "._PS;
# echo $sql;
$res = $DB->KAss($sql);
#	h.ano=h2.ano and h2.msgid!='' and h2.msgid!='<no value>'


# 총 참여자
$sql = "select count(distinct(writer)) from transfer where page='D'";
$man = $DB->FRow($sql);
# 오늘 작업
$sql = "select count(distinct(project)) as cntP, count(ano) as cntA from htrpass3 where left(wdate,10)='".date("Y-m-d")."'";
$tod = $DB->FRow($sql);

# 진척율
$sql = "select count(h.ano) from htrpass3 as h where h.msgid<>''".$Twhere;
$tot = $DB->FRow($sql);
$sql = "select count(h.ano) from htrpass3 as h where h.msgid<>'' and h.msgstr<>''".$Twhere;
$ing = $DB->FRow($sql);

# HTML
if($res['Count'] > 0)
{
	for($i=0;$i<$res['Count'];$i++)
	{
		$HTML .= '
	<tr style="height:28px;text-align:center;">
		<td align="left" style="padding-left:10px;"><a href="desk_pack'._LINK_.'.php?P='.$res[$i]['project'].'&Query='.$k_Query.'&Colm='.$k_Colm.'&Type='.$k_Type.'">'.RE(SLASH($res[$i]['project'],1), $k_Query,1).'</a></td>
		<td>'.number_format($res[$i]['cnt1']).'</td>
		<td align="left" style="font-size:10px;vertical-align:middle;"><div style="border:1px solid #828282;height:17px;background-color:#a5a5a5;font-size:10px;color:#000000;margint:0px;padding:0px;vertical-align:top;"><span style="float:left;width:'.round($res[$i]['cnt2']/$res[$i]['cnt1']*100,1).'%;height:15px;background-color:#33cc00;"><small>'.round($res[$i]['cnt2']/$res[$i]['cnt1']*100,2).' %</small></span></div></td>
		<td>'.number_format($res[$i]['cnt2']).'</td>
		<td></td>
		<td>'.number_format($res[$i]['cnt1']-$res[$i]['cnt2']).'</td>
		<td>'.substr($res[$i]['wdate'],0,10).'</td>';
if($_SESSION['group']=='commiter') $HTML .= '<td>'.number_format($res[$i]['tcnt']).' 개</td>';
		$HTML .='
	</tr>
		';
#		$cnt1+=$res[$i]['cnt1'];
#		$cnt2+=$res[$i]['cnt2'];
	}
}


/*
	<tr height="28" align="center" bgcolor="#ffffff">
		<!--td><?=number_format($Number-($ii++));?></td//-->
		<td align="left" style="padding-left:10px;"><a href="pack<?=_LINK_;?>.php?P=<?=$res[$i]['project'];?>"><?=$res[$i]['project'];?></a></td>
		<td><?=number_format($res[$i]['cnt1']);?></td>
		<td align="left" style="font-size:10px;vertical-align:middle;"><div style="border:1px solid #828282;height:17px;background-color:#00cc00;font-size:10px;color:#000000;margint:0px;padding:0px;vertical-align:top;"><span style="float:left;width:<?=round($res[$i]['cnt2']/$res[$i]['cnt1']*100,1);?>%;height:15px;background-color:#ffcc33;"><small><?=round($res[$i]['cnt2']/$res[$i]['cnt1']*100,2);?> %</small></span></div></td>
		<td><?=number_format($res[$i]['cnt2']);?></td>
		<td></td>
		<!--td></td//-->
		<td><?=number_format($res[$i]['cnt1']-$res[$i]['cnt2']);?></td>
		<td><?=substr($res[$i]['wdate'],0,10);?></td>
		<td><?=$res[$i]['writer'];?></td>
	</tr>

*/
$SEARCH = '
<form name="Sform" method="get" action="">
<table class="table">
	<tr height="28">
		<td align="left">
			<span class="btn-lg-3" style="float:left;padding-right:10px;">
				<a class="btn btn-info" href="desktop_bs.php">전체 리스트</a>
				<a class="btn btn-warning" href="desktop_bs.php?Type=no">미번역</a>
			</span>
			<span class="btn-lg-3" style="float:left;padding-right:10px;">
				<select name="Colm" class="form-control" style="vertical-align:middle;">
					<option value="">전체</option>
					<option value="project"'.Sele($k_Colm, "project",1).'>패키지명</option>
					<option value="msgid"'.Sele($k_Colm, "msgid",1).'>원문내용</option>
					<option value="msgstr"'.Sele($k_Colm, "msgstr",1).'>번역내용</option>
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

<div id="wrap" class="bs3-wrap" style="padding-top:30px;">
	<div id="left_menu">
		<ul id="menu">
			<!--li><h2>한글화 진행현황</h2></li//-->
				<ul>
					<p>
					<span style="padding-bottom:5px;"><a href="korean<?=_LINK_;?>.php" class="btn btn-default btn-lg-3">패키지별 한글화</a></span>
					<span><a href="desktop<?=_LINK_;?>.php" class="btn btn-success bgn-lg-3">데스크탑 한글화</a></span>
<?if($_SESSION['group']=='commiter' || $_SESSION['group']=='all') echo '<span style="float:right;"><a href="'._DIR_.'/" class="btn btn-success">커미터 메뉴</a></span>';?>
<?if($_SESSION['group']=='writer' || $_SESSION['group']=='all') echo '<span style="float:right;"><a href="'._DIR_.'/my_list.php" class="btn btn-success">내가 작성한 글목록</a>&nbsp;</span>';?>
<?//if($_SESSION['group']=='commiter') echo '<span style="float:right;"><a class="btn btn-danger" href="javascript:;" id="dialog-commiter">Commiter</a></span>';?>
					</p>
					<!--li><a href="javascript:;" class="Butt_">메뉴별</a></li//-->
				</ul>
				<ul style="">
<table width="100%">
	<tr>
		<td style="padding-left:10px;">- 그룹 프로젝트명 : Linux-Mint-17-Quana-Mate-64bit</td>
<?if($member['is_admin']=='Y'){?>
		<td rowspan="10" valign="top">
			<table class="table">
				<tr>
					<td>참여자 활동 :</td>
					<td>
						<table class="table table-striped table-hover">
<?
$sql = "
select
	distinct(count(t_idx)) as cnt, writer
from
	transfer
where
	msgstr<>'' and page='D'
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
<?
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
	<tr><td style="padding-left:15px;">&nbsp;한글화 진척율 : <?=number_format($ing[0]/$tot[0]*100, 2);?>% (총 <?=number_format($tot[0]);?> 건 중 <?=number_format($ing[0]);?> 건 완료)</td></tr>
	<tr><td style="padding-left:20px;"><div style="width:100%;background-color:#a5a5a5;height:17px;border:1px solid #000000;"><span style="float:left;width:<?=round($ing[0]/$tot[0]*100,1);?>%;height:15px;background-color:#9966ff;color:white;padding-left:10px;"><small><?=round($ing[0]/$tot[0]*100,2);?> %</small></span></div></td></tr>
	<tr><td style="padding-left:15px;">&nbsp;총 참여자 : <span style="color:#ff0000;" class="bold"><?=number_format($man[0]);?></span> 명</td></tr>
	<tr><td style="padding-left:15px;">&nbsp;금일 수정 항목 : <?=number_format($tod[0]);?>개 프로젝트 총 <?=number_format($tod[1]);?>건</td></tr>
</table>
<h5 style="padding-top:30px;">ㅇ 총 패키지 수 : <?=number_format($cnt);?> 개</h5>

<?=$SEARCH;?>

<table class="table table-striped table-hover">
	<tbody height="40" align="center" style="font-weight:bold;vertical-align:middle;" bgcolor="#f7f7f7">
		<!--td>Num</td//-->
		<td>패키지명<?Order("h.project",_Asc,_Ord,_L);?></td>
		<td>총 항목수<?Order("cnt1",_Asc,_Ord,_L);?></td>
		<td width="100">번역율</td>
		<td>번역완료<?Order("cnt2",_Asc,_Ord,_L);?></td>
		<td>번역중<br />(검토중)</td>
		<!--td>번역개선중</td//-->
		<td>미번역</td>
		<td>최종번역일<?Order("wdate",_Asc,_Ord,_L);?></td>
<?if($_SESSION['group']=='commiter') echo '<td>신규 등록</td>';?>
	</tbody>
<?=$HTML;?>
</table>
<br />

<?=$SEARCH;?>

				</ul>
		</ul>
	</div>
</div>
<script>
<!--
function COMMIT(){$( "#dialog" ).dialog("option","width","800");}
//-->
  </script>

<!--div id="dialog" title="Commiter" style="display:none;">
<iframe width="100%" height="100%" border="5" frameborder="0" scrolling="yes" id="FRM"></iframe>
</div>

<script>
$( "#dialog-commiter" ).click(function( event ){$( "#dialog" ).dialog( "open" );event.preventDefault();$("#FRM").attr("src","<?=_DIR_;?>/commiter.php");});
$( "#dialog" ).dialog({autoOpen: false,width: $(window).width()-200,height:$(window).height()-200,buttons: [{text: "창닫기",click: function() {$( this ).dialog( "close" );}}]});
</script//-->

<?
include 'hmnk_footer.php';
?>
