<?php
/**
 * @class checkipModel
 * @author CMD (webmaster@comcorea.com)
 * @brief checkip 모듈의 model class
 **/

class checkipModel extends checkip {
	/**
	 * @brief 초기화
	 */
	function init() {
	}

	/**
	 * @brief 모듈의 global 설정 구함
	 */
	function getModuleConfig() {
		static $config = null;
		if(is_null($config)) {
			$oModuleModel = &getModel('module');
			$config = $oModuleModel->getModuleConfig('checkip');
		}

		return $config;
	}
	
	/**
	 * @brief 중복 IP의 갯수를 구함
	 */
	 
	function getMemberIpCount($reg_ip) {
		$args->reg_ip = $reg_ip;
		$output = executeQuery('checkip.countMemberIP', $args);
		
		return $output->data->count;
	}
	
	function getLatestRegIP($reg_ip) {
		$args->reg_ip = $reg_ip;
		$args->order_type = 'desc';
		$args->sort_index = 'log_srl';
		$output = executeQuery('checkip.getLatestRegIP', $args);
		
		return $output->data->regdate;
	}
	
}