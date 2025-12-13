/**
 * @file   modules/popup/js/popup_admin.js
 * @author zirho (zirho6@gmail.com)
 * @brief  popup 모듈의 관리자용 javascript
 **/

/* 팝업 미리보기 */
function openpop(url) {
    window.open(url);
}

/* 조건에 따라 입력 값 변경 */
function togglePopupDataType() {
	jQuery("tr[alt='popup_data_type']").toggle();
}

/* 팝업 커낵션 생성 후 */
function completeInsertPopupConn(ret_obj) {
    var error = ret_obj['error'];
    var message = ret_obj['message'];
    var popup_conn_srl = ret_obj['popup_conn_srl'];

    alert(message);

    var url = current_url.setQuery('act','dispPop_upAdminInsertPopupConn').setQuery('popup_conn_srl',popup_conn_srl);
    location.href = url;
}

/* 팝업 커낵션 삭제 후 */
function completeDeletePopupConn(ret_obj) {
    var error = ret_obj['error'];
    var message = ret_obj['message'];
    var page = ret_obj['page'];
    alert(message);

    var url = current_url.setQuery('act','dispPop_upAdminContent').setQuery('popup_conn_srl','');
    if(page) url = url.setQuery('page',page);
    location.href = url;
}
