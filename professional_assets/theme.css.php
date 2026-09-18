<?php
require_once __DIR__.'/bootstrap_portal.php';
header('Content-Type: text/css; charset=utf-8');
$font = max(12,min(20,(int)setting($con,'ui_font_size','16')));
$accent = setting($con,'ui_accent','#2c78d4');
$sidebar = setting($con,'ui_sidebar_color','#10253f');
$bg = setting($con,'ui_page_color','#f4f7fb');
$card = setting($con,'ui_card_color','#ffffff');
function valid_color($v,$fallback){return preg_match('/^#[0-9a-fA-F]{6}$/',$v)?$v:$fallback;}
$accent=valid_color($accent,'#2c78d4');$sidebar=valid_color($sidebar,'#10253f');$bg=valid_color($bg,'#f4f7fb');$card=valid_color($card,'#ffffff');
echo ":root{--ui-font-size:{$font}px;--blue:{$accent};--navy:{$sidebar};--bg:{$bg};--card:{$card}}body{font-size:var(--ui-font-size)}.sidebar{background:var(--navy)}.primary-btn,.nav-item.active,.th, .panel th{background:var(--blue)}a{--link-accent:var(--blue)}input:focus,select:focus,textarea:focus{border-color:var(--blue)!important;box-shadow:0 0 0 3px color-mix(in srgb,var(--blue) 15%,transparent)}";
?>
