//<![CDATA[
var li = new Object();
li.layout_type = "{$li->layout_type}";
li.menu_colorset = "{$li->menu_colorset}";
li.expand_sitemap = {$li->expand_sitemap != 'Y' ? 'false' : 'true'};
li.use_camera_slide = {$li->use_camera_slide != 'Y' ? 'false' : 'true'};
li.use_camera_slide_thumb = {$li->use_camera_slide_thumb != 'Y' ? 'false' : 'true'};
li.camera_slide_alignment = "{$li->camera_slide_alignment}";
li.camera_slide_advance = {$li->camera_slide_image_2 ? 'true' : 'false'};
//]]>