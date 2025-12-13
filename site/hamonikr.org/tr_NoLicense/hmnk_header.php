<?
session_start();
define('__ZBXE__', true);
define('__XE__', true);
require_once('../config/config.inc.php');
$oContext = &Context::getInstance();
$oContext->init();

define(_V_, '00000001');
define(_LINK_, '_bs');
define(_DIR_, '/tr');
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
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<!-- META -->
<meta charset="utf-8">
<meta name="Generator" content="XpressEngine">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- TITLE -->
<title>HamoniKr | 하모니카 프로젝트</title>
<!-- CSS -->
<link rel="stylesheet" href="/common/css/xe.min.css?20141102212543" />
<link rel="stylesheet" href="/addons/oembed/jquery.oembed.min.css?20141102212528" />
<link rel="stylesheet" href="/common/js/plugins/ui/jquery-ui.min.css?20141102212544" />
<link rel="stylesheet" href="/layouts/TM_Tiva/css/reset.css?20130104101632" />
<link rel="stylesheet" href="/layouts/TM_Tiva/css/text.css?20130104101632" />
<link rel="stylesheet" href="/layouts/TM_Tiva/css/style.css?20141118165533" />
<link rel="stylesheet" href="/layouts/TM_Tiva/css/maximage.css?20130104101632" />
<link rel="stylesheet" href="/modules/editor/styles/default/style.css?20141102212612" />
<link rel="stylesheet" href="/addons/bootstrap3_css/bootstrap.min.css?20141103234159" />
<!-- JS -->
<!--[if lt IE 9]><script src="/common/js/jquery-1.x.min.js?20141102212543"></script>
<![endif]--><!--[if gte IE 9]><!--><script src="/common/js/jquery.min.js?20141102212543"></script>
<![endif]--><script src="/common/js/x.min.js?20141102212543"></script>
<script src="/common/js/xe.min.js?20141102212543"></script>
<script src="/layouts/TM_Tiva/js/modernizr-2.0.6.js?20141118202017"></script>
<script src="/layouts/TM_Tiva/js/jquery.quicksand.js?20130104101708"></script>
<script src="/layouts/TM_Tiva/js/jquery.prettyPhoto.js?20130104101708"></script>
<script src="/layouts/TM_Tiva/js/jquery.cycle.all.js?20130503084830"></script>
<script src="/layouts/TM_Tiva/js/jquery.roundabout.js?20130104101708"></script>
<!--script src="/layouts/TM_Tiva/js/jquery.easing.1.3.js?20130104101708"></script//-->
<script src="/layouts/TM_Tiva/js/jquery.twitter.js?20130104101708"></script>
<script src="/layouts/TM_Tiva/js/jquery.quovolver.js?20130104101708"></script>
<script src="/layouts/TM_Tiva/js/hoverIntent.js?20130104101708"></script>
<script src="/layouts/TM_Tiva/js/supersubs.js?20130104101708"></script>
<script src="/layouts/TM_Tiva/js/superfish.js?20130104101708"></script>
<script src="/layouts/TM_Tiva/js/custom.js?20130906145730"></script>
<!--script src="/layouts/TM_Tiva/js/jquery.maximage.js?20141118212723"></script//-->
<!--[if lt IE 9]><script src="/common/js/html5.js"></script><![endif]-->
<!-- RSS -->
<link rel="alternate" type="application/rss+xml" title="Site RSS" href="/index.php?module=rss&amp;act=rss" /><link rel="alternate" type="application/atom+xml" title="Site Atom" href="/index.php?module=rss&amp;act=atom" /><!-- ICON -->
<link rel="shortcut icon" href="files/attach/xeicon/favicon.ico" /><link rel="apple-touch-icon" href="./modules/admin/tpl/img/mobiconSample.png" />

<style> .xe_content { font-family:'Noto sans', '나눔 고딕', Verdana, Geneva, sans-serif;font-size:12px; }</style>
<style type="text/css">
#tiva-contents{
	/*background: url('layouts/TM_Tiva/images/office1_gallery_bg.jpg') no-repeat 50% 0;*/
	}

body{ background-image:none;}
#navigation-container { float:left; margin-left: -20px; margin-top:10px; height:65px; }
#header-logo { margin-top:15px; }
.sf-menu a {font-size:16px;  }
#header-wrapper{ height:132px;}
#header-container{ margin-top:9px;}
#header-social-icons { margin-top: 10px;}
.sf-menu li.sfHover ul {top: 50px;}
</style>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
<!--#Meta:layouts/TM_Tiva/css/reset.css--><!--#Meta:layouts/TM_Tiva/css/text.css--><!--#Meta:layouts/TM_Tiva/css/style.css--><!--#Meta:layouts/TM_Tiva/js/modernizr-2.0.6.js--><!--#Meta:layouts/TM_Tiva/js/jquery.quicksand.js--><!--#Meta:layouts/TM_Tiva/js/jquery.prettyPhoto.js--><!--#Meta:layouts/TM_Tiva/js/jquery.cycle.all.js--><!--#Meta:layouts/TM_Tiva/js/jquery.roundabout.js--><!--#Meta:layouts/TM_Tiva/js/jquery.easing.1.3.js--><!--#Meta:layouts/TM_Tiva/js/jquery.twitter.js--><!--#Meta:layouts/TM_Tiva/js/jquery.quovolver.js--><!--#Meta:layouts/TM_Tiva/js/hoverIntent.js--><!--#Meta:layouts/TM_Tiva/js/supersubs.js--><!--#Meta:layouts/TM_Tiva/js/superfish.js--><!--#Meta:layouts/TM_Tiva/js/custom.js--><!--#Meta:layouts/TM_Tiva/css/maximage.css--><!--#Meta:layouts/TM_Tiva/js/jquery.maximage.js--><!-- 동영상 배경화면 -->
<!-- 슬라이드 배경화면 -->
    <div id="maximage">
        <div class="first-item">
                    </div>
                            </div>
<script type="text/javascript" charset="utf-8">
/*			jQuery(function($){
				$('#maximage').maximage({
					cycleOptions: {
						fx:'scrollHorz',
						speed: 800,
						timeout: 8000,
						prev: '#arrow_left',
						next: '#arrow_right'
					},
					onFirstImageLoaded: function(){
						jQuery('#cycle-loader').hide();
						jQuery('#maximage').fadeIn('fast');
					}
				});
			});*/
</script>
<script language="JavaScript">
			function bookmarksite(title,url) {
			   // Internet Explorer
			   if(document.all)
			   {
				   window.external.AddFavorite(url, title);
			   }
			   // Google Chrome
			   else if(window.chrome){
				  alert("크롬브라우져 : Ctrl+D 를 사용하여 추가해 주시기 바랍니다.");
			   }
			   // Firefox
			   else if (window.sidebar)
			   {
				   window.sidebar.addPanel(title, url, "");
			   }
			}
</script>


<script language="javascript">
<!--
function Edit(T){$("#dialog").dialog("open");$("#FRM").attr("src","my_edit.php?T="+T);}
function DeskEdit(T){$("#dialog").dialog("open");$("#FRM").attr("src","desk_edit.php?T="+T);}
//-->
</script>







    <!--HEADER WRAPPER STARTS-->
    <div id="header-wrapper" >
    	<!--HEADER CONTAINER STARTS-->
        <div id="header-container" class="container">

            <!--TOP ELEMENTS STARTS-->
            <div id="top-elements">
            <div id="header-logo">
                <a href="/">
                                <img src="http://hamonikr.org/files/attach/images/194/28c4bef00c6940e04d363504b7ef8ab3.png" alt="logo" title="index.php" /><script>
//<![CDATA[
var current_url = "http://hamonikr.org<?=$_SERVER['PHP_SELF'];?>";
var request_uri = "http://hamonikr.org/";
var current_mid = "page_jBcr78";
var waiting_message = "서버에 요청 중입니다. 잠시만 기다려주세요.";
var ssl_actions = new Array();
var default_url = "http://hamonikr.org/";
xe.current_lang = "ko";
xe.cmd_find = "찾기";
xe.cmd_cancel = "취소";
xe.cmd_confirm = "확인";
xe.msg_no_root = "루트는 선택 할 수 없습니다.";
xe.msg_no_shortcut = "바로가기는 선택 할 수 없습니다.";
xe.msg_select_menu = "대상 메뉴 선택";
//]]>
</script>
</head>
<body>
<!--Google Fonts-->
</h1>
                                </a>
            </div>
                <div id="header-social-icons">
                    <ul>
                        <li><a href="#"><img src="/layouts/TM_Tiva/images/icons/social-from-iconsweets/twitter.png" alt="twitter" /></a></li>
                        <li><a href="#"><img src="/layouts/TM_Tiva/images/icons/social-from-iconsweets/facebook.png" alt="facebook" /></a></li>
                        <li  class="tiva_user"><a href="#" class="loginbt"><img src="/layouts/TM_Tiva/images/icons/social-from-iconsweets/login.png" alt="login" /></a></li>
<?if($_SESSION['member_srl']!=''){?>
<li id="member_option" class="tiva_user" >
                        	<span><a href="/index.php?mid=&amp;act=dispMemberLogout" style="margin-right:5px;">LOGOUT</a></span>
                            <span><a href="/index.php?mid=&amp;act=dispMemberInfo" >INFO</a></span>
                            <span><a href="/index.php?module=admin" target="_new" >ADMIN</a></span>                        </li>
<?}else{?>                        <li id="member_option" class="tiva_user" >
                        	<span><a href="#" class="loginbt" style="margin-right:5px;">LOGIN</a></span>
                            <span><a href="/index.php?<?=$_SERVER['PHP_SELF'];?>&amp;act=dispMemberSignUpForm" >JOIN</a></span>
                        </li>
<?}?>
						<li><a href="javascript:bookmarksite('Hamonikrㅣ 하모니카 프로젝트', 'http://www.hamonikr.org')"><img src="/layouts/TM_Tiva/images/icons/social-from-iconsweets/favorit.png" alt="favorit" /></a></li>
                        <li><div id="header-search">
                        <form action="http://hamonikr.org/" method="post" class="iSearch"><input type="hidden" name="error_return_url" value="/" />
                                                        <input type="hidden" name="mid" value="page_wFpT75" />
                            <input type="hidden" name="act" value="IS" />
                            <input type="hidden" name="search_target" value="title_content" />
                            <input name="is_keyword"  placeholder="Search the site" type="text" class="iText" id="search" title="keyword" />
                            <input type="submit" id="go" src="/layouts/TM_Tiva/images/lcblue/buttonSearch.gif" alt="검색" class="submit" />
                        </form>

                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!--TOP ELEMENTS ENDS-->

        	<div>
            <!--NAVIGATION CONTAINER STARTS-->
            <div id="navigation-container" >


            <!--NAVIGATION STARTS-->
			<ul id="gnb" class="sf-menu">
				<li class="current"><a href="/index.php?mid=page_jBcr78">홈으로</a>
									</li><li><a href="<?=_DIR_;?>/korean_bs.php">한글화서비스</a>
					<ul >
						<li><a href="/index.php?mid=page_bPzp37">한글화 도움말</a>
                        	                        </li><li><a href="<?=_DIR_;?>/my_list.php">내가 참여한 한글화</a>
                        	                        </li><li><a href="<?=_DIR_;?>/korean_bs.php">패키지별 한글화</a>
                        	<ul>
                                <li><a href="/">패키지별 한글화</a></li><li><a href="/">데스크탑 한글화</a></li>                            </ul>                        </li><li><a href="/index.php?mid=board_RXwB16">한글화 추천 오픈소스</a>
                        	                        </li>					</ul>				</li><li><a href="/index.php?mid=page_wFpT75">다운로드</a>
									</li><li><a href="/index.php?mid=board_hzVL42&amp;category=453">사용자커뮤니티</a>
					<ul >
						<li><a href="/index.php?mid=board_aMBI05">하모니</a></li>
						<li><a href="/index.php?mid=board_hzVL42&amp;category=453">인터넷 뱅킹</a>
                        	<ul>
                                <li><a href="/index.php?mid=board_hzVL42">인터넷 뱅킹 (전체)</a></li>
							</ul>
						</li>
						<li><a href="/index.php?mid=board_IUmu37">인터넷 쇼핑</a></li>
						<li><a href="/index.php?mid=board_bFBk25">팁 & 테크</a></li>
						<li><a href="/index.php?mid=board_KtxL32">배경화면</a></li>
					</ul>
					</li>
					<li><a href="/index.php?mid=terms_of_use">이용 약관</a></li>
				</ul>

            <!--NAVIGATION ENDS-->
            </div>
            <!--NAVIGATION CONTAINER ENDS-->
            </div>
            <!--GNB WRAP  ENDS-->

        </div>
        <!--HEADER CONTAINER ENDS-->
    </div>
    <!--HEADER WRAPPER ENDS-->





    <!--ROUNDABOUT WRAPPER STARTS-->
        <!--ROUNDABOUT WRAPPER ENDS-->


    <!--BREADCRUMBS WRAPPER STARTS-->
        <!--BREADCRUMBS WRAPPER ENDS-->



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



<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.js"></script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet" />

