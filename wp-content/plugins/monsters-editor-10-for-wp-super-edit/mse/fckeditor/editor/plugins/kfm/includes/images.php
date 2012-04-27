<?php
function _changeCaption($fid,$newCaption){
	$im = new Image($fid);
	$im->setCaption($newCaption);
	return kfm_loadFiles($_SESSION['kfm']['cwd_id']);
}
function _getThumbnail($fileid,$width,$height){
	$im=new Image($fileid);
	$im->setThumbnail($width,$height); // Already done in the Image constructor, maybe needed for Thumbnails with different sizes.
	return array($fileid,array('icon'=>$im->thumb_url,'width'=>$im->width,'height'=>$im->height,'caption'=>$im->caption));
}
function _resizeImage($fid,$width,$height){
	$im = new Image($fid);
	$im->resize($width, $height);
	return kfm_loadFiles($_SESSION['kfm']['cwd_id']);
}
function _rotateImage($fid,$direction){
	$im = new Image($fid);
	$im->rotate($direction);
	return kfm_loadFiles($_SESSION['kfm']['cwd_id']);
}
function _setCaption($fid,$caption){
	
}
?>
