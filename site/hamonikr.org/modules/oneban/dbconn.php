<?
//설정해야할 부분 시작----------------------------------------------------
$conn = mysql_connect("localhost", "디비아이디", "디비암호");
$md5key = "여기에 비밀키";

$linkStart = 'http://내홈페이지 주소/이 스크릅트 설치한 곳/';
$linkStart2 = 'http://내홈페이지 주소/제로보드 설치한 곳/?module=admin&act=dispDocumentAdminList&search_target=member_srl&search_keyword=';
//설정해야할 부분 끝----------------------------------------------------

if (!$conn) {
        echo "Unable to connect to DB: " . mysql_error();
        exit;
    }

if (!mysql_select_db("gagalive")) {
      echo "Unable to select mydbname: " . mysql_error();
      exit;
    }
?>