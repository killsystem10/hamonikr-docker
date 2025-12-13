<?php
/**
 * @class checkipAdminView
 * @author CMD (webmaster@comcorea.com)
 * @brief checkip 모듈의 controller class
 **/

class checkipAdminView extends checkip {
	/**
	 * @brief 초기화
	 */
	function init() {
		// 템플릿 폴더 지정
		$this->setTemplatePath($this->module_path.'tpl');
	}

	/**
	 * @brief 로그인 기록 열람
	 */
	function dispCheckipAdminIndex() {
		$oModel = &getModel('checkip');
		$config = $oModel->getModuleConfig();
		
		$oMemberModel = &getModel('member');

		Context::set('config', $config);

		// 목록을 구하기 위한 옵션
		$args->page = Context::get('page'); ///< 페이지
		$args->list_count = 30; ///< 한페이지에 보여줄 기록 수
		$args->page_count = 10; ///< 페이지 네비게이션에 나타날 페이지의 수
		$args->sort_index = 'regdate';
		$args->order_type = 'desc';

		$search_keyword = Context::get('search_keyword');
		$search_target = trim(Context::get('search_target'));

		if($search_keyword) {
			switch($search_target) {
				case 'user_id':
					$args->member_srl = $oMemberModel->getMemberSrlByUserID($search_keyword);
					break;
				case 'nick_name':
					$args->member_srl = $oMemberModel->getMemberSrlByNickName($search_keyword);
					break;
				case 'member_srl':
					$args->member_srl = (int) $search_keyword;
					break;
				case 'regdate':
					$args->s_regdate = $search_keyword;
					break;
				case 'ipaddress':
					$args->s_reg_ip = $search_keyword;
					break;
			}
		}

		$output = executeQueryArray('checkip.getRegIpList', $args);

		// 템플릿에 쓰기 위해 Context::set
		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('log_list', $output->data);
		Context::set('page_navigation', $output->page_navigation);

		// 템플릿 파일 지정
		$this->setTemplateFile('index');
	}
}