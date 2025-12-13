<?
include 'hmnk_header.php';
?>


    <!--BREADCRUMBS WRAPPER STARTS-->
    <div id="breadcrumbs-wrapper">
    	<!--BREADCRUMBS CONTAINER STARTS-->
        <div id="breadcrumbs-container" class="container">
                <!--BREADCRUMBS STARTS-->
            <p class="breadcrumbs"><a href="#"><img src="/layouts/TM_Tiva/images/home.png" /></a><span class="breadcrumb-arrow"></span>
                            <a  href="<?=_DIR_;?>/korean_bs.php">한글화 작업</a>  <span class="breadcrumb-arrow"></span>
                    <span>
                    <a href="<?=_DIR_;?>/my_list.php"> 내가 작성한 글</span>                 </a>
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

<script type="text/javascript">
<!--
function Del(T){if(confirm("삭제하시겠습니까?")){document.location.href="proc.php?Kdiv=tran_del&tid="+T;}}
//-->
</script>
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

$where ='';
//if($k_Query!='' && $k_Colm!='') $where = " and ".$k_Colm." like '%".$k_Query."%'";
//elseif($k_Query!='' && $k_Colm=='') $where = " and (t.project like '%".$k_Query."%' or t.msgstr like '%".$k_Query."%' or h.msgcommit like '%".$k_Query."%')";
$where = ($k_Query!='') ? " and (h.project like '%".$k_Query."%' or h.msgid like '%".$k_Query."%' or h.msgstr like '%".$k_Query."%' or h.msgcommit like '%".$k_Query."%')":"";


$SEARCH = '
<form name="Sform" method="get" action="">
<table class="table">
	<tr height="28">
		<td align="left">
			<span class="btn-lg-3" style="float:left;padding-right:10px;">
				<select name="Colm" class="form-control" style="vertical-align:middle;">
					<option value="">전체</option>
					<option value="t.project"'.Sele($k_Colm, "t.project",1).'>패키지명</option>
					<option value="h.msgid"'.Sele($k_Colm, "h.msgid",1).'>원문내용</option>
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



$sql = "select count(t.t_idx) from transfer as t, htrpass1 as h where t.writer='".$member['user_id']."'".$where." and t.ano>0 and t.project=h.project and h.ano=t.ano group by t.t_idx";
//$sql = "select count(t.t_idx) from transfer as t, htrpass1 as h where t.writer='".$member['user_id']."'".$where." and t.ano>0 and t.project=h.project group by t.t_idx";
$r1 = $DB->NRow($sql);
$cnt = $r1;
$Number=($cnt - (_N - 1) * _PS);
//select h.*, count(h.project) as cnt1, if(h.msgid='', (select count(h2.ano) from htrpass1 as h2 where h2.project=h.project and h.msgid!='' and h2.ano=h.ano), '') as cnt2 from htrpass1 as h group by h.project order by h.wdate desc
/*$sql = "
select
	t.*, h.project, h.msgid
from
	transfer as t, htrpass1 as h
where
	t.writer='".$member['user_id']."' and t.ano>0".$where." and h.project=t.project and h.ano=t.ano
group by
	t.t_idx, t.status, t.vote desc
";*/
$sql = "
select
	t.*, h.project, h.msgid
from
	transfer as t, htrpass1 as h
where
	t.writer='".$member['user_id']."' and t.ano>0".$where." and h.project=t.project and h.ano=t.ano
group by
	t.t_idx, t.status, t.vote desc
";
$res = $DB->KAss($sql);
#	h.ano=h2.ano and h2.msgid!='' and h2.msgid!='<no value>'
//if($_SERVER['REMOTE_ADDR']=='211.58.153.75') echo $sql;
# HTML

if($res['Count'] > 0)
{
	for($i=0;$i<$res['Count'];$i++)
	{
		$HTML .= '
	<tr align="center" style="border-top:2px solid #666666;">
		<td class="bs3-wrap">'.RE($res[$i]['project'], $k_Query,1).'</td>
		<td align="left" style="line-height:20px;">
			<div style="clear:both;"><ul style="border:0px solid;float:left;height:100%;">원 문 :&nbsp;</ul><ul class="bold F_00" style="float:left;width:80%;border:0px solid;word-break:break-all;">'.(($res[$i]['msgid']!=false)?RE(htmlspecialchars($res[$i]['msgid']),$k_Query,1):'&nbsp;').'</ul></div>
			<div style="clear:both;"><ul style="border:0px solid;float:left;height:100%;">번 역 :&nbsp;</ul><ul class="bold F_00" style="float:left;width:80%;border:0px solid;word-break:break-all;color:#ff6600;height:100%;">'.RE(htmlspecialchars(SLASH($res[$i]['msgstr'],1)),$k_Query,1).'</ul></div>
		</td>
		<td align="right" style="line-height:20px;"><span class="F_00">'.strlen($res[$i]['msgid']).' Bytes</span><br /><span class="F_00">'.strlen($res[$i]['msgstr']).' Bytes</span></td>
		<td>'.$res[$i]['regdate'].'</td>
		<td style="color:red;font-size:15px;">'.(($res[$i]['status']=='y')?'O':'X').'</td>
		<td>'.number_format($res[$i]['vote']).'</td>
		<td style="line-height:23px;">';
if($res[$i]['status']=='n' && $res[$i]['vote']=='0') $HTML .= '<a href="javascript:Edit(\''.$res[$i]['t_idx'].'\');" class="btn btn-info btn-xs">수정</a><br /><a href="javascript:Del(\''.$res[$i]['t_idx'].'\');" class="btn btn-danger btn-xs">삭제</a>';
else $HTML .= '<a href="javascript:;" class="jqtooltip" title="내가 쓴 글 또는 추천이 있거나|최종 승인된 글입니다"><u>X</u></a>';
		$HTML .= '
		</td>
	</tr>
		';
	}
}
?>

<div id="wrap" class="bs3-wrap" style="padding-top:30px;">
	<div id="left_menu">
		<ul id="menu">
				<ul>
					<p>
					<span style="padding-bottom:5px;"><a href="korean<?=_LINK_;?>.php" class="btn btn-success btn-lg-3">패키지별 한글화</a></span>
					<span><a href="desktop<?=_LINK_;?>.php" class="btn btn-default bgn-lg-3">데스크탑 한글화</a></span>
					</p>
				</ul>
				<ul style="">
<h5 style="padding-top:30px;">ㅇ 내가 작성한 글 : <?=number_format($cnt);?> 개</h5>

<?=$SEARCH;?>

<table class="table table-striped table-hover">
	<col width="" />
	<col width="" />
	<col width="100" />
	<col width="100" />
	<col width="70" />
	<col width="60" />
	<col width="60" />
	<tbody height="40" align="center" style="font-weight:bold;vertical-align:middle;" bgcolor="#f7f7f7">
		<td>패키지명</td>
		<td>원문</td>
		<td>길이</td>
		<td>작성일시</td>
		<td>승인여부</td>
		<td>추천수</td>
		<td>관리</td>
	</tbody>
<?=$HTML;?>
</table>
<br />

<?=$SEARCH;?>

				</ul>
		</ul>
	</div>
</div>


<div id="dialog" title="내가 쓴 글" style="display:none;">
<iframe width="100%" height="100%" border="5" frameborder="0" scrolling="yes" id="FRM"></iframe>
</div>

<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.js?0000010016"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/ui-lightness/jquery-ui.css" rel="stylesheet" />

<script>
$( "#dialog" ).dialog({autoOpen: false,width: $(window).width()-200,height:$(window).height()-200,buttons: [{text: "창닫기",click: function() {$("#FRM").attr('src','about:blank');$( this ).dialog( "close" );}}]});
</script>

<script>
$('.jqtooltip').tooltip({
	content: function(callback) {
		callback($(this).prop('title').replace('|', '<br />'));
	}
});
</script>



<?
include 'hmnk_footer.php';
?>
