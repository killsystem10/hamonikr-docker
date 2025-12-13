<?php
/**
 * @class checkipAdminController
 * @author CMD (webmaster@comcorea.com)
 * @brief checkip 모듈의 admin controller class
 **/

class checkipAdminController extends checkip {
	/**
	 * @brief 초기화
	 */
	function init() {
	}

	/**
	 * @brief 설정 저장
	 */
	function procCheckipAdminInsertConfig() {
		$config = Context::getRequestVars();
		if(!$config->delete_reg_ip) $config->delete_reg_ip = 'N';
		if(!$config->check_ip) $config->check_ip = 'N';
		if(!$config->expiration_period) $config->expiration_period = 0;

		$oModuleController = &getController('module');
		$oModuleController->insertModuleConfig('checkip', $config);

		$this->setMessage('success_saved');
	}

	/**
	 * @brief 기록 초기화
	 */

	function procCheckipAdminInitLogs() {
		$msg_code = 'success_reset';

		$output = executeQuery('checkip.initRegIP');
		if(!$output->toBool()) $msg_code = 'msg_failed_reset_logs';

		$this->setMessage($msg_code);
	}
	
	/**
	 * @brief 기록 일괄 삭제
	 */
	
	function procCheckipAdminAllDelete() {

            // 변수 체크
            $cart = trim(Context::get('cart'));
            if(!$cart) return new Object(-1, 'msg_cart_is_null');

            $cart_list = explode('|@|', $cart);
            if(!count($cart_list)) return new Object(-1, 'msg_cart_is_null');

			$log_count = count($cart_list);
            $target = array();
            for($i=0;$i<$log_count;$i++) {
                $log_srl = (int)trim($cart_list[$i]);
                if(!$log_srl) continue;
                $target[] = $log_srl;
            }
            if(!count($target)) return new Object(-1,'msg_cart_is_null');

            // 삭제
            $args->log_srls = implode(',',$target);
            $output = $this->deleteRegIP($args);
            if(!$output->toBool()) return $output;

            $this->setMessage('success_deleted');
		}
	
	/**
	 * @brief 기록 삭제
	 */	
	function deleteRegIP($args) {
			if(!$args) return false;
            return executeQuery('checkip.deleteRegIpByLogSrl', $args);
		}

}