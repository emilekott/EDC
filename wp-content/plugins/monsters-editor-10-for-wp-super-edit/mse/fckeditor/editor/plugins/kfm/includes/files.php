<?php
function _add_file_to_db($filename,$directory_id){
	global $kfmdb,$kfm_db_prefix;
	$sql="insert into ".$kfm_db_prefix."files (name,directory) values('".addslashes($filename)."',".$directory_id.")";
	$q=$kfmdb->query($sql);
	return $kfmdb->lastInsertId($kfm_db_prefix.'files','id');
}
function _copyFiles($files,$dir_id){
	global $kfmdb,$kfm_db_prefix;
	$to=kfm_getDirectoryParents($dir_id);
	$copied=0;
	if(!kfm_checkAddr($to))return 'error: illegal directory "'.$to.'"'; # TODO: new string
	foreach($files as $fid){
		$oldFile=new File($fid);
		if(!$oldFile)return 'error: no data for file id "'.$fid.'"'; # TODO: new string
		$filename=$oldFile->name;
		if(!kfm_checkAddr($oldFile->path))return;
		copy($oldFile->path,$to.'/'.$filename);
		$id=kfm_add_file_to_db($filename,$dir_id);
		if($oldFile->isImage()){
			$oldFile=new Image($fid);
			$newFile=new Image($id);
			$newFile->setCaption($oldFile->caption);
		}
		else $newFile=new File($id);
		$newFile->setTags($oldFile->getTags());
		++$copied;
	}
	return $copied.' files copied'; # TODO: new string
}
function _createEmptyFile($filename){
	if(!kfm_checkAddr($_SESSION['kfm']['currentdir'].'/'.$filename))return 'error: filename "'.$filename.'" not allowed'; # TODO: new string
	return(touch($_SESSION['kfm']['currentdir'].'/'.$filename))?kfm_loadFiles($_SESSION['kfm']['cwd_id']):'error: could not write file "'.$filename.'"'; # TODO: new string
}
function _downloadFileFromUrl($url,$filename){
	if(!kfm_checkAddr($_SESSION['kfm']['currentdir'].'/'.$filename))return 'error: filename not allowed';
	if(substr($url,0,4)!='http')return 'error: url must begin with http';
	$file=file_get_contents(str_replace(' ','%20',$url));
	if(!$file)return 'error: could not download file "'.$url.'"';
	return(file_put_contents($_SESSION['kfm']['currentdir'].'/'.$filename,$file))?kfm_loadFiles($_SESSION['kfm']['cwd_id']):'error: could not write file "'.$filename.'"';
}
function _extractZippedFile($id){
	$file=new File($id);
	$dir=$file->directory.'/';
	{ # try native system unzip command
		$res=-1;
		$arr=array();
		exec('unzip -l "'.$dir.$file->name.'"',$arr,$res);
		if(!$res){
			for($i=3;$i<count($arr)-2;++$i){
				$filename=preg_replace('/.* /','',$arr[$i]);
				if(!kfm_checkAddr($filename))return 'error: zip contains a banned filename';
			}
			exec('unzip -o "'.$dir.$file->name.'" -x -d "'.$dir.'"',$arr,$res);
		}
	}
	if($res){ # try PHP unzip command
		return 'error: unzip failed';
		$zip=zip_open($dir.$file->name);
		while($zip_entry=zip_read($zip)){
			$entry=zip_entry_open($zip,$zip_entry);
			$filename=zip_entry_name($zip_entry);
			$target_dir=$dir.substr($filename,0,strrpos($filename,'/'));
			$filesize=zip_entry_filesize($zip_entry);
			if(is_dir($target_dir)||mkdir($target_dir)){
				if($filesize>0){
					$contents=zip_entry_read($zip_entry,$filesize);
					file_put_contents($dir.$filename,$contents);
				}
			}
		}
	}
	return kfm_loadFiles($_SESSION['kfm']['cwd_id']);
}
function _getFileAsArray($filename){
	return explode("\n",rtrim(file_get_contents($filename)));
}
function _getFileDetails($fid){
	$file=new File($fid);
	if(!file_exists($file->path))return;
	$details=array(
		'id'=>$fid,
		'filename'=>$file->name,
		'mimetype'=>$file->mimetype,
		'filesize'=>$file->size2str(),
		'tags'=>$file->getTags()
	);
	if($file->isImage()){
		$im=new Image($file);
		$details['caption']=$im->caption;
	}
	return $details;
}
function _getFileUrl($fid,$x=0,$y=0){
	$file=new File($fid);
	return $file->getUrl($x,$y);
}
function _getTagName($id){
	global $kfmdb,$kfm_db_prefix;
	$q=$kfmdb->query("select name from ".$kfm_db_prefix."tags where id=".$id);
	$r=$q->fetchRow();
	if(count($r))return array($id,$r['name']);
	return array($id,'UNKNOWN TAG '.$id);
}
function _getTextFile($fid){
	$file=new File($fid);
	if(!kfm_checkAddr($file->name))return;
	$ext=$file->getExtension();
	if(in_array($ext,$GLOBALS['kfm_editable_extensions'])){
		if(!$file->isWritable())return 'error: '.$file->name.' is not writable'; # TODO: new string
		/**
		 * determine language for Codepress
		 */
		switch($ext){
			case 'html':
			case 'tpl':
				$language='html';
				break;
			case 'php':
				$language = 'php';
				break;
			case 'css':
				$language = 'css';
				break;
			case 'js':
				$language = 'javascript';
				break;
			case 'j':
				$language = 'java';
				break;
			case 'pl':
				$language = 'perl';
				break;
			case 'ruby':
				$language = 'ruby';
				break;
			case 'sql':
				$language = 'sql';
				break;
			case 'txt':
				$language = 'text';
				break;
			default:
				$language = 'generic';
				break;
		}
		return array('content'=>str_replace(array('<','>'),array('&lt;','&gt;'),$file->getContent()),'name'=>$file->name,'id'=>$file->id, 'language'=>$language);
	}
	return 'error: "'.$file->name.'" cannot be edited (restricted)'; # TODO: new string
}
function _loadFiles($rootid=1){
	global $kfmdb,$kfm_db_prefix;
	$dirdata=kfm_getDirectoryDbInfo($rootid);
	$reqdir=kfm_getDirectoryParents($rootid);
	$root='/'.str_replace($GLOBALS['rootdir'],'',$reqdir);
	if(!kfm_checkAddr($root))return 'error: invalid directory "'.$root.'"';
	$reqdir=$GLOBALS['rootdir'].$root;
	if(!is_dir($reqdir))return 'error: "'.$reqdir.'" is not a directory'; # TODO: new string
	if($handle=opendir($reqdir)){
		$q=$kfmdb->query("select * from ".$kfm_db_prefix."files where directory=".$rootid);
		$filesdb=$q->fetchAll();
		$fileshash=array();
		if(is_array($filesdb))foreach($filesdb as $r)$fileshash[$r['name']]=$r['id'];
		$files=array();
		while(false!==($filename=readdir($handle)))if(strpos($filename,'.')!==0&&is_file($reqdir.'/'.$filename)){
			if(in_array(strtolower($filename),$GLOBALS['kfm_banned_files']))continue;
			if(!isset($fileshash[$filename]))$fileshash[$filename]=kfm_add_file_to_db($filename,$rootid);
			$file=new File($fileshash[$filename]);
			if($file->isImage())$file=new Image($fileshash[$filename]);
			$file->writable=$file->isWritable();
			if($file->isImage()){
				unset($file->bits);
				unset($file->channels);
				unset($file->directory);
				unset($file->image_id);
				unset($file->info);
				unset($file->mimetype);
				unset($file->parent);
				unset($file->path);
				unset($file->size);
				unset($file->thumb_id);
				unset($file->thumb_path);
				unset($file->thumb_url);
				unset($file->type);
			}
			unset($file->error_array);
			$files[]=$file;
			unset($fileshash[$filename]);
		}
		closedir($handle);
		if(count($fileshash)){ # remove stale database entries (directories removed by hand)
			foreach($fileshash as $k=>$v){
			#	$f=new File($v);
			#	$f->delete();
			}
		}
		{ # update session data
			$_SESSION['kfm']['currentdir']=$reqdir;
			$_SESSION['kfm']['cwd_id']=$rootid;
		}
		return array('reqdir'=>$root,'files'=>$files,'uploads_allowed'=>$GLOBALS['kfm_allow_file_uploads']);
	}
	return 'couldn\'t read directory';
}
function _moveFiles($files,$dir_id){
	global $kfmdb,$kfm_db_prefix,$kfm_allow_file_move;
	if(!$kfm_allow_file_move)return 'error: permission denied: cannot move file'; # TODO: new string
	$dirdata=kfm_getDirectoryDbInfo($dir_id);
	if(!$dirdata)return 'error: no data for directory id "'.$dir_id.'"'; # TODO: new string
	$to=kfm_getDirectoryParents($dir_id);
	if(!kfm_checkAddr($to))return 'error: illegal directory "'.$to.'"'; # TODO: new string
	foreach($files as $fid){
		$q=$kfmdb->query("select directory,name from ".$kfm_db_prefix."files where id=".$fid);
		if(!($filedata=$q->fetchRow()))return 'error: no data for file id "'.$file.'"'; # TODO: new string
		$dir=kfm_getDirectoryParents($filedata['directory']);
		$file=$filedata['name'];
		if(!kfm_checkAddr($dir.'/'.$file))return;
		rename($dir.'/'.$file,$to.'/'.$file);
		$q=$kfmdb->query("update ".$kfm_db_prefix."files set directory=".$dir_id." where id=".$fid);
	}
	return kfm_loadFiles($_SESSION['kfm']['cwd_id']);
}
function _renameFile($fid,$newfilename,$refreshFiles=true){
	global $kfmdb,$kfm_db_prefix;
	$file=new File($fid);
	if(!file_exists($file->path))return;
	$filename=$file->name;
	if(!kfm_checkAddr($filename)||!kfm_checkAddr($newfilename))return 'error: cannot rename "'.$filename.'" to "'.$newfilename.'"'; # TODO: new string
	$newfile=$_SESSION['kfm']['currentdir'].'/'.$newfilename;
	if(file_exists($newfile))return 'error: a file of that name already exists'; # TODO: new string
	rename($_SESSION['kfm']['currentdir'].'/'.$filename,$newfile);
	$kfmdb->query("update ".$kfm_db_prefix."files set name='".addslashes($newfilename)."' where id=".$fid);
	if($refreshFiles)return kfm_loadFiles($_SESSION['kfm']['cwd_id']);
}
function _renameFiles($files,$template){
	$prefix=preg_replace('/\*.*/','',$template);
	$postfix=preg_replace('/.*\*/','',$template);
	$precision=strlen(preg_replace('/[^*]/','',$template));
	for($i=1;$i<count($files)+1;++$i){
		$num=str_pad($i,$precision,'0',STR_PAD_LEFT);
		$ret=_renameFile($files[$i-1],$prefix.$num.$postfix,false);
		if($ret)return $ret; # error detected
	}
	return kfm_loadFiles($_SESSION['kfm']['cwd_id']);
}
function _resize_bytes($size){
	$count=0;
	$format=array("B","KB","MB","GB","TB","PB","EB","ZB","YB");
	while(($size/1024)>1&&$count<8){
		$size=$size/1024;
		++$count;
	}
	$return=number_format($size,0,'','.')." ".$format[$count];
	return $return;
}
function _rm($id,$dontLoadFiles=false){
	if(is_array($id)){
		foreach($id as $f){
			$ret=_rm($f,true);
			if($ret)return $ret;
		}
	}
	else{
		$file=new File($id);
		if($file->isImage())$file=new Image($file->id);
		$ret=$file->delete();
		if(!$ret)return $file->getErrors();
	}
	if(!$dontLoadFiles)return kfm_loadFiles($_SESSION['kfm']['cwd_id']);
}
function _saveTextFile($fid,$text){
	$f=new File($fid);
	$f->setContent(str_replace(array('&lt;','&gt;'),array('<','>'),$text));
	return $f->hasErrors()?$f->getErrors():'file saved';
}
function _search($keywords,$tags){
	global $kfmdb,$kfm_db_prefix;
	$valid_files=array();
	if($tags){
		$arr=explode(',',$tags);
		foreach($arr as $tag){
			$tag=ltrim(rtrim($tag));
			if($tag){
				$q=$kfmdb->query("select id from ".$kfm_db_prefix."tags where name='".addslashes($tag)."'");
				$r=$q->fetchRow();
				if(count($r)){
					if(count($valid_files))$constraints=' and (file_id='.join(' or file_id=',$valid_files).')';
					$q2=$kfmdb->query("select file_id from ".$kfm_db_prefix."tagged_files where tag_id=".$r['id'].$constraints);
					$rs2=$q2->fetchAll();
					if(count($rs2)){
						$valid_files=array();
						foreach($rs2 as $r2)$valid_files[]=$r2['file_id'];
					}
					else $valid_files=array(0);
				}
			}
		}
	}
	if(($tags&&count($valid_files))||$keywords){ # keywords
		$constraints='';
		if(count($valid_files))$constraints=' and (id='.join(' or id=',$valid_files).')';
		$q=$kfmdb->query("select id from ".$kfm_db_prefix."files where name like '%".addslashes($keywords)."%'".$constraints." order by name");
		$files=array();
		foreach($q->fetchAll() as $f){
			$file=new File($f['id']);
			if($file->isImage())$file=new Image($f['id']);
			$files[]=$file;
		}
	}
	return array('reqdir'=>'search results','files'=>$files,'uploads_allowed'=>0); # TODO: new string
}
function _tagAdd($recipients,$tagList){
	global $kfmdb,$kfm_db_prefix;
	if(!is_array($recipients))$recipients=array($recipients);
	$arr=explode(',',$tagList);
	$tagList=array();
	foreach($arr as $v){
		$v=ltrim(rtrim($v));
		if($v)$tagList[]=$v;
	}
	if(count($tagList))foreach($tagList as $tag){
		$q=$kfmdb->query("select id from ".$kfm_db_prefix."tags where name='".addslashes($tag)."'");
		$r=$q->fetchRow();
		if(count($r)){
			$tag_id=$r['id'];
			$kfmdb->query("delete from ".$kfm_db_prefix."tagged_files where tag_id=".$tag_id." and (file_id=".join(' or file_id=',$recipients).")");
		}
		else{
			$q=$kfmdb->query("insert into ".$kfm_db_prefix."tags (name) values('".addslashes($tag)."')");
			$tag_id=$kfmdb->lastInsertId($kfm_db_prefix.'tags','id');
		}
		foreach($recipients as $file_id)$kfmdb->query("insert into ".$kfm_db_prefix."tagged_files (tag_id,file_id) values(".$tag_id.",".$file_id.")");
	}
	return _getFileDetails($recipients[0]);
}
function _tagRemove($recipients,$tagList){
	global $kfmdb,$kfm_db_prefix;
	if(!is_array($recipients))$recipients=array($recipients);
	$arr=explode(',',$tagList);
	$tagList=array();
	foreach($arr as $tag){
		$tag=ltrim(rtrim($tag));
		if($tag){
			$q=$kfmdb->query("select id from ".$kfm_db_prefix."tags where name='".addslashes($tag)."'");
			$r=$q->fetchRow();
			if(count($r))$tagList[]=$r['id'];
		}
	}
	if(count($tagList))$kfmdb->exec("delete from ".$kfm_db_prefix."tagged_files where (file_id=".join(' or file_id=',$recipients).") and (tag_id=".join(' or tag_id="',$tagList).")");
	return _getFileDetails($recipients[0]);
}
function _viewTextFile($fileid){
	global $kfm_viewable_extensions, $kfm_highlight_extensions, $kfm_editable_extensions;
	$file=new File($fileid);
	$ext=$file->getExtension();
	$buttons_to_show=1; # boolean addition: 1=Close, 2=Edit
	if(in_array($ext,$kfm_editable_extensions)&&$file->isWritable())$buttons_to_show+=2;
	if(in_array($ext,$kfm_viewable_extensions)){
		$code=file_get_contents($file->path);
		if(array_key_exists($ext,$kfm_highlight_extensions)){
			require_once('Text/Highlighter.php');
			require_once('Text/Highlighter/Renderer/Html.php');
			$renderer=new Text_Highlighter_Renderer_Html(array('numbers'=>HL_NUMBERS_TABLE,'tabsize'=>4));
			$hl=&Text_Highlighter::factory($kfm_highlight_extensions[$ext]);
			$hl->setRenderer($renderer);
			$code=$hl->highlight($code);
		}else if($ext=='txt'){
			$code=nl2br($code);
		}
		return array('id'=>$fileid,'content'=>$code,'buttons_to_show'=>$buttons_to_show,'name'=>$file->name);
	}
	return "error: viewing file is not allowed"; # TODO: new string
}
function _zip($filename,$files){
	global $rootdir;
	if(!kfm_checkAddr($_SESSION['kfm']['currentdir'].'/'.$filename))return 'error: filename "'.$filename.'" not allowed'; # TODO: new string
	$arr=array();
	foreach($files as $f){
		$file=new File($f);
		if(!$file)return 'error: missing file in selection'; # TODO: new string
		$arr[]=$file->path;
	}
	{ # try native system zip command
		$res=-1;
		$pdir=str_replace('//','/',$_SESSION['kfm']['currentdir'].'/');
		$zipfile=$pdir.$filename;
		for($i=0;$i<count($arr);++$i)$arr[$i]=str_replace($pdir,'',$arr[$i]);
		exec('cd "'.$rootdir.'" && zip -D "'.$zipfile.'" "'.join('" "',$arr).'"',$arr,$res);
	}
	if($res)return 'error: no native "zip" command'; # TODO: new string
	return kfm_loadFiles($_SESSION['kfm']['cwd_id']);
}
?>
