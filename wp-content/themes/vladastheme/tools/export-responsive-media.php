<?php
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
require dirname( __DIR__, 4 ) . '/wp-load.php';
$front=get_option('page_on_front');$ids=array();
foreach(array('header_image','header_image_2','about_us_image','call_background') as $key){$v=get_field($key,$front);if(is_array($v)){$v=$v['ID']??$v['url']??'';}$id=is_numeric($v)?(int)$v:attachment_url_to_postid($v);if($id)$ids[]=$id;}
foreach(array('site_logo','logo_white') as $key){$v=get_field($key,'options');$id=is_numeric($v)?(int)$v:attachment_url_to_postid($v);if($id)$ids[]=$id;}
foreach(get_posts(array('post_type'=>array('projekti','stanovi'),'post_status'=>'publish','numberposts'=>50)) as $p){$id=get_post_thumbnail_id($p->ID);if($id)$ids[]=$id;}
foreach(array_unique($ids) as $id){$file=get_attached_file($id);$meta=wp_get_attachment_metadata($id);if($file&&$meta)echo wp_json_encode(array('id'=>$id,'file'=>$meta['file'],'width'=>$meta['width'],'height'=>$meta['height'],'bytes'=>filesize($file))).PHP_EOL;}
