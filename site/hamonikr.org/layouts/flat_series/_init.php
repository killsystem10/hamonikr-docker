<?php

$li = &Context::get('layout_info');

if (!$li->layout_type)            $li->layout_type = 'mainpage';
if (!$li->menu_colorset)          $li->menu_colorset = 'black';
if (!$li->camera_slide_pattern)   $li->camera_slide_pattern = 'none';
if (!$li->camera_slide_alignment) $li->camera_slide_alignment = 'center';
if (!$li->body_img_repeat)        $li->body_img_repeat = 'no-repeat';
if (!$li->body_img_position)      $li->body_img_position = 'left top';
if (!$li->body_img_attach)        $li->body_img_attach = 'scroll';
if (!$li->body_img_size)          $li->body_img_size = 'default';

if ($li->search_type == 'fixed' && Context::get('mid') == 'IS' && Context::get('act') != 'IS')
{
	$db_info     = Context::getDBInfo();
	$default_url = $db_info->default_url;
	
	unset($db_info);
	header(sprintf('Location: %s', $default_url));
}

$footer_menu = &Context::get('footer_menu');
foreach ($footer_menu->list as $key=>$val)
{
	$output = array();
	
	if ($val['selected'])                   $output[] = 'active';
	if ($val === reset($footer_menu->list)) $output[] = 'first-child';
	if ($val === end($footer_menu->list))   $output[] = 'last-child';
	
	$output = implode(' ', $output);
	$footer_menu->list[$key]['class'] = $output;
}

Context::set('li', $li);
Context::set('if_migrated', version_compare(__XE_VERSION__ | __ZBXE_VERSION__, '1.7.0.0', '<'));
Context::addMetaTag('viewport', 'width=1020');
Context::addBodyClass($li->layout_type);
Context::addBodyClass($li->menu_colorset);