<?php
/* 윈컴이 (http://www.wincomi.com) */
if(!defined('__XE__')) exit();
if($called_position=='before_display_content' && Context::get('module') != 'admin') Context::addCSSFile('./addons/bootstrap3_css/bootstrap.min.css', false);
?>