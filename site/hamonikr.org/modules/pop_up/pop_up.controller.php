<?php
/**
 * @class  pop_up Controller
 * @author zirho (zirho6.gmail.com)
 * @brief  pop_up 모듈의 controller class
 **/

class pop_upController extends pop_up {

	/**
	 * @brief 초기화
	 **/
	function init() {
	}

	/**
	 * @brief 팝업 연결 입력
	 **/
	function procPop_upInsertPopupConn($args) {

		// begin transaction
		$oDB = &DB::getInstance();
		$oDB->begin();

		// 변수 정리후 query 실행
		if(!$args->popup_conn_srl) $args->popup_conn_srl = getNextSequence();

		//debugPrint($args);
		//$output = executeQuery('popup.isExistPopupConn', $args);
		//debugPrint($output);
		//if(!$output->toBool() || $output->data->count) {
		//    $oDB->rollback();
		//    return new Object(-1, 'msg_popup_conn_exists');
		//}
		//unset($output);

		// 모듈 등록
		$output = executeQuery('pop_up.insertPopupConn', $args);
		if(!$output->toBool()) {
			$oDB->rollback();
			return $output;
		}

		// commit
		$oDB->commit();

		$output->add('popup_conn_srl',$args->popup_conn_srl);
		return $output;
	}

	/**
	 * @brief 팝업 연결 수정
	 **/
	function procPop_upUpdatePopupConn($args) {
		// begin transaction
		$oDB = &DB::getInstance();
		$oDB->begin();

		$output = executeQuery('pop_up.updatePopupConn', $args);
		if(!$output->toBool()) {
			$oDB->rollback();
			return $output;
		}

		$oDB->commit();

		$output->add('popup_conn_srl',$args->popup_conn_srl);
		return $output;
	}

	/**
	 * @brief 팝업 연결 삭제
	 **/
	function procPop_upDeletePopupConn($args = null) {

		// begin transaction
		$oDB = &DB::getInstance();
		$oDB->begin();

		// module 정보를 DB에서 삭제
		$output = executeQuery('pop_up.deletePopupConn', $args);
		if(!$output->toBool()) {
			$oDB->rollback();
			return $output;
		}

		$oDB->commit();
		return $output;
	}
}
?>
