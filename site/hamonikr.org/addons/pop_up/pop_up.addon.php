<?PHP

    if(!defined("__ZBXE__")) exit();

    if(false || $called_position == 'before_module_proc' ) {		
		
		
		function getPopupScript($val){

			if($val->content){
				$order   = array("\r\n", "\n", "\r");
				$replace = '';

				$val->content = str_replace($order, $replace, $val->content);
			}

			return "	jQuery('<div></div>').xe_popup({id:'".$val->popup_conn_srl."'"
				.($val->popup_type?",popup_type:'".$val->popup_type."'":"")
				.($val->content?",content:'".$val->content."'":"")
				.($val->popup_url?",url:'".$val->popup_url."'":"")
				.($val->popup_linkto?",linkto:'".$val->popup_linkto."'":"")
				.($val->popup_linkto_newwindow?",linkto_newwindow:'".$val->popup_linkto_newwindow."'":"")
				.($val->open_type?",open_type:'".$val->open_type."'":"")
				.($val->top?",top:'".$val->top."px'":"")
				.($val->left?",left:'".$val->left."px'":"")
				.($val->width?",width:'".$val->width."px'":"")
				.($val->height?",height:'".$val->height."px'":"")
				.($val->exp_days?",exp_days:'".$val->exp_days."'":"")
				."});"."\n";
		}

		// disable when admin module
		// disable when there's no module_srl
		if($this->module_info->module != 'admin' && $this->module_info->module_srl){
            // 
            $args->site_srl = $this->module_info->site_srl;
            $output = executeQuery('module.getSite', $args);

			$domain = $output->data->domain;

			$oPopupModel = &getModel('pop_up');
			$targets->module_srl = $this->module_info->module_srl;
			$popupList = $oPopupModel->getPop_upsForThisSrl($targets);

			if($popupList){
				Context::addJsFile('./addons/pop_up/pop_up.js');

				$addPopupScript = '<script type="text/javascript">'."\n";
				$addPopupScript .= 'jQuery(function(){'."\n";

				if($popupList) {					
					if(!is_array($popupList)) $popupList = array($popupList);

					foreach($popupList as $val){
						$addPopupScript .= getPopupScript($val);
					}
				}

				$addPopupScript .= '});'."\n";
				$addPopupScript .= '</script>'."\n";
				Context::addHtmlHeader($addPopupScript);
			}
		}
    }
?>
