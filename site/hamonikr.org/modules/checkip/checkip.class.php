<?php
/**
 * @class checkip
 * @author CMD (webmaster@comcorea.com)
 * @brief checkip 모듈의 high class
 **/

class checkip extends ModuleObject {
	/**
	 * @brief 모듈 설치
	 */
	function moduleInstall() {
		$oModuleController = &getController('module');

		$oModuleController->insertTrigger('member.insertMember', 'checkip', 'controller', 'triggerInsertMember', 'after');
		$oModuleController->insertTrigger('member.deleteMember', 'checkip', 'controller', 'triggerDeleteMember', 'after');
		$oModuleController->insertTrigger('member.procMemberInsert', 'checkip', 'controller', 'triggerProcMemberInsert', 'before');

		return new Object();
	}

	/**
	 * @brief 모듈 삭제
	 */
	function moduleUninstall() {

		$oModuleModel = &getModel('module');
		$oModuleController = &getController('module');

		// 트리거 삭제
		if($oModuleModel->getTrigger('member.insertMember', 'checkip', 'controller', 'triggerInsertMember', 'after'))
			$oModuleController->deleteTrigger('member.insertMember', 'checkip', 'controller', 'triggerInsertMember', 'after');
			
		if($oModuleModel->getTrigger('member.deleteMember', 'checkip', 'controller', 'triggerDeleteMember', 'after'))
			$oModuleController->deleteTrigger('member.deleteMember', 'checkip', 'controller', 'triggerDeleteMember', 'after');
			
		if($oModuleModel->getTrigger('member.procMemberInsert', 'checkip', 'controller', 'triggerProcMemberInsert', 'before'))
			$oModuleController->deleteTrigger('member.procMemberInsert', 'checkip', 'controller', 'triggerProcMemberInsert', 'before');

		return new Object();
	}

	/**
	 * @brief 업데이트가 필요한지 확인
	 **/
	function checkUpdate() {
	
		return false;
		
	}

	/**
	 * @brief 모듈 업데이트
	 **/
	function moduleUpdate() {
	
	}

	/**
	 * @brief 캐시 파일 재생성
	 **/
	function recompileCache() {
	}
}