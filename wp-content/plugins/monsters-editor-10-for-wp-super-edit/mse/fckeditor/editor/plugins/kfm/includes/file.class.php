<?php
class File extends Object{
	var $id=-1;
	var $name='';
	var $directory='';
	var $parent=0;
	var $path='';
	var $mimetype='';
	var $size=0;
	var $type;
	function File(){
		global $kfmdb,$kfm_db_prefix;
		if(func_num_args()==1){
			$this->id=func_get_arg(0);
			$qf=$kfmdb->query("SELECT id,name,directory FROM ".$kfm_db_prefix."files WHERE id=".$this->id);
			$filedata=$qf->fetchRow();
			$this->name=$filedata['name'];
			$this->parent=$filedata['directory'];
			$this->directory=kfm_getDirectoryParents($this->parent,1);
			$this->path=str_replace('//','/',$this->directory.'/'.$filedata['name']);
			if(!file_exists($this->path)){
				$this->error('File cannot be found');
				return false;
			}
			$mimetype=get_mimetype($this->path);
			$pos=strpos($mimetype,';');
			$this->mimetype=($pos===false)?$mimetype:substr($mimetype,0,$pos);
			$this->type=trim(substr(strstr($this->mimetype,'/'),1));
		}
	}
	function getContent(){
		return ($this->id==-1)?false:file_get_contents($this->path);
	}
	function checkAddr($addr){
		return (
			strpos($addr,'..')===false&&
			strpos($addr,'.')!==0&&
			strpos($addr,'/')===false &&
			!in_array(preg_replace('/.*\./','',$addr),$GLOBALS['kfm_banned_extensions'])
			);
	}
	function getExtension(){
		/* Function that returns the extension of the file.
		 * if a parameter is given, the extension of that parameters is returned
		 * returns false on error.
		 */
		if(func_num_args()==1){
			$filename=func_get_arg(0);
		}else{
			if($this->id==-1)return false;
			$filename=$this->name;
		}
		$dotext=strrchr($filename,'.');
		if($dotext === false) return false;
		return strtolower(substr($dotext,1));
	}
	function getUrl($x=0,$y=0){
		global $rootdir, $kfm_userfiles_output,$kfm_workdirectory;
		$cwd=$this->directory.'/'==$rootdir?'':str_replace($rootdir,'',$this->directory);
		if(!file_exists($this->path))return 'javascript:alert("missing file")';
		if(preg_replace('/.*(get\.php)$/','$1',$kfm_userfiles_output)=='get.php'){
			if($kfm_userfiles_output=='get.php')$url=preg_replace('/\/[^\/]*$/','/get.php?id='.$this->id.GET_PARAMS,$_SERVER['REQUEST_URI']);
			else $url=$kfm_userfiles_output.'?id='.$this->id;
			if($x&&$y)$url.='&width='.$x.'&height='.$y;
		}
		else{
			if($this->isImage()&&$x&&$y){
				$img=new Image($this);
				$img->setThumbnail($x,$y);
				return $kfm_userfiles_output.$kfm_workdirectory.'/thumbs/'.$img->thumb_id;
			}
			else $url=preg_replace('/([^:])\/\//','$1/',$kfm_userfiles_output.'/'.$cwd.'/'.$this->name); # TODO: check this line - $cwd may be incorrect if the requested file is from a search
		}
		return $url;
	}
	function delete(){
		global $kfmdb,$kfm_db_prefix,$kfm_allow_file_delete;
		if(!$kfm_allow_file_delete)$this->error('permission denied: cannot delete file'); # TODO: new string
		if(!kfm_cmsHooks_allowedToDeleteFile($this->id))$this->error('CMS does not allow "'.$this->path.'" to be deleted'); # TODO: new string
		if(!$this->hasErrors()){
			if(unlink($this->path)||!file_exists($this->path))$kfmdb->exec("DELETE FROM ".$kfm_db_prefix."files WHERE id=".$this->id);
			else $this->error('unable to delete file '.$this->name);
		}
		return !$this->hasErrors();
	}
	function getSize(){
		if(!$this->size)$this->size=filesize($this->path);
		return $this->size;
	}
	function getTags(){
		global $kfmdb,$kfm_db_prefix;
		$q=$kfmdb->query("select tag_id from ".$kfm_db_prefix."tagged_files where file_id=".$this->id);
		$arr=array();
		foreach($q->fetchAll() as $r)$arr[]=$r['tag_id'];
		return $arr;
	}
	function isImage(){
		return in_array($this->getExtension(),array('jpg', 'jpeg', 'gif', 'png', 'bmp'));
	}
	function isWritable(){
		return (($this->id==-1)||!is_writable($this->path))?false:true;
	}
	function setContent($content){
		global $kfm_allow_file_edit;
		if(!$kfm_allow_file_edit)return $this->error('permission denied: cannot edit file'); # TODO: new string
		$result=file_put_contents($this->path, $content);
		if(!$result)$this->error('error setting file content'); # TODO: new string
	}
	function setTags($tags){
		global $kfmdb,$kfm_db_prefix;
		if(!count($tags))return;
		$kfmdb->exec("DELETE FROM ".$kfm_db_prefix."tagged_files WHERE file_id=".$this->id);
		foreach($tags as $tag)$kfmdb->exec("INSERT INTO ".$kfm_db_prefix."tagged_files (file_id,tag_id) VALUES(".$this->id.",".$tag.")");
	}
	function size2str(){
		# returns the size in a human-readable way
		# expects input size in bytes
	 	# if no input parameter is given, the size of the file object is returned 
		$size=func_num_args()?func_get_arg(0):$this->getSize();
		$format=array("B","KB","MB","GB","TB","PB","EB","ZB","YB");
		$n=floor(log($size)/log(1024));
		return round($size/pow(1024,$n),1).' '.$format[$n];
	}
}
?>
