<?php
    /**
     * @class  popup Model
     * @author zirho (zirho6.gmail.com)
     * @brief  popup 모듈의 model class
     **/

    class pop_upModel extends pop_up {

        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief 팝업 정보 가져오기
         **/
        function getPop_upInfoByPopupSrl($popup_conn_srl) {
            // 데이터를 가져옴
            $args->popup_conn_srl = $popup_conn_srl;
            $output = executeQuery('pop_up.getPopupList', $args);
            if($output->data){
				if(is_array($output->data)) return array_pop($output->data);
				else return $output->data;
			}

            return ;
		}

        /**
         * @brief 현재 모듈의 팝업 정보 가져오기
         **/
		function getPop_upsForThisSrl($targets){
			$stamp = mktime();
			$stamp = date("Ymd", $stamp);
			$targets->curdate = $stamp;

			$output = executeQuery('pop_up.getPopupsForThisSrl', $targets);

			if($output->data) return $output->data;
			return;
		}
	}
?>