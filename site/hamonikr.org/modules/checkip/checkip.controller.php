<?php
/**
 * @class checkipController
 * @author CMD (webmaster@comcorea.com)
 * @brief checkip 모듈의 controller class
 **/

class checkipController extends checkip {
	/**
	 * @brief 초기화
	 */
	function init() {
	}
	/**
	 * @brief 가입시 IP 기록
	 */
	function triggerInsertMember(&$args) {
		
		// 가입 IP를 기록
		$args->log_srl = getNextSequence();
		$args->reg_ip = $_SERVER['REMOTE_ADDR'];
		$args->regdate = date('YmdHis');
		$output = executeQuery('checkip.insertRegIP', $args);
		if(!$output->toBool()) return $output;

		return new Object();
	}

	function triggerProcMemberInsert() {
		
		//중복 IP를 검사하도록 하였는지 검사
		$oCheckipModel = &getModel('checkip');
		$config = $oCheckipModel->getModuleConfig();
		if($config->check_ip != 'Y') return new Object();
		
		
		
		$count = $oCheckipModel->getMemberIpCount($_SERVER['REMOTE_ADDR']);
    //만료 기간 체크
		if($config->expiration_period && $oCheckipModel->getLatestRegIP($_SERVER['REMOTE_ADDR']) < date('YmdHis', strtotime('-'.$config->expiration_period.'Days'))) return new Object();
		if($count != 0) return new Object(-1, "msg_duplicated_ip");
		
		return new Object();
	}


	/**
	 * @brief 회원 탈퇴 시 가입 IP 기록 삭제
	 */
	 
	function triggerDeleteMember(&$obj) {
		$oCheckipModel = &getModel('checkip');
		$config = $oCheckipModel->getModuleConfig();

		if($config->delete_reg_ip != 'Y') return new Object();

		$member_srl = $obj->member_srl;
		if(!$member_srl) return new Object();

		$output = executeQuery('checkip.deleteMemberRegIP', $obj);
		if(!$output->toBool()) return $output;

		return new Object();
	}
	
}