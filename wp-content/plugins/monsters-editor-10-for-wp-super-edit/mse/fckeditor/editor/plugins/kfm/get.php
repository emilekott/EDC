<?php
# see license.txt for licensing
require_once('initialise.php');
$id=$_GET['id'];
if(!is_numeric($id)){
	echo 'error: invalid id'; # TODO: new string
	exit;
}
if(isset($_GET['type'])&&$_GET['type']=='thumb'){
	$path=WORKPATH.'thumbs/'.$id;
	$name=$id;
}
else{
	$q=$kfmdb->query("select directory,name from ".$kfm_db_prefix."files where ".$kfm_db_prefix."files.id=".$id);
	$r=$q->fetchRow();
	if(!count($r)){
		echo 'error: file id #'.$id.' not found in database'; # TODO: new string
		exit;
	}
	if(isset($_GET['width'])&&isset($_GET['height'])){
		$width=$_GET['width'];
		$height=$_GET['height'];
		$q2=$kfmdb->query("select id from ".$kfm_db_prefix."files_images_thumbs where image_id=".$id." and width<=".$width." and height<=".$height." and (width=".$width." or height=".$height.")");
		$r2=$q2->fetchRow();
		if(count($r2)){
			$path=WORKPATH.'thumbs/'.$r2['id'];
			$name=$r2['id'];
		}
		else{
			$image=new Image($id);
			$thumb=$image->createThumb($width,$height);
			$path=WORKPATH.'thumbs/'.$thumb;
			$name=$thumb;
		}
	}
	else{
		$path=kfm_getDirectoryParents($r['directory']).$r['name'];
		$name=$r['name'];
	}
}
{ # headers
	if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE'))$name=preg_replace('/\./','%2e',$name,substr_count($name,'.')-1);
	set_time_limit(0);
	header("Cache-Control: ");
	header("Pragma: ");
	header('Content-Length: '.(string)(filesize($path)));
	if(isset($_GET['forcedownload'])){
		header('Content-Type: force/download');
		header('Content-Disposition: attachment; filename="'.$name.'"');
	}
	else header('Content-Type: '.get_mimetype($path));
	header('Content-Transfer-Encoding: binary');
}
if($file=fopen($path,'rb')){ # send file
	while((!feof($file))&&(connection_status()==0)){
		print(fread($file,1024*8));
		flush();
	}
	fclose($file);
}
return((connection_status()==0) and !connection_aborted());
?>
