<?php
	if (!defined('__XE__') && !defined('__ZBXE__')) exit();

/**
 * @file block_ip.addon.php
 * @brief IP 차단 애드온
 * @nick_name 키스투엑스이
 * 특정 IP를 차단합니다.
 **/
 
	if($called_position != 'before_module_init' || Context::get('module')=='admin') return;

	if(trim($addon_info->block_ip) && isset($addon_info->block_ip)) {
		$addr = $_SERVER['REMOTE_ADDR'];
		$ipaddressList = str_replace("\r","",$addon_info->block_ip);
		$ipaddressList = explode("\n",$ipaddressList);
		foreach($ipaddressList as $ipaddressKey => $ipaddressValue) {
			preg_match("/(\d{1,3}(?:.(\d{1,3}|\*)){3})\s*(\/\/\s*(.*))?/",$ipaddressValue,$matches);
			if($ipaddress=trim($matches[1])) { 
				$ip = str_replace('.', '\.', str_replace('*','(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)',$ipaddress));
				if(preg_match('/^'.$ip.'$/', $addr, $matches)) {
					header('location:'.$addon_info->where);
					exit();
				}
			}
		}
	}