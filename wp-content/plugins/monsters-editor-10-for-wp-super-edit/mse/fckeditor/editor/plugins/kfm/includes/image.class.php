<?php
class Image extends File{
	var $caption='';
	var $width;
	var $height;
	var $thumb_url;
	var $thumb_id;
	var $thumb_path;
	var $info=array(); # info from getimagesize
	function Image($file){
		if(is_object($file) && $file->isImage())parent::File($file->id);
		else if(is_numeric($file))parent::File($file);
		else return false;
		$this->image_id=$this->getImageId();
		$this->info=getimagesize($this->path);
		$this->type=str_replace('image/','',$this->info['mime']);
		$this->width=$this->info[0];
		$this->height=$this->info[1];
	}
	function createResizedCopy($to,$width,$height){
		$load='imagecreatefrom'.$this->type;
		$save='image'.$this->type;
		if(!function_exists($load)||!function_exists($save))return $this->error('server cannot handle image of type "'.$this->type.'"');
		$im=$load($this->path);
		$imresized=imagecreatetruecolor($width,$height);
		imagealphablending($imresized,false);
		imagecopyresampled($imresized,$im,0,0,0,0,$width,$height,$this->width,$this->height);
		imagesavealpha($imresized,true);
		$save($imresized,$to,($this->type=='jpeg'?100:9));
		imagedestroy($imresized);
		imagedestroy($im);
	}
	function createThumb($width=64,$height=64){
		global $kfmdb,$kfm_db_prefix;
		if(!is_dir(WORKPATH.'thumbs'))mkdir(WORKPATH.'thumbs');
		$ratio=min($width/$this->width,$height/$this->height);
		$thumb_width=$this->width*$ratio;
		$thumb_height=$this->height*$ratio;
		$kfmdb->exec("INSERT INTO ".$kfm_db_prefix."files_images_thumbs (image_id,width,height) VALUES(".$this->id.",".$thumb_width.",".$thumb_height.")");
		$id=$kfmdb->lastInsertId($kfm_db_prefix.'files_images_thumbs','id');
		$file=WORKPATH.'thumbs/'.$id;
		if($this->useImageMagick($this->path,'resize '.$thumb_width.'x'.$thumb_height,$file))$this->createResizedCopy($file,$thumb_width,$thumb_height);
		return $id;
	}
	function delete(){
		global $kfmdb,$kfm_db_prefix;
		parent::delete();
		$this->deleteThumbs();
		$kfmdb->exec('DELETE FROM '.$kfm_db_prefix.'files_images WHERE file_id='.$this->id);
		return !$this->hasErrors();
	}
	function deleteThumbs(){
		global $kfmdb,$kfm_db_prefix;
		$q=$kfmdb->query("SELECT id FROM ".$kfm_db_prefix."files_images_thumbs WHERE image_id=".$this->id);
		$rs=$q->fetchAll();
		foreach($rs as $r){
			$icons=glob(WORKPATH.'thumbnails/'.$r['id'].'.*');
			foreach($icons as $f)unlink($f);
		}
		$q=null;
		$kfmdb->exec("DELETE FROM ".$kfm_db_prefix."files_images_thumbs WHERE image_id=".$this->id);
		# TODO: remove the following two lines at version 1.0
		$icons=glob(WORKPATH.$this->id.' [0-9]*x[0-9]*.*');
		foreach($icons as $f)unlink($f);
	}
	function getImageId(){
		global $kfmdb,$kfm_db_prefix;
		$sql="SELECT id,caption FROM ".$kfm_db_prefix."files_images WHERE file_id='".$this->id."'";
		$res=$kfmdb->query($sql);
		if(!$res->numRows()){ # db record not found. create it
			# TODO: retrieve caption generation code from get.php
			$sql="INSERT INTO ".$kfm_db_prefix."files_images (file_id, caption) VALUES ('".$this->id."','".$this->name."')";
			$this->caption=$this->name;
			$kfmdb->exec($sql);
			return $kfmdb->lastInsertId($kfm_db_prefix.'files_images','id');
		}
		$row=$res->fetchRow();
		$this->caption=$row['caption'];
		return $row['id'];
	}
	function resize($new_width, $new_height=-1){
		if(!$this->isWritable()){
			$this->error('Image is not writable, so cannot be resized');
			return false;
		}
		$this->deleteThumbs();
		if($new_height==-1)$new_height=$this->height*$new_width/$this->width;
		if(!$this->useImageMagick($this->path,'resize '.$new_width.'x'.$new_height,$this->path))return;
		$this->createResizedCopy($this->path,$new_width,$new_height);
	}
	function rotate($direction){
		if(!$this->isWritable()){
			$this->error('Image is not writable, so cannot be rotated');
			return false;
		}
		$this->deleteThumbs();
		if(!$this->useImageMagick($this->path,'rotate -'.$direction,$this->path))return;
		{ # else use GD
			$load='imagecreatefrom'.$this->type;
			$save='image'.$this->type;
			$im=$load($this->path);
			$im=imagerotate($im,$direction,0);
			$save($im,$this->path,($this->type=='jpeg'?100:9));
			imagedestroy($im);
		}
	}
	function setCaption($caption){
		global $kfmdb,$kfm_db_prefix;
		$kfmdb->exec("UPDATE ".$kfm_db_prefix."files_images SET caption='".addslashes($caption)."' WHERE file_id=".$this->id);
		$this->caption=$caption;
	}
	function setThumbnail($width=64,$height=64){
		global $kfmdb,$kfm_db_prefix;
		$thumbname=$this->id.' '.$width.'x'.$height.' '.$this->name;
		if(!isset($this->info['mime'])||!in_array($this->info['mime'],array('image/jpeg','image/gif','image/png')))return false;
		$q=$kfmdb->query("SELECT id FROM ".$kfm_db_prefix."files_images_thumbs WHERE image_id=".$this->id." and width<=".$width." and height<=".$height." and (width=".$width." or height=".$height.")");
		if($q->numRows()){
			$r=$q->fetchRow();
			$id=$r['id'];
		}
		else{
			$q=null;
			$id=$this->createThumb($width,$height);
		}
		$this->thumb_url='get.php?type=thumb&id='.$id.GET_PARAMS;
		$this->thumb_id=$id;
		$this->thumb_path=str_replace('//','/',WORKPATH.'thumbs/'.$id);
		if(!file_exists($this->thumb_path)){
			copy(WORKPATH.'thumbs/'.$id.'.'.preg_replace('/.*\//','',$this->info['mime']),$this->thumb_path);
			unlink(WORKPATH.'thumbs/'.$id.'.'.preg_replace('/.*\//','',$this->info['mime']));
		}
		if(!file_exists($this->thumb_path))$this->createThumb();
	}
	function useImageMagick($from,$action,$to){
		if(!file_exists(IMAGEMAGICK_PATH))return true;
		$retval=true;
		$arr=array();
		exec(IMAGEMAGICK_PATH.' "'.$from.'" -'.$action.' "'.$to.'"',$arr,$retval);
		return $retval;
	}
}
?>
