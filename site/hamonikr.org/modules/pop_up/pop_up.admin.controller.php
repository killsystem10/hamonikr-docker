<?php
    /**
     * @class  pop_upAdminController
     * @author zirho (zirho6@gmail.com)
     * @brief  pop_up 모듈의 admin controller class
     **/

    class pop_upAdminController extends pop_up {

        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief 팝업 모듈 시스템 정보 입력
         **/
		function procPop_upAdminInsertPopupData(){
			
			$oModuleModel = &getModel('module');
			$ouput = $oModuleModel->isIDExists($this->module);
			if(!$output){
				// module 에 pop_up 데이터 입력
				$site_module_info = Context::get('site_module_info');
				$args->site_srl = $site_module_info->site_srl;
				$args->mid = $this->module;
				$args->module = $this->module;

				$oModuleController = &getController('module');
				$output = $oModuleController->insertModule($args);

				// 팝업 내용 텍스트 에디터 높이 적용
				$module_srl = $output->get('module_srl');
				Context::set('target_module_srl', $module_srl);
				Context::set('editor_height', '200');
				Context::set('enable_autosave', 'N');

				$oEditorController = &getController('editor');
				$oEditorController->procEditorInsertModuleConfig();
			}
		}

        /**
         * @brief 팝업 연결 추가
         **/
        function procPop_upAdminInsertPopupConn() {

            // 모듈의 정보 설정
            $args = Context::getRequestVars();

			// 팝업 내용 저장
            $args->title = $args->popup_name;
            $oDocumentController = &getController('document');

            // 팝업 모듈의 model/controller 객체 생성
            $oPopupController = &getController('pop_up');
            $oPopupModel = &getModel('pop_up');
			
			// popup_conn_srl이 넘어오면 원 모듈이 있는지 확인
			if($args->popup_conn_srl) {
                $popup_conn_info = $oPopupModel->getPop_upInfoByPopupSrl($args->popup_conn_srl);
                if($popup_conn_info->popup_conn_srl != $args->popup_conn_srl) unset($args->popup_conn_srl);
            }
			
			$args->document_srl = $popup_conn_info->document_srl;

            // module_srl의 값에 따라 insert/update
            if(!$args->popup_conn_srl) {

				$output = $oDocumentController->insertDocument($args);			
				$args->document_srl = $output->get('document_srl');

                $args->popup_conn_srl = getNextSequence();
                $output = $oPopupController->procPop_upInsertPopupConn($args);
                $msg_code = 'success_registed';
            } else {
				
				$oDocumentModel = &getModel('document');
				$oDocument = $oDocumentModel->getDocument($popup_conn_info->document_srl, $this->grant->manager);

				$oDocumentController = &getController('document');
				$output = $oDocumentController->updateDocument($oDocument, $args);			

                $output = $oPopupController->procPop_upUpdatePopupConn($args);
                $msg_code = 'success_updated';

                // 캐시 파일 삭제
                $cache_file = sprintf("./files/cache/pop_up/%d.cache.php", $popup_conn_info->popup_conn_srl);
                if(file_exists($cache_file)) FileHandler::removeFile($cache_file);
            }

            if(!$output->toBool()) return $output;

            // 등록 성공후 return될 메세지 정리
            $this->add("popup_conn_srl", $output->get('popup_conn_srl'));
            $this->setMessage($msg_code);
        }

        /**
         * @brief 팝업 삭제
         **/
        function procPop_upAdminDeletePopupConn() {
            $popup_conn_srl = Context::get('popup_conn_srl');

            // 원본을 구해온다
            $oPopupController = &getController('pop_up');
			$args->popup_conn_srl = $popup_conn_srl;
            $output = $oPopupController->procPop_upDeletePopupConn($args);
            if(!$output->toBool()) return $output;

            //$this->add('module','popup');
            $this->add('page',Context::get('page'));
            $this->setMessage('success_deleted');
        }
    }
?>
