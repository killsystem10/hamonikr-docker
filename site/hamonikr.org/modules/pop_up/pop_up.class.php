<?php
    /**
     * @class  pop_up
     * @author zirho6 (zirho6@gmail.com)
     * @brief  pop_up 모듈의 high class
     **/

    class pop_up extends ModuleObject {

        /**
         * @brief 설치시 추가 작업이 필요할시 구현
         **/
        function moduleInstall() {
			// v0.0.2
			$oPopupAdminController = &getAdminController('pop_up');
			$oPopupAdminController->procPop_upAdminInsertPopupData();
            return new Object();
        }

        /**
         * @brief if update is necessary it returns true
         **/
        function checkUpdate() {
			// v0.0.2
            $oDB = &DB::getInstance();
            if(!$oDB->isColumnExists("pop_ups","document_srl")) return true;

			// v0.0.2
			$oModuleModel = &getModel('module');
			$output = $oModuleModel->isIDExists($this->module);		
			if(!$output){ return true; }

            return false;
        }

        /**
         * @brief update module 
         * @return new Object
         **/
        function moduleUpdate() {
			// v0.0.2
            $oDB = &DB::getInstance();			
            if(!$oDB->isColumnExists("pop_ups","document_srl")) $oDB->addColumn('pop_ups',"document_srl","number",11);

			// v0.0.2
			$oModuleModel = &getModel('module');
			$output = $oModuleModel->isIDExists($this->module);		
			if(!$output){ 
				$oPopupAdminController = &getAdminController('pop_up');
				$oPopupAdminController->procPop_upAdminInsertPopupData();
			}

            return new Object(0,'success_updated');
        }

        /**
         * @brief 캐시 파일 재생성
         **/
        function recompileCache() {
        }

    }
?>
