<?php
    /**
     * @class  pop_upAdminView
     * @author zirho (zirho6@gmail.com)
     * @brief  pop_up 모듈의 admin view class
     **/

    class pop_upAdminView extends pop_up {

        /**
         * @brief 초기화
         *
         **/
        function init() {

            // 템플릿 경로 지정
            $template_path = sprintf("%stpl/",$this->module_path);
            $this->setTemplatePath($template_path);
        }

        /**
         * @brief 팝업 관리 목록 보여줌
         **/
        function dispPop_upAdminContent() {

            $args->page = Context::get('page');
            $args->list_count = 20;
            $args->page_count = 10;
			
			$output = executeQueryArray('pop_up.getPopupList', $args);

            // 템플릿에 쓰기 위해서 context::set
            Context::set('total_count', $output->total_count);
            Context::set('total_page', $output->total_page);
            Context::set('page', $output->page);
            Context::set('board_list', $output->data);
            Context::set('page_navigation', $output->page_navigation);
			
            Context::set('popup_list', $output->data);

            // 템플릿 파일 지정
            $this->setTemplateFile('index');
        }

        /**
         * @brief 팝업 연결 추가 페이지
         **/
        function dispPop_upAdminInsertPopupConn() {
			
			$oPopupModel = &getModel('pop_up');

			// popup_conn_srl 이 있으면 미리 체크하여 존재하는 팝업이면 popup_info 세팅
            $popup_conn_srl = Context::get('popup_conn_srl');
            if($popup_conn_srl) {
                $popup_info = $oPopupModel->getPop_upInfoByPopupSrl($popup_conn_srl);
			}else{
				$popup_info->popup_type = 'content';
			}	
			
            Context::set('popup_info',$popup_info);
			
			//가능 대상 모듈 목록 가져오기
			$oModuleModel = &getModel('module');

            $site_module_info = Context::get('site_module_info');
            $args->site_srl = $site_module_info->site_srl;
			$args->sort_index = 'module';
			$target_modules = $oModuleModel->getMidList($args);
            Context::set('target_modules', $target_modules);

			$output = $oModuleModel->getModuleSrlByMid($this->module);
			
			$document_srl = $popup_info->document_srl;
            $oDocumentModel = &getModel('document');
            $oDocument = $oDocumentModel->getDocument($document_srl, $this->grant->manager);
            $oDocument->add('module_srl', array_pop($output));//$this->module_srl);

            Context::set('oDocument', $oDocument);
            Context::set('mid', 'freeboardt5');

            // 템플릿 파일 지정
            $this->setTemplateFile('popup_conn_insert');

        }

        /**
         * @brief 팝업 연결 삭제 페이지
         **/
        function dispPop_upAdminDeletePopupConn($args = null) {
			
			if(!Context::get('popup_conn_srl')) return $this->dispPop_upAdminContent();
//            if(!in_array($this->module_info->module, array('admin'))) {
//                return $this->alertMessage('msg_invalid_request');
//            }
            
			//팝업창 목록 가져오기
			$args->popup_conn_srl = Context::get('popup_conn_srl');
			//debugPrint($args);
			$output = executeQuery('pop_up.getPopupList', $args);
			//debugPrint($output);
			unset($args);

            $this->setTemplateFile('popup_conn_delete');
			if(!$output->data) return;

			if (is_array($output->data))
				$popup_module = array_pop($output->data);
			else
				$popup_module = $output->data;

            Context::set('popup_module', $popup_module);

            // 템플릿 파일 지정
        }
    }
?>
